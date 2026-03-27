<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectTaskAssign;
use App\Models\Developer;
use Illuminate\Support\Facades\DB;

class ProjectTaskController extends Controller
{
    public function index($projectId)
    {
        $project = Project::with(['tasks.assignments.developer', 'tasks.creator'])->findOrFail($projectId);
        $developers = Developer::all();
        return view('admin.project.tasks', compact('project', 'developers'));
    }

    public function store(Request $request, $projectId)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'task' => 'required',
            'developer_id' => 'nullable|exists:developers,id',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $task = ProjectTask::create([
                'project_id' => $projectId,
                'title' => $request->title,
                'task' => $request->task,
                'status' => 'Pending',
                'created_by' => auth()->guard('admin')->id(),
                'created_by_type' => get_class(auth()->guard('admin')->user()),
            ]);

            if ($request->developer_id) {
                ProjectTaskAssign::create([
                    'project_id' => $projectId,
                    'task_id' => $task->id,
                    'developer_id' => $request->developer_id,
                    'remarks' => $request->remarks,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('success', 'Task created successfully.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }
}
