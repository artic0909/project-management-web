<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeadNoteController extends Controller
{
    private function getCurrentUser()
    {
        if (Auth::guard('admin')->check()) {
            return Auth::guard('admin')->user();
        } elseif (Auth::guard('sale')->check()) {
            return Auth::guard('sale')->user();
        }
        return Auth::user();
    }

    public function store(Request $request, Lead $lead)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $user = $this->getCurrentUser();

        LeadNote::create([
            'lead_id' => $lead->id,
            'notes' => $request->notes,
            'created_by' => $user->id,
            'created_by_type' => get_class($user),
            'updated_by' => $user->id,
            'updated_by_type' => get_class($user),
        ]);

        return redirect()->back()->with('success', 'Note added successfully!');
    }

    public function update(Request $request, LeadNote $note)
    {
        $request->validate([
            'notes' => 'required|string',
        ]);

        $user = $this->getCurrentUser();

        $note->update([
            'notes' => $request->notes,
            'updated_by' => $user->id,
            'updated_by_type' => get_class($user),
        ]);

        return redirect()->back()->with('success', 'Note updated successfully!');
    }

    public function destroy(LeadNote $note)
    {
        $note->delete();
        return redirect()->back()->with('success', 'Note deleted successfully!');
    }
}
