<?php

namespace App\Http\Controllers\Admin;

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
        $query = Meeting::with(['lead', 'order', 'project', 'createdBy']);

        // Filtering
        if ($request->filled('date')) {
            $query->whereDate('meeting_date', $request->date);
        }

        if ($request->filled('sale_id')) {
            $query->whereJsonContains('assignsale_ids', (int)$request->sale_id);
        }

        if ($request->filled('dev_id')) {
            $query->whereJsonContains('assigndev_ids', (int)$request->dev_id);
        }

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function($q) use ($s) {
                $q->where('meeting_title', 'like', "%$s%")
                  ->orWhere('meeting_description', 'like', "%$s%");
            });
        }

        // Status Counts
        $counts = [
            'total' => Meeting::count(),
            'pending' => Meeting::where('status', 'pending')->count(),
            'rescheduled' => Meeting::where('status', 'rescheduled')->count(),
            'completed' => Meeting::where('status', 'completed')->count(),
            'canceled' => Meeting::where('status', 'canceled')->count(),
        ];

        $meetings = $query->orderByRaw('ABS(DATEDIFF(meeting_date, CURDATE())) ASC')
            ->orderBy('meeting_time', 'asc')
            ->paginate(15);
        $sales = Sale::all();
        $developers = Developer::all();
            
        $routePrefix = 'admin';
        return view('admin.meetings.index', compact('meetings', 'counts', 'sales', 'developers', 'routePrefix'));
    }

    public function create()
    {
        $leads = Lead::all();
        $orders = Order::all();
        $projects = Project::all();
        $developers = Developer::all();
        $sales = Sale::all();
        
        $routePrefix = 'admin';
        return view('admin.meetings.create', compact('leads', 'orders', 'projects', 'developers', 'sales', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'meeting_type' => 'required|in:lead,order,project',
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|string',
            'status' => 'required|string|in:pending,rescheduled,completed,canceled',
            'meeting_description' => 'nullable|string',
            'meeting_link' => 'nullable|url',
        ]);

        $meeting = new Meeting($request->all());
        $meeting->created_by_id = auth()->guard('admin')->id();
        $meeting->created_by_type = \App\Models\Admin::class;
        $meeting->assigndev_ids = array_map('intval', (array)($request->assigndev_ids ?? []));
        $meeting->assignsale_ids = array_map('intval', (array)($request->assignsale_ids ?? []));
        $meeting->save();

        $routePrefix = 'admin';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting scheduled successfully.');
    }

    public function show(Meeting $meeting)
    {
        $meeting->load(['lead', 'order', 'project', 'createdBy']);
        $routePrefix = 'admin';
        return view('admin.meetings.show', compact('meeting', 'routePrefix'));
    }

    public function edit(Meeting $meeting)
    {
        $leads = Lead::all();
        $orders = Order::all();
        $projects = Project::all();
        $developers = Developer::all();
        $sales = Sale::all();
        
        $routePrefix = 'admin';
        return view('admin.meetings.edit', compact('meeting', 'leads', 'orders', 'projects', 'developers', 'sales', 'routePrefix'));
    }

    public function update(Request $request, Meeting $meeting)
    {
        $request->validate([
            'meeting_type' => 'required|in:lead,order,project',
            'meeting_title' => 'required|string|max:255',
            'meeting_date' => 'required|date',
            'meeting_time' => 'required|string',
            'status' => 'required|string|in:pending,rescheduled,completed,canceled',
            'meeting_description' => 'nullable|string',
            'meeting_link' => 'nullable|url',
        ]);

        $meeting->fill($request->all());
        $meeting->assigndev_ids = array_map('intval', (array)($request->assigndev_ids ?? []));
        $meeting->assignsale_ids = array_map('intval', (array)($request->assignsale_ids ?? []));
        $meeting->save();

        $routePrefix = 'admin';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting updated successfully.');
    }

    public function destroy(Meeting $meeting)
    {
        $meeting->delete();
        $routePrefix = 'admin';
        return redirect()->route($routePrefix . '.meetings.index')->with('success', 'Meeting deleted successfully.');
    }
}
