<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->guard('developer')->user()->projects()->with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'services', 'sources']);

        $query = $this->applyFilters($query, $request);

        $projects = $query->latest()->paginate(10)->withQueryString();
        
        $totalProjects = (clone $query)->count();
        $activeProjects = (clone $query)->whereHas('projectStatus', function($q){ $q->where('name','development'); })->count();
        $completedProjects = (clone $query)->whereHas('projectStatus', function($q){ $q->where('name','complete'); })->count();
        $onHoldProjects = (clone $query)->whereHas('projectStatus', function($q){ $q->where('name','on hold'); })->count();
        
        $statuses = [
            'project_statuses' => \App\Models\Status::where('type', 'project')->get(),
            'payment_statuses' => \App\Models\Status::where('type', 'payment')->get(),
        ];
        $allDevelopers = \App\Models\Developer::all();
        $allSales = \App\Models\Sale::all();
        $allServices = \App\Models\Service::all();
        $allSources = \App\Models\Source::all();

        $routePrefix = 'developer';

        return view('admin.project.index', compact(
            'projects', 'totalProjects', 'activeProjects', 'completedProjects', 'onHoldProjects',
            'statuses', 'allDevelopers', 'allSales', 'allServices', 'allSources', 'routePrefix'
        ));
    }

    public function show($projectId)
    {
        $project = auth()->guard('developer')->user()->projects()
            ->with(['projectStatus', 'paymentStatus', 'feedbacks', 'developers'])
            ->findOrFail($projectId);
        $routePrefix = 'developer';
        $statuses = [
            'project_statuses' => \App\Models\Status::where('type', 'project')->get(),
            'payment_statuses' => \App\Models\Status::where('type', 'payment')->get(),
        ];
        return view('admin.project.show', compact('project', 'routePrefix', 'statuses'));
    }

    private function applyFilters($query, Request $request)
    {
        // Search Filter (q)
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace('#PRJ-', '', $q), '0');
            if (empty($cleanId)) $cleanId = $q;

            $query->where(function($qq) use ($q, $cleanId) {
                $qq->where('projects.id', 'LIKE', "%$cleanId%")
                   ->orWhere('project_name', 'like', "%$q%")
                   ->orWhere('client_name', 'like', "%$q%")
                   ->orWhere('company_name', 'like', "%$q%")
                   ->orWhere('domain_name', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%")
                   ->orWhere('cms_platform', 'like', "%$q%")
                   ->orWhereHas('projectStatus', function($s) use ($q) {
                       $s->where('name', 'like', "%$q%");
                   })
                   ->orWhereHas('services', function($s) use ($q) {
                       $s->where('services.name', 'like', "%$q%");
                   });
            });
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('projects.created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Project Status Filter
        if ($request->filled('project_status_id')) {
            $query->where('project_status_id', $request->project_status_id);
        }

        // Payment Status Filter
        if ($request->filled('payment_status_id')) {
            $query->where('payment_status_id', $request->payment_status_id);
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

        if ($request->filled('sales_person_id')) {
            $query->whereHas('salesPersons', function($q) use ($request) {
                $q->where('sale_id', $request->sales_person_id);
            });
        }

        return $query;
    }

    public function export(Request $request)
    {
        $query = auth()->guard('developer')->user()->projects()->with(['projectStatus', 'paymentStatus', 'developers', 'salesPersons', 'createdBy', 'services', 'sources', 'order']);
        
        $query = $this->applyFilters($query, $request);
        
        $projects = $query->latest()->get();

        $filename = "projects_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($projects) {
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

            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

            foreach ($projects as $project) {
                $emailsDecoded = is_string($project->emails) ? json_decode($project->emails, true) : $project->emails;
                $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');
                
                $phoneList = is_string($project->phones) ? json_decode($project->phones, true) : $project->phones;
                $phoneList = is_array($phoneList) ? $phoneList : [];
                $fullPhones = [];
                foreach($phoneList as $p) {
                    if (is_array($p) && isset($p['num'])) {
                        $code = $codes[$p['code'] ?? null] ?? '';
                        $fullPhones[] = $code . ($code ? ' ' : '') . $p['num'];
                    } elseif (is_string($p)) {
                        $fullPhones[] = $p;
                    }
                }
                $phonesStr = implode(', ', $fullPhones);
                if (!empty($phonesStr)) {
                    $phonesStr = "\t" . $phonesStr;
                }
                
                $servicesStr = $project->services->pluck('name')->implode(', ');
                $plansStr = $project->plans ? $project->plans->pluck('name')->implode(', ') : '';
                $sourcesStr = $project->sources->pluck('name')->implode(', ');
                
                $projStatus = $project->projectStatus->name ?? $project->project_status ?? '';
                $payStatus = $project->paymentStatus->name ?? $project->payment_status ?? '';

                $createdBy = $project->createdBy ? $project->createdBy->name . ' (' . $project->createdBy->email . ')' : 'System';
                
                $devs = [];
                foreach($project->developers as $dev) {
                    $devs[] = $dev->name . ' (' . $dev->email . ')';
                }
                $devStr = implode(', ', $devs);

                $sales = [];
                foreach($project->salesPersons as $sale) {
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
                    $project->order_date_create ? \Carbon\Carbon::parse($project->order_date_create)->format('Y-m-d') : '',
                    $project->expected_delivery_date ? \Carbon\Carbon::parse($project->expected_delivery_date)->format('Y-m-d') : '',
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

    public function quickUpdate(Request $request, $id)
    {
        $project = auth()->guard('developer')->user()->projects()->findOrFail($id);
        
        // Update Statuses
        $project->update([
            'project_status_id' => $request->project_status_id,
            'payment_status_id' => $request->payment_status_id,
            'project_status' => \App\Models\Status::find($request->project_status_id)?->name,
            'payment_status' => \App\Models\Status::find($request->payment_status_id)?->name,
        ]);

        // Add Feedback Log if notes provided
        if ($request->filled('internal_notes') || $request->filled('feedback_summary')) {
            $project->feedbacks()->create([
                'status' => $project->project_status,
                'last_update_date' => now(),
                'feedback_summary' => $request->feedback_summary ?? 'Quick status update by Developer',
                'internal_notes' => $request->internal_notes,
            ]);
        }

        return back()->with('success', 'Project updated successfully.');
    }

        public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:projects,id',
        ]);

        $this->getFilteredProjects()->whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' projects deleted successfully!');
    }

}
