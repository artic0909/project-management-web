<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectTaskAssign;

class TaskController extends Controller
{
    public function projectTasks($projectId)
    {
        return redirect()->route('developer.tasks.completed', ['project_id' => $projectId]);
    }

    public function update(Request $request, $taskId)
    {
        $request->validate([
            'remarks' => 'nullable|string',
            'status' => 'required|in:Pending,In Progress,Completed',
        ]);

        $assignment = ProjectTaskAssign::where('task_id', $taskId)
            ->where('developer_id', auth()->guard('developer')->id())
            ->firstOrFail();

        $assignment->update(['remarks' => $request->remarks]);
        
        $task = ProjectTask::findOrFail($taskId);
        $task->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Task updated successfully.');
    }

    public function myTasks(Request $request)
    {
        $developer = auth()->guard('developer')->user();
        
        $total_completed = ProjectTask::where('status', 'Completed')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            });
            
        $total_pending = ProjectTask::where('status', 'Pending')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            });
            
        $total_in_progress = ProjectTask::where('status', 'In Progress')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            });

        if ($request->filled('project_id')) {
            $total_completed->where('project_id', $request->project_id);
            $total_pending->where('project_id', $request->project_id);
            $total_in_progress->where('project_id', $request->project_id);
        }

        $total_completed = $total_completed->count();
        $total_pending = $total_pending->count();
        $total_in_progress = $total_in_progress->count();

        $query = ProjectTask::whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            })
            ->with(['project', 'assignments' => function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            }])
            ->latest();

        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $tasks = $query->paginate(15)->withQueryString();
        $routePrefix = 'developer';

        return view('admin.tasks.index', compact('tasks', 'total_completed', 'total_pending', 'total_in_progress', 'routePrefix'));
    }

    public function show($taskId)
    {
        $task = ProjectTask::with('project', 'creator', 'assignments.developer')->findOrFail($taskId);
        $routePrefix = 'developer';
        return view('admin.tasks.view', compact('task', 'routePrefix'));
    }
}
