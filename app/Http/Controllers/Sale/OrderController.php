<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderAssign;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    private function getFilteredOrders()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        return Order::where(function($master) use ($saleId, $saleType) {
            $master->where(function ($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)
                    ->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function ($q) use ($saleId) {
                $q->where('assigned_to', $saleId);
            });
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredOrders();

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

        $aggQuery = clone $query;
        $totalOrdersCount = (clone $aggQuery)->count();
        $perPage = $request->get('per_page', 20);
        if ($perPage === 'all') {
            $perPage = $totalOrdersCount ?: 20;
        }

        $query->with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy'])->withCount('followups');
        $orders = $query->latest()->paginate($perPage)->withQueryString();

        // Total Calling & Message Followups for the logged-in salesperson's assigned orders
        $orderIds = (clone $aggQuery)->pluck('id');
        $followupCounts = \App\Models\Followup::whereIn('followable_id', $orderIds)
            ->where('followable_type', \App\Models\Order::class)
            ->select('followup_type', DB::raw('count(*) as count'))
            ->groupBy('followup_type')
            ->pluck('count', 'followup_type');

        $totalCallingUserFollowups = ($followupCounts['Calling'] ?? 0) + ($followupCounts['Both'] ?? 0);
        $totalMessageUserFollowups = ($followupCounts['Message'] ?? 0) + ($followupCounts['Both'] ?? 0);

        // Counts (Only for their orders)
        $totalOrders = (clone $aggQuery)->count();
        $marketingOrders = (clone $aggQuery)->where('is_marketing', true)->count();
        $totalValue = (clone $aggQuery)->whereHas('status', function ($q) {
            $q->where('name', '!=', 'cancel');
        })->sum('order_value');
        $cancelledOrders = (clone $aggQuery)->whereHas('status', function ($q) {
            $q->where('name', 'cancel');
        })->count();

        $totalReceived = \App\Models\Payment::whereIn('order_id', $orderIds)->sum('amount');
        $pendingValue = $totalValue - $totalReceived;

        $allStatuses = Status::where('type', 'order')->get();
        $allServices = Service::all();
        $allSales = Sale::all();

        $routePrefix = 'sale';
        return view('admin.orders.index', compact(
            'orders',
            'totalOrders',
            'marketingOrders',
            'totalValue',
            'cancelledOrders',
            'pendingValue',
            'totalReceived',
            'allStatuses',
            'allServices',
            'allSales',
            'totalCallingUserFollowups',
            'totalMessageUserFollowups',
            'routePrefix'
        ));
    }

    public function create($lead_id = null)
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $lead = $lead_id ? Lead::where(function ($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType);
        })->orWhereHas('assignments', function ($q) use ($saleId) {
            $q->where('assigned_to', $saleId);
        })->with(['status', 'sources', 'services', 'assignments'])->find($lead_id) : null;

        $services = Service::all();
        $sources = \App\Models\Source::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        $plans = \App\Models\Plan::all();

        $routePrefix = 'sale';
        return view('admin.orders.create', compact('lead', 'services', 'sources', 'sales', 'orderStatuses', 'paymentStatuses', 'plans', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'order_value' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'exists:sources,id',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
            'domain_name' => 'required|string|max:255',
            'delivery_date' => 'required|date',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|numeric|digits:6',
            'full_address' => 'required|string',
        ]);

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

        $emails = array_filter($request->input('email', []), fn($e) => !empty($e));

        $order = Order::create([
            'lead_id' => $request->lead_id,
            'company_name' => $request->company_name,
            'client_name' => $request->client_name,
            'order_value' => $request->order_value,
            'discount' => $request->discount,
            'advance_payment' => $request->advance_payment ?? 0,
            'status_id' => $request->status_id,
            'emails' => array_values($emails),
            'phones' => $phones,
            'domain_name' => $request->domain_name,
            'payment_terms_id' => $request->payment_terms_id,
            'delivery_date' => $request->delivery_date,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'full_address' => $request->full_address,
            'is_marketing' => $request->has('is_marketing'),
            'mkt_starting_date' => $request->mkt_starting_date,
            'mkt_username' => $request->mkt_username,
            'mkt_password' => $request->mkt_password,
            'created_by' => auth()->guard('sale')->id(),
            'created_by_type' => \App\Models\Sale::class,
        ]);

        if ($request->has('service_ids')) {
            $order->services()->sync($request->service_ids);
        }
        if ($request->has('source_ids')) {
            $order->sources()->sync($request->source_ids);
        }
        $order->plans()->sync($request->plan_ids);

        // Automatic Payment record for Advance Payment
        if ($order->advance_payment > 0) {
            $paymentStatus = \App\Models\Status::where('type', 'payment')->where('name', 'Advance')->first();
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => $paymentStatus ? $paymentStatus->id : null,
                'created_by' => auth()->guard('sale')->id(),
                'created_by_type' => \App\Models\Sale::class,
            ]);
        }

        if ($request->has('assign_to')) {
            foreach ($request->assign_to as $sale_id) {
                OrderAssign::create([
                    'order_id' => $order->id,
                    'assigned_to' => $sale_id,
                ]);
            }
        } else {
            OrderAssign::create([
                'order_id' => $order->id,
                'assigned_to' => auth()->guard('sale')->id(),
            ]);
        }

        // Add initial note to history if present
        if (!empty($request->notes)) {
            \App\Models\OrderNote::create([
                'order_id' => $order->id,
                'notes' => $request->notes,
                'created_by' => auth()->guard('sale')->id(),
                'created_by_type' => \App\Models\Sale::class,
            ]);
        }

        return redirect()->route('sale.orders.index')->with('success', 'Order created successfully!');
    }

    public function show($id)
    {
        $order = $this->getFilteredOrders()->with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy', 'lead', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();

        $routePrefix = 'sale';
        return view('admin.orders.show', compact('order', 'orderStatuses', 'paymentStatuses', 'routePrefix'));
    }

    public function edit($id)
    {
        $order = $this->getFilteredOrders()->with(['assignments', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $services = Service::all();
        $sources = \App\Models\Source::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        $plans = \App\Models\Plan::all();

        $routePrefix = 'sale';
        return view('admin.orders.edit', compact('order', 'services', 'sources', 'sales', 'orderStatuses', 'paymentStatuses', 'plans', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'order_value' => 'required|numeric',
            'discount' => 'nullable|numeric|min:0',
            'status_id' => 'required|exists:statuses,id',
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'source_ids' => 'required|array|min:1',
            'source_ids.*' => 'exists:sources,id',
            'plan_ids' => 'nullable|array',
            'plan_ids.*' => 'exists:plans,id',
            'domain_name' => 'required|string|max:255',
            'delivery_date' => 'required|date',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|numeric|digits:6',
            'full_address' => 'required|string',
        ]);

        $order = $this->getFilteredOrders()->findOrFail($id);

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

        $emails = array_filter($request->input('email', []), fn($e) => !empty($e));

        $order->update([
            'company_name' => $request->company_name,
            'client_name' => $request->client_name,
            'order_value' => $request->order_value,
            'discount' => $request->discount,
            'advance_payment' => $request->advance_payment ?? 0,
            'status_id' => $request->status_id,
            'emails' => array_values($emails),
            'phones' => $phones,
            'domain_name' => $request->domain_name,
            'payment_terms_id' => $request->payment_terms_id,
            'delivery_date' => $request->delivery_date,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'full_address' => $request->full_address,
            'is_marketing' => $request->has('is_marketing'),
            'mkt_starting_date' => $request->mkt_starting_date,
            'mkt_username' => $request->mkt_username,
            'mkt_password' => $request->mkt_password,
        ]);

        if ($request->has('service_ids')) {
            $order->services()->sync($request->service_ids);
        }
        if ($request->has('source_ids')) {
            $order->sources()->sync($request->source_ids);
        }
        $order->plans()->sync($request->plan_ids);

        // Update Advance Payment Record if none exists
        if ($order->advance_payment > 0 && !$order->payments()->where('payment_method', 'Advance')->exists()) {
            $paymentStatus = \App\Models\Status::where('type', 'payment')->where('name', 'Advance')->first();
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => $paymentStatus ? $paymentStatus->id : null,
                'created_by' => auth()->guard('sale')->id(),
                'created_by_type' => \App\Models\Sale::class,
            ]);
        }

        if ($request->has('assign_to')) {
            OrderAssign::where('order_id', $id)->delete();
            foreach ($request->assign_to as $sale_id) {
                OrderAssign::create([
                    'order_id' => $order->id,
                    'assigned_to' => $sale_id,
                ]);
            }
        }

        return redirect()->route('sale.orders.index')->with('success', 'Order updated successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $order = $this->getFilteredOrders()->findOrFail($id);

        $order->update([
            'status_id' => $request->status_id,
            'payment_terms_id' => $request->payment_terms_id,
            'mkt_payment_status_id' => $request->mkt_payment_status_id,
        ]);

        return redirect()->back()->with('success', 'Order status updated!');
    }

    public function destroy($id)
    {
        $order = $this->getFilteredOrders()->findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully!');
    }

    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array|min:1',
            'ids.*' => 'exists:orders,id',
        ]);

        $this->getFilteredOrders()->whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', count($request->ids) . ' orders deleted successfully!');
    }

    public function export(Request $request)
    {
        $query = $this->getFilteredOrders();

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
