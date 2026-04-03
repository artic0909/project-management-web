<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Order;
use App\Models\Status;
use App\Models\Service;
use App\Models\Source;
use App\Models\Developer;
use App\Models\Sale;
use App\Models\ProjectAssign;
use App\Models\ClientFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    private function getFilteredProjects()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        return Project::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)
                  ->where('created_by_type', $saleType);
            })->orWhereHas('salesPersons', function($q) use ($saleId) {
                $q->where('sale_id', $saleId);
            })->orWhereHas('order', function($q) use ($saleId, $saleType) {
                $q->where(function($sq) use ($saleId, $saleType) {
                    $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            });
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredProjects()->with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'order.assignments.sale', 'order.createdBy', 'services', 'sources']);

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

        if ($request->filled('service_id')) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }
        if ($request->filled('source_id')) {
            $query->whereHas('sources', function($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
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
        $allSales = \App\Models\Sale::all();
        $allServices = \App\Models\Service::all();
        $allSources = \App\Models\Source::all();

        $routePrefix = 'sale';
        return view('admin.project.index', compact(
            'projects', 
            'totalProjects', 
            'activeProjects', 
            'completedProjects', 
            'onHoldProjects',
            'statuses',
            'allDevelopers',
            'allSales',
            'allServices',
            'allSources',
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

    public function create($order_id = null)
    {
        $order = $order_id ? Order::find($order_id) : null;
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $orders = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->latest()->get();

        $developers = Developer::latest()->get();
        $salesPersons = Sale::latest()->get();
        $statuses = $this->getStatusOptions();
        $plans = \App\Models\Plan::all();
        $services = Service::all();
        $sources = Source::all();
        $routePrefix = 'sale';
        return view('admin.project.create', compact('order', 'orders', 'developers', 'salesPersons', 'statuses', 'services', 'sources', 'plans', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'full_address' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'domain_name' => 'required|string|max:255',
            'plan_ids' => 'required|array|min:1',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'domain_provider_name' => 'required|string|max:255',
            'domain_renewal_price' => 'required|numeric|min:0',
            'hosting_provider_name' => 'required|string|max:255',
            'hosting_renewal_price' => 'required|numeric|min:0',
            'primary_domain_name' => 'required|string|max:255',
            'cms_platform' => 'required|string|max:255',
            'order_date_create' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'project_price' => 'nullable|numeric',
            'project_status_id' => 'required|exists:statuses,id',
        ]);

        $data = $request->all();
        $data['emails'] = $request->email ?? [];
        
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

        $data['created_by'] = Auth::id() ?? auth()->guard('sale')->id();
        $data['created_by_type'] = auth()->guard('sale')->check() ? \App\Models\Sale::class : get_class(auth()->user());
        
        // Ensure project_name and client_name are set
        $data['project_name'] = $request->domain_name;
        $data['client_name'] = trim($request->first_name . ' ' . $request->last_name);

        // Map statuses to names
        $status = Status::find($request->project_status_id);
        $data['project_status'] = $status ? $status->name : 'Development';

        if ($request->cms_platform === 'Others' && $request->cms_custom) {
            $data['cms_platform'] = $request->cms_custom;
        }

        $project = Project::create($data);

        if ($request->has('service_ids')) {
            $validServiceIds = \App\Models\Service::whereIn('id', (array)$request->service_ids)->pluck('id')->toArray();
            $project->services()->sync($validServiceIds);
        }
        if ($request->has('plan_ids')) {
            $validPlanIds = \App\Models\Plan::whereIn('id', (array)$request->plan_ids)->pluck('id')->toArray();
            $project->plans()->sync($validPlanIds);
        }
        if ($request->has('source_ids')) {
            $validSourceIds = \App\Models\Source::whereIn('id', (array)$request->source_ids)->pluck('id')->toArray();
            $project->sources()->sync($validSourceIds);
        }

        if ($request->has('assign_to')) {
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $validSalesIds = \App\Models\Sale::whereIn('id', (array)$request->sales_person_ids)->pluck('id')->toArray();
            $project->salesPersons()->sync($validSalesIds);
        }

        return redirect()->route('sale.projects.index')->with('success', 'Project created successfully!');
    }

    public function show($id)
    {
        $project = $this->getFilteredProjects()->with(['projectStatus', 'paymentStatus', 'developers', 'feedbacks', 'order', 'services', 'sources', 'salesPersons'])->findOrFail($id);
        $statuses = $this->getStatusOptions();
        $routePrefix = 'sale';
        return view('admin.project.show', compact('project', 'statuses', 'routePrefix'));
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
        $plans = \App\Models\Plan::all();
        $services = \App\Models\Service::all();
        $sources = \App\Models\Source::all();
        $routePrefix = 'sale';
        return view('admin.project.edit', compact('project', 'orders', 'developers', 'salesPersons', 'statuses', 'services', 'sources', 'plans', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $project = $this->getFilteredProjects()->findOrFail($id);
        
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'company_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|string|max:20',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'full_address' => 'required|string',
            'zip_code' => 'required|string|max:10',
            'domain_name' => 'required|string|max:255',
            'plan_ids' => 'required|array|min:1',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'domain_provider_name' => 'required|string|max:255',
            'domain_renewal_price' => 'required|numeric|min:0',
            'hosting_provider_name' => 'required|string|max:255',
            'hosting_renewal_price' => 'required|numeric|min:0',
            'primary_domain_name' => 'required|string|max:255',
            'cms_platform' => 'required|string|max:255',
            'order_date_create' => 'required|date',
            'expected_delivery_date' => 'nullable|date',
            'project_price' => 'nullable|numeric',
            'project_status_id' => 'required|exists:statuses,id',
        ]);

        $data = $request->all();
        $data['emails'] = $request->email ?? [];
        
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

        // Ensure project_name and client_name are updated
        $data['project_name'] = $request->domain_name;
        $data['client_name'] = trim($request->first_name . ' ' . $request->last_name);

        // Map statuses to names
        $status = Status::find($request->project_status_id);
        $data['project_status'] = $status ? $status->name : $project->project_status;

        if ($request->cms_platform === 'Others' && $request->cms_custom) {
            $data['cms_platform'] = $request->cms_custom;
        }
        
        $project->update($data);

        if ($request->has('service_ids')) {
            $validServiceIds = \App\Models\Service::whereIn('id', (array)$request->service_ids)->pluck('id')->toArray();
            $project->services()->sync($validServiceIds);
        }
        if ($request->has('plan_ids')) {
            $validPlanIds = \App\Models\Plan::whereIn('id', (array)$request->plan_ids)->pluck('id')->toArray();
            $project->plans()->sync($validPlanIds);
        }
        if ($request->has('source_ids')) {
            $validSourceIds = \App\Models\Source::whereIn('id', (array)$request->source_ids)->pluck('id')->toArray();
            $project->sources()->sync($validSourceIds);
        }

        if ($request->has('assign_to')) {
            $project->developers()->sync($request->assign_to);
        }

        if ($request->has('sales_person_ids')) {
            $validSalesIds = \App\Models\Sale::whereIn('id', (array)$request->sales_person_ids)->pluck('id')->toArray();
            $project->salesPersons()->sync($validSalesIds);
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
