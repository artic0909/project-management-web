<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Order;
use App\Models\Developer;
use App\Models\ProjectAssign;
use App\Models\ClientFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::latest()->get();
        return view('admin.project.index', compact('projects'));
    }

    public function create()
    {
        $orders = Order::latest()->get();
        $developers = Developer::latest()->get();
        return view('admin.project.create', compact('orders', 'developers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name' => 'required',
            'client_name' => 'required',
            'project_price' => 'required|numeric',
        ]);

        $data = $request->all();

        // Handle Multi-Email
        $data['emails'] = $request->email ?? [];

        // Handle Multi-Phone with Country Codes
        $phones = [];
        if ($request->has('phone')) {
            foreach ($request->phone as $idx => $num) {
                if ($num) {
                    $phones[] = [
                        'code' => $request->country_code[$idx] ?? null,
                        'num' => $num
                    ];
                }
            }
        }
        $data['phones'] = $phones;

        $data['created_by'] = Auth::id();
        $data['created_by_type'] = get_class(Auth::user());

        $project = Project::create($data);

        // Historical Logging
        if ($request->anyFilled(['last_update_date', 'client_feedback_summary', 'internal_notes'])) {
            $project->feedbacks()->create([
                'status' => $project->project_status,
                'last_update_date' => $request->last_update_date,
                'feedback_summary' => $request->client_feedback_summary,
                'internal_notes' => $request->internal_notes,
            ]);
        }

        if ($request->has('assign_to')) {
            foreach ($request->assign_to as $developerId) {
                ProjectAssign::create([
                    'project_id' => $project->id,
                    'assigned_to' => $developerId,
                    'type' => 'developer'
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = Project::with(['developers', 'order', 'feedbacks'])->findOrFail($id);
        return view('admin.project.show', compact('project'));
    }

    public function edit($id)
    {
        $project = Project::with('developers')->findOrFail($id);
        $orders = Order::latest()->get();
        $developers = Developer::latest()->get();
        return view('admin.project.edit', compact('project', 'orders', 'developers'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        $request->validate([
            'project_name' => 'required',
            'client_name' => 'required',
            'project_price' => 'required|numeric',
        ]);

        $data = $request->all();
        
        // Handle Multi-Email
        $data['emails'] = $request->email ?? [];

        // Handle Multi-Phone with Country Codes
        $phones = [];
        if ($request->has('phone')) {
            foreach ($request->phone as $idx => $num) {
                if ($num) {
                    $phones[] = [
                        'code' => $request->country_code[$idx] ?? null,
                        'num' => $num
                    ];
                }
            }
        }
        $data['phones'] = $phones;
        
        $project->update($data);

        // Historical Logging
        if ($request->anyFilled(['last_update_date', 'client_feedback_summary', 'internal_notes'])) {
            $project->feedbacks()->create([
                'status' => $project->project_status,
                'last_update_date' => $request->last_update_date,
                'feedback_summary' => $request->client_feedback_summary,
                'internal_notes' => $request->internal_notes,
            ]);
        }

        if ($request->has('assign_to')) {
            ProjectAssign::where('project_id', $project->id)->delete();
            foreach ($request->assign_to as $developerId) {
                ProjectAssign::create([
                    'project_id' => $project->id,
                    'assigned_to' => $developerId,
                    'type' => 'developer'
                ]);
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }
}
