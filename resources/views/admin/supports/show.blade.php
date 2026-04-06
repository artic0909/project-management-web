@extends('admin.layout.app')

@section('title', 'Ticket Details - #' . $ticket->id)

@section('content')

<style>
    .detail-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .dk-item {
        text-align: center;
    }
    .dk-val {
        font-size: 18px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 4px;
    }
    .dk-lbl {
        font-size: 11px;
        font-weight: 600;
        color: var(--t3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .detail-row { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icon { width:34px; height:34px; border-radius:10px; background:var(--bg4); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--t3); font-size:15px; }
    .detail-lbl { font-size:10px; color:var(--t4); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom: 2px; }
    .detail-val { font-size:13px; font-weight:700; color:var(--t1); }
</style>

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('admin.supports.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Tickets
                    </a>
                </div>
                <h1 class="page-title">Support Ticket Details</h1>
                <p class="page-desc">Viewing request from <strong>{{ $ticket->company_name }}</strong></p>
            </div>
            <div style="display:flex;gap:10px;">
                <button class="btn-primary-solid danger sm" onclick="confirmDelete('{{ route('admin.supports.destroy', $ticket->id) }}')">
                    <i class="bi bi-trash-fill"></i> Delete Ticket
                </button>
            </div>
        </div>

        <div class="dash-grid">
            
            {{-- Left Column: Identity & Contact --}}
            <div class="dash-card span-4" style="height:fit-content;">
                <div class="card-head">
                    <div class="card-title">Identity & Contact</div>
                </div>
                <div class="card-body" style="padding:0 18px 24px;">
                    @php 
                        $initials = strtoupper(substr($ticket->company_name, 0, 1) . substr($ticket->your_name, 0, 1));
                    @endphp

                    <div style="display:flex;flex-direction:column;align-items:center;padding:24px 0 20px;border-bottom:1px solid var(--b1);text-align:center;">
                        <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;margin-bottom:14px;box-shadow:0 8px 30px rgba(99,102,241,.3);">{{ $initials }}</div>
                        <div style="font-size:19px;font-weight:800;color:var(--t1);letter-spacing:-.4px;">{{ $ticket->company_name }}</div>
                        <div style="font-size:13px;color:var(--accent);margin-top:4px;font-weight:600;">{{ $ticket->email }}</div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;margin-top:20px;">
                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(99, 102, 241, 0.1); color:var(--accent);"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $ticket->your_name }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(16, 185, 129, 0.1); color:#10b981;"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Phone Number</div>
                                <div class="detail-val">{{ $ticket->phone }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(14, 165, 233, 0.1); color:#0ea5e9;"><i class="bi bi-globe"></i></div>
                            <div>
                                <div class="detail-lbl">Domain / URL</div>
                                <div class="detail-val">{{ $ticket->domain_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(245, 158, 11, 0.1); color:#f59e0b;"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Technical IP</div>
                                <div class="detail-val" style="font-family:monospace;font-size:12px;">{{ $ticket->ip_address }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Content & Replies --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                
                {{-- KPI Strip --}}
                <div class="dash-card">
                    <div class="card-body">
                        <div class="detail-kpis">
                            <div class="dk-item">
                                <div class="dk-val" style="color:var(--t1);">#{{ $ticket->id }}</div>
                                <div class="dk-lbl">Ticket ID</div>
                            </div>
                            <div class="dk-item">
                                @php
                                    $pClr = ['high' => '#ef4444', 'medium' => '#f59e0b', 'low' => '#0ea5e9'];
                                    $pclr = $pClr[$ticket->priority] ?? '#6366f1';
                                @endphp
                                <div class="dk-val" style="color:{{ $pclr }}; text-transform:uppercase;">{{ $ticket->priority }}</div>
                                <div class="dk-lbl">Priority</div>
                            </div>
                            <div class="dk-item">
                                @php
                                    $sClr = ['pending' => '#f59e0b', 'review' => '#0ea5e9', 'replied' => '#10b981', 'closed' => '#6b7280'];
                                    $sclr = $sClr[$ticket->status] ?? '#6366f1';
                                @endphp
                                <div class="dk-val" style="color:{{ $sclr }};">{{ ucfirst($ticket->status) }}</div>
                                <div class="dk-lbl">Ticket Status</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val">{{ $ticket->created_at->format('d M Y') }}</div>
                                <div class="dk-lbl">Submit Date</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ticket Message --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Customer Request</div>
                    </div>
                    <div class="card-body" style="padding:18px;">
                        <div style="font-size:15px; font-weight:700; color:var(--t1); margin-bottom:10px;">Subject: {{ $ticket->subject }}</div>
                        <div style="font-size:14px; line-height:1.7; color:var(--t2); background:var(--bg3); padding:20px; border-radius:12px; border:1px solid var(--b2); white-space:pre-wrap;">{{ $ticket->message }}</div>
                        
                        @if($ticket->attachment)
                        <div style="margin-top:20px; display:flex; align-items:center; gap:15px; background:var(--bg3); padding:12px; border-radius:12px; border:1px dashed var(--b2);">
                            <div style="width:60px; height:60px; border-radius:8px; overflow:hidden; border:1px solid var(--b1);">
                                <img src="{{ asset('storage/' . $ticket->attachment) }}" style="width:100%; height:100%; object-fit:cover; cursor:pointer;" onclick="window.open(this.src, '_blank')">
                            </div>
                            <div>
                                <div style="font-size:13px; font-weight:700; color:var(--t1);">Attachment Included</div>
                                <a href="{{ asset('storage/' . $ticket->attachment) }}" download style="font-size:12px; font-weight:600; color:var(--accent); text-decoration:none;">
                                    <i class="bi bi-download"></i> Download Image
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>


                {{-- Reply Area --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Post a Reply</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <form action="{{ route('admin.supports.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="form-row mb-4">
                                <label class="form-lbl">Administrative Message</label>
                                <textarea name="message_reply" class="form-inp" rows="5" placeholder="Address the client's concern..." required style="resize:none; padding:15px;"></textarea>
                            </div>
                            
                            <div style="display:grid; grid-template-columns: 1fr 200px; gap:15px; align-items:end;">
                                <div class="form-row mb-0">
                                    <label class="form-lbl">Set New Ticket Status</label>
                                    <select name="status" class="form-inp">
                                        <option value="replied" {{ $ticket->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                        <option value="review" {{ $ticket->status == 'review' ? 'selected' : '' }}>Review</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-primary-solid" style="height:44px; width:100%;">
                                    <i class="bi bi-send-fill me-2"></i> Submit Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                                {{-- Conversation History --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-chat-left-dots"></i> Conversation History</div>
                    </div>
                    <div class="card-body" style="padding:24px; display:flex; flex-direction:column; gap:20px;">
                        @forelse($ticket->replies as $rep)
                        <div style="display:flex; gap:16px;">
                            <div style="width:36px; height:36px; border-radius:50%; background:var(--accent); display:flex; align-items:center; justify-content:center; color:white; flex-shrink:0;">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <div style="flex:1;">
                                <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                                    <span style="font-size:14px; font-weight:700; color:var(--t1);">Support Team Agent</span>
                                    <span style="font-size:11px; font-weight:600; color:var(--t4);">{{ $rep->created_at->format('M d, h:i A') }}</span>
                                </div>
                                <div style="background:var(--bg3); padding:16px; border-radius:0 14px 14px 14px; border:1px solid var(--b2); font-size:14px; color:var(--t2); line-height:1.6;">
                                    {{ $rep->message_reply }}
                                </div>
                                <div style="margin-top:8px; display:flex; align-items:center; gap:5px;">
                                    <span style="font-size:10px; font-weight:800; color:var(--accent); text-transform:uppercase;">Ticket Status set to: {{ $rep->status }}</span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:32px; color:var(--t4); font-size:14px;">
                            <i class="bi bi-info-circle" style="font-size:20px; display:block; margin-bottom:10px;"></i>
                            No interaction history recorded yet. Use the form below to post a reply.
                        </div>
                        @endforelse
                    </div>
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
    function openModal(id) { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }
</script>

@endsection
