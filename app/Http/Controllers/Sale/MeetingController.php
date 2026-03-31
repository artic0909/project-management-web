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
        if ($request->filled('date')) {
            $query->whereDate('meeting_date', $request->date);
        }

        if ($request->filled('sale_id')) {
            $query->whereJsonContains('assignsale_ids', (string)$request->sale_id);
        }

        if ($request->filled('dev_id')) {
            $query->whereJsonContains('assigndev_ids', (string)$request->dev_id);
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
        $countQuery = Meeting::whereJsonContains('assignsale_ids', $saleId);
        $counts = [
            'total' => (clone $countQuery)->count(),
            'pending' => (clone $countQuery)->where('status', 'pending')->count(),
            'rescheduled' => (clone $countQuery)->where('status', 'rescheduled')->count(),
            'completed' => (clone $countQuery)->where('status', 'completed')->count(),
            'canceled' => (clone $countQuery)->where('status', 'canceled')->count(),
        ];

        $meetings = $query->latest('meeting_date')->latest('meeting_time')->paginate(15);
        $sales = Sale::all();
        $developers = Developer::all();
            
        return view('sale.meetings.index', compact('meetings', 'counts', 'sales', 'developers'));
    }

    public function create()
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
        
        return view('sale.meetings.create', compact('leads', 'orders', 'projects', 'developers', 'sales'));
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

        return redirect()->route('sale.meetings.index')->with('success', 'Meeting scheduled successfully.');
    }

    public function show(Meeting $meeting)
    {
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $meeting->load(['lead', 'order', 'project', 'createdBy']);
        return view('sale.meetings.show', compact('meeting'));
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
        
        return view('sale.meetings.edit', compact('meeting', 'leads', 'orders', 'projects', 'developers', 'sales'));
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

        return redirect()->route('sale.meetings.index')->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $meeting->delete();
        return redirect()->route('sale.meetings.index')->with('success', 'Meeting deleted successfully.');
    }
}
