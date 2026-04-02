<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    private function getFilteredPayments()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        return Payment::whereHas('order', function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($sq) use ($saleId) {
                $sq->where('assigned_to', $saleId);
            });
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredPayments()->with(['order.status', 'order.services', 'order.sources', 'order.assignments.sale', 'status', 'createdBy']);

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

        // Summaries (Only for their records)
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;
        $filteredOrders = Order::where(function($master) use ($saleId, $saleType) {
            $master->where(function($q) use ($saleId, $saleType) {
                $q->where('created_by', $saleId)->where('created_by_type', $saleType);
            })->orWhereHas('assignments', function($sq) use ($saleId) {
                $sq->where('assigned_to', $saleId);
            });
        });

        $totalCollected = $this->getFilteredPayments()->sum('amount');
        $totalOrderValue = $filteredOrders->sum('order_value');
        $totalOutstanding = $totalOrderValue - $totalCollected;

        $allStatuses = Status::where('type', 'payment')->get();

        $routePrefix = 'sale';
        return view('admin.payment.index', compact(
            'payments', 'totalCollected', 'totalOrderValue', 'totalOutstanding', 'allStatuses', 'routePrefix'
        ));
    }

    public function create($order_id)
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        $order = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->with(['status', 'services', 'sources', 'assignments.sale', 'payments', 'paymentTerms', 'createdBy'])->findOrFail($order_id);

        $paymentStatuses = Status::where('type', 'payment')->get();
        $routePrefix = 'sale';
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
        ]);

        $data = $request->all();
        $data['created_by'] = auth()->guard('sale')->id();
        $data['created_by_type'] = \App\Models\Sale::class;
        
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

        return redirect()->route('sale.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function destroy($id)
    {
        $payment = $this->getFilteredPayments()->findOrFail($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment entry removed.');
    }
}
