<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoiceController extends Controller
{
    private function getFilteredInvoices()
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        return Invoice::where(function($q) use ($saleId, $saleType) {
            $q->whereHas('order', function($master) use ($saleId, $saleType) {
                $master->where(function($sq) use ($saleId, $saleType) {
                    $sq->where('created_by', $saleId)->where('created_by_type', $saleType);
                })->orWhereHas('assignments', function($sq) use ($saleId) {
                    $sq->where('assigned_to', $saleId);
                });
            })->orWhere('sender_email', auth()->guard('sale')->user()->email); // Also show if they created it manually
        });
    }

    public function index(Request $request)
    {
        $query = $this->getFilteredInvoices()->with('order');

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
        $routePrefix = 'sale';

        return view('admin.invoice.index', compact('invoices', 'routePrefix'));
    }

    public function create(Request $request)
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        // Still show their orders for convenience, but the form is universal
        $orders = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->latest()->get();

        $selectedOrder = null;
        if ($request->filled('order_id')) {
            $selectedOrder = Order::find($request->order_id);
            // We don't strictly block this here so they can invoice any order if they have the ID, 
            // but the dropdown only shows theirs.
        }
        
        $routePrefix = 'sale';

        // Generate Invoice No
        do {
            $invoice_no = random_int(1000000000, 9999999999);
        } while (Invoice::where('invoice_no', $invoice_no)->exists());

        return view('admin.invoice.create', compact('orders', 'selectedOrder', 'invoice_no', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'invoice_no' => 'required|unique:invoices,invoice_no',
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
        
        // Auto-set sender email for Sales to ensure they can view it later
        if (!isset($data['sender_email']) || empty($data['sender_email'])) {
            $data['sender_email'] = auth()->guard('sale')->user()->email;
        }
        
        // Universal creation: we don't strictly block order_id here anymore
        // allowing it to be standalone or linked if the ID is known.
        
        $invoice = Invoice::create($data);

        return redirect()->route('sale.invoices.show', $invoice->id)->with('success', 'Invoice created successfully');
    }

    public function copy($id)
    {
        $invoice = $this->getFilteredInvoices()->findOrFail($id);
        $newInvoice = $invoice->replicate();
        
        // Ensure ownership
        $newInvoice->sender_email = auth()->guard('sale')->user()->email;
        
        // Generate new invoice number
        do {
            $newInvoice->invoice_no = random_int(1000000000, 9999999999);
        } while (Invoice::where('invoice_no', $newInvoice->invoice_no)->exists());
        $newInvoice->invoice_date = now();
        
        $newInvoice->save();
        
        return redirect()->route('sale.invoices.edit', $newInvoice->id)->with('success', 'Invoice copied successfully. You can now edit the details.');
    }

    public function show($id)
    {
        $invoice = $this->getFilteredInvoices()->with('order')->findOrFail($id);
        return view('admin.invoice.show', compact('invoice'));
    }

    public function edit($id)
    {
        $invoice = $this->getFilteredInvoices()->findOrFail($id);
        
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;
        $orders = Order::where(function($q) use ($saleId, $saleType) {
            $q->where('created_by', $saleId)->where('created_by_type', $saleType)
              ->orWhereHas('assignments', function($sq) use ($saleId) {
                  $sq->where('assigned_to', $saleId);
              });
        })->latest()->get();

        $routePrefix = 'sale';
        
        return view('admin.invoice.edit', compact('invoice', 'orders', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $invoice = $this->getFilteredInvoices()->findOrFail($id);
        
        $request->validate([
            'invoice_no' => 'required|unique:invoices,invoice_no,' . $id,
            'invoice_date' => 'required|date',
            'client_name' => 'required|string',
            'items' => 'required|array|min:1',
            'total' => 'required|numeric',
            'status' => 'required|string|in:PAID,UNPAID,PROFORMA',
        ]);

        $data = $request->all();
        $data['items'] = array_values($request->items);

        $invoice->update($data);

        return redirect()->route('sale.invoices.index')->with('success', 'Invoice updated successfully');
    }

    public function destroy($id)
    {
        $invoice = $this->getFilteredInvoices()->findOrFail($id);
        $invoice->delete();
        return redirect()->route('sale.invoices.index')->with('success', 'Invoice deleted successfully');
    }
}
