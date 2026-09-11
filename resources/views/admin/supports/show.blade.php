@extends('admin.layout.app')

@section('title', 'Ticket Details - ' . ($ticket->ticket_no ?? '#' . $ticket->id))

@section('content')

<style>
    /* ─── SUPPORTS SHOW PAGE RESPONSIVE STYLES ─── */
    .supports-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    .supports-show-layout {
        display: grid;
        grid-template-columns: 350px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .supports-show-layout {
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 18px;
        }
    }

    @media (max-width: 992px) {
        .supports-show-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .left-profile-col, .right-content-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 0;
    }

    /* Client Profile Card */
    .client-avatar-lg {
        width: 76px;
        height: 76px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 14px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }

    .profile-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 18px 20px;
        border-bottom: 1px solid var(--b3);
        text-align: center;
    }

    .profile-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -0.4px;
        line-height: 1.3;
    }

    .profile-sub {
        font-size: 13px;
        color: var(--accent);
        font-weight: 600;
        margin-top: 4px;
        word-break: break-all;
    }

    /* Detail Rows */
    .detail-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 16px 18px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--b3);
        text-decoration: none;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        font-size: 15px;
    }

    .detail-lbl {
        font-size: 10.5px;
        color: var(--t3);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 2px;
    }

    .detail-val {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--t1);
        line-height: 1.4;
        word-break: break-word;
    }

    /* KPI Ribbon */
    .ticket-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 640px) {
        .ticket-kpis {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 380px) {
        .ticket-kpis {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    .kpi-box {
        background: var(--bg2);
        border: 1px solid var(--b3);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .kpi-val {
        font-size: 18px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 4px;
        letter-spacing: -0.5px;
        font-family: var(--mono, monospace);
    }

    .kpi-lbl {
        font-size: 11px;
        font-weight: 700;
        color: var(--t3);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* Reply Action Layout */
    .reply-form-grid {
        display: grid;
        grid-template-columns: 1fr 220px;
        gap: 16px;
        align-items: flex-end;
    }

    @media (max-width: 640px) {
        .reply-form-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }
    }

    /* Message Attachments */
    .attachment-card {
        display: flex;
        align-items: center;
        gap: 14px;
        background: var(--bg3);
        padding: 12px 14px;
        border-radius: 10px;
        border: 1px solid var(--b3);
        transition: var(--transition);
    }

    .attachment-card:hover {
        border-color: var(--accent);
        background: var(--bg2);
    }

    .attachment-thumb {
        width: 54px;
        height: 54px;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--b3);
        flex-shrink: 0;
    }

    .attachment-thumb img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        cursor: pointer;
    }

    /* Reply Bubbles */
    .reply-bubble-item {
        display: flex;
        gap: 14px;
        align-items: flex-start;
    }

    .agent-avatar {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: var(--accent);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
        font-size: 15px;
    }

    .reply-bubble-content {
        flex: 1;
        min-width: 0;
    }

    .reply-bubble-box {
        background: var(--bg3);
        padding: 16px;
        border-radius: 0 12px 12px 12px;
        border: 1px solid var(--b3);
        font-size: 13.5px;
        color: var(--t2);
        line-height: 1.6;
        word-break: break-word;
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page">

        <!-- PAGE HEADER -->
        <div class="supports-show-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route(($routePrefix ?? 'admin') . '.supports.index') }}" class="btn-ghost sm">
                        <i class="bi bi-arrow-left"></i> All Tickets
                    </a>
                    <span style="font-size: 12px; color: var(--t3);">•</span>
                    <span style="font-size: 12px; color: var(--t3); font-weight: 600; font-family: var(--mono, monospace);">
                        Ticket {{ $ticket->ticket_no }}
                    </span>
                </div>
                <h1 class="page-title">Ticket {{ $ticket->ticket_no }}</h1>
                <p class="page-desc">Viewing request from <strong>{{ $ticket->company_name ?? $ticket->your_name }}</strong></p>
            </div>
            
            <div class="header-actions">
                @if(($routePrefix ?? 'admin') !== 'developer')
                <button type="button" class="btn-ghost sm danger" style="color:#ef4444;" onclick="confirmDelete('{{ route(($routePrefix ?? 'admin') . '.supports.destroy', $ticket->id) }}')">
                    <i class="bi bi-trash-fill"></i> Delete Ticket
                </button>
                @endif
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="supports-show-layout">
            
            <!-- LEFT COLUMN: Identity & Contact -->
            <div class="left-profile-col">
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-vcard-fill"></i> Contact & Identity</div>
                    </div>
                    
                    @php 
                        $initials = strtoupper(substr($ticket->company_name ?? 'C', 0, 1) . substr($ticket->your_name ?? 'U', 0, 1));
                    @endphp

                    <div class="profile-hero">
                        <div class="client-avatar-lg">{{ $initials }}</div>
                        <div class="profile-title">{{ $ticket->company_name ?? 'Client' }}</div>
                        <div class="profile-sub">{{ $ticket->email }}</div>
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(99, 102, 241, 0.12); color:var(--accent);">
                                <i class="bi bi-person-fill"></i>
                            </div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $ticket->your_name }}</div>
                            </div>
                        </div>

                        <a href="mailto:{{ $ticket->email }}" class="detail-row">
                            <div class="detail-icon" style="background:rgba(14, 165, 233, 0.12); color:#0ea5e9;">
                                <i class="bi bi-envelope-fill"></i>
                            </div>
                            <div>
                                <div class="detail-lbl">Email Address</div>
                                <div class="detail-val" style="color:var(--accent);">{{ $ticket->email }}</div>
                            </div>
                        </a>

                        <a href="tel:{{ $ticket->phone }}" class="detail-row">
                            <div class="detail-icon" style="background:rgba(16, 185, 129, 0.12); color:#10b981;">
                                <i class="bi bi-telephone-fill"></i>
                            </div>
                            <div>
                                <div class="detail-lbl">Phone Number</div>
                                <div class="detail-val" style="color:#10b981;">{{ $ticket->phone }}</div>
                            </div>
                        </a>

                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(139, 92, 246, 0.12); color:#8b5cf6;">
                                <i class="bi bi-globe"></i>
                            </div>
                            <div>
                                <div class="detail-lbl">Domain / Website</div>
                                <div class="detail-val">
                                    @if($ticket->domain_name)
                                        <a href="https://{{ $ticket->domain_name }}" target="_blank" rel="noopener noreferrer" style="color:var(--accent); text-decoration:none;">
                                            {{ $ticket->domain_name }} <i class="bi bi-box-arrow-up-right" style="font-size:10px;"></i>
                                        </a>
                                    @else
                                        <span style="color:var(--t3);">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon" style="background:rgba(245, 158, 11, 0.12); color:#f59e0b;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <div class="detail-lbl">Technical IP</div>
                                <div class="detail-val" style="font-family:var(--mono, monospace); font-size:12px;">{{ $ticket->ip_address ?? '127.0.0.1' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Content, Reply & Timeline -->
            <div class="right-content-col">
                
                <!-- 4-KPI RIBBON -->
                <div class="ticket-kpis">
                    <div class="kpi-box">
                        <div class="kpi-val" style="color:var(--t1);">{{ $ticket->ticket_no }}</div>
                        <div class="kpi-lbl">Ticket ID</div>
                    </div>
                    
                    <div class="kpi-box">
                        @php
                            $pClr = ['high' => '#ef4444', 'medium' => '#f59e0b', 'low' => '#0ea5e9'];
                            $pclr = $pClr[$ticket->priority] ?? '#6366f1';
                        @endphp
                        <div class="kpi-val" style="color:{{ $pclr }}; text-transform:uppercase; font-size:16px;">
                            {{ $ticket->priority }}
                        </div>
                        <div class="kpi-lbl">Priority Level</div>
                    </div>

                    <div class="kpi-box">
                        @php
                            $sClr = ['active' => '#10b981', 'pending' => '#f59e0b', 'review' => '#0ea5e9', 'replied' => '#10b981', 'closed' => '#6b7280'];
                            $sclr = $sClr[$ticket->status] ?? '#6366f1';
                        @endphp
                        <div class="kpi-val" style="color:{{ $sclr }}; text-transform:uppercase; font-size:16px;">
                            {{ ucfirst($ticket->status) }}
                        </div>
                        <div class="kpi-lbl">Ticket Status</div>
                    </div>

                    <div class="kpi-box">
                        <div class="kpi-val" style="font-size:14px; color:var(--t1);">{{ $ticket->created_at->format('d M Y') }}</div>
                        <div class="kpi-lbl">Submission Date</div>
                    </div>
                </div>

                <!-- TICKET MESSAGE REQUEST -->
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-chat-left-text-fill"></i> Customer Request</div>
                        <span style="font-size:11.5px; color:var(--t3);">{{ $ticket->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="card-body" style="padding:18px;">
                        <div style="font-size:15px; font-weight:800; color:var(--t1); margin-bottom:12px; letter-spacing:-0.2px;">
                            Subject: {{ $ticket->subject }}
                        </div>
                        <div style="font-size:14px; line-height:1.7; color:var(--t2); background:var(--bg3); padding:16px 18px; border-radius:10px; border:1px solid var(--b3); white-space:pre-wrap; word-break:break-word;">{!! e($ticket->message) !!}</div>
                        
                        @if($ticket->attachment && is_array($ticket->attachment) && count($ticket->attachment) > 0)
                            <div style="margin-top:20px;">
                                <div style="font-size:12.5px; font-weight:700; color:var(--t1); margin-bottom:10px; text-transform:uppercase; letter-spacing:0.5px;">
                                    <i class="bi bi-paperclip"></i> Attachments Included ({{ count($ticket->attachment) }})
                                </div>
                                <div style="display:flex; flex-direction:column; gap:10px;">
                                    @foreach($ticket->attachment as $attach)
                                    <div class="attachment-card">
                                        <div class="attachment-thumb">
                                            <img src="{{ asset('storage/' . $attach) }}" alt="Attachment {{ $loop->iteration }}" onclick="window.open(this.src, '_blank')">
                                        </div>
                                        <div style="flex:1; min-width:0;">
                                            <div style="font-size:13px; font-weight:700; color:var(--t1);">Screenshot / Image {{ $loop->iteration }}</div>
                                            <a href="{{ asset('storage/' . $attach) }}" download style="font-size:12px; font-weight:600; color:var(--accent); text-decoration:none; display:inline-flex; align-items:center; gap:4px; margin-top:2px;">
                                                <i class="bi bi-download"></i> Download Image
                                            </a>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- POST A REPLY FORM -->
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-reply-fill"></i> Post Administrative Reply</div>
                    </div>
                    <div class="card-body" style="padding:18px;">
                        <form action="{{ route(($routePrefix ?? 'admin') . '.supports.reply', $ticket->id) }}" method="POST">
                            @csrf
                            <div class="form-row" style="margin-bottom:16px;">
                                <label class="form-lbl" style="font-size:12px; font-weight:700; color:var(--t2); text-transform:uppercase; margin-bottom:6px;">
                                    Administrative Message Response <span style="color:#ef4444;">*</span>
                                </label>
                                <textarea name="message_reply" class="form-inp" rows="5" placeholder="Address the client's concern or provide troubleshooting steps..." required style="resize:vertical; padding:12px 14px; font-size:13.5px;"></textarea>
                            </div>
                            
                            <div class="reply-form-grid">
                                <div class="form-row" style="margin:0;">
                                    <label class="form-lbl" style="font-size:12px; font-weight:700; color:var(--t2); text-transform:uppercase; margin-bottom:6px;">Set New Ticket Status</label>
                                    <select name="status" class="form-inp" style="height:42px; font-size:13.5px;">
                                        <option value="active" {{ $ticket->status == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="replied" {{ $ticket->status == 'replied' ? 'selected' : '' }}>Replied</option>
                                        <option value="review" {{ $ticket->status == 'review' ? 'selected' : '' }}>Under Review</option>
                                        <option value="pending" {{ $ticket->status == 'pending' ? 'selected' : '' }}>Pending Client Response</option>
                                        <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn-primary-solid" style="height:42px; width:100%; justify-content:center; font-size:14px; font-weight:600;">
                                    <i class="bi bi-send-fill"></i> Submit Reply
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- CONVERSATION TIMELINE -->
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-chat-left-dots-fill"></i> Interaction & Reply History</div>
                        <span style="font-size:12px; color:var(--t3);">{{ count($ticket->replies ?? []) }} recorded replies</span>
                    </div>
                    <div class="card-body" style="padding:18px; display:flex; flex-direction:column; gap:18px;">
                        @forelse($ticket->replies as $rep)
                        <div class="reply-bubble-item">
                            <div class="agent-avatar">
                                <i class="bi bi-shield-fill"></i>
                            </div>
                            <div class="reply-bubble-content">
                                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:6px; flex-wrap:wrap; gap:8px;">
                                    <span style="font-size:13.5px; font-weight:700; color:var(--t1);">Support Agent</span>
                                    <span style="font-size:11.5px; color:var(--t3);">{{ $rep->created_at->format('d M Y, h:i A') }}</span>
                                </div>
                                <div class="reply-bubble-box">
                                    {{ $rep->message_reply }}
                                </div>
                                <div style="margin-top:6px; display:flex; align-items:center; gap:6px;">
                                    <span style="font-size:10.5px; font-weight:800; color:var(--accent); text-transform:uppercase; letter-spacing:0.5px;">
                                        Status set to: {{ $rep->status }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:36px 20px; color:var(--t3); font-size:13.5px;">
                            <i class="bi bi-chat-square-text" style="font-size:24px; color:var(--t4); display:block; margin-bottom:8px;"></i>
                            No interaction history recorded yet. Use the form above to post a response.
                        </div>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" style="width: min(440px, calc(100vw - 32px));" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
                <span style="color:#ef4444; font-weight:700;"><i class="bi bi-exclamation-triangle-fill"></i> Delete Ticket</span>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:28px 20px;">
                <div style="width:60px;height:60px;background:rgba(239, 68, 68, 0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:13.5px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this support ticket?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top: 1px solid var(--b3); justify-content:flex-end; gap:10px;">
                <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13.5px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
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
    function openModal(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    }
</script>

@endsection
