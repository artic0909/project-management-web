<?php

namespace App\Http\Controllers;

use App\Models\OrderInquiry;
use Illuminate\Http\Request;

class OrderInquiryController extends Controller
{
    /**
     * Show admin listing of inquiries.
     */
    public function index(Request $request)
    {
        $query = OrderInquiry::query();

        // Search - across all relevant fields
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qq) use ($q) {
                $qq->where('company_name', 'like', "%$q%")
                   ->orWhere('client_name', 'like', "%$q%")
                   ->orWhere('domain_name', 'like', "%$q%")
                   ->orWhere('city', 'like', "%$q%")
                   ->orWhere('state', 'like', "%$q%")
                   ->orWhere('order_value', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%");
            });
        }

        // Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Date Range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('range') && $request->range == '7_days') {
            $query->where('created_at', '>=', now()->subDays(7));
        }

        $inquiries = $query->latest()->paginate(15)->appends($request->all());

        // Stats - clone for filtering boxes
        $statsQuery = OrderInquiry::query();
        if ($request->filled('q')) {
            $q = $request->q;
            $statsQuery->where(function($qq) use ($q) {
                $qq->where('company_name', 'like', "%$q%")
                   ->orWhere('client_name', 'like', "%$q%")
                   ->orWhere('domain_name', 'like', "%$q%")
                   ->orWhere('city', 'like', "%$q%")
                   ->orWhere('state', 'like', "%$q%")
                   ->orWhere('order_value', 'like', "%$q%")
                   ->orWhere('emails', 'like', "%$q%")
                   ->orWhere('phones', 'like', "%$q%");
            });
        }
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $statsQuery->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        } elseif ($request->filled('range') && $request->range == '7_days') {
            $statsQuery->where('created_at', '>=', now()->subDays(7));
        }

        $stats = [
            'total'     => (clone $statsQuery)->count(),
            'pending'   => (clone $statsQuery)->where('status', 'pending')->count(),
            'reviewed'  => (clone $statsQuery)->where('status', 'reviewed')->count(),
            'converted' => (clone $statsQuery)->where('status', 'converted')->count(),
            'rejected'  => (clone $statsQuery)->where('status', 'rejected')->count(),
        ];

        return view('admin.inquiry.index', compact('inquiries', 'stats'));
    }

    /**
     * Store a new order inquiry from the welcome page.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'domain_name' => 'required|string|max:255',
            'service_ids' => 'nullable|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'order_value' => 'nullable|numeric',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|numeric|digits:6',
            'full_address' => 'required|string',
        ]);

        // Process Phones with country codes
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

        // Create inquiry record
        $inquiry = OrderInquiry::create([
            'company_name' => $request->company_name,
            'client_name' => $request->client_name,
            'emails' => array_values(array_filter($request->email)),
            'phones' => $phones,
            'domain_name' => $request->domain_name,
            'order_value' => $request->order_value,
            'service_ids' => $request->service_ids,
            'source_ids' => $request->source_ids,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'full_address' => $request->full_address,
            'notes' => $request->notes,
            'ip_address' => $request->ip(),
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'title' => 'Inquiry Submitted!',
            'message' => 'Thank you, ' . $request->client_name . '. Your project request has been logged successfully. Our experts will contact you shortly.'
        ]);
    }


    /**
     * Display a specific inquiry.
     */
    public function show($id)
    {
        $inquiry = OrderInquiry::findOrFail($id);
        
        // Load names for services and sources from IDs
        $services = \App\Models\Service::whereIn('id', (array)$inquiry->service_ids)->pluck('name');
        $sources = \App\Models\Source::whereIn('id', (array)$inquiry->source_ids)->pluck('name');

        // Auto-mark reviewed
        if($inquiry->status === 'pending') {
            $inquiry->update(['status' => 'reviewed']);
        }

        return view('admin.inquiry.show', compact('inquiry', 'services', 'sources'));
    }

    /**
     * Show edit form for an inquiry.
     */
    public function edit($id)
    {
        $inquiry = OrderInquiry::findOrFail($id);
        $services = \App\Models\Service::all();
        $sources = \App\Models\Source::all();
        return view('admin.inquiry.edit', compact('inquiry', 'services', 'sources'));
    }

    /**
     * Update an inquiry.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'email' => 'required|array|min:1',
            'email.*' => 'required|email|max:255',
            'phone' => 'required|array|min:1',
            'phone.*' => 'required|numeric|digits_between:7,15',
            'domain_name' => 'required|string|max:255',
            'service_ids' => 'nullable|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'order_value' => 'nullable|numeric',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'zip_code' => 'required|numeric|digits:6',
            'full_address' => 'required|string',
        ]);

        $inquiry = OrderInquiry::findOrFail($id);

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

        $inquiry->update([
            'company_name' => $request->company_name,
            'client_name' => $request->client_name,
            'emails' => array_values(array_filter($request->email)),
            'phones' => $phones,
            'domain_name' => $request->domain_name,
            'order_value' => $request->order_value,
            'service_ids' => $request->service_ids,
            'source_ids' => $request->source_ids,
            'city' => $request->city,
            'state' => $request->state,
            'zip_code' => $request->zip_code,
            'full_address' => $request->full_address,
            'notes' => $request->notes,
            'status' => $request->status ?? $inquiry->status
        ]);

        return redirect()->route('admin.inquiry.index')->with('success', 'Inquiry updated successfully.');
    }

    /**
     * Delete an inquiry.
     */
    public function destroy($id)
    {
        $inquiry = OrderInquiry::findOrFail($id);
        $inquiry->delete();
        return back()->with('success', 'Inquiry deleted successfully.');
    }

    /**
     * Update status of an inquiry.
     */
    public function updateStatus(Request $request, $id)
    {
        $inquiry = OrderInquiry::findOrFail($id);
        
        $data = [];
        if ($request->has('status')) $data['status'] = $request->status;
        if ($request->has('notes')) $data['notes'] = $request->notes;

        $inquiry->update($data);

        return back()->with('success', 'Inquiry updated successfully.');
    }

    /**
     * Export inquiries to CSV.
     */
    public function export(Request $request)
    {
        $filename = "inquiries_" . date('Y-m-d_H-i-s') . ".csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($request) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['ID', 'Date', 'Company', 'Contact', 'Emails', 'Phones', 'Domain', 'Budget', 'City', 'State', 'Status', 'IP']);

            $query = OrderInquiry::query();
            
            // Search - across all relevant fields
            if ($request->filled('q')) {
                $q = $request->q;
                $query->where(function($qq) use ($q) {
                    $qq->where('company_name', 'like', "%$q%")
                       ->orWhere('client_name', 'like', "%$q%")
                       ->orWhere('domain_name', 'like', "%$q%")
                       ->orWhere('city', 'like', "%$q%")
                       ->orWhere('state', 'like', "%$q%")
                       ->orWhere('order_value', 'like', "%$q%")
                       ->orWhere('emails', 'like', "%$q%")
                       ->orWhere('phones', 'like', "%$q%");
                });
            }

            // Status Filter
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            // Date Range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
            } elseif ($request->filled('range') && $request->range == '7_days') {
                $query->where('created_at', '>=', now()->subDays(7));
            }
            
            $inquiries = $query->latest()->get();

            foreach ($inquiries as $in) {
                fputcsv($file, [
                    $in->id,
                    $in->created_at->format('d M Y'),
                    $in->company_name,
                    $in->client_name,
                    implode(', ', (array)$in->emails),
                    collect((array)$in->phones)->pluck('number')->implode(', '),
                    $in->domain_name,
                    $in->order_value,
                    $in->city,
                    $in->state,
                    $in->status,
                    $in->ip_address
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
