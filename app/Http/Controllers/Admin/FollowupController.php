<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Followup;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowupController extends Controller
{
    public function index($id)
    {
        $lead = Lead::with(['status', 'source', 'service', 'assignments.sale', 'followups.creator'])->findOrFail($id);
        
        $totalFollowups = $lead->followups->count();
        $lastFollowup = $lead->followups->first();
        
        return view('admin.followup', compact('lead', 'totalFollowups', 'lastFollowup'));
    }

    public function store(Request $request, $id)
    {
        $request->validate([
            'followup_date' => 'required|date',
            'followup_type' => 'required|string|in:Calling,Message,Both',
            'calling_note' => 'nullable|string',
            'message_note' => 'nullable|string',
        ]);

        $lead = Lead::findOrFail($id);

        $lead->followups()->create([
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
