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
    public function index()
    {
        $saleId = auth()->guard('sale')->id();
        $meetings = Meeting::whereJsonContains('assignsale_ids', (string)$saleId)
            ->with(['lead', 'order', 'project', 'createdBy'])
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->paginate(15);
            
        return view('sale.meetings.index', compact('meetings'));
    }

    public function create()
    {
        $leads = Lead::all();
        $orders = Order::all();
        $projects = Project::all();
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
        $meeting->assigndev_ids = $request->assigndev_ids ?? [];
        $meeting->assignsale_ids = $request->assignsale_ids ?? [];
        
        // Ensure the salesperson themselves is included in assignments if not already
        $saleId = auth()->guard('sale')->id();
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
        $saleId = auth()->guard('sale')->id();
        if (!in_array($saleId, $meeting->assignsale_ids ?? [])) {
            abort(403);
        }
        
        $leads = Lead::all();
        $orders = Order::all();
        $projects = Project::all();
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
        $meeting->assigndev_ids = $request->assigndev_ids ?? [];
        $meeting->assignsale_ids = $request->assignsale_ids ?? [];
        
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
