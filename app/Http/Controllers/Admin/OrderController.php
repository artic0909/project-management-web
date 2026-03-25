<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Sale;
use App\Models\Status;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('admin.orders.index');
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

    public function edit()
    {
        return view('admin.orders.edit');
    }
}
