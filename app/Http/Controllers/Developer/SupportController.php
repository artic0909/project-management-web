<?php

namespace App\Http\Controllers\Developer;

use App\Http\Controllers\Controller;
use App\Models\Support;
use App\Models\Reply;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    /**
     * Developer: List all tickets (similar to Admin).
     */
    public function index(Request $request)
    {
        $query = Support::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function($qq) use ($q) {
                $qq->where('ticket_no', 'like', "%$q%")
                   ->orWhere('company_name', 'like', "%$q%")
                   ->orWhere('your_name', 'like', "%$q%")
                   ->orWhere('email', 'like', "%$q%")
                   ->orWhere('phone', 'like', "%$q%")
                   ->orWhere('subject', 'like', "%$q%");
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('status', '!=', 'closed');
            } else {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        $perPage = $request->get('per_page', 10);
        if ($perPage === 'all') {
            $perPage = Support::count() ?: 10;
        }

        $tickets = $query->latest()->paginate($perPage)->withQueryString();

        // Stats for boxes
        $total = Support::count();
        $active = Support::where('status', '!=', 'closed')->count();
        $closed = Support::where('status', 'closed')->count();
        $pending = Support::where('status', 'pending')->count();
        $routePrefix = 'developer';

        return view('admin.supports.index', compact('tickets', 'total', 'active', 'closed', 'pending', 'routePrefix'));
    }

    /**
     * Developer: Show ticket details and replies.
     */
    public function show($id)
    {
        $ticket = Support::with('replies')->findOrFail($id);
        $routePrefix = 'developer';
        return view('admin.supports.show', compact('ticket', 'routePrefix'));
    }

    /**
     * Developer: Store reply to a ticket.
     */
    public function reply(Request $request, $id)
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
     * Developer: Quick status update.
     */
    public function statusUpdate(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,pending,review,replied,closed',
        ]);

        $ticket = Support::findOrFail($id);
        $ticket->update(['status' => $request->status]);

        return back()->with('success', 'Ticket status updated!');
    }
}
