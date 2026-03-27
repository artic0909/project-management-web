<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\Order;
use App\Models\Developer;
use App\Models\ProjectAssign;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with(['developers', 'order'])->latest()->paginate(10);
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
        $data['created_by'] = Auth::id();
        $data['created_by_type'] = get_class(Auth::user());

        $project = Project::create($data);

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

        $project->update($request->all());

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
