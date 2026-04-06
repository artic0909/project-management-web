<?php

namespace App\Http\Controllers;

use App\Models\OrderInquiry;
use Illuminate\Http\Request;

class OrderInquiryController extends Controller
{
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
            'service_ids' => 'required|array|min:1',
            'service_ids.*' => 'exists:services,id',
            'order_value' => 'required|numeric',
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
}
