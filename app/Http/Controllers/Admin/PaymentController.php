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
        return redirect()->route($routePrefix . '.payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();
        return redirect()->back()->with('success', 'Payment entry removed.');
    }

    public function export(Request $request)
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

        $payments = $query->latest()->get();

        $filename = "payments_export_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($payments) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");
            
            fputcsv($file, [
                'Payment ID',
                'Order ID',
                'Transaction Date',
                'Amount',
                'Payment Method',
                'Transaction ID',
                'Notes',
                'Payment Status',
                'Company Name',
                'Client Name',
                'Emails',
                'Order Value',
                'Order Status',
                'Sales Persons',
                'Recorded By',
                'Recorded At'
            ]);

            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

            foreach ($payments as $payment) {
                $order = $payment->order;
                
                $emailsStr = '';
                $phonesStr = '';
                $companyName = '';
                $clientName = '';
                $orderValue = '';
                $orderStatus = '';
                $salesStr = '';

                if ($order) {
                    $companyName = $order->company_name;
                    $clientName = $order->client_name;
                    $orderValue = $order->order_value;
                    $orderStatus = $order->status->name ?? '';

                    $emailsDecoded = is_string($order->emails) ? json_decode($order->emails, true) : $order->emails;
                    $emailsStr = is_array($emailsDecoded) ? implode(', ', $emailsDecoded) : ($emailsDecoded ?? '');
                    
                    $phoneList = is_string($order->phones) ? json_decode($order->phones, true) : $order->phones;
                    $phoneList = is_array($phoneList) ? $phoneList : [];
                    $fullPhones = [];
                    foreach($phoneList as $p) {
                        if (isset($p['num'])) {
                            $code = $codes[$p['code_idx'] ?? null] ?? '';
                            $fullPhones[] = $code . ($code ? ' ' : '') . $p['num'];
                        } elseif (is_string($p)) {
                            $fullPhones[] = $p;
                        }
                    }
                    $phonesStr = implode(', ', $fullPhones);
                    if (!empty($phonesStr)) {
                        $phonesStr = "\t" . $phonesStr;
                    }

                    $sales = [];
                    foreach($order->assignments as $assign) {
                        if($assign->sale) {
                            $sales[] = $assign->sale->name . ' (' . $assign->sale->email . ')';
                        }
                    }
                    $salesStr = implode(', ', $sales);
                }

                $createdBy = $payment->createdBy ? $payment->createdBy->name . ' (' . $payment->createdBy->email . ')' : 'System';

                fputcsv($file, [
                    '#PAY-' . $payment->id,
                    $payment->order_id ? '#ORD-' . $payment->order_id : '',
                    $payment->transaction_date ? $payment->transaction_date->format('Y-m-d') : '',
                    $payment->amount,
                    $payment->payment_method,
                    $payment->transaction_id,
                    $payment->notes,
                    $payment->status->name ?? '',
                    $companyName,
                    $clientName,
                    $emailsStr,
                    $orderValue,
                    $orderStatus,
                    $salesStr,
                    $createdBy,
                    $payment->created_at ? $payment->created_at->format('Y-m-d H:i:s') : ''
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function invoice($id)
    {
        $payment = Payment::with(['order.status', 'order.services', 'order.sources', 'order.assignments.sale', 'status', 'createdBy'])->findOrFail($id);
        $routePrefix = 'admin';
        return view('admin.invoice', compact('payment', 'routePrefix'));
    }
}
