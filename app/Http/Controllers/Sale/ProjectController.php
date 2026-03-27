<?php

namespace App\Http\Controllers\Sale;

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
    private function getFilteredProjects()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        return Project::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)
              ->where('created_by_type', $saleType);
        })->orWhereHas('salesPersons', function($q) use ($saleId) {
            $q->where('sale_id', $saleId);
        })->orWhereHas('order', function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredProjects()->with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'order.assignments.sale', 'order.createdBy']);

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

        $projects = $query->latest()->paginate(10)->withQueryString();
        
        // Accurate Counts (Only for their projects)
        $totalProjects = $this->getFilteredProjects()->count();
        
        $activeProjects = $this->getFilteredProjects()->whereHas('projectStatus', function($q) {
            $q->where('name', 'development');
        })->count();

        $completedProjects = $this->getFilteredProjects()->whereHas('projectStatus', function($q) {
            $q->where('name', 'complete');
        })->count();

        $onHoldProjects = $this->getFilteredProjects()->whereHas('projectStatus', function($q) {
            $q->where('name', 'on hold');
        })->count();

        $statuses = $this->getStatusOptions();
        $allDevelopers = Developer::all();

        return view('sale.project.index', compact(
            'projects', 
            'totalProjects', 
            'activeProjects', 
            'completedProjects', 
            'onHoldProjects',
            'statuses',
            'allDevelopers'
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
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $orders = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->latest()->get();

        $developers = Developer::latest()->get();
        $salesPersons = \App\Models\Sale::latest()->get();
        $statuses = $this->getStatusOptions();
        return view('sale.project.create', compact('orders', 'developers', 'salesPersons', 'statuses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'project_name' => 'required|string|max:255',
            'project_status_id' => 'required|exists:statuses,id',
        ]);

        $project = Project::create($request->all() + [
            'created_by' => auth()->guard('sale')->id(),
            'created_by_type' => \App\Models\Sale::class,
        ]);

        if ($request->has('assign_to')) {
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $project->salesPersons()->sync($request->sales_person_ids);
        }

        return redirect()->route('sale.projects.index')->with('success', 'Project created successfully!');
    }

    public function show($id)
    {
        $project = $this->getFilteredProjects()->with(['projectStatus', 'paymentStatus', 'developers', 'feedbacks', 'order'])->findOrFail($id);
        $statuses = $this->getStatusOptions();
        return view('sale.project.show', compact('project', 'statuses'));
    }

    public function edit($id)
    {
        $project = $this->getFilteredProjects()->with(['developers', 'salesPersons'])->findOrFail($id);
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $orders = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->latest()->get();

        $developers = Developer::all();
        $salesPersons = \App\Models\Sale::all();
        $statuses = $this->getStatusOptions();
        return view('sale.project.edit', compact('project', 'orders', 'developers', 'salesPersons', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $project = $this->getFilteredProjects()->findOrFail($id);
        $project->update($request->all());

        if ($request->has('assign_to')) {
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $project->salesPersons()->sync($request->sales_person_ids);
        }

        return redirect()->route('sale.projects.index')->with('success', 'Project updated successfully!');
    }

    public function quickUpdate(Request $request, $id)
    {
        $project = $this->getFilteredProjects()->findOrFail($id);
        
        $project->update([
            'project_status_id' => $request->project_status_id,
            'payment_status_id' => $request->payment_status_id,
        ]);

        if ($request->filled('feedback')) {
            ClientFeedback::create([
                'project_id' => $project->id,
                'feedback' => $request->feedback,
                'date' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Project status updated!');
    }

    public function destroy($id)
    {
        $project = $this->getFilteredProjects()->findOrFail($id);
        $project->delete();
        return redirect()->back()->with('success', 'Project deleted successfully!');
    }
}
