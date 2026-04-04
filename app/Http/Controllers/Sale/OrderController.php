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
            $query->where(function ($sub) use ($q) {
                $sub->where('company_name', 'LIKE', "%$q%")
                    ->orWhere('client_name', 'LIKE', "%$q%")
                    ->orWhere('emails', 'LIKE', "%$q%")
                    ->orWhere('phones', 'LIKE', "%$q%")
                    ->orWhere('domain_name', 'LIKE', "%$q%");
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
        $query->with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy'])->withCount('followups');
        $orders = $query->latest()->paginate(20)->withQueryString();

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
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => 21, // 'paid'
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
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => 21, // 'paid'
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
}
