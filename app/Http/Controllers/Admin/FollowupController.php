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
        
        \App\Models\NotificationRead::firstOrCreate([
            'user_type' => get_class(auth()->guard('admin')->user()),
            'user_id' => auth()->guard('admin')->id(),
            'item_type' => 'followup',
            'item_id' => $id,
        ]);

        if ($followup->followable_type === \App\Models\Lead::class) {
            return redirect()->route('admin.leads.index', ['type' => 'followup_today'])->with('highlight_lead_id', $followup->followable_id);
        }
        
        return redirect()->back();
    }

    public function checkNotifications()
    {
        $adminId = auth()->guard('admin')->id();
        $adminType = \App\Models\Admin::class;
        $today = \Carbon\Carbon::today();
        $nowPlus15 = \Carbon\Carbon::now()->addMinutes(15);

        // Read notifications
        $readFollowupIds = \App\Models\NotificationRead::where('user_type', $adminType)
            ->where('user_id', $adminId)
            ->where('item_type', 'followup')
            ->pluck('item_id');

        $readTaskAssignIds = \App\Models\NotificationRead::where('user_type', $adminType)
            ->where('user_id', $adminId)
            ->where('item_type', 'task_assign')
            ->pluck('item_id');

        $todayTimedFollowups = \App\Models\Lead::where('is_losted', 0)
            ->whereHas('followups', function ($q) use ($today, $readFollowupIds, $nowPlus15) {
                $q->whereIn('id', function($sub) {
                    $sub->selectRaw('max(id)')->from('followups')
                        ->whereColumn('followable_id', 'leads.id')
                        ->where('followable_type', \App\Models\Lead::class);
                })->whereDate('next_schedule_date', $today)
                  ->whereTime('next_schedule_date', '!=', '00:00:00')
                  ->where('next_schedule_date', '<=', $nowPlus15)
                  ->whereNotIn('id', $readFollowupIds);
            })->with(['followups' => function($q) {
                $q->orderBy('id', 'desc');
            }])->get();

        $unreadTaskReplies = \App\Models\ProjectTaskAssign::whereNotNull('remarks')
            ->where('remarks', '!=', '')
            ->whereNotIn('id', $readTaskAssignIds)
            ->whereHas('task')
            ->with(['task.project', 'task.creator', 'developer'])
            ->latest('updated_at')
            ->take(15)
            ->get();

        $upcomingRenewals = \App\Models\Order::whereBetween('renewal_date', [
            now()->startOfDay(),
            now()->addDays(3)->endOfDay()
        ])->get();

        $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];

        $followupList = [];
        foreach ($todayTimedFollowups as $lead) {
            $latest = $lead->followups->first();
            if (!$latest) continue;

            $phoneList = is_array($lead->phones) ? $lead->phones : (json_decode($lead->phones, true) ?? []);
            $firstPhone = reset($phoneList);
            $displayPhone = '';
            if ($firstPhone && is_array($firstPhone)) {
                $displayPhone = ($codes[$firstPhone['code_idx'] ?? null] ?? '') . ($firstPhone['number'] ?? '');
            } elseif ($firstPhone) {
                $displayPhone = (string) $firstPhone;
            }

            $followupList[] = [
                'followup_id' => $latest->id,
                'lead_id' => $lead->id,
                'title' => $lead->company ?: ($lead->contact_person ?: ('Lead #' . $lead->id)),
                'phone' => $displayPhone,
                'time' => \Carbon\Carbon::parse($latest->next_schedule_date)->format('h:i A'),
                'read_url' => route('admin.followup.read_notif', $latest->id),
            ];
        }

        $taskReplyList = [];
        foreach ($unreadTaskReplies as $assign) {
            $taskReplyList[] = [
                'id' => $assign->id,
                'task_id' => $assign->task_id,
                'title' => '#TSK-' . $assign->task_id . ' ' . ($assign->task->title ?? 'Task'),
                'dev_name' => $assign->developer->name ?? 'Developer',
                'project_name' => $assign->task->project->project_name ?? 'Project',
                'remarks' => \Illuminate\Support\Str::limit(trim($assign->remarks ?? ''), 48),
                'time_ago' => $assign->updated_at ? $assign->updated_at->diffForHumans() : 'Just now',
                'read_url' => route('admin.tasks.read_reply_notif', $assign->id),
            ];
        }

        $renewalList = [];
        foreach ($upcomingRenewals as $order) {
            $diff = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($order->renewal_date)->startOfDay(), false);
            $timeText = $diff == 0 ? 'Today' : ($diff == 1 ? 'Tomorrow' : "in $diff days");
            $renewalList[] = [
                'id' => $order->id,
                'company_name' => $order->company_name,
                'order_number' => $order->order_number,
                'domain_name' => $order->domain_name ?? 'N/A',
                'timeText' => $timeText,
                'date' => \Carbon\Carbon::parse($order->renewal_date)->format('d M, Y'),
                'url' => route('admin.orders.show', $order->id),
            ];
        }

        $totalNotifs = count($followupList) + count($taskReplyList) + count($renewalList);

        return response()->json([
            'total' => $totalNotifs,
            'activeFollowupsCount' => count($followupList),
            'followups' => $followupList,
            'taskReplies' => $taskReplyList,
            'renewals' => $renewalList,
        ]);
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

    public function update(Request $request, $id)
    {
        $rules = [
            'followup_date' => 'required_unless:followup_type,None|date',
            'followup_type' => 'required|string|in:Calling,Message,Both,None',
            'calling_note' => 'required_if:followup_type,Calling,Both|nullable|string',
            'message_note' => 'required_if:followup_type,Message,Both|nullable|string',
        ];

        $request->validate($rules, [
            'calling_note.required_if' => 'The calling note is required when interaction involves calling.',
            'message_note.required_if' => 'The message note is required when interaction involves messaging.',
        ]);

        $followup = Followup::findOrFail($id);

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
            $followup->update([
                'followup_date' => $request->followup_date,
                'next_schedule_date' => $nextScheduleDate,
                'followup_type' => $request->followup_type,
                'calling_note' => $request->calling_note,
                'message_note' => $request->message_note,
            ]);
        }

        return redirect()->back()->with('success', 'Followup updated successfully!');
    }
}
