<?php

namespace App\Http\Controllers\Admin;

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
    public function index(Request $request)
    {
        $query = Lead::with(['status', 'service', 'source', 'campaign', 'assignments']);

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

        $leads = $query->orderBy('created_at', 'desc')->get();

        // Statistics
        $totalLeads = Lead::count();
        $statuses = Status::where('type', 'lead')->withCount('leads')->get();
        $convertedLeads = $statuses->where('name', 'Booked')->first()->leads_count ?? 0;
        
        $sources = Source::withCount('leads')->get();
        $services = Service::withCount('leads')->get();
        $campaigns = Campaign::withCount('leads')->get();
        
        $priorityCounts = Lead::groupBy('priority')
            ->select('priority', DB::raw('count(*) as total'))
            ->pluck('total', 'priority')
            ->toArray();

        return view('admin.leads.index', compact(
            'leads', 'totalLeads', 'convertedLeads', 'statuses', 
            'sources', 'services', 'campaigns', 'priorityCounts'
        ));
    }

    public function create()
    {
        $statuses = Status::where('type', 'lead')->get();
        $sales = Sale::all();
        $sources = Source::all();
        $services = Service::all();
        $campaigns = Campaign::all();

        // Detect current user
        $user = auth()->user();
        $createdBy = $user ? $user->id : null;
        $createdByType = $user ? get_class($user) : null;

        return view('admin.leads.create', compact('statuses', 'sales', 'sources', 'services', 'createdBy', 'createdByType', 'campaigns'));
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
            'created_by' => auth()->id(),
            'created_by_type' => get_class(auth()->user()),
        ]);

        // Process assignments
        if ($request->has('assign_to')) {
            foreach ($request->assign_to as $person) {
                LeadAssign::create([
                    'lead_id' => $lead->id,
                    'assigned_to' => $person,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Lead created successfully!');
    }



    public function show($id)
    {
        $lead = Lead::with(['status', 'source', 'service', 'campaign', 'createdBy', 'assignments.sale'])->findOrFail($id);
        $statuses = Status::where('type', 'lead')->get();
        return view('admin.leads.show', compact('lead', 'statuses'));
    }

    public function updateStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $lead->update([
            'status_id' => $request->status_id,
            'priority' => $request->priority,
            'notes' => $request->notes
        ]);
        return redirect()->back()->with('success', 'Lead updated successfully!');
    }

    public function edit($id)
    {
        $lead = Lead::with('assignments')->findOrFail($id);
        $sources = Source::all();
        $services = Service::all();
        $statuses = Status::where('type', 'lead')->get();
        $campaigns = Campaign::all();
        $sales = Sale::all();
        
        return view('admin.leads.edit', compact('lead', 'sources', 'services', 'statuses', 'campaigns', 'sales'));
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

        $lead = Lead::findOrFail($id);

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
        LeadAssign::where('lead_id', $id)->delete();
        if ($request->has('assign_to')) {
            foreach ($request->assign_to as $sale_id) {
                LeadAssign::create([
                    'lead_id' => $lead->id,
                    'assigned_to' => $sale_id,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Lead updated successfully!');
    }

    public function lostedLeads()
    {
        return view('admin.losted-leads');
    }
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }
}
