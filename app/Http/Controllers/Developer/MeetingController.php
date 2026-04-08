<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Sale;
use App\Models\Developer;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $devId = (int)auth()->guard('developer')->id();
        $query = Meeting::whereJsonContains('assigndev_ids', $devId)
            ->with(['lead', 'order', 'project', 'createdBy']);

        // Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
        } elseif ($request->filled('date')) {
            $query->whereDate('meeting_date', $request->date);
        }

        if ($request->filled('q') || $request->filled('search')) {
            $s = $request->q ?? $request->search;
            $cleanId = ltrim(str_ireplace(['#MT-', '#MT0'], '', $s), '0');
            if(empty($cleanId)) $cleanId = $s;

            $query->where(function($q) use ($s, $cleanId) {
                $q->where('id', 'LIKE', "%$cleanId%")
                  ->orWhere('meeting_title', 'like', "%$s%")
                  ->orWhere('meeting_description', 'like', "%$s%")
                  ->orWhereHas('project', function($pq) use ($s) {
                      $pq->where('project_name', 'like', "%$s%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Scoped Status Counts
        $statsQuery = Meeting::whereJsonContains('assigndev_ids', $devId);
        
        // Apply current filters to counts too
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $statsQuery->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
        }
        
        $counts = [
            'total' => (clone $statsQuery)->count(),
            'pending' => (clone $statsQuery)->where('status', 'pending')->count(),
            'rescheduled' => (clone $statsQuery)->where('status', 'rescheduled')->count(),
            'completed' => (clone $statsQuery)->where('status', 'completed')->count(),
            'canceled' => (clone $statsQuery)->where('status', 'canceled')->count(),
        ];

        $meetings = $query->orderByRaw('ABS(DATEDIFF(meeting_date, CURDATE())) ASC')
            ->orderBy('meeting_time', 'asc')
            ->paginate(15);
        $sales = Sale::all();
        $developers = Developer::all();
            
        $routePrefix = 'developer';
            
        return view('admin.meetings.index', compact('meetings', 'counts', 'sales', 'developers', 'routePrefix'));
    }

    public function export(Request $request)
    {
        $devId = (int)auth()->guard('developer')->id();
        $query = Meeting::whereJsonContains('assigndev_ids', $devId)
            ->with(['lead', 'order', 'project', 'createdBy']);

        // Filtering - Same as Index
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('q')) {
            $s = $request->q;
            $cleanId = ltrim(str_ireplace(['#MT-', '#MT0'], '', $s), '0');
            if(empty($cleanId)) $cleanId = $s;

            $query->where(function($q) use ($s, $cleanId) {
                $q->where('id', 'LIKE', "%$cleanId%")
                  ->orWhere('meeting_title', 'like', "%$s%")
                  ->orWhere('meeting_description', 'like', "%$s%")
                  ->orWhereHas('project', function($pq) use ($s) {
                      $pq->where('project_name', 'like', "%$s%");
                  });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $meetings = $query->orderBy('meeting_date', 'desc')->get();

        $filename = "my_meetings_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($meetings) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Meeting ID',
                'Date',
                'Time',
                'Type',
                'Target (Lead/Order/Project)',
                'Title',
                'Description',
                'Status',
                'Meeting Link',
                'Participants (Devs/Sales)',
                'Created By',
                'Created At'
            ]);

            $developers = Developer::all()->pluck('name', 'id')->toArray();
            $sales = Sale::all()->pluck('name', 'id')->toArray();

            foreach ($meetings as $meeting) {
                $target = '';
                if($meeting->meeting_type == 'lead' && $meeting->lead) {
                    $target = $meeting->lead->company . ' (Lead #' . $meeting->lead_id . ')';
                } elseif($meeting->meeting_type == 'order' && $meeting->order) {
                    $target = $meeting->order->company_name . ' (Order #' . $meeting->order_id . ')';
                } elseif($meeting->meeting_type == 'project' && $meeting->project) {
                    $target = $meeting->project->project_name . ' (Project #' . $meeting->project_id . ')';
                }

                $d_ids = is_string($meeting->assigndev_ids) ? json_decode($meeting->assigndev_ids, true) : ($meeting->assigndev_ids ?? []);
                $s_ids = is_string($meeting->assignsale_ids) ? json_decode($meeting->assignsale_ids, true) : ($meeting->assignsale_ids ?? []);
                
                $participantNames = [];
                foreach((array)$d_ids as $id) if(isset($developers[$id])) $participantNames[] = $developers[$id];
                foreach((array)$s_ids as $id) if(isset($sales[$id])) $participantNames[] = $sales[$id];
                $participantsStr = implode(', ', $participantNames);

                $createdBy = $meeting->createdBy ? $meeting->createdBy->name : 'System';

                fputcsv($file, [
                    '#MT-' . $meeting->id,
                    $meeting->meeting_date->format('Y-m-d'),
                    \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A'),
                    strtoupper($meeting->meeting_type),
                    $target,
                    $meeting->meeting_title,
                    $meeting->meeting_description,
                    ucfirst($meeting->status),
                    $meeting->meeting_link,
                    $participantsStr,
                    $createdBy,
                    $meeting->created_at->format('Y-m-d H:i:s')
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function show(Meeting $meeting)
    {
        $devId = (int)auth()->guard('developer')->id();
        if (!in_array($devId, $meeting->assigndev_ids ?? [])) {
            abort(403);
        }
        
        $meeting->load(['lead', 'order', 'project', 'createdBy']);
        $routePrefix = 'developer';
        return view('admin.meetings.show', compact('meeting', 'routePrefix'));
    }

    public function updateStatus(Request $request, $id)
    {
        $meeting = Meeting::findOrFail($id);
        $devId = (int)auth()->guard('developer')->id();

        if (!in_array($devId, $meeting->assigndev_ids ?? [])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string|in:pending,rescheduled,completed,canceled',
        ]);

        $meeting->update([
            'status' => $request->status,
        ]);

        return redirect()->back()->with('success', 'Meeting status updated successfully.');
    }
}
