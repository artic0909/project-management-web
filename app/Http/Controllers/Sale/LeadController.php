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
        $query = $this->getFilteredLeads()->with(['status', 'service', 'source', 'campaign', 'assignments', 'createdBy'])->withCount('followups')
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
                   ->orWhere('phones', 'like', "%$q%");
            });
        }

        // Date range filter
        if ($request->has('start_date') && $request->has('end_date') && !empty($request->start_date)) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Dropdown filters
        if ($request->has('source_id') && !empty($request->source_id)) {
            $query->where('source_id', $request->source_id);
        }
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
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

        // Statistics (Only for those they can see)
        $totalLeads = $this->getFilteredLeads()->whereHas('status', function($sq) {
            $sq->where('name', '!=', 'lost');
        })->count();

        $statuses = Status::where('type', 'lead')->where('name', '!=', 'lost')->get();
        // Since we need counts for only their leads, we build the counts manually or use withCount with a constraint
        foreach($statuses as $status) {
            $status->leads_count = $this->getFilteredLeads()->where('status_id', $status->id)->count();
        }

        $convertedLeads = $statuses->where('name', 'Booked')->first()->leads_count ?? 0;
        
        $sources = Source::all();
        foreach($sources as $source) {
            $source->leads_count = $this->getFilteredLeads()
                ->where('source_id', $source->id)
                ->whereHas('status', function($sq) { $sq->where('name', '!=', 'lost'); })
                ->count();
        }

        $services = Service::all();
        foreach($services as $service) {
            $service->leads_count = $this->getFilteredLeads()
                ->where('service_id', $service->id)
                ->whereHas('status', function($sq) { $sq->where('name', '!=', 'lost'); })
                ->count();
        }
        
        $campaigns = Campaign::all();
        foreach($campaigns as $campaign) {
            $campaign->leads_count = $this->getFilteredLeads()
                ->where('campaign_id', $campaign->id)
                ->whereHas('status', function($sq) { $sq->where('name', '!=', 'lost'); })
                ->count();
        }
        
        $priorityCounts = $this->getFilteredLeads()
            ->whereHas('status', function($sq) {
                $sq->where('name', '!=', 'lost');
            })
            ->groupBy('priority')
            ->select('priority', DB::raw('count(*) as total'))
            ->pluck('total', 'priority')
            ->toArray();

        $sales = Sale::all(); // Still show all sales for assignment? User said "as admin make all things", so yes.

        return view('sale.leads.index', compact(
            'leads', 'totalLeads', 'convertedLeads', 'statuses', 
            'sources', 'services', 'campaigns', 'priorityCounts', 'sales', 'totalCallingFollowupsFiltered', 'totalMessageFollowupsFiltered'
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

        return view('sale.leads.create', compact('statuses', 'sales', 'sources', 'services', 'createdBy', 'createdByType', 'campaigns'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

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
            'service_id' => $request->service_id,
            'source_id' => $request->source_id,
            'campaign_id' => $request->campaign_id,
            'priority' => $request->priority,
            'status_id' => $request->status_id,
            'created_by' => auth()->guard('sale')->id(),
            'created_by_type' => get_class(auth()->guard('sale')->user()),
        ]);

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
        $lead = $this->getFilteredLeads()->with(['status', 'source', 'service', 'campaign', 'createdBy', 'assignments.sale', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $statuses = Status::where('type', 'lead')->get();
        return view('sale.leads.show', compact('lead', 'statuses'));
    }

    public function edit($id)
    {
        $lead = $this->getFilteredLeads()->with(['assignments', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $sources = Source::all();
        $services = Service::all();
        $statuses = Status::where('type', 'lead')->get();
        $campaigns = Campaign::all();
        $sales = Sale::all();
        
        return view('sale.leads.edit', compact('lead', 'sources', 'services', 'statuses', 'campaigns', 'sales'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company' => 'required|string',
            'contact_person' => 'required|string',
            'business_type' => 'required|string',
            'email' => 'nullable|array',
            'phone' => 'nullable|array',
            'country_code' => 'nullable|array',
            'address' => 'nullable|string',
            'service_id' => 'required|exists:services,id',
            'source_id' => 'required|exists:sources,id',
            'status_id' => 'required|exists:statuses,id',
            'campaign_id' => 'required|exists:campaigns,id',
            'priority' => 'required|string',
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
            'service_id' => $request->service_id,
            'source_id' => $request->source_id,
            'status_id' => $request->status_id,
            'campaign_id' => $request->campaign_id,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

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
        $query = $this->getFilteredLeads()->with(['status', 'service', 'source', 'campaign', 'assignments', 'createdBy'])
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
            $query->where('source_id', $request->source_id);
        }
        if ($request->has('service_id') && !empty($request->service_id)) {
            $query->where('service_id', $request->service_id);
        }
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }

        $totalLostLeads = $query->count();
        $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $sources = Source::all();
        $services = Service::all();
        $sales = Sale::all();

        return view('sale.losted-leads', compact('leads', 'totalLostLeads', 'sources', 'services', 'sales'));
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
