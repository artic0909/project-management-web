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
        $project = auth()->guard('developer')->user()->projects()->findOrFail($projectId);
        $tasks = ProjectTask::where('project_id', $projectId)
            ->whereHas('assignments', function($q) {
                $q->where('developer_id', auth()->guard('developer')->id());
            })
            ->with(['creator', 'assignments' => function($q) {
                $q->where('developer_id', auth()->guard('developer')->id());
            }])
            ->get();

        return view('developer.task.index', compact('project', 'tasks'));
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
        
        // Counts for KPI cards
        $total_completed = ProjectTask::where('status', 'Completed')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            })->count();
            
        $total_pending = ProjectTask::where('status', '!=', 'Completed')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            })->count();
            
        $total_in_progress = ProjectTask::where('status', 'In Progress')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            })->count();

        $query = ProjectTask::where('status', 'Completed')
            ->whereHas('assignments', function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            })
            ->with(['project', 'assignments' => function($q) use ($developer) {
                $q->where('developer_id', $developer->id);
            }])
            ->latest();

        if ($request->filled('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        $tasks = $query->paginate(15)->withQueryString();

        return view('developer.task.my-tasks', compact('tasks', 'total_completed', 'total_pending', 'total_in_progress'));
    }

    public function show($taskId)
    {
        $task = ProjectTask::with('project', 'creator', 'assignments.developer')->findOrFail($taskId);
        return view('developer.task.show', compact('task'));
    }
}
