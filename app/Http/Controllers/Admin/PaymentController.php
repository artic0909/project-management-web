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
        $query = Payment::with(['order.status', 'order.service', 'order.assignments.sale', 'status', 'createdBy']);

        // Search Filter
        if ($request->filled('q')) {
            $q = $request->q;
            $query->whereHas('order', function($sub) use ($q) {
                $sub->where('company_name', 'LIKE', "%$q%")
                    ->orWhere('client_name', 'LIKE', "%$q%")
                    ->orWhere('emails', 'LIKE', "%$q%")
                    ->orWhere('phones', 'LIKE', "%$q%");
            });
        }

        // Status Filter
        if ($request->filled('status_id')) {
            $query->where('status_id', $request->status_id);
        }

        $payments = $query->latest()->get();

        // Summaries (Keep them global)
        $totalCollected = Payment::sum('amount');
        $totalOrderValue = Order::sum('order_value');
        $totalOutstanding = $totalOrderValue - $totalCollected;

        $allStatuses = Status::where('type', 'payment')->get(); // Filter by payment status now

        return view('admin.payment.index', compact(
            'payments', 'totalCollected', 'totalOrderValue', 'totalOutstanding', 'allStatuses'
        ));
    }

    public function create($order_id)
    {
        $order = Order::with(['status', 'service', 'assignments.sale', 'payments', 'paymentTerms', 'createdBy'])->findOrFail($order_id);
        $paymentStatuses = Status::where('type', 'payment')->get();
        return view('admin.payment.create', compact('order', 'paymentStatuses'));
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

        return redirect()->back()->with('success', 'Payment recorded successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment entry removed.');
    }
}
