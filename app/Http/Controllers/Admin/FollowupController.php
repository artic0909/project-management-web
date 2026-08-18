<?php

namespace App\Http\Controllers\Admin;

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
    public function readNotification($id)
    {
        $followup = Followup::findOrFail($id);
        $followup->is_notif_read = 1;
        $followup->save();

        if ($followup->followable_type === \App\Models\Lead::class) {
            return redirect()->route('admin.leads.index', ['type' => 'followup_today'])->with('highlight_lead_id', $followup->followable_id);
        }
        
        return redirect()->back();
    }

    public function index(Request $request, $id)
    {
        $routePrefix = 'admin';
        $isOrder = Route::is($routePrefix . '.orders.*');
        $returnUrl = $request->query('return_url');
        
        if ($isOrder) {
            $model = Order::with(['status', 'services', 'sources', 'assignments.sale', 'followups.creator', 'paymentTerms', 'mktPaymentStatus'])->findOrFail($id);
            $typeLabel = 'Order';
            $backRoute = route($routePrefix . '.orders.index');
            $orderStatuses = Status::where('type', 'order')->get();
            $paymentStatuses = Status::where('type', 'payment')->get();
            $statuses = [];
        } else {
            $model = Lead::with(['status', 'sources', 'services', 'assignments.sale', 'followups.creator', 'notes_history.createdBy', 'notes_history.updatedBy'])->findOrFail($id);
            $typeLabel = 'Lead';
            $backRoute = route($routePrefix . '.leads.index');
            $statuses = Status::where('type', 'lead')->where('name', '!=', 'lost')->get();
            $orderStatuses = [];
            $paymentStatuses = [];
        }
        
        $totalFollowups = $model->followups->count();
        $lastFollowup = $model->followups->first();
        
        return view('admin.followup', compact('model', 'totalFollowups', 'lastFollowup', 'isOrder', 'typeLabel', 'backRoute', 'orderStatuses', 'paymentStatuses', 'statuses', 'routePrefix', 'returnUrl'));
    }

    public function store(Request $request, $id)
    {
        $routePrefix = 'admin';
        $isOrder = Route::is($routePrefix . '.orders.*');
        
        $rules = [
            'followup_date' => 'required_unless:followup_type,None|date',
            'followup_type' => 'required|string|in:Calling,Message,Both,None',
            'calling_note' => 'required_if:followup_type,Calling,Both|nullable|string',
            'message_note' => 'required_if:followup_type,Message,Both|nullable|string',
        ];

        if (!$isOrder) {
            $rules['status_id'] = 'required|exists:statuses,id';
            $rules['priority'] = 'required|string';
        }

        $request->validate($rules, [
            'calling_note.required_if' => 'The calling note is required when interaction involves calling.',
            'message_note.required_if' => 'The message note is required when interaction involves messaging.',
        ]);

        $model = $isOrder ? Order::findOrFail($id) : Lead::findOrFail($id);
        
        if (!$isOrder) {
            $model->update([
                'status_id' => $request->status_id,
                'priority' => $request->priority,
            ]);
        }

        $nextScheduleDate = null;
        if ($request->schedule_type) {
            if ($request->schedule_type === 'Today') {
                $nextScheduleDate = \Carbon\Carbon::today();
            } elseif ($request->schedule_type === 'Tomorrow') {
                $nextScheduleDate = \Carbon\Carbon::tomorrow();
            } elseif ($request->schedule_type === 'After 2 Days') {
                $nextScheduleDate = \Carbon\Carbon::today()->addDays(2);
            } elseif ($request->schedule_type === 'After 3 Days') {
                $nextScheduleDate = \Carbon\Carbon::today()->addDays(3);
            } elseif ($request->schedule_type === 'After 5 Days') {
                $nextScheduleDate = \Carbon\Carbon::today()->addDays(5);
            } elseif ($request->schedule_type === 'After 7 Days') {
                $nextScheduleDate = \Carbon\Carbon::today()->addDays(7);
            } elseif ($request->schedule_type === 'Custom' && $request->custom_schedule_date) {
                $nextScheduleDate = \Carbon\Carbon::parse($request->custom_schedule_date);
            }

            if ($nextScheduleDate && $request->schedule_time) {
                $time = \Carbon\Carbon::parse($request->schedule_time);
                $nextScheduleDate->setTime($time->hour, $time->minute, 0);
            }
        }

        if ($request->followup_type !== 'None') {
            $model->followups()->create([
                'followup_date' => $request->followup_date,
                'next_schedule_date' => $nextScheduleDate,
                'followup_type' => $request->followup_type,
                'calling_note' => $request->calling_note,
                'message_note' => $request->message_note,
                'status' => 'pending',
                'created_by_id' => Auth::id(),
                'created_by_type' => get_class(Auth::user()),
            ]);
        }

        if (!$isOrder) {
            session()->put('highlight_lead_id', $model->id);
        }

        $redirectUrl = $request->input('return_url');
        if ($redirectUrl) {
            $hash = '#lead-' . $model->id;
            if (strpos($redirectUrl, '#') === false) {
                $redirectUrl .= $hash;
            } else {
                $redirectUrl = preg_replace('/#.*/', $hash, $redirectUrl);
            }
            return redirect($redirectUrl)->with('success', 'Followup added successfully!');
        }

        return redirect()->back()->with('success', 'Followup added successfully!');
    }
}
