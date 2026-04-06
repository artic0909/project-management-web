@extends('admin.layout.app')

@section('title', 'Ticket Details - ' . $ticket->id)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">
        <div class="page-header">
            <div>
                <a href="{{ route('admin.supports.index') }}" class="btn-ghost sm mb-2"><i class="bi bi-arrow-left"></i> Back to Tickets</a>
                <h1 class="page-title">Ticket Reference: #{{ $ticket->id }}</h1>
                <p class="page-desc">Viewing details for ticket submitted by {{ $ticket->your_name }} from {{ $ticket->company_name }}.</p>
            </div>
            <div class="d-flex gap-2">
                <span class="status-pill big" style="background:var(--accent-bg); color:var(--accent); font-weight:700;">{{ strtoupper($ticket->status) }}</span>
                <span class="status-pill big" style="background:rgba(255, 77, 28, 0.1); color:var(--accent); font-weight:700;">{{ strtoupper($ticket->priority) }} PRIORITY</span>
            </div>
        </div>

        <div style="display:grid; grid-template-columns: 1fr 340px; gap:24px;">
            <!-- Left side: Content and Conversation -->
            <div style="display:flex; flex-direction:column; gap:24px;">
                
                <!-- Ticket Content -->
                <div class="dash-card" style="padding:24px;">
                    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:16px;">
                        <div>
                            <h2 style="font-size:20px; font-weight:700; color:var(--t1); margin-bottom:4px;">{{ $ticket->subject }}</h2>
                            <p style="font-size:13px; color:var(--t3);"><i class="bi bi-clock-history"></i> Submitted on {{ $ticket->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                    
                    <div style="background:var(--bg2); padding:20px; border-radius:12px; border:1px solid var(--b1); line-height:1.7; color:var(--t2);">
                        {!! nl2br(e($ticket->message)) !!}
                    </div>

                    @if($ticket->attachment)
                    <div style="margin-top:20px;">
                        <p style="font-size:14px; font-weight:600; color:var(--t1); margin-bottom:8px;"><i class="bi bi-paperclip"></i> Attachment</p>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="attachment-preview" 
                           style="display:inline-flex; align-items:center; gap:10px; padding:12px; border:1px solid var(--b1); border-radius:10px; background:var(--bg2); text-decoration:none;">
                           <img src="{{ asset('storage/' . $ticket->attachment) }}" style="width:40px; height:40px; object-fit:cover; border-radius:6px;">
                           <div style="font-size:13px;">
                               <div style="color:var(--t1); font-weight:600;">View Full Image</div>
                               <div style="color:var(--t3);">{{ basename($ticket->attachment) }}</div>
                           </div>
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Conversation/Replies -->
                <div class="dash-card" style="padding:24px;">
                    <h3 style="font-size:18px; font-weight:700; color:var(--t1); margin-bottom:20px;"><i class="bi bi-chat-left-dots"></i> Conversation History</h3>
                    
                    <div style="display:flex; flex-direction:column; gap:20px;">
                        @forelse($ticket->replies as $reply)
                        <div style="display:flex; gap:16px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:var(--accent); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                                <i class="bi bi-person-fill" style="color:#fff;"></i>
                            </div>
                            <div style="flex:1; background:var(--bg3); padding:16px; border-radius:0 16px 16px 16px; border:1px solid var(--b2);">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <strong style="font-size:14px; color:var(--t1);">Support Team Agent</strong>
                                    <span style="font-size:11px; color:var(--t3);">{{ $reply->created_at->diffForHumans() }}</span>
                                </div>
                                <div style="font-size:14px; color:var(--t2); line-height:1.6;">
                                    {!! nl2br(e($reply->message_reply)) !!}
                                </div>
                                <div style="margin-top:8px; border-top:1px solid var(--b1); padding-top:4px; font-size:10px; font-weight:700; color:var(--accent); text-transform:uppercase;">
                                    Status set to: {{ $reply->status }}
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:32px; color:var(--t4);">No replies yet. Use the sidebar to send a response.</div>
                        @endforelse
                    </div>
                </div>

                <!-- Reply Form -->
                <div class="dash-card" style="padding:24px;">
                    <h3 style="font-size:18px; font-weight:700; color:var(--t1); margin-bottom:20px;"><i class="bi bi-reply-fill"></i> Send Response</h3>
                    <form action="{{ route('admin.supports.reply', $ticket->id) }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <label class="form-lbl">Your Reply</label>
                            <textarea name="message_reply" class="form-inp" rows="6" placeholder="Type your response here..." required></textarea>
                            @error('message_reply') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Update Status</label>
                            <select name="status" class="form-inp" required>
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Keep Pending</option>
                                <option value="review" {{ $ticket->status == 'review' ? 'selected' : '' }}>Set to Review</option>
                                <option value="replied" {{ $ticket->status == 'replied' ? 'selected' : 'selected' }}>Set to Replied</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Close Ticket</option>
                            </select>
                            @error('status') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn-primary-solid">Send Reply</button>
                    </form>
                </div>

            </div>

            <!-- Right side: Meta Data -->
            <div style="display:flex; flex-direction:column; gap:24px;">
                <div class="dash-card" style="padding:24px;">
                    <h3 style="font-size:16px; font-weight:700; color:var(--t1); margin-bottom:16px;">General Metadata</h3>
                    
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div>
                            <div style="font-size:12px; color:var(--t3); margin-bottom:2px;">Contact Person</div>
                            <div style="font-size:14px; font-weight:600; color:var(--t2);">{{ $ticket->your_name }}</div>
                            <div style="font-size:13px; font-weight:500; color:var(--accent);"><i class="bi bi-envelope"></i> {{ $ticket->email }}</div>
                            <div style="font-size:13px; font-weight:500; color:var(--accent);"><i class="bi bi-telephone"></i> {{ $ticket->phone }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px; color:var(--t3); margin-bottom:2px;">Company / Domain</div>
                            <div style="font-size:14px; font-weight:600; color:var(--t2);">{{ $ticket->company_name }}</div>
                            <div style="font-size:12px; color:var(--accent);">{{ $ticket->domain_name ?? 'No Domain Provided' }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px; color:var(--t3); margin-bottom:2px;">Reference ID</div>
                            <div style="font-size:14px; font-weight:600; color:var(--t2);">#{{ $ticket->id }}</div>
                        </div>
                        <div>
                            <div style="font-size:12px; color:var(--t3); margin-bottom:2px;">Client IP</div>
                            <div style="font-size:14px; font-weight:600; color:var(--t4); font-family:monospace;">{{ $ticket->ip_address }}</div>
                        </div>
                    </div>

                    <hr style="border:none; border-top:1px solid var(--b1); margin:20px 0;">

                    <h3 style="font-size:16px; font-weight:700; color:var(--t1); margin-bottom:16px;">Quick Actions</h3>
                    <form action="{{ route('admin.supports.status', $ticket->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="form-row">
                            <label class="form-lbl">Set Direct Status</label>
                            <select name="status" class="form-inp" style="padding:8px 12px; font-size:13px;" onchange="this.form.submit()">
                                <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="review" {{ $ticket->status == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="replied" {{ $ticket->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </form>

                    <button class="btn-ghost danger sm w-100 mt-2" onclick="confirmDelete('{{ route('admin.supports.destroy', $ticket->id) }}')">
                        <i class="bi bi-trash"></i> Delete Ticket
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Ticket</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this support ticket?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function confirmDelete(url) {
        const form = document.getElementById('deleteForm');
        form.action = url;
        openModal('deleteModal');
    }
</script>

@endsection
