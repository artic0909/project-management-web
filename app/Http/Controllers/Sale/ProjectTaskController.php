<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectTask;
use App\Models\ProjectTaskAssign;
use App\Models\Developer;
use Illuminate\Support\Facades\DB;

class ProjectTaskController extends Controller
{
    private function getScopedProject($projectId)
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = get_class(auth()->guard('sale')->user());

        return Project::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)
              ->where('created_by_type', $saleType);
        })->orWhereHas('salesPersons', function($q) use ($saleId) {
            $q->where('sale_id', $saleId);
        })->findOrFail($projectId);
    }

    public function index($projectId)
    {
        $project = $this->getScopedProject($projectId);
        $project->load(['tasks.assignments.developer', 'tasks.creator']);
        $developers = Developer::all();
        return view('sale.project.tasks', compact('project', 'developers'));
    }

    public function store(Request $request, $projectId)
    {
        $project = $this->getScopedProject($projectId);

        $request->validate([
            'title' => 'required|string|max:255',
            'task' => 'required',
            'developer_id' => 'nullable|exists:developers,id',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $task = ProjectTask::create([
                'project_id' => $project->id,
                'title' => $request->title,
                'task' => $request->task,
                'status' => 'Pending',
                'created_by' => auth()->guard('sale')->id(),
                'created_by_type' => get_class(auth()->guard('sale')->user()),
            ]);

            if ($request->developer_id) {
                ProjectTaskAssign::create([
                    'project_id' => $project->id,
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
