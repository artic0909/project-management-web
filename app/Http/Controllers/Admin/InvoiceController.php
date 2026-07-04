<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Invoice::query()->with('order');

        // Filters
        if ($request->filled('search')) {
            $search = $request->search;
            $cleanSearch = str_ireplace('STW', '', $search);
            $query->where(function($q) use ($search, $cleanSearch) {
                $q->where('invoice_no', 'LIKE', "%$cleanSearch%")
                  ->orWhere('client_name', 'LIKE', "%$search%")
                  ->orWhere('status', 'LIKE', "%$search%")
                  ->orWhereHas('order', function($sq) use ($search) {
                      $sq->where('order_number', 'LIKE', "%$search%");
                  });
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('invoice_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('invoice_date', '<=', $request->to_date);
        }

        $invoices = $query->latest()->paginate(20);
        $routePrefix = 'admin';

        return view('admin.invoice.index', compact('invoices', 'routePrefix'));
    }

    public function create(Request $request)
    {
        $orders = Order::latest()->get();
        $payments = [];
        if ($request->filled('order_id')) {
            $payments = Payment::where('order_id', $request->order_id)->get();
        }
        
        $selectedOrder = $request->filled('order_id') ? Order::find($request->order_id) : null;
        $routePrefix = 'admin';

        // Generate Invoice No
        do {
            $invoice_no = random_int(1000000000, 9999999999);
        } while (Invoice::where('invoice_no', $invoice_no)->exists());

        return view('admin.invoice.create', compact('orders', 'payments', 'selectedOrder', 'invoice_no', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|numeric|digits:10|unique:invoices,invoice_no',
            'invoice_date' => 'required|date',
            'client_name' => 'required|string',
            'items' => 'required|array|min:1',
            'items.*.desc' => 'required|string',
            'items.*.qty' => 'required|numeric',
            'items.*.rate' => 'required|numeric',
            'total' => 'required|numeric',
            'status' => 'required|string|in:PAID,UNPAID,PROFORMA',
        ]);

        $data = $request->all();
        $data['items'] = array_values($request->items);

        $invoice = Invoice::create($data);

        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }

    public function copy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $newInvoice = $invoice->replicate();
        
        // Generate new invoice number
        do {
            $newInvoice->invoice_no = random_int(1000000000, 9999999999);
        } while (Invoice::where('invoice_no', $newInvoice->invoice_no)->exists());
        $newInvoice->invoice_date = now();
        
        $newInvoice->save();
        
        return redirect()->route('admin.invoices.edit', $newInvoice->id)->with('success', 'Invoice copied successfully. You can now edit the details.');
    }

    public function show($id)
    {
        $invoice = Invoice::with('order', 'payment')->findOrFail($id);
        return view('admin.invoice.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $orders = Order::latest()->get();
        $payments = Payment::where('order_id', $invoice->order_id)->get();
        $routePrefix = 'admin';
        
        return view('admin.invoice.edit', compact('invoice', 'orders', 'payments', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        
        $request->validate([
            'invoice_no' => 'required|numeric|digits:10|unique:invoices,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'client_name' => 'required|string',
            'items' => 'required|array|min:1',
            'total' => 'required|numeric',
            'status' => 'required|string|in:PAID,UNPAID,PROFORMA',
        ]);

        $data = $request->all();
        $data['items'] = array_values($request->items);

        $invoice->update($data);

        return redirect()->route('admin.invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();
        return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
    }
}
