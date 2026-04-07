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
        $query = Lead::with(['status', 'services', 'sources', 'campaign', 'assignments', 'createdBy'])->withCount('followups')
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
        // Dropdown filters
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

        // Counts by priority (using filtered query)
        $priorityCounts = (clone $statsQuery)->groupBy('priority')
            ->select('priority', DB::raw('count(*) as total'))
            ->pluck('total', 'priority')
            ->toArray();

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

        $sales = Sale::all();

        $routePrefix = 'admin';
        return view('admin.leads.index', compact(
            'leads', 'totalLeads', 'convertedLeads', 'statuses', 
            'sources', 'services', 'campaigns', 'priorityCounts', 'sales', 
            'totalCallingFollowupsFiltered', 'totalMessageFollowupsFiltered', 'routePrefix'
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

        $routePrefix = 'admin';
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
            'created_by' => auth()->id(),
            'created_by_type' => get_class(auth()->user()),
        ]);

        $lead->services()->sync($request->service_ids);
        $lead->sources()->sync($request->source_ids);

        // Add initial note to history if present
        if (!empty($request->notes)) {
            \App\Models\LeadNote::create([
                'lead_id' => $lead->id,
                'notes' => $request->notes,
                'created_by' => auth()->id(),
                'created_by_type' => get_class(auth()->user()),
            ]);
        }

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
        $lead = Lead::with(['status', 'sources', 'services', 'campaign', 'createdBy', 'assignments.sale', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $statuses = Status::where('type', 'lead')->get();
        $routePrefix = 'admin';
        return view('admin.leads.show', compact('lead', 'statuses', 'routePrefix'));
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
        $lead = Lead::with(['assignments', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $sources = Source::all();
        $services = Service::all();
        $statuses = Status::where('type', 'lead')->get();
        $campaigns = Campaign::all();
        $sales = Sale::all();
        
        $routePrefix = 'admin';
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
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'status_id' => $request->status_id,
            'campaign_id' => $request->campaign_id,
            'priority' => $request->priority,
            'notes' => $request->notes,
        ]);

        $lead->services()->sync($request->service_ids);
        $lead->sources()->sync($request->source_ids);

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

    public function lostedLeads(Request $request)
    {
        $query = Lead::with(['status', 'services', 'sources', 'campaign', 'assignments', 'createdBy'])
            ->whereHas('status', function($sq) {
                $sq->where('name', 'lost');
            });

        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace(['#LD-', '#LD0'], '', $q), '0');
            if(empty($cleanId)) $cleanId = $q;
            
            $query->where(function($fq) use ($q, $cleanId) {
                $fq->where('id', 'LIKE', "%$cleanId%")
                   ->orWhere('company', 'like', "%$q%")
                   ->orWhere('contact_person', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%")
                   ->orWhere('priority', 'like', "%$q%")
                   ->orWhereHas('campaign', function($cq) use ($q) { $cq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('sources', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('services', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
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
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        $totalLostLeads = $query->count();
        $leads = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $sources = Source::all();
        $services = Service::all();
        $sales = Sale::all();

        $routePrefix = 'admin';
        return view('admin.losted-leads', compact('leads', 'totalLostLeads', 'sources', 'services', 'sales', 'routePrefix'));
    }
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->back()->with('success', 'Lead deleted successfully!');
    }


    public function export(Request $request)
    {
        $query = Lead::with(['status', 'services', 'sources', 'campaign', 'assignments.sale', 'createdBy'])
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

        $leads = $query->orderBy('created_at', 'desc')->get();

        $filename = "leads_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($leads) {
            $file = fopen('php://output', 'w');
            // Adding BOM for Excel
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID',
                'Date',
                'Company Name',
                'Business Type',
                'Contact Person',
                'Emails',
                'Phones',
                'Campaign / Source',
                'Services',
                'Priority',
                'Status',
                'Created By',
                'Sales Person',
                'Address',
                'State',
                'Zip Code',
            ]);

            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

            foreach ($leads as $lead) {
                // Emails
                $emailsDecoded = is_string($lead->emails) ? json_decode($lead->emails, true) : $lead->emails;
                $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');
                
                // Phones
                $phoneList = is_string($lead->phones) ? json_decode($lead->phones, true) : $lead->phones;
                $phoneList = is_array($phoneList) ? $phoneList : [];
                $fullPhones = [];
                foreach($phoneList as $p) {
                    if (isset($p['number'])) {
                        $code = $codes[$p['code_idx'] ?? null] ?? '';
                        $fullPhones[] = $code . ($code ? ' ' : '') . $p['number'];
                    }
                }
                $phonesStr = implode(', ', $fullPhones);
                if (!empty($phonesStr)) {
                    $phonesStr = "\t" . $phonesStr;
                }
                
                // Campaign & Source
                $campaign = $lead->campaign->name ?? '';
                $sources = $lead->sources->pluck('name')->implode(', ');
                $campaignSource = trim($campaign . ($campaign && $sources ? ' / ' : '') . $sources);
                
                // Services
                $servicesStr = $lead->services->pluck('name')->implode(', ');
                
                // Created By
                $createdBy = $lead->createdBy ? $lead->createdBy->name . ' (' . $lead->createdBy->email . ')' : 'System';
                
                // Sales Person
                $salesPersons = [];
                foreach($lead->assignments as $assign) {
                    if($assign->sale) {
                        $salesPersons[] = $assign->sale->name . ' (' . $assign->sale->email . ')';
                    }
                }
                $salesPersonStr = implode(', ', $salesPersons);

                fputcsv($file, [
                    $lead->id,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->company,
                    $lead->business_type,
                    $lead->contact_person,
                    $emailsStr,
                    $phonesStr,
                    $campaignSource,
                    $servicesStr,
                    $lead->priority,
                    $lead->status->name ?? '',
                    $createdBy,
                    $salesPersonStr,
                    $lead->address,
                    $lead->state,
                    $lead->zip_code,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function lostedLeadExport(Request $request)
    {
        $query = Lead::with(['status', 'services', 'sources', 'campaign', 'assignments.sale', 'createdBy'])
            ->whereHas('status', function($sq) {
                $sq->where('name', 'lost');
            });

        // Search filter
        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace(['#LD-', '#LD0'], '', $q), '0');
            if(empty($cleanId)) $cleanId = $q;
            
            $query->where(function($fq) use ($q, $cleanId) {
                $fq->where('id', 'LIKE', "%$cleanId%")
                   ->orWhere('company', 'like', "%$q%")
                   ->orWhere('contact_person', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%")
                   ->orWhere('priority', 'like', "%$q%")
                   ->orWhereHas('campaign', function($cq) use ($q) { $cq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('sources', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
                   ->orWhereHas('services', function($sq) use ($q) { $sq->where('name', 'like', "%$q%"); })
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
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        $filename = "losted_leads_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($leads) {
            $file = fopen('php://output', 'w');
            // Adding BOM for Excel
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'ID',
                'Date',
                'Company Name',
                'Business Type',
                'Contact Person',
                'Emails',
                'Phones',
                'Campaign / Source',
                'Services',
                'Priority',
                'Status',
                'Created By',
                'Sales Person',
                'Address',
                'State',
                'Zip Code',
         
            ]);

            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

            foreach ($leads as $lead) {
                // Emails
                $emailsDecoded = is_string($lead->emails) ? json_decode($lead->emails, true) : $lead->emails;
                $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');
                
                // Phones
                $phoneList = is_string($lead->phones) ? json_decode($lead->phones, true) : $lead->phones;
                $phoneList = is_array($phoneList) ? $phoneList : [];
                $fullPhones = [];
                foreach($phoneList as $p) {
                    if (isset($p['number'])) {
                        $code = $codes[$p['code_idx'] ?? null] ?? '';
                        $fullPhones[] = $code . ($code ? ' ' : '') . $p['number'];
                    }
                }
                $phonesStr = implode(', ', $fullPhones);
                if (!empty($phonesStr)) {
                    $phonesStr = "\t" . $phonesStr;
                }
                
                // Campaign & Source
                $campaign = $lead->campaign->name ?? '';
                $sources = $lead->sources->pluck('name')->implode(', ');
                $campaignSource = trim($campaign . ($campaign && $sources ? ' / ' : '') . $sources);
                
                // Services
                $servicesStr = $lead->services->pluck('name')->implode(', ');
                
                // Created By
                $createdBy = $lead->createdBy ? $lead->createdBy->name . ' (' . $lead->createdBy->email . ')' : 'System';
                
                // Sales Person
                $salesPersons = [];
                foreach($lead->assignments as $assign) {
                    if($assign->sale) {
                        $salesPersons[] = $assign->sale->name . ' (' . $assign->sale->email . ')';
                    }
                }
                $salesPersonStr = implode(', ', $salesPersons);

                fputcsv($file, [
                    $lead->id,
                    $lead->created_at->format('Y-m-d H:i:s'),
                    $lead->company,
                    $lead->business_type,
                    $lead->contact_person,
                    $emailsStr,
                    $phonesStr,
                    $campaignSource,
                    $servicesStr,
                    $lead->priority,
                    $lead->status->name ?? '',
                    $createdBy,
                    $salesPersonStr,
                    $lead->address,
                    $lead->state,
                    $lead->zip_code,
               
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
        $header = array_map(function($h) {
            return trim(preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $h));
        }, $header);

        $rowCount = 0;
        $successCount = 0;
        
        DB::beginTransaction();
        try {
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) < 3) continue; // Skip near-empty lines
                
                // Map headers to row data, ignoring extra columns
                $data = [];
                foreach($header as $idx => $label) {
                    if (isset($row[$idx])) {
                        $data[$label] = $row[$idx];
                    }
                }

                $rowCount++;

                // Robust mapping with variations
                $companyName = $data['Company Name'] ?? ($data['company'] ?? ($data['Company'] ?? ''));
                if (empty($companyName)) {
                    // Try column 2 if headers are totally missing/wrong
                    $companyName = isset($row[2]) ? $row[2] : '';
                }
                if (empty($companyName)) continue;

                $contactPerson = $data['Contact Person'] ?? ($data['contact_person'] ?? ($data['Contact'] ?? ($row[3] ?? '')));

                $emailsStr = $data['Emails'] ?? ($data['emails'] ?? ($data['Email'] ?? ''));
                $emails = !empty($emailsStr) ? array_filter(array_map('trim', explode(',', $emailsStr))) : [];

                $phonesRaw = $data['Phones'] ?? ($data['phones'] ?? ($data['Phone'] ?? ''));
                $phonesArray = [];
                if (!empty($phonesRaw)) {
                    $phonesList = explode(',', $phonesRaw);
                    foreach($phonesList as $p) {
                        $p = trim($p);
                        if (!empty($p)) {
                            // Keep original for visibility or clean it
                            $num = preg_replace('/[^0-9+]/', '', $p); 
                            if (!empty($num)) {
                                $phonesArray[] = ['code_idx' => 20, 'number' => $num];
                            }
                        }
                    }
                }

                $campaignSourceStr = $data['Campaign / Source'] ?? ($data['campaign_source'] ?? '');
                $campaignName = '';
                $sourceNames = [];
                if (!empty($campaignSourceStr)) {
                    $parts = explode('/', $campaignSourceStr);
                    $campaignName = trim($parts[0]);
                    if (count($parts) > 1) {
                        $sourceNames = array_map('trim', explode(',', $parts[1]));
                    }
                } else {
                    // Try individual columns if they exist
                    $campaignName = $data['Campaign'] ?? '';
                    $sourcesStr = $data['Source'] ?? ($data['Sources'] ?? '');
                    if (!empty($sourcesStr)) {
                        $sourceNames = array_map('trim', explode(',', $sourcesStr));
                    }
                }

                $servicesStr = $data['Services'] ?? ($data['services'] ?? ($data['Service'] ?? ''));
                $serviceNames = !empty($servicesStr) ? array_map('trim', explode(',', $servicesStr)) : [];

                $statusName = trim($data['Status'] ?? ($data['status'] ?? 'interested'));
                if (empty($statusName)) $statusName = 'interested';

                $priority = strtolower(trim($data['Priority'] ?? ($data['priority'] ?? 'warm')));
                if (empty($priority)) $priority = 'warm';

                // Find or create relationship models
                $status = Status::firstOrCreate(['name' => $statusName]);
                $campaign = !empty($campaignName) ? Campaign::firstOrCreate(['name' => $campaignName]) : null;

                // Identify IDs for single-column relations (source_id and service_id)
                $firstSourceId = null;
                if (!empty($sourceNames)) {
                    $firstSource = Source::firstOrCreate(['name' => $sourceNames[0]]);
                    $firstSourceId = $firstSource->id;
                }

                $firstServiceId = null;
                if (!empty($serviceNames)) {
                    $firstService = Service::firstOrCreate(['name' => $serviceNames[0]]);
                    $firstServiceId = $firstService->id;
                }

                $lead = Lead::create([
                    'company' => $companyName,
                    'contact_person' => $contactPerson,
                    'business_type' => $data['Type'] ?? ($data['business_type'] ?? ($data['Company Type'] ?? '')),
                    'emails' => $emails,
                    'phones' => $phonesArray,
                    'priority' => $priority,
                    'status_id' => $status->id,
                    'campaign_id' => $campaign?->id,
                    'source_id' => $firstSourceId,
                    'service_id' => $firstServiceId,
                    'address' => $data['Address'] ?? ($data['address'] ?? ''),
                    'state' => $data['State'] ?? ($data['state'] ?? ''),
                    'zip_code' => $data['Zip Code'] ?? ($data['zip_code'] ?? ($data['Zip'] ?? '')),
               
                    'created_by' => auth()->guard('admin')->id(),
                    'created_by_type' => \App\Models\Admin::class,
                ]);

                // Sync sources
                if (!empty($sourceNames)) {
                    $sourceIds = [];
                    foreach($sourceNames as $sn) {
                        $s = Source::firstOrCreate(['name' => $sn]);
                        $sourceIds[] = $s->id;
                    }
                    $lead->sources()->sync($sourceIds);
                }

                // Sync services
                if (!empty($serviceNames)) {
                    $serviceIds = [];
                    foreach($serviceNames as $sn) {
                        $s = Service::firstOrCreate(['name' => $sn]);
                        $serviceIds[] = $s->id;
                    }
                    $lead->services()->sync($serviceIds);
                }

                // Lead assignments
                $salesPersonStr = $data['Sales Person'] ?? ($data['sales_person'] ?? '');
                if (!empty($salesPersonStr)) {
                    $spNames = array_map('trim', explode(',', $salesPersonStr));
                    foreach($spNames as $sp) {
                        $name = trim(explode('(', $sp)[0]);
                        $sale = Sale::where('name', 'like', "%$name%")->first();
                        if ($sale) {
                            LeadAssign::create([
                                'lead_id' => $lead->id,
                                'assigned_to' => $sale->id,
                                'assigned_by' => auth()->guard('admin')->id(),
                                'assigned_by_type' => \App\Models\Admin::class,
                            ]);
                        }
                    }
                }
                
                $successCount++;
            }
            DB::commit();
            fclose($handle);
            return back()->with('success', "Imported $successCount of $rowCount leads successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            fclose($handle);
            return back()->withErrors(['import' => 'Error importing file: ' . $e->getMessage()]);
        }
    }
}
