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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $routePrefix = 'admin';
        $query = Order::with(['status', 'services', 'sources', 'plans', 'assignments.sale', 'createdBy'])->withCount('followups');

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
        if ($request->filled('assigned_to')) {
            $query->whereHas('assignments', function($q) use ($request) {
                $q->where('assigned_to', $request->assigned_to);
            });
        }

        $orders = $query->latest()->paginate(20)->withQueryString();
        
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
        $totalOrders = Order::count();
        $marketingOrders = Order::where('is_marketing', true)->count();
        $totalValue = Order::whereHas('status', function($q) {
            $q->where('name', '!=', 'cancel');
        })->sum('order_value');
        $cancelledOrders = Order::whereHas('status', function($q) {
            $q->where('name', 'cancel'); // Corrected from 'Cancelled'
        })->count();
        $totalReceived = \App\Models\Payment::sum('amount');
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
        $lead = $lead_id ? Lead::with(['status', 'sources', 'services', 'assignments'])->find($lead_id) : null;
        $services = Service::all();
        $sources = \App\Models\Source::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        $plans = \App\Models\Plan::all();
        
        $routePrefix = 'admin';
        return view('admin.orders.create', compact('lead', 'services', 'sources', 'sales', 'orderStatuses', 'paymentStatuses', 'plans', 'routePrefix'));
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
            'lead_id', 'company_name', 'client_name', 'domain_name',
            'order_value', 'payment_terms_id', 'delivery_date', 'city', 'state',
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

        $order = Order::create($orderData);

        $order->services()->sync($request->service_ids);
        $order->sources()->sync($request->source_ids);
        $order->plans()->sync($request->plan_ids);

        // Detailed Payment record for Order Creation
        if ($request->input('amount') > 0) {
            $paymentData = [
                'order_id' => $order->id,
                'transaction_date' => $request->input('transaction_date') ?? now(),
                'amount' => $request->input('amount'),
                'payment_method' => $request->input('payment_method', 'Advance'),
                'transaction_id' => $request->input('transaction_id'),
                'notes' => $request->input('notes') ?? 'Initial Payment at Order Creation',
                'status_id' => 21, // Paid/Collected status
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
            'order_value', 'advance_payment', 'payment_terms_id', 'delivery_date', 'city', 'state',
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
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => 21, // 'paid'
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
}
