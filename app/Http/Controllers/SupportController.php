<?php

namespace App\Http\Controllers;

use App\Models\Support;
use App\Models\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Str;

class SupportController extends Controller
{
    /**
     * Show front support form.
     */
    public function create()
    {
        return view('support');
    }

    /**
     * Store a new support ticket.
     */
    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'nullable|string|max:255',
            'your_name'    => 'required|string|max:255',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'domain_name'  => 'nullable|string|max:255',
            'subject'      => 'required|string|max:255',
            'priority'     => 'required|in:high,medium,low',
            'message'      => 'required|string',
            'attachment'   => 'nullable|array',
            'attachment.*' => 'image|max:2048',
        ]);

        $data = $request->all();
        $data['ip_address'] = $request->ip();
        $data['status'] = 'pending';

        if ($request->hasFile('attachment')) {
            $attachments = [];
            foreach ($request->file('attachment') as $file) {
                $attachments[] = $file->store('support_attachments', 'public');
            }
            $data['attachment'] = $attachments;
        }

        $data['ticket_no'] = 'TKT-' . strtoupper(Str::random(8));

        $support = Support::create($data);

        return back()->with('success', 'Your support ticket has been submitted successfully!')->with('ticket_no', $support->ticket_no);
    }

    /**
     * Admin: List all tickets.
     */
    public function adminIndex(Request $request)
    {
        $query = Support::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qq) use ($q) {
                $qq->where('company_name', 'like', "%$q%")
                   ->orWhere('your_name', 'like', "%$q%")
                   ->orWhere('subject', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $tickets = $query->latest()->paginate(15);

        // Stats for boxes
        $total = Support::count();
        $active = Support::where('status', 'active')->count();
        $closed = Support::where('status', 'closed')->count();
        $pending = Support::where('status', 'pending')->count();

        return view('admin.supports.index', compact('tickets', 'total', 'active', 'closed', 'pending'));
    }

    /**
     * Admin: Show ticket details and replies.
     */
    public function adminShow($id)
    {
        $ticket = Support::with('replies')->findOrFail($id);
        return view('admin.supports.show', compact('ticket'));
    }

    /**
     * Admin: Store reply to a ticket.
     */
    public function adminReply(Request $request, $id)
    {
        $request->validate([
            'message_reply' => 'required|string',
            'status'        => 'required|in:active,pending,review,replied,closed',
        ]);

        $ticket = Support::findOrFail($id);
        
        Reply::create([
            'support_id'    => $ticket->id,
            'message_reply' => $request->message_reply,
            'status'        => $request->status,
        ]);

        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Reply sent and status updated!');
    }

    /**
     * Admin: Quick status update.
     */
    public function adminStatusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,pending,review,replied,closed',
        ]);

        $ticket = Support::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated!');
    }

    /**
     * Admin: Delete ticket.
     */
    public function destroy($id)
    {
        $ticket = Support::findOrFail($id);
        if ($ticket->attachment && is_array($ticket->attachment)) {
            foreach ($ticket->attachment as $path) {
                Storage::disk('public')->delete($path);
            }
        }
        $ticket->delete();

    }

    /**
     * Admin: Bulk delete tickets.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'exists:supports,id',
        ]);

        $tickets = Support::whereIn('id', $request->ids)->get();
        foreach ($tickets as $ticket) {
            if ($ticket->attachment && is_array($ticket->attachment)) {
                foreach ($ticket->attachment as $path) {
                    Storage::disk('public')->delete($path);
                }
            }
            $ticket->delete();
        }

        return back()->with('success', 'Selected tickets deleted successfully!');
    }
}
