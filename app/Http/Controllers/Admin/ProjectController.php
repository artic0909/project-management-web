<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Order;
use App\Models\Developer;
use App\Models\ProjectAssign;
use App\Models\ClientFeedback;
use App\Models\Status;
use App\Models\Service;
use App\Models\Source;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Plan;
use App\Models\Sale;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'services', 'sources']);

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace('#PRJ-', '', $q), '0');
            if (empty($cleanId))
                $cleanId = $q;

            $query->where(function ($qq) use ($q, $cleanId) {
                $qq->where('id', 'LIKE', "%$cleanId%")
                    ->orWhere('project_name', 'like', "%$q%")
                    ->orWhere('client_name', 'like', "%$q%")
                    ->orWhere('company_name', 'like', "%$q%")
                    ->orWhere('domain_name', 'like', "%$q%")
                    ->orWhere('emails', 'like', "%$q%")
                    ->orWhere('phones', 'like', "%$q%")
                    ->orWhere('cms_platform', 'like', "%$q%")
                    ->orWhereHas('projectStatus', function ($s) use ($q) {
                        $s->where('name', 'like', "%$q%");
                    })
                    ->orWhereHas('services', function ($s) use ($q) {
                        $s->where('services.name', 'like', "%$q%");
                    })
                    ->orWhereHas('developers', function ($s) use ($q) {
                        $s->where('developers.name', 'like', "%$q%")
                            ->orWhere('developers.email', 'like', "%$q%");
                    })
                    ->orWhereHas('salesPersons', function ($s) use ($q) {
                        $s->where('sales.name', 'like', "%$q%")
                            ->orWhere('sales.email', 'like', "%$q%");
                    })
                    ->orWhereHasMorph('createdBy', [\App\Models\User::class, \App\Models\Sale::class], function ($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%")
                            ->orWhere('email', 'LIKE', "%$q%");
                    });
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
            $query->whereHas('developers', function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }
        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }
        if ($request->filled('source_id')) {
            $query->whereHas('sources', function ($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }
        if ($request->filled('sales_person_id')) {
            $query->whereHas('salesPersons', function ($q) use ($request) {
                $q->where('sale_id', $request->sales_person_id);
            });
        }

        $aggQuery = clone $query;
        $totalProjectsCount = (clone $aggQuery)->count();
        $perPage = $request->get('per_page', 10);
        if ($perPage === 'all') {
            $perPage = $totalProjectsCount ?: 10;
        }

        $projects = $query->latest()->paginate($perPage)->withQueryString();

        // Accurate Counts for KPI Cards (dynamically reflect filters)
        $totalProjects = (clone $aggQuery)->count();

        $activeProjects = (clone $aggQuery)->whereHas('projectStatus', function ($q) {
            $q->where('name', 'development');
        })->count();

        $completedProjects = (clone $aggQuery)->whereHas('projectStatus', function ($q) {
            $q->where('name', 'complete');
        })->count();

        $onHoldProjects = (clone $aggQuery)->whereHas('projectStatus', function ($q) {
            $q->where('name', 'on hold');
        })->count();

        $statuses = $this->getStatusOptions();
        $allDevelopers = Developer::all();
        $allSales = \App\Models\Sale::all();
        $allServices = Service::all();
        $allSources = Source::all();

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
        $orders = Order::latest()->get();
        $developers = Developer::latest()->get();
        $salesPersons = \App\Models\Sale::latest()->get();
        $statuses = $this->getStatusOptions();
        $plans = \App\Models\Plan::all();
        $services = Service::all();
        $sources = Source::all();

        $orderData = $orders->map(function ($o) {
            return [
                'id' => $o->id,
                'company_name' => $o->company_name,
                'client_name' => $o->client_name,
                'emails' => is_array($o->emails) ? array_values($o->emails) : [],
                'phones' => is_array($o->phones) ? array_values($o->phones) : [],
                'domain_name' => $o->domain_name,
                'state' => $o->state,
                'city' => $o->city,
                'zip_code' => $o->zip_code,
                'full_address' => $o->full_address,
                'order_value' => $o->order_value,
                'advance_payment' => $o->advance_payment,
                'delivery_date' => $o->delivery_date ? $o->delivery_date->format('Y-m-d') : null,
                'created_at_val' => $o->created_at ? $o->created_at->format('Y-m-d') : null,
                'plan_name' => $o->plan_name,
                'mkt_username' => $o->mkt_username,
                'mkt_password' => $o->mkt_password,
                'mkt_starting_date' => $o->mkt_starting_date ? \Carbon\Carbon::parse($o->mkt_starting_date)->format('Y-m-d') : null,
                'service_ids' => $o->services->pluck('id')->toArray(),
                'source_ids' => $o->sources->pluck('id')->toArray(),
                'plan_ids' => $o->plans->pluck('id')->toArray(),
                'sales_person_ids' => $o->sales->pluck('id')->toArray(),
            ];
        })->values()->toArray();

        $routePrefix = 'admin';
        return view('admin.project.create', compact('order', 'orders', 'orderData', 'developers', 'salesPersons', 'services', 'sources', 'statuses', 'plans', 'routePrefix'));
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

        // Ensure project_name and client_name are set
        $data['project_name'] = $request->domain_name;
        $data['client_name'] = trim($request->first_name . ' ' . $request->last_name);

        // Map statuses to names for legacy fields if needed
        $status = Status::find($request->project_status_id);
        $data['project_status'] = $status ? $status->name : 'Development';

        if ($request->cms_platform === 'Others' && $request->cms_custom) {
            $data['cms_platform'] = $request->cms_custom;
        }

        $project = Project::create($data);

        if ($request->has('service_ids')) {
            $validServiceIds = \App\Models\Service::whereIn('id', (array) $request->service_ids)->pluck('id')->toArray();
            $project->services()->sync($validServiceIds);
        }
        if ($request->has('plan_ids')) {
            $validPlanIds = \App\Models\Plan::whereIn('id', (array) $request->plan_ids)->pluck('id')->toArray();
            $project->plans()->sync($validPlanIds);
        }
        if ($request->has('source_ids')) {
            $validSourceIds = \App\Models\Source::whereIn('id', (array) $request->source_ids)->pluck('id')->toArray();
            $project->sources()->sync($validSourceIds);
        }

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
            $validSalesIds = \App\Models\Sale::whereIn('id', (array) $request->sales_person_ids)->pluck('id')->toArray();
            $project->salesPersons()->sync($validSalesIds);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully.');
    }

    public function show($id)
    {
        $project = Project::with(['developers', 'order', 'feedbacks', 'projectStatus', 'paymentStatus', 'services', 'sources', 'salesPersons'])->findOrFail($id);
        $statuses = $this->getStatusOptions();
        $routePrefix = 'admin';
        return view('admin.project.show', compact('project', 'statuses', 'routePrefix'));
    }

    public function quickUpdate(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        // Update Statuses & Dates
        $updateData = [
            'project_status_id' => $request->project_status_id,
            'project_status' => Status::find($request->project_status_id)?->name,
        ];

        if ($request->filled('payment_status_id')) {
            $updateData['payment_status_id'] = $request->payment_status_id;
            $updateData['payment_status'] = Status::find($request->payment_status_id)?->name;
        }

        if ($request->filled('expected_delivery_date')) {
            $updateData['expected_delivery_date'] = $request->expected_delivery_date;
        }

        $project->update($updateData);

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
        $plans = \App\Models\Plan::all();
        $services = Service::all();
        $sources = Source::all();

        $orderData = $orders->map(function ($o) {
            return [
                'id' => $o->id,
                'company_name' => $o->company_name,
                'client_name' => $o->client_name,
                'emails' => is_array($o->emails) ? array_values($o->emails) : [],
                'phones' => is_array($o->phones) ? array_values($o->phones) : [],
                'domain_name' => $o->domain_name,
                'state' => $o->state,
                'city' => $o->city,
                'zip_code' => $o->zip_code,
                'full_address' => $o->full_address,
                'order_value' => $o->order_value,
                'advance_payment' => $o->advance_payment,
                'delivery_date' => $o->delivery_date ? $o->delivery_date->format('Y-m-d') : null,
                'created_at_val' => $o->created_at ? $o->created_at->format('Y-m-d') : null,
                'plan_name' => $o->plan_name,
                'mkt_username' => $o->mkt_username,
                'mkt_password' => $o->mkt_password,
                'mkt_starting_date' => $o->mkt_starting_date ? \Carbon\Carbon::parse($o->mkt_starting_date)->format('Y-m-d') : null,
                'service_ids' => $o->services->pluck('id')->toArray(),
                'source_ids' => $o->sources->pluck('id')->toArray(),
                'plan_ids' => $o->plans->pluck('id')->toArray(),
                'sales_person_ids' => $o->sales->pluck('id')->toArray(),
            ];
        })->values()->toArray();

        $routePrefix = 'admin';
        return view('admin.project.edit', compact('project', 'orders', 'orderData', 'developers', 'salesPersons', 'services', 'sources', 'statuses', 'plans', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

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
            $validServiceIds = \App\Models\Service::whereIn('id', (array) $request->service_ids)->pluck('id')->toArray();
            $project->services()->sync($validServiceIds);
        }
        if ($request->has('plan_ids')) {
            $validPlanIds = \App\Models\Plan::whereIn('id', (array) $request->plan_ids)->pluck('id')->toArray();
            $project->plans()->sync($validPlanIds);
        }
        if ($request->has('source_ids')) {
            $validSourceIds = \App\Models\Source::whereIn('id', (array) $request->source_ids)->pluck('id')->toArray();
            $project->sources()->sync($validSourceIds);
        }

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
            $validSalesIds = \App\Models\Sale::whereIn('id', (array) $request->sales_person_ids)->pluck('id')->toArray();
            $project->salesPersons()->sync($validSalesIds);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        return redirect()->route('admin.projects.index')->with('success', 'Project deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:projects,id',
        ]);

        Project::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' projects deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = Project::with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'services', 'sources', 'order']);

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace('#PRJ-', '', $q), '0');
            if (empty($cleanId))
                $cleanId = $q;

            $query->where(function ($qq) use ($q, $cleanId) {
                $qq->where('id', 'LIKE', "%$cleanId%")
                    ->orWhere('project_name', 'like', "%$q%")
                    ->orWhere('client_name', 'like', "%$q%")
                    ->orWhere('company_name', 'like', "%$q%")
                    ->orWhere('domain_name', 'like', "%$q%")
                    ->orWhere('emails', 'like', "%$q%")
                    ->orWhere('phones', 'like', "%$q%")
                    ->orWhere('cms_platform', 'like', "%$q%")
                    ->orWhereHas('projectStatus', function ($s) use ($q) {
                        $s->where('name', 'like', "%$q%");
                    })
                    ->orWhereHas('services', function ($s) use ($q) {
                        $s->where('services.name', 'like', "%$q%");
                    })
                    ->orWhereHas('developers', function ($s) use ($q) {
                        $s->where('developers.name', 'like', "%$q%")
                            ->orWhere('developers.email', 'like', "%$q%");
                    })
                    ->orWhereHas('salesPersons', function ($s) use ($q) {
                        $s->where('sales.name', 'like', "%$q%")
                            ->orWhere('sales.email', 'like', "%$q%");
                    })
                    ->orWhereHasMorph('createdBy', [\App\Models\User::class, \App\Models\Sale::class], function ($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%")
                            ->orWhere('email', 'LIKE', "%$q%");
                    });
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
            $query->whereHas('developers', function ($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }
        if ($request->filled('service_id')) {
            $query->whereHas('services', function ($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }
        if ($request->filled('source_id')) {
            $query->whereHas('sources', function ($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }
        if ($request->filled('sales_person_id')) {
            $query->whereHas('salesPersons', function ($q) use ($request) {
                $q->where('sale_id', $request->sales_person_id);
            });
        }

        $projects = $query->latest()->get();

        $filename = "projects_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($projects) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, [
                'Project ID',
                'Project Code',
                'Order ID',
                'Project Name',
                'Client Name',
                'First Name',
                'Last Name',
                'Company Name',
                'Emails',
                'Phones',
                'City',
                'State',
                'Zip Code',
                'Full Address',
                'Domain Name',
                'Primary Domain Name',
                'Domain Provider Name',
                'Domain Renewal Price',
                'Domain Server Book',
                'Hosting Provider Name',
                'Hosting Renewal Price',
                'CMS Platform',
                'CMS Custom',
                'No Of Pages',
                'Required Features',
                'Extra Features',
                'Reference Websites',
                'Username',
                'Password',
                'No Of Mail IDs',
                'Mail Password',
                'Services',
                'Plans',
                'Sources',
                'Order Date Create',
                'Expected Delivery Date',
                'Project Status',
                'Payment Status',
                'Invoice Number',
                'Assigned Developers',
                'Sales Persons',
                'Created By',
                'Created At'
            ]);

            $codes = [0 => '+93', 1 => '+355', 2 => '+213', 3 => '+376', 4 => '+244', 5 => '+54', 6 => '+61', 7 => '+43', 8 => '+880', 9 => '+32', 10 => '+55', 11 => '+1', 12 => '+86', 13 => '+57', 14 => '+45', 15 => '+20', 16 => '+33', 17 => '+49', 18 => '+233', 19 => '+30', 20 => '+91', 21 => '+62', 22 => '+98', 23 => '+964', 24 => '+353', 25 => '+972', 26 => '+39', 27 => '+81', 28 => '+962', 29 => '+254', 30 => '+965', 31 => '+961', 32 => '+60', 33 => '+52', 34 => '+212', 35 => '+977', 36 => '+31', 37 => '+64', 38 => '+234', 39 => '+47', 40 => '+968', 41 => '+92', 42 => '+63', 43 => '+48', 44 => '+351', 45 => '+974', 46 => '+7', 47 => '+966', 48 => '+65', 49 => '+27', 50 => '+34', 51 => '+94', 52 => '+46', 53 => '+41', 54 => '+886', 55 => '+66', 56 => '+90', 57 => '+971', 58 => '+44', 59 => '+1', 60 => '+84', 61 => '+260', 62 => '+263'];

            foreach ($projects as $project) {
                // Emails
                $emailsDecoded = is_string($project->emails) ? json_decode($project->emails, true) : $project->emails;
                $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');

                // Phones
                $phoneList = is_string($project->phones) ? json_decode($project->phones, true) : $project->phones;
                $phoneList = is_array($phoneList) ? $phoneList : [];
                $fullPhones = [];
                foreach ($phoneList as $p) {
                    if (is_array($p) && isset($p['number'])) {
                        $code = $codes[$p['code_idx'] ?? null] ?? '';
                        $fullPhones[] = $code . ($code ? ' ' : '') . $p['number'];
                    } elseif (is_string($p)) {
                        $fullPhones[] = $p;
                    }
                }
                $phonesStr = implode(', ', $fullPhones);
                if (!empty($phonesStr)) {
                    $phonesStr = "\t" . $phonesStr;
                }

                // Services, Plans, Sources
                $servicesStr = $project->services->pluck('name')->implode(', ');
                $plansStr = $project->plans ? $project->plans->pluck('name')->implode(', ') : '';
                $sourcesStr = $project->sources->pluck('name')->implode(', ');

                // Statuses
                $projStatus = $project->projectStatus->name ?? $project->project_status ?? '';
                $payStatus = $project->paymentStatus->name ?? $project->payment_status ?? '';

                // People
                $createdBy = $project->createdBy ? $project->createdBy->name . ' (' . $project->createdBy->email . ')' : 'System';

                $devs = [];
                foreach ($project->developers as $dev) {
                    $devs[] = $dev->name . ' (' . $dev->email . ')';
                }
                $devStr = implode(', ', $devs);

                $sales = [];
                foreach ($project->salesPersons as $sale) {
                    $sales[] = $sale->name . ' (' . $sale->email . ')';
                }
                $salesStr = implode(', ', $sales);

                fputcsv($file, [
                    '#PRJ-' . $project->id,
                    $project->project_code,
                    $project->order_id ? '#ORD-' . $project->order_id : '',
                    $project->project_name,
                    $project->client_name,
                    $project->first_name,
                    $project->last_name,
                    $project->company_name,
                    $emailsStr,
                    $phonesStr,
                    $project->city,
                    $project->state,
                    $project->zip_code,
                    $project->full_address,
                    $project->domain_name,
                    $project->primary_domain_name,
                    $project->domain_provider_name,
                    $project->domain_renewal_price,
                    $project->domain_server_book,
                    $project->hosting_provider_name,
                    $project->hosting_renewal_price,
                    $project->cms_platform,
                    $project->cms_custom,
                    $project->no_of_pages,
                    $project->required_features,
                    $project->extra_features,
                    $project->reference_websites,
                    $project->username,
                    $project->password,
                    $project->no_of_mail_ids,
                    $project->mail_password,
                    $servicesStr,
                    $plansStr,
                    $sourcesStr,
                    $project->order_date_create ? $project->order_date_create->format('Y-m-d') : '',
                    $project->expected_delivery_date ? $project->expected_delivery_date->format('Y-m-d') : '',
                    $projStatus,
                    $payStatus,
                    $project->invoice_number,
                    $devStr,
                    $salesStr,
                    $createdBy,
                    $project->created_at ? $project->created_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt'
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();
        $handle = fopen($path, 'r');

        // Skip UTF-8 BOM if present
        $bom = fread($handle, 3);
        if ($bom !== "\xef\xbb\xbf") {
            rewind($handle);
        }

        $header = fgetcsv($handle);
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'Empty file provided');
        }

        // Clean headers (remove BOM/white-space)
        $header = array_map(function ($h) {
            return trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h));
        }, $header);

        $rowCount = 0;
        $successCount = 0;
        $errorsList = [];

        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (empty(array_filter($row)))
                    continue;

                // Map headers to row data
                $data = [];
                foreach ($header as $idx => $label) {
                    if (isset($row[$idx])) {
                        $data[$label] = $row[$idx];
                    }
                }

                $rowCount++;

                // Robust mapping with variations
                $projectName = $data['Project Name'] ?? ($data['project_name'] ?? ($data['Project'] ?? ($data['Domain Name'] ?? '')));
                if (empty($projectName)) {
                    $errorsList[] = "Row $rowCount skipped: Project Name missing.";
                    continue;
                }

                $clientName = $data['Client Name'] ?? ($data['client_name'] ?? ($data['Client'] ?? ''));
                $firstName = $data['First Name'] ?? ($data['first_name'] ?? '');
                $lastName = $data['Last Name'] ?? ($data['last_name'] ?? '');

                $emailsStr = $data['Emails'] ?? ($data['emails'] ?? ($data['Email'] ?? ($data['email'] ?? '')));
                $emails = !empty($emailsStr) ? array_filter(array_map('trim', explode(',', $emailsStr))) : [];

                $phonesRaw = $data['Phones'] ?? ($data['phones'] ?? ($data['Phone'] ?? ($data['phone'] ?? '')));
                $phonesArray = [];
                if (!empty($phonesRaw)) {
                    $phonesList = explode(',', $phonesRaw);
                    foreach ($phonesList as $p) {
                        $p = trim($p);
                        if (!empty($p)) {
                            $num = preg_replace('/[^0-9+]/', '', $p);
                            if (!empty($num)) {
                                $phonesArray[] = ['code_idx' => 20, 'number' => $num];
                            }
                        }
                    }
                }

                $projectStatusName = trim($data['Project Status'] ?? ($data['project_status'] ?? 'New'));
                $projectStatus = Status::where('type', 'project')->where('name', 'like', "%$projectStatusName%")->first();
                if (!$projectStatus && !empty($projectStatusName)) {
                    $projectStatus = Status::create(['name' => $projectStatusName, 'type' => 'project']);
                }

                $paymentStatusName = trim($data['Payment Status'] ?? ($data['payment_status'] ?? ''));
                $paymentStatus = !empty($paymentStatusName) ? Status::where('type', 'payment')->where('name', 'like', "%$paymentStatusName%")->first() : null;

                // Project Code handling
                $projectCode = $data['Project Code'] ?? ($data['project_code'] ?? null);
                if ($projectCode && Project::where('project_code', $projectCode)->exists()) {
                    $projectCode = null; // Conflict found, let system generate
                }

                if (!$projectCode) {
                    $lastProject = Project::orderBy('id', 'desc')->first();
                    $lastId = $lastProject ? $lastProject->id : 0;
                    $projectCode = 'PROJ-' . str_pad($lastId + 1, 2, '0', STR_PAD_LEFT);
                }

                $project = Project::create([
                    'project_code' => $projectCode,
                    'order_id' => !empty($data['Order ID']) ? ltrim(str_ireplace('#ORD-', '', $data['Order ID']), '0') : null,
                    'project_name' => $projectName,
                    'client_name' => $clientName,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'emails' => $emails,
                    'phones' => $phonesArray,
                    'company_name' => $data['Company Name'] ?? ($data['company_name'] ?? ''),
                    'city' => $data['City'] ?? ($data['city'] ?? ''),
                    'state' => $data['State'] ?? ($data['state'] ?? ''),
                    'zip_code' => preg_replace('/[^0-9]/', '', $data['Zip Code'] ?? ($data['zip_code'] ?? '')),
                    'full_address' => $data['Full Address'] ?? ($data['full_address'] ?? ''),
                    'domain_name' => $data['Domain Name'] ?? ($data['domain_name'] ?? ''),
                    'primary_domain_name' => $data['Primary Domain Name'] ?? ($data['primary_domain_name'] ?? ''),
                    'domain_provider_name' => $data['Domain Provider Name'] ?? ($data['domain_provider_name'] ?? ''),
                    'domain_renewal_price' => preg_replace('/[^0-9.]/', '', $data['Domain Renewal Price'] ?? '0'),
                    'domain_server_book' => $data['Domain Server Book'] ?? ($data['domain_server_book'] ?? ''),
                    'hosting_provider_name' => $data['Hosting Provider Name'] ?? ($data['hosting_provider_name'] ?? ''),
                    'hosting_renewal_price' => preg_replace('/[^0-9.]/', '', $data['Hosting Renewal Price'] ?? '0'),
                    'cms_platform' => $data['CMS Platform'] ?? ($data['cms_platform'] ?? ''),
                    'cms_custom' => $data['CMS Custom'] ?? ($data['cms_custom'] ?? ''),
                    'no_of_pages' => $data['No Of Pages'] ?? ($data['no_of_pages'] ?? 0),
                    'required_features' => $data['Required Features'] ?? ($data['required_features'] ?? ''),
                    'extra_features' => $data['Extra Features'] ?? ($data['extra_features'] ?? ''),
                    'reference_websites' => $data['Reference Websites'] ?? ($data['reference_websites'] ?? ''),
                    'username' => $data['Username'] ?? ($data['username'] ?? ''),
                    'password' => $data['Password'] ?? ($data['password'] ?? ''),
                    'no_of_mail_ids' => $data['No Of Mail IDs'] ?? ($data['no_of_mail_ids'] ?? 0),
                    'mail_password' => $data['Mail Password'] ?? ($data['mail_password'] ?? ''),
                    'order_date_create' => !empty($data['Order Date Create']) ? date('Y-m-d', strtotime($data['Order Date Create'])) : null,
                    'expected_delivery_date' => !empty($data['Expected Delivery Date']) ? date('Y-m-d', strtotime($data['Expected Delivery Date'])) : null,
                    'project_status_id' => $projectStatus?->id,
                    'payment_status_id' => $paymentStatus?->id,
                    'invoice_number' => $data['Invoice Number'] ?? ($data['invoice_number'] ?? ''),
                    'created_by' => auth()->guard('admin')->id(),
                    'created_by_type' => \App\Models\Admin::class,
                ]);

                // Sync Services
                $rawServices = $data['Services'] ?? ($data['services'] ?? '');
                if (!empty($rawServices)) {
                    $serviceNames = array_map('trim', explode(',', $rawServices));
                    $serviceIds = [];
                    foreach ($serviceNames as $sn) {
                        $s = Service::firstOrCreate(['name' => $sn]);
                        $serviceIds[] = $s->id;
                    }
                    $project->services()->sync($serviceIds);
                }

                // Sync Plans
                $rawPlans = $data['Plans'] ?? ($data['plans'] ?? '');
                if (!empty($rawPlans)) {
                    $planNames = array_map('trim', explode(',', $rawPlans));
                    $planIds = [];
                    foreach ($planNames as $pn) {
                        $p = Plan::firstOrCreate(['name' => $pn]);
                        $planIds[] = $p->id;
                    }
                    $project->plans()->sync($planIds);
                }

                // Sync Sources
                $rawSources = $data['Sources'] ?? ($data['sources'] ?? '');
                if (!empty($rawSources)) {
                    $sourceNames = array_map('trim', explode(',', $rawSources));
                    $sourceIds = [];
                    foreach ($sourceNames as $sn) {
                        $s = Source::firstOrCreate(['name' => $sn]);
                        $sourceIds[] = $s->id;
                    }
                    $project->sources()->sync($sourceIds);
                }

                // Sync Developers
                $rawDevs = $data['Assigned Developers'] ?? ($data['developers'] ?? '');
                if (!empty($rawDevs)) {
                    $devEntries = array_map('trim', explode(',', $rawDevs));
                    $devIds = [];
                    foreach ($devEntries as $de) {
                        $name = trim(explode('(', $de)[0]);
                        $dev = Developer::where('name', 'like', "%$name%")->first();
                        if ($dev) {
                            $devIds[] = $dev->id;
                        }
                    }
                    $project->developers()->sync($devIds);
                }

                // Sync Sales Persons
                $rawSales = $data['Sales Persons'] ?? ($data['sales_persons'] ?? '');
                if (!empty($rawSales)) {
                    $salesEntries = array_map('trim', explode(',', $rawSales));
                    $saleIds = [];
                    foreach ($salesEntries as $se) {
                        $name = trim(explode('(', $se)[0]);
                        $sale = Sale::where('name', 'like', "%$name%")->first();
                        if ($sale) {
                            $saleIds[] = $sale->id;
                        }
                    }
                    $project->salesPersons()->sync($saleIds);
                }

                $successCount++;
            }
            DB::commit();
            fclose($handle);

            $msg = "Imported $successCount of $rowCount projects successfully.";
            if (!empty($errorsList)) {
                $msg .= " Note: " . implode(' ', $errorsList);
            }
            return back()->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->with('error', 'Error at row ' . ($rowCount + 1) . ': ' . $e->getMessage());
        }
    }
}
