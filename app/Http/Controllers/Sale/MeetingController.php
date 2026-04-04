<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Meeting;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Project;
use App\Models\Developer;
use App\Models\Sale;
use Illuminate\Http\Request;

class MeetingController extends Controller
{
    public function index(Request $request)
    {
        $saleId = (int)auth()->guard('sale')->id();
        $query = Meeting::whereJsonContains('assignsale_ids', $saleId)
            ->with(['lead', 'order', 'project', 'createdBy']);

        // Filtering
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('meeting_date', [$request->start_date, $request->end_date]);
        }

        if ($request->filled('sale_id')) {
            $query->whereJsonContains('assignsale_ids', (int)$request->sale_id);
        }

        if ($request->filled('dev_id')) {
            $query->whereJsonContains('assigndev_ids', (int)$request->dev_id);
        }

        if ($request->has('q') && !empty($request->q)) {
            $s = $request->q;
            $cleanId = ltrim(str_ireplace(['#MT-', '#MT0'], '', $s), '0');
            if(empty($cleanId)) $cleanId = $s;

            // Find matching devs/sales for JSON array searching
            $devIds = \App\Models\Developer::where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->pluck('id');
            $saleIds = \App\Models\Sale::where('name', 'like', "%$s%")->orWhere('email', 'like', "%$s%")->pluck('id');

            $query->where(function($q) use ($s, $cleanId, $devIds, $saleIds) {
                $q->where('id', 'LIKE', "%$cleanId%")
                  ->orWhere('meeting_title', 'like', "%$s%")
                  ->orWhere('meeting_description', 'like', "%$s%")
                  ->orWhere('status', 'like', "%$s%")
                  ->orWhere('meeting_type', 'like', "%$s%")
                  ->orWhereHas('lead', function($lq) use ($s) {
                      $lq->where('company', 'like', "%$s%")->orWhere('contact_person', 'like', "%$s%");
                  })
                  ->orWhereHas('order', function($oq) use ($s) {
                      $oq->where('company_name', 'like', "%$s%")->orWhere('client_name', 'like', "%$s%");
                  })
                  ->orWhereHas('project', function($pq) use ($s) {
                      $pq->where('project_name', 'like', "%$s%");
                  });

                foreach($devIds as $dId) {
                    $q->orWhereJsonContains('assigndev_ids', $dId);
                }
                foreach($saleIds as $sId) {
                    $q->orWhereJsonContains('assignsale_ids', $sId);
                }
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Scoped Status Counts
        $countQuery = clone $query;
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
            
        $routePrefix = 'sale';
        return view('admin.meetings.index', compact('meetings', 'counts', 'sales', 'developers', 'routePrefix'));
    }

    public function create(Request $request)
    {
        $saleId = (int)auth()->guard('sale')->id();

        $leads = Lead::where(function($q) use ($saleId) {
            $q->whereHas('assignments', fn($aq) => $aq->where('assigned_to', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $orders = Order::where(function($q) use ($saleId) {
            $q->whereHas('sales', fn($aq) => $aq->where('assigned_to', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $projects = Project::where(function($q) use ($saleId) {
            $q->whereHas('salesPersons', fn($aq) => $aq->where('sale_id', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $developers = Developer::all();
        $sales = Sale::all();
        
        $routePrefix = 'sale';
        return view('admin.meetings.create', compact('leads', 'orders', 'projects', 'developers', 'sales', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_type' => 'required|in:lead,order,project',
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'status' => 'required|string',
        ]);

        $meeting = new Meeting($request->all());
        $meeting->created_by_id = auth()->guard('sale')->id();
        $meeting->created_by_type = \App\Models\Sale::class;
        $meeting->assigndev_ids = array_map('intval', (array)($request->assigndev_ids ?? []));
        $meeting->assignsale_ids = array_map('intval', (array)($request->assignsale_ids ?? []));
        
        // Ensure the salesperson themselves is included in assignments if not already
        $saleId = (int)auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids)) {
            $meeting->assignsale_ids = array_merge([$saleId], $meeting->assignsale_ids);
        }
        
        $meeting->save();

        $routePrefix = 'sale';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting scheduled successfully.');
    }

    public function show(Meeting $meeting)
    {
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $meeting->load(['lead', 'order', 'project', 'createdBy']);
        $routePrefix = 'sale';
        return view('admin.meetings.show', compact('meeting', 'routePrefix'));
    }

    public function edit(Meeting $meeting)
    {
        $saleId = (int)auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $leads = Lead::where(function($q) use ($saleId) {
            $q->whereHas('assignments', fn($aq) => $aq->where('assigned_to', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $orders = Order::where(function($q) use ($saleId) {
            $q->whereHas('sales', fn($aq) => $aq->where('assigned_to', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $projects = Project::where(function($q) use ($saleId) {
            $q->whereHas('salesPersons', fn($aq) => $aq->where('sale_id', $saleId))
              ->orWhere(function($oq) use ($saleId) {
                  $oq->where('created_by', $saleId)
                     ->where('created_by_type', \App\Models\Sale::class);
              });
        })->get();

        $developers = Developer::all();
        $sales = Sale::all();
        
        $routePrefix = 'sale';
        return view('admin.meetings.edit', compact('meeting', 'leads', 'orders', 'projects', 'developers', 'sales', 'routePrefix'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }

        $request->validate([
            'meeting_type' => 'required|in:lead,order,project',
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required',
            'status' => 'required|string',
        ]);

        $meeting->fill($request->all());
        $meeting->assigndev_ids = array_map('intval', (array)($request->assigndev_ids ?? []));
        $meeting->assignsale_ids = array_map('intval', (array)($request->assignsale_ids ?? []));
        
        // Ensure the salesperson stays assigned
        if (!in_array($saleId, $meeting->assignsale_ids)) {
            $meeting->assignsale_ids = array_merge([$saleId], $meeting->assignsale_ids);
        }
        
        $meeting->save();

        $routePrefix = 'sale';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $meeting->delete();
        $routePrefix = 'sale';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting deleted successfully.');
    }
}
