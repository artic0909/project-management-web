<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderAssign;
use App\Models\OrderInquiry;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $routePrefix = 'admin';
        $query = Order::query();

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace('#ORD-', '', $q), '0');
            if (empty($cleanId)) $cleanId = $q;

            $query->where(function ($sub) use ($q, $cleanId) {
                $sub->where('id', 'LIKE', "%$cleanId%")
                    ->orWhere('order_number', 'LIKE', "%$q%")
                    ->orWhere('company_name', 'LIKE', "%$q%")
                    ->orWhere('client_name', 'LIKE', "%$q%")
                    ->orWhere('emails', 'LIKE', "%$q%")
                    ->orWhere('phones', 'LIKE', "%$q%")
                    ->orWhere('domain_name', 'LIKE', "%$q%")
                    ->orWhere('order_value', 'LIKE', "%$q%")
                    ->orWhere('advance_payment', 'LIKE', "%$q%")
                    ->orWhereHas('status', function($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('services', function($s) use ($q) {
                        $s->where('services.name', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('sales', function($s) use ($q) {
                        $s->where('sales.name', 'LIKE', "%$q%")
                          ->orWhere('sales.email', 'LIKE', "%$q%");
                    })
                    ->orWhereHasMorph('createdBy', [\App\Models\User::class, \App\Models\Sale::class], function ($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%")
                          ->orWhere('email', 'LIKE', "%$q%");
                    });
            });
        }

        // Service Filter
        if ($request->filled('service_id')) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        // Source Filter
        if ($request->filled('source_id')) {
            $query->whereHas('sources', function($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }

        // Status Filter
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // Type Filter (Marketing vs Website)
        if ($request->filled('is_marketing')) {
            $query->where('is_marketing', $request->is_marketing == '1');
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        $aggQuery = clone $query;
        $totalOrdersCount = (clone $aggQuery)->count();
        $perPage = $request->get('per_page', 20);
        if ($perPage === 'all') {
            $perPage = $totalOrdersCount ?: 20;
        }

        $query->with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy'])->withCount('followups');
        $orders = $query->latest()->paginate($perPage)->withQueryString();
        
        // Total Followups for filtered salesperson
        $totalCallingFollowupsFiltered = 0;
        $totalMessageFollowupsFiltered = 0;
        if ($request->filled('assigned_to')) {
            $followupCounts = \App\Models\Followup::whereHasMorph(
                'followable',
                [\App\Models\Order::class],
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
        
        // Counts
        $totalOrders = (clone $aggQuery)->count();
        $marketingOrders = (clone $aggQuery)->where('is_marketing', true)->count();
        $totalValue = (clone $aggQuery)->whereHas('status', function($q) {
            $q->where('name', '!=', 'cancel');
        })->sum('order_value');
        $cancelledOrders = (clone $aggQuery)->whereHas('status', function($q) {
            $q->where('name', 'cancel'); // Corrected from 'Cancelled'
        })->count();
        $totalReceived = \App\Models\Payment::whereIn('order_id', (clone $aggQuery)->select('id'))->sum('amount');
        $pendingValue = $totalValue - $totalReceived;

        $allStatuses = Status::where('type', 'order')->get();
        $allServices = Service::all();
        $allSources = \App\Models\Source::all();
        $allSales = Sale::all();

        $routePrefix = 'admin';
        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'marketingOrders', 'totalValue', 'cancelledOrders', 'pendingValue', 'totalReceived', 'allStatuses', 'allServices', 'allSources', 'allSales', 'totalCallingFollowupsFiltered', 'totalMessageFollowupsFiltered', 'routePrefix'
        ));
    }

    public function create($lead_id = null)
    {
        $routePrefix = 'admin';
        $lead = null;
        $inquiry = null;

        if ($lead_id) {
            $lead = Lead::with(['status', 'sources', 'services', 'assignments'])->find($lead_id);
        } elseif (request()->has('inquiry_id')) {
            $inquiry = OrderInquiry::find(request('inquiry_id'));
        }

        $services = Service::all();
        $sources = \App\Models\Source::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        $plans = \App\Models\Plan::all();
        
        return view('admin.orders.create', compact('lead', 'inquiry', 'services', 'sources', 'sales', 'orderStatuses', 'paymentStatuses', 'plans', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $routePrefix = 'admin';
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'domain_name' => 'required|string|max:255',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'exists:sources,id',
            'order_value' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0',
            'payment_terms_id' => 'required|exists:statuses,id',
            'delivery_date' => 'required|date',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|numeric|digits:6',
            'full_address' => 'required|string',
            'status_id' => 'required|exists:statuses,id',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
            'sales_person' => 'nullable|array',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string',
            'transaction_id' => 'nullable|string|max:255',
            'screenshot' => 'nullable|image|max:5120',
        ]);

        // Process Emails
        $emails = array_filter($request->input('email', []));

        // Process Phones
        $phones = [];
        $phoneNumbers = $request->input('phone', []);
        $countryCodes = $request->input('country_code', []);
        foreach ($phoneNumbers as $idx => $num) {
            if (!empty($num)) {
                $phones[] = [
                    'number' => $num,
                    'code_idx' => $countryCodes[$idx] ?? null
                ];
            }
        }

        $orderData = $request->only([
            'lead_id', 'inquiry_id', 'company_name', 'client_name', 'domain_name',
            'order_value', 'discount', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['advance_payment'] = $request->input('amount', 0);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 
        
        // Audit
        $orderData['created_by'] = Auth::id();
        $orderData['created_by_type'] = get_class(Auth::user());

        // Generate Order Number
        $lastOrder = Order::where('order_number', 'LIKE', 'ORD-%')
            ->orderByRaw('CAST(SUBSTRING(order_number, 5) AS UNSIGNED) DESC')
            ->first();
            
        if ($lastOrder) {
            $lastNum = (int) str_replace('ORD-', '', $lastOrder->order_number);
            $nextNum = $lastNum + 1;
        } else {
            $nextNum = 1001;
        }
        $orderData['order_number'] = 'ORD-' . $nextNum;

        $order = Order::create($orderData);

        $order->services()->sync($request->service_ids);
        $order->sources()->sync($request->source_ids);
        $order->plans()->sync($request->plan_ids);

        // Detailed Payment record for Order Creation
        if ($request->input('amount') > 0) {
            $paymentStatus = \App\Models\Status::where('type', 'payment')->where('name', 'Advance')->first();
            $paymentData = [
                'order_id' => $order->id,
                'transaction_date' => $request->input('transaction_date') ?? now(),
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method', 'Advance'),
                'transaction_id' => $request->input('transaction_id'),
                'notes' => $request->input('notes') ?? 'Initial Payment at Order Creation',
                'status_id' => $paymentStatus ? $paymentStatus->id : null,
                'created_by' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ];

            if ($request->hasFile('screenshot')) {
                $path = $request->file('screenshot')->store('payments', 'public');
                $paymentData['screenshot'] = $path;
            }

            \App\Models\Payment::create($paymentData);
        }

        // Add initial note to history if present
        if (!empty($request->notes)) {
            \App\Models\OrderNote::create([
                'order_id' => $order->id,
                'notes' => $request->notes,
                'created_by' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ]);
        }

        // Assign Sales Personnel
        if ($request->has('sales_person')) {
            $order->sales()->sync($request->sales_person);
        }

        // Optional: Update Lead Status if converted
        if ($request->filled('lead_id')) {
            $lead = Lead::find($request->lead_id);
            if ($lead) {
                $convertedStatus = Status::where('name', 'Converted')->first();
                if ($convertedStatus) {
                    $lead->update(['status_id' => $convertedStatus->id]);
                }
            }
        }

        // Optional: Update Inquiry Status if converted
        if ($request->filled('inquiry_id')) {
            $inquiry = OrderInquiry::find($request->inquiry_id);
            if ($inquiry) {
                $inquiry->update(['status' => 'converted']);
            }
        }

        return redirect()->back()->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $routePrefix = 'admin';
        $order = Order::with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy', 'paymentTerms', 'mktPaymentStatus', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();

        $routePrefix = 'admin';
        return view('admin.orders.show', compact('order', 'orderStatuses', 'paymentStatuses', 'routePrefix'));
    }

    public function updateStatus(Request $request, $id)
    {
        $routePrefix = 'admin';
        $order = Order::findOrFail($id);
        
        $data = $request->only(['status_id', 'payment_terms_id', 'mkt_payment_status_id']);
        $order->update($data);

        return back()->with('success', 'Order status updated successfully.');
    }

    public function edit($id)
    {
        $routePrefix = 'admin';
        $order = Order::with(['assignments', 'services', 'sources', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $services = Service::all();
        $sources = \App\Models\Source::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        $plans = \App\Models\Plan::all();
        
        $routePrefix = 'admin';
        return view('admin.orders.edit', compact('order', 'services', 'sources', 'sales', 'orderStatuses', 'paymentStatuses', 'plans', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $routePrefix = 'admin';
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'order_value' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0',
            'zip_code' => 'required|numeric|digits:6',
            'status_id' => 'required|exists:statuses,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'exists:sources,id',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
            'sales_person' => 'nullable|array',
        ]);

        $order = Order::findOrFail($id);

        // Process Emails
        $emails = array_filter($request->input('email', []));

        // Process Phones
        $phones = [];
        $phoneNumbers = $request->input('phone', []);
        $countryCodes = $request->input('country_code', []);
        foreach ($phoneNumbers as $idx => $num) {
            if (!empty($num)) {
                $phones[] = [
                    'number' => $num,
                    'code_idx' => $countryCodes[$idx] ?? null
                ];
            }
        }

        $orderData = $request->only([
            'company_name', 'client_name', 'domain_name',
            'order_value', 'discount', 'advance_payment', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 

        $order->update($orderData);

        $order->services()->sync($request->service_ids);
        $order->sources()->sync($request->source_ids);
        $order->plans()->sync($request->plan_ids);

        // Update Advance Payment Record if none exists
        if ($order->advance_payment > 0 && !$order->payments()->where('payment_method', 'Advance')->exists()) {
            $paymentStatus = \App\Models\Status::where('type', 'payment')->where('name', 'Paid')->first();
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => $paymentStatus ? $paymentStatus->id : null,
                'created_by' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ]);
        }

        // Update Assignments
        if ($request->has('sales_person')) {
            $order->sales()->sync($request->sales_person);
        } else {
            $order->sales()->detach();
        }

        return redirect()->route($routePrefix . '.orders.show', $order->id)->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:orders,id',
        ]);

        Order::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' orders deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = Order::query();

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace('#ORD-', '', $q), '0');
            if (empty($cleanId)) $cleanId = $q;

            $query->where(function ($sub) use ($q, $cleanId) {
                $sub->where('id', 'LIKE', "%$cleanId%")
                    ->orWhere('company_name', 'LIKE', "%$q%")
                    ->orWhere('client_name', 'LIKE', "%$q%")
                    ->orWhere('emails', 'LIKE', "%$q%")
                    ->orWhere('phones', 'LIKE', "%$q%")
                    ->orWhere('domain_name', 'LIKE', "%$q%")
                    ->orWhere('order_value', 'LIKE', "%$q%")
                    ->orWhere('advance_payment', 'LIKE', "%$q%")
                    ->orWhereHas('status', function($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('services', function($s) use ($q) {
                        $s->where('services.name', 'LIKE', "%$q%");
                    })
                    ->orWhereHas('sales', function($s) use ($q) {
                        $s->where('sales.name', 'LIKE', "%$q%")
                          ->orWhere('sales.email', 'LIKE', "%$q%");
                    })
                    ->orWhereHasMorph('createdBy', [\App\Models\User::class, \App\Models\Sale::class], function ($s) use ($q) {
                        $s->where('name', 'LIKE', "%$q%")
                          ->orWhere('email', 'LIKE', "%$q%");
                    });
            });
        }

        // Service Filter
        if ($request->filled('service_id')) {
            $query->whereHas('services', function($q) use ($request) {
                $q->where('services.id', $request->service_id);
            });
        }

        // Source Filter
        if ($request->filled('source_id')) {
            $query->whereHas('sources', function($q) use ($request) {
                $q->where('sources.id', $request->source_id);
            });
        }

        // Status Filter
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        // Type Filter (Marketing vs Website)
        if ($request->filled('is_marketing')) {
            $query->where('is_marketing', $request->is_marketing == '1');
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        $query->with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy', 'paymentTerms', 'mktPaymentStatus']);
        $orders = $query->latest()->get();

        $filename = "orders_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($orders) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Order ID',
                'Order Number',
                'Lead ID',
                'Date',
                'Type',
                'Company Name',
                'Client Name',
                'Emails',
                'Phones',
                'Domain',
                'Sources',
                'Services',
                'Plans',
                'Order Value',
                'Discount',
                'Advance Payment',
                'Payment Terms',
                'Delivery Date',
                'Status',
                'Mkt Payment Status',
                'Mkt Starting Date',
                'Mkt Username',
                'Mkt Password',
                'Created By',
                'Sales Person',
                'City',
                'State',
                'Zip Code',
                'Full Address'
            ]);

            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

            foreach ($orders as $order) {
                $emailsDecoded = is_string($order->emails) ? json_decode($order->emails, true) : $order->emails;
                $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');
                
                $phoneList = is_string($order->phones) ? json_decode($order->phones, true) : $order->phones;
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
                
                $sourcesStr = $order->sources->pluck('name')->implode(', ');
                $servicesStr = $order->services->pluck('name')->implode(', ');
                $plansStr = $order->plans->pluck('name')->implode(', ');
                $paymentTerms = $order->paymentTerms->name ?? '';
                $mktPaymentStatus = $order->mktPaymentStatus->name ?? '';
                
                $createdBy = $order->createdBy ? $order->createdBy->name . ' (' . $order->createdBy->email . ')' : 'System';
                
                $salesPersons = [];
                foreach($order->assignments as $assign) {
                    if($assign->sale) {
                        $salesPersons[] = $assign->sale->name . ' (' . $assign->sale->email . ')';
                    }
                }
                $salesPersonStr = implode(', ', $salesPersons);

                fputcsv($file, [
                    '#ORD-' . $order->id,
                    $order->order_number,
                    $order->lead_id ? '#LEAD-' . $order->lead_id : '',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->is_marketing ? 'Marketing' : 'Website',
                    $order->company_name,
                    $order->client_name,
                    $emailsStr,
                    $phonesStr,
                    $order->domain_name,
                    $sourcesStr,
                    $servicesStr,
                    $plansStr,
                    $order->order_value,
                    $order->discount,
                    $order->advance_payment,
                    $paymentTerms,
                    $order->delivery_date,
                    $order->status->name ?? '',
                    $mktPaymentStatus,
                    $order->mkt_starting_date,
                    $order->mkt_username,
                    $order->mkt_password,
                    $createdBy,
                    $salesPersonStr,
                    $order->city,
                    $order->state,
                    $order->zip_code,
                    $order->full_address
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
