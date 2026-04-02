<?php

namespace App\Http\Controllers\Sale;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Status;
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
        $routePrefix = 'sale';
        $isOrder = Route::is($routePrefix . '.orders.*');
        
        if ($isOrder) {
            $model = Order::with(['status', 'service', 'assignments.sale', 'followups.creator', 'paymentTerms', 'mktPaymentStatus'])->findOrFail($id);
            $this->checkAccess($model);
            $typeLabel = 'Order';
            $backRoute = route($routePrefix . '.orders.index');
            $orderStatuses = Status::where('type', 'order')->get();
            $paymentStatuses = Status::where('type', 'payment')->get();
            $statuses = [];
        } else {
            $model = Lead::with(['status', 'source', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
            $this->checkAccess($model);
            $typeLabel = 'Lead';
            $backRoute = route($routePrefix . '.leads.index');
            $statuses = Status::where('type', 'lead')->get();
            $orderStatuses = [];
            $paymentStatuses = [];
        }
        
        $totalFollowups = $model->followups->count();
        $lastFollowup = $model->followups->first();
        
        return view('admin.followup', compact('model', 'totalFollowups', 'lastFollowup', 'isOrder', 'typeLabel', 'backRoute', 'orderStatuses', 'paymentStatuses', 'statuses', 'routePrefix'));
    }

    public function store(Request $request, $id)
    {
        $routePrefix = 'sale';
        $request->validate([
            'followup_date' => 'required|date',
            'followup_type' => 'required|string|in:Calling,Message,Both',
            'calling_note' => 'required_if:followup_type,Calling,Both|nullable|string',
            'message_note' => 'required_if:followup_type,Message,Both|nullable|string',
        ], [
            'calling_note.required_if' => 'The calling note is required when interaction involves calling.',
            'message_note.required_if' => 'The message note is required when interaction involves messaging.',
        ]);

        $isOrder = Route::is($routePrefix . '.orders.*');
        $model = $isOrder ? Order::findOrFail($id) : Lead::findOrFail($id);
        $this->checkAccess($model);

        // Exclusive Re-assignment logic: 
        // If assigned to more than one sales person, re-assign exclusively to the one who makes the followup.
        $currentSaleId = auth()->guard('sale')->id();
        if ($model->assignments()->count() > 1) {
            $model->assignments()->delete();
            $model->assignments()->create([
                'assigned_to' => $currentSaleId
            ]);
        }

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
