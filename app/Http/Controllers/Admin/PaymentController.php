<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['order.status', 'order.services', 'order.sources', 'order.assignments.sale', 'status', 'createdBy']);

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $cleanId = ltrim(str_ireplace(['#ORD-', '#PAY-'], '', $q), '0');
            if (empty($cleanId)) $cleanId = $q;

            $query->where(function($sub) use ($q, $cleanId) {
                // Payment fields
                $sub->where('transaction_id', 'LIKE', "%$q%")
                    ->orWhere('payment_method', 'LIKE', "%$q%")
                    ->orWhere('amount', 'LIKE', "%$q%")
                    // Order ID search
                    ->orWhere('order_id', 'LIKE', "%$cleanId%")
                    // Related Order Fields
                    ->orWhereHas('order', function($o) use ($q) {
                        $o->where('company_name', 'LIKE', "%$q%")
                          ->orWhere('client_name', 'LIKE', "%$q%")
                          ->orWhere('emails', 'LIKE', "%$q%")
                          ->orWhere('phones', 'LIKE', "%$q%");
                    })
                    // Created By for Payment
                    ->orWhereHasMorph('createdBy', [\App\Models\User::class, \App\Models\Sale::class], function($c) use ($q) {
                        $c->where('name', 'LIKE', "%$q%")
                          ->orWhere('email', 'LIKE', "%$q%");
                    });
            });
        }

        // Date Range Filter
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('transaction_date', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        // Status Filter
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $aggQuery = clone $query;
        $payments = $query->latest()->get();

        // Summaries
        $totalCollected = (clone $aggQuery)->sum('amount');
        
        $orderIds = (clone $aggQuery)->pluck('order_id')->unique();
        $totalOrderValue = Order::whereIn('id', $orderIds)->sum('order_value');
        $actualCollectedForTheseOrders = Payment::whereIn('order_id', $orderIds)->sum('amount');
        
        $totalOutstanding = $totalOrderValue - $actualCollectedForTheseOrders;

        $allStatuses = Status::where('type', 'payment')->get(); // Filter by payment status now

        $routePrefix = 'admin';
        return view('admin.payment.index', compact(
            'payments', 'totalCollected', 'totalOrderValue', 'totalOutstanding', 'allStatuses', 'routePrefix'
        ));
    }

    public function create($order_id)
    {
        $order = Order::with(['status', 'services', 'sources', 'assignments.sale', 'payments', 'paymentTerms', 'createdBy'])->findOrFail($order_id);
        $paymentStatuses = Status::where('type', 'payment')->get();
        $routePrefix = 'admin';
        return view('admin.payment.create', compact('order', 'paymentStatuses', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'transaction_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'nullable|string',
            'transaction_id' => 'nullable|string',
            'screenshot' => 'nullable|file|mimes:png,jpg,jpeg,pdf|max:5120',
            'notes' => 'nullable|string',
        ]);

        $data = $request->all();
        $data['created_by'] = Auth::id();
        $data['created_by_type'] = get_class(Auth::user());
        
        // Status ID for payment?
        // Let's find or default to a "Paid" or "Pending" payment status.
        $status = Status::where('type', 'payment')->where('name', 'Paid')->first();
        if(!$status) {
           $status = Status::where('type', 'payment')->first();
        }
        $data['status_id'] = $status ? $status->id : 1;

        if ($request->hasFile('screenshot')) {
            $path = $request->file('screenshot')->store('payments', 'public');
            $data['screenshot'] = $path;
        }

        Payment::create($data);
        $routePrefix = 'admin';
        return redirect()->route($routePrefix . '.payment.index')->with('success', 'Payment recorded successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment entry removed.');
    }
}
