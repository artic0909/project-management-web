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
        if ($request->filled('date')) {
            $query->whereDate('meeting_date', $request->date);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('meeting_title', 'like', "%$s%")
                  ->orWhere('meeting_description', 'like', "%$s%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Scoped Status Counts
        $countQuery = Meeting::whereJsonContains('assigndev_ids', $devId);
        $counts = [
            'total' => (clone $countQuery)->count(),
            'pending' => (clone $countQuery)->where('status', 'pending')->count(),
            'rescheduled' => (clone $countQuery)->where('status', 'rescheduled')->count(),
            'completed' => (clone $countQuery)->where('status', 'completed')->count(),
            'canceled' => (clone $countQuery)->where('status', 'canceled')->count(),
        ];

        $meetings = $query->orderByRaw('ABS(DATEDIFF(meeting_date, CURDATE())) ASC')
            ->orderBy('meeting_time', 'asc')
            ->paginate(15);
        $sales = Sale::all();
        $developers = Developer::all();
            
        $routePrefix = 'developer';
            
        return view('admin.meetings.index', compact('meetings', 'counts', 'sales', 'developers', 'routePrefix'));
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
}
