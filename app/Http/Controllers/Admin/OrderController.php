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
        $query = Order::with(['status', 'service', 'assignments.sale', 'createdBy'])->withCount('followups');

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
            $query->where('service_id', $request->service_id);
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
        $allSales = Sale::all();

        $routePrefix = 'admin';
        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'marketingOrders', 'totalValue', 'cancelledOrders', 'pendingValue', 'totalReceived', 'allStatuses', 'allServices', 'allSales', 'totalCallingFollowupsFiltered', 'totalMessageFollowupsFiltered', 'routePrefix'
        ));
    }

    public function create($lead_id = null)
    {
        $routePrefix = 'admin';
        $lead = $lead_id ? Lead::with(['status', 'source', 'service', 'assignments'])->find($lead_id) : null;
        $services = Service::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        
        $routePrefix = 'admin';
        return view('admin.orders.create', compact('lead', 'services', 'sales', 'orderStatuses', 'paymentStatuses', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $routePrefix = 'admin';
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'order_value' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id',
            'service_id' => 'required|exists:services,id',
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
            'lead_id', 'company_name', 'client_name', 'domain_name', 'service_id',
            'order_value', 'advance_payment', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id', 'plan_name',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 
        
        // Audit
        $orderData['created_by'] = Auth::id();
        $orderData['created_by_type'] = get_class(Auth::user());

        $order = Order::create($orderData);

        // Automatic Payment record for Advance Payment
        if ($order->advance_payment > 0) {
            \App\Models\Payment::create([
                'order_id' => $order->id,
                'transaction_date' => now(),
                'amount' => $order->advance_payment,
                'payment_method' => 'Advance',
                'notes' => 'Automated Advance Payment',
                'status_id' => $order->status_id,
                'created_by' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ]);
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
        $order = Order::with(['status', 'service', 'assignments.sale', 'createdBy', 'paymentTerms', 'mktPaymentStatus', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        
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
        $order = Order::with(['assignments', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
        $services = Service::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        
        $routePrefix = 'admin';
        return view('admin.orders.edit', compact('order', 'services', 'sales', 'orderStatuses', 'paymentStatuses', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $routePrefix = 'admin';
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'order_value' => 'required|numeric',
            'status_id' => 'required|exists:statuses,id',
            'service_id' => 'required|exists:services,id',
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
            'company_name', 'client_name', 'domain_name', 'service_id',
            'order_value', 'advance_payment', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id', 'plan_name',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 

        $order->update($orderData);

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
