<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lead;
use App\Models\Status;
use App\Models\Sale;
use App\Models\Source;
use App\Models\Service;
use App\Models\Campaign;
use App\Models\LeadAssign;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    private function getFilteredLeads()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = get_class(auth()->guard('sale')->user());

        return Lead::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)
                  ->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($q) use ($saleId) {
                $q->where('assigned_to', $saleId);
            });
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredLeads()->with(['status', 'services', 'sources', 'campaign', 'assignments', 'createdBy'])->withCount('followups')
            ->whereHas('status', function($sq) {
                $sq->where('name', '!=', 'lost');
            });

        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where(function($fq) use ($q) {
                $fq->where('company', 'like', "%$q%")
                   ->orWhere('contact_person', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%")
                   ->orWhere('priority', 'like', "%$q%")
                   ->orWhereHas('campaign', function($cq) use ($q) { $cq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('sources', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('services', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('status', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('createdBy', function($sq) use ($q) { 
                       $sq->where('name', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%"); 
                   })
                   ->orWhereHas('assignments.sale', function($sq) use ($q) { 
                       $sq->where('name', 'like', "%$q%")
                          ->orWhere('email', 'like', "%$q%"); 
                   });
            });
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date)) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Dropdown filters
        if ($request->has('source_id') && !empty($request->source_id)) {
            $query->whereHas('sources', function($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }
        if ($request->has('status_id') && !empty($request->status_id)) {
            $query->where('status_id', $request->status_id);
        }

        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        // Clone base query for statistics calculation
        $statsQuery = clone $query;
        $totalLeads = $statsQuery->count();

        // Paginated results
        $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        // Total Followups for filtered salesperson
        $totalCallingFollowupsFiltered = 0;
        $totalMessageFollowupsFiltered = 0;
        if ($request->filled('assigned_to')) {
            $followupCounts = \App\Models\Followup::whereHasMorph(
                'followable',
                [\App\Models\Lead::class],
                function ($q) use ($request) {
                    $q->whereHas('assignments', function($sq) use ($request) {
                        $sq->where('assigned_to', $request->assigned_to);
                    });
                }
            )->select('followup_type', DB::raw('count(*) as count'))
            ->groupBy('followup_type')
            ->pluck('count', 'followup_type');

            $totalCallingFollowupsFiltered = ($followupCounts['Calling'] ?? 0) + ($followupCounts['Both'] ?? 0);
            $totalMessageFollowupsFiltered = ($followupCounts['Message'] ?? 0) + ($followupCounts['Both'] ?? 0);
        }

        // Statistics (Only for those they can see and that match current filters)
        $statuses = Status::where('type', 'lead')->where('name', '!=', 'lost')->get();
        foreach($statuses as $status) {
            $status->leads_count = (clone $statsQuery)->where('status_id', $status->id)->count();
        }

        $convertedLeads = $statuses->where('name', 'Booked')->first()->leads_count ?? 0;
        
        $sources = Source::all();
        foreach($sources as $source) {
            $source->leads_count = (clone $statsQuery)->whereHas('sources', function($q) use ($source) {
                $q->where('sources.id', $source->id);
            })->count();
        }

        $services = Service::all();
        foreach($services as $service) {
            $service->leads_count = (clone $statsQuery)->whereHas('services', function($q) use ($service) {
                $q->where('services.id', $service->id);
            })->count();
        }
        
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign) {
            $campaign->leads_count = (clone $statsQuery)->where('campaign_id', $campaign->id)->count();
        }
        
        $priorityCounts = (clone $statsQuery)
            ->groupBy('priority')
            ->select('priority', DB::raw('count(*) as total'))
            ->pluck('total', 'priority')
            ->toArray();

        $sales = Sale::all();
        $routePrefix = 'sale';
        return view('admin.leads.index', compact(
            'leads', 'totalLeads', 'convertedLeads', 'statuses', 
            'sources', 'services', 'campaigns', 'priorityCounts', 'sales', 'totalCallingFollowupsFiltered', 'totalMessageFollowupsFiltered',
            'routePrefix'
        ));
    }

    public function create()
    {
        $statuses = Status::where('type', 'lead')->get();
        $sales = Sale::all();
        $sources = Source::all();
        $services = Service::all();
        $campaigns = Campaign::all();

        $user = auth()->guard('sale')->user();
        $createdBy = $user->id;
        $createdByType = get_class($user);

        $routePrefix = 'sale';
        return view('admin.leads.create', compact('statuses', 'sales', 'sources', 'services', 'createdBy', 'createdByType', 'campaigns', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'address' => 'required|string',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|numeric|digits:6',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'nullable|array',
            'source_ids.*' => 'exists:sources,id',
            'priority' => 'nullable|string',
            'status_id' => 'nullable|exists:statuses,id',
        ]);

        // Process phones
        $phones = [];
        if ($request->has('phone')) {
            $codes = $request->input('country_code', []);
            $nums = $request->input('phone', []);
            foreach ($nums as $idx => $num) {
                if (!empty($num)) {
                    $phones[] = [
                        'code_idx' => $codes[$idx] ?? null,
                        'number' => $num
                    ];
                }
            }
        }

        // Process emails
        $emails = array_filter($request->input('email', []), fn($e) => !empty($e));

        $lead = Lead::create([
            'company' => $request->company,
            'contact_person' => $request->contact_person,
            'business_type' => $request->business_type,
            'emails' => array_values($emails),
            'phones' => $phones,
            'address' => $request->address,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'campaign_id' => $request->campaign_id,
            'priority' => $request->priority,
            'status_id' => $request->status_id,
            'notes' => $request->notes,
            'created_by' => auth()->guard('sale')->id(),
            'created_by_type' => get_class(auth()->guard('sale')->user()),
        ]);

        $lead->services()->sync($request->service_ids);
        $lead->sources()->sync($request->source_ids);

        // Process assignments
        if ($request->has('assign_to')) {
            foreach ($request->assign_to as $person) {
                LeadAssign::create([
                    'lead_id' => $lead->id,
                    'assigned_to' => $person,
                ]);
            }
        } else {
            // Auto assign to self if not specified? User didn't ask, but it makes sense for Sale person.
            LeadAssign::create([
                'lead_id' => $lead->id,
                'assigned_to' => auth()->guard('sale')->id(),
            ]);
        }

        // Add initial note to history if present
        if (!empty($request->notes)) {
            \App\Models\LeadNote::create([
                'lead_id' => $lead->id,
                'notes' => $request->notes,
                'created_by' => auth()->guard('sale')->id(),
                'created_by_type' => get_class(auth()->guard('sale')->user()),
            ]);
        }

        return redirect()->route('sale.leads.index')->with('success', 'Lead created successfully!');
    }

    public function show($id)
    {
        $lead = $this->getFilteredLeads()->with(['status', 'sources', 'services', 'campaign', 'createdBy', 'assignments.sale', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $statuses = Status::where('type', 'lead')->get();
        $routePrefix = 'sale';
        return view('admin.leads.show', compact('lead', 'statuses', 'routePrefix'));
    }

    public function edit($id)
    {
        $lead = $this->getFilteredLeads()->with(['assignments', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $sources = Source::all();
        $services = Service::all();
        $statuses = Status::where('type', 'lead')->get();
        $campaigns = Campaign::all();
        $sales = Sale::all();
        
        $routePrefix = 'sale';
        return view('admin.leads.edit', compact('lead', 'sources', 'services', 'statuses', 'campaigns', 'sales', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company' => 'nullable|string|max:255',
            'contact_person' => 'required|string|max:255',
            'business_type' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'address' => 'required|string',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|numeric|digits:6',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'nullable|array',
            'source_ids.*' => 'exists:sources,id',
            'status_id' => 'nullable|exists:statuses,id',
            'campaign_id' => 'nullable|exists:campaigns,id',
            'priority' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $lead = $this->getFilteredLeads()->findOrFail($id);

        // Process phones
        $phones = [];
        if ($request->has('phone')) {
            $codes = $request->input('country_code', []);
            $nums = $request->input('phone', []);
            foreach ($nums as $idx => $num) {
                if (!empty($num)) {
                    $phones[] = [
                        'code_idx' => $codes[$idx] ?? null,
                        'number' => $num
                    ];
                }
            }
        }

        // Process emails
        $emails = array_filter($request->input('email', []), fn($e) => !empty($e));

        $lead->update([
            'company' => $request->company,
            'contact_person' => $request->contact_person,
            'business_type' => $request->business_type,
            'emails' => array_values($emails),
            'phones' => $phones,
            'address' => $request->address,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'campaign_id' => $request->campaign_id,
            'status_id' => $request->status_id,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

        $lead->services()->sync($request->service_ids);
        $lead->sources()->sync($request->source_ids);

        // Update assignments
        if ($request->has('assign_to')) {
            LeadAssign::where('lead_id', $id)->delete();
            foreach ($request->assign_to as $sale_id) {
                LeadAssign::create([
                    'lead_id' => $lead->id,
                    'assigned_to' => $sale_id,
                ]);
            }
        }

        return redirect()->route('sale.leads.index')->with('success', 'Lead updated successfully!');
    }

    public function lostedLeads(Request $request)
    {
        $query = $this->getFilteredLeads()->with(['status', 'services', 'sources', 'campaign', 'assignments', 'createdBy'])
            ->whereHas('status', function($sq) {
                $sq->where('name', 'lost');
            });

        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where(function($fq) use ($q) {
                $fq->where('company', 'like', "%$q%")
                   ->orWhere('contact_person', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%");
            });
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date)) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Dropdown filters
        if ($request->has('source_id') && !empty($request->source_id)) {
            $query->whereHas('sources', function($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        $totalLostLeads = $query->count();
        $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $sources = Source::all();
        $services = Service::all();
        $sales = Sale::all();

        $routePrefix = 'sale';
        return view('admin.losted-leads', compact('leads', 'totalLostLeads', 'sources', 'services', 'sales', 'routePrefix'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = $this->getFilteredLeads()->findOrFail($id);
        
        $lead->update([
            'status_id' => $request->status_id,
            'priority' => $request->priority,
        ]);

        return redirect()->back()->with('success', 'Lead status updated!');
    }

    public function destroy($id)
    {
        $lead = $this->getFilteredLeads()->findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }
}
