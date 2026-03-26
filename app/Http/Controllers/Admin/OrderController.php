<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Sale;
use App\Models\Status;
use App\Models\Order;
use App\Models\OrderAssign;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['service', 'status', 'assignments.sale'])->latest()->get();
        
        // Dynamic Counts
        $totalOrders = Order::count();
        $marketingOrders = Order::where('is_marketing', true)->count();
        $totalValue = Order::sum('order_value');
        $cancelledOrders = Order::whereHas('status', function($q) {
            $q->where('name', 'Cancelled');
        })->count();
        
        // Pending logic (e.g. status is Processing or Pending)
        $pendingValue = Order::whereHas('status', function($q) {
            $q->whereIn('name', ['Pending', 'Processing']);
        })->sum('order_value');

        return view('admin.orders.index', compact(
            'orders', 'totalOrders', 'marketingOrders', 'totalValue', 'cancelledOrders', 'pendingValue'
        ));
    }

    public function create($lead_id = null)
    {
        $lead = $lead_id ? Lead::with(['status', 'source', 'service', 'assignments'])->find($lead_id) : null;
        $services = Service::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        
        return view('admin.orders.create', compact('lead', 'services', 'sales', 'orderStatuses', 'paymentStatuses'));
    }

    public function store(Request $request)
    {
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
            'order_value', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id', 'plan_name',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 

        $order = Order::create($orderData);

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

        return redirect()->route('admin.orders.index')->with('success', 'Order created successfully.');
    }

    public function show($id)
    {
        $order = Order::with(['lead', 'service', 'status', 'paymentTerms', 'mktPaymentStatus', 'assignments.sale'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function edit($id)
    {
        $order = Order::with('assignments')->findOrFail($id);
        $services = Service::all();
        $sales = Sale::all();
        $orderStatuses = Status::where('type', 'order')->get();
        $paymentStatuses = Status::where('type', 'payment')->get();
        
        return view('admin.orders.edit', compact('order', 'services', 'sales', 'orderStatuses', 'paymentStatuses'));
    }

    public function update(Request $request, $id)
    {
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
            'order_value', 'payment_terms_id', 'delivery_date', 'city', 'state',
            'zip_code', 'full_address', 'status_id', 'plan_name',
            'mkt_payment_status_id', 'mkt_starting_date', 'mkt_username', 'mkt_password'
        ]);

        $orderData['emails'] = array_values($emails);
        $orderData['phones'] = $phones;
        $orderData['is_marketing'] = $request->has('is_marketing'); 

        $order->update($orderData);

        // Update Assignments
        if ($request->has('sales_person')) {
            $order->sales()->sync($request->sales_person);
        } else {
            $order->sales()->detach();
        }

        return redirect()->route('admin.orders.index')->with('success', 'Order updated successfully.');
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();
        return redirect()->back()->with('success', 'Order deleted successfully.');
    }
}
