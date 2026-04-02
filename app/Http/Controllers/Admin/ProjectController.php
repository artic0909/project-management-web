<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Order;
use App\Models\Developer;
use App\Models\ProjectAssign;
use App\Models\ClientFeedback;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy']);

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qq) use ($q) {
                $qq->where('project_name', 'like', "%$q%")
                   ->orWhere('client_name', 'like', "%$q%")
                   ->orWhere('company_name', 'like', "%$q%")
                   ->orWhere('domain_name', 'like', "%$q%");
            });
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Project Status Filter
        if ($request->filled('project_status_id')) {
            $query->where('project_status_id', $request->project_status_id);
        }

        // Payment Status Filter
        if ($request->filled('payment_status_id')) {
            $query->where('payment_status_id', $request->payment_status_id);
        }
        if ($request->filled('assigned_to')) {
            $query->whereHas('developers', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }
        if ($request->filled('sales_person_id')) {
            $query->whereHas('salesPersons', function($q) use ($request) {
                $q->where('sale_id', $request->sales_person_id);
            });
        }

        $projects = $query->latest()->paginate(10)->withQueryString();
        
        // Accurate Counts for KPI Cards (independent of pagination/filters for the summary)
        $totalProjects = Project::count();
        
        $activeProjects = Project::whereHas('projectStatus', function($q) {
            $q->where('name', 'development');
        })->count();

        $completedProjects = Project::whereHas('projectStatus', function($q) {
            $q->where('name', 'complete');
        })->count();

        $onHoldProjects = Project::whereHas('projectStatus', function($q) {
            $q->where('name', 'on hold');
        })->count();

        $statuses = $this->getStatusOptions();
        $allDevelopers = Developer::all();
        $allSales = \App\Models\Sale::all();

        $routePrefix = 'admin';
        return view('admin.project.index', compact(
            'projects', 
            'totalProjects', 
            'activeProjects', 
            'completedProjects', 
            'onHoldProjects',
            'statuses',
            'allDevelopers',
            'allSales',
            'routePrefix'
        ));
    }

    private function getStatusOptions()
    {
        return [
            'project_statuses' => Status::where('type', 'project')->get(),
            'payment_statuses' => Status::where('type', 'payment')->get(),
        ];
    }

    public function create()
    {
        $orders = Order::latest()->get();
        $developers = Developer::latest()->get();
        $salesPersons = \App\Models\Sale::latest()->get();
        $statuses = $this->getStatusOptions();
        $routePrefix = 'admin';
        return view('admin.project.create', compact('orders', 'developers', 'salesPersons', 'statuses', 'routePrefix'));
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

        // Map statuses to IDs if provided
        if ($request->project_status_id) {
            $data['project_status'] = Status::find($request->project_status_id)?->name;
        }
        if ($request->payment_status_id) {
            $data['payment_status'] = Status::find($request->payment_status_id)?->name;
        }

        $project = Project::create($data);

        // Historical Logging (if any fields provided)
        if ($request->anyFilled(['last_update_date', 'client_feedback_summary', 'internal_notes'])) {
            $project->feedbacks()->create([
                'status' => $project->project_status,
                'last_update_date' => $request->last_update_date,
                'feedback_summary' => $request->client_feedback_summary,
                'internal_notes' => $request->internal_notes,
            ]);
        }

        if ($request->has('assign_to')) {
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $project->salesPersons()->sync($request->sales_person_ids);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = Project::with(['developers', 'order', 'feedbacks', 'projectStatus', 'paymentStatus'])->findOrFail($id);
        $statuses = $this->getStatusOptions();
        $routePrefix = 'admin';
        return view('admin.project.show', compact('project', 'statuses', 'routePrefix'));
    }

    public function quickUpdate(Request $request, $id)
    {
        $project = Project::findOrFail($id);
        
        // Update Statuses
        $project->update([
            'project_status_id' => $request->project_status_id,
            'payment_status_id' => $request->payment_status_id,
            'project_status' => Status::find($request->project_status_id)?->name,
            'payment_status' => Status::find($request->payment_status_id)?->name,
        ]);

        // Add Feedback Log if notes provided
        if ($request->filled('internal_notes') || $request->filled('feedback_summary')) {
            $project->feedbacks()->create([
                'status' => $project->project_status,
                'last_update_date' => now(),
                'feedback_summary' => $request->feedback_summary ?? 'Quick status update',
                'internal_notes' => $request->internal_notes,
            ]);
        }

        return back()->with('success', 'Project updated successfully.');
    }

    public function edit($id)
    {
        $project = Project::with(['developers', 'salesPersons'])->findOrFail($id);
        $orders = Order::latest()->get();
        $developers = Developer::latest()->get();
        $salesPersons = \App\Models\Sale::latest()->get();
        $statuses = $this->getStatusOptions();
        $routePrefix = 'admin';
        return view('admin.project.edit', compact('project', 'orders', 'developers', 'salesPersons', 'statuses', 'routePrefix'));
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

        // Map statuses to IDs if provided
        if ($request->project_status_id) {
            $data['project_status'] = Status::find($request->project_status_id)?->name;
        }
        if ($request->payment_status_id) {
            $data['payment_status'] = Status::find($request->payment_status_id)?->name;
        }
        
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
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $project->salesPersons()->sync($request->sales_person_ids);
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
