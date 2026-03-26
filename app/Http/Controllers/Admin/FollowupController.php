<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FollowupController extends Controller
{
    public function index($id)
    {
        $isOrder = Route::is('admin.orders.*');
        
        if ($isOrder) {
            $model = Order::with(['status', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
            $typeLabel = 'Order';
            $backRoute = route('admin.orders.index');
        } else {
            $model = Lead::with(['status', 'source', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
            $typeLabel = 'Lead';
            $backRoute = route('admin.leads.index');
        }
        
        $totalFollowups = $model->followups->count();
        $lastFollowup = $model->followups->first();
        
        return view('admin.followup', compact('model', 'totalFollowups', 'lastFollowup', 'isOrder', 'typeLabel', 'backRoute'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'followup_date' => 'required|date',
            'followup_type' => 'required|string|in:Calling,Message,Both',
            'calling_note' => 'nullable|string',
            'message_note' => 'nullable|string',
        ]);

        $isOrder = Route::is('admin.orders.*');
        $model = $isOrder ? Order::findOrFail($id) : Lead::findOrFail($id);

        $model->followups()->create([
            'followup_date' => $request->followup_date,
            'followup_type' => $request->followup_type,
            'calling_note' => $request->calling_note,
            'message_note' => $request->message_note,
            'status' => 'pending',
            'created_by_id' => Auth::id(),
            'created_by_type' => get_class(Auth::user()),
        ]);

        return redirect()->back()->with('success', 'Followup added successfully!');
    }
}
