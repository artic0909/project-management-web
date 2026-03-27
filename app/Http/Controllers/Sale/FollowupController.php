<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class FollowupController extends Controller
{
    private function checkAccess($model)
    {
        $saleId = auth()->guard('sale')->id();
        $saleType = \App\Models\Sale::class;

        if ($model instanceof Order) {
            $hasAccess = $model->created_by == $saleId && $model->created_by_type == $saleType;
            if (!$hasAccess) {
                $hasAccess = $model->assignments()->where('assigned_to', $saleId)->exists();
            }
        } else {
            $hasAccess = $model->created_by == $saleId && $model->created_by_type == $saleType;
            if (!$hasAccess) {
                $hasAccess = $model->assignments()->where('assigned_to', $saleId)->exists();
            }
        }

        if (!$hasAccess) {
            abort(403, 'Unauthorized access to this followup.');
        }
    }

    public function index($id)
    {
        $isOrder = Route::is('sale.orders.*');
        
        if ($isOrder) {
            $model = Order::with(['status', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
            $this->checkAccess($model);
            $typeLabel = 'Order';
            $backRoute = route('sale.orders.index');
        } else {
            $model = Lead::with(['status', 'source', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
            $this->checkAccess($model);
            $typeLabel = 'Lead';
            $backRoute = route('sale.leads.index');
        }
        
        $totalFollowups = $model->followups->count();
        $lastFollowup = $model->followups->first();
        
        return view('sale.followup', compact('model', 'totalFollowups', 'lastFollowup', 'isOrder', 'typeLabel', 'backRoute'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'followup_date' => 'required|date',
            'followup_type' => 'required|string|in:Calling,Message,Both',
            'calling_note' => 'nullable|string',
            'message_note' => 'nullable|string',
        ]);

        $isOrder = Route::is('sale.orders.*');
        $model = $isOrder ? Order::findOrFail($id) : Lead::findOrFail($id);
        $this->checkAccess($model);

        $model->followups()->create([
            'followup_date' => $request->followup_date,
            'followup_type' => $request->followup_type,
            'calling_note' => $request->calling_note,
            'message_note' => $request->message_note,
            'status' => 'pending',
            'created_by_id' => auth()->guard('sale')->id(),
            'created_by_type' => \App\Models\Sale::class,
        ]);

        return redirect()->back()->with('success', 'Followup added successfully!');
    }
}
