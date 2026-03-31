@extends('admin.layout.app')

@section('title', 'Lead Profile: ' . $lead->company)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-lead-detail">

        <!-- ── PAGE HEADER ── -->
        <div class="page-header" style="margin-bottom:20px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route('sale.leads.index') }}"
                        style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);transition:var(--transition);text-decoration:none;"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Leads
                    </a>
                    <span style="color:var(--t4);font-size:12px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">{{ $lead->company }}</span>
                </div>
                <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                    <div>
                        <h1 class="page-title">{{ $lead->company }}</h1>
                        <p class="page-desc">Comprehensive profile and lead intelligence</p>
                    </div>
                    <!-- <div style="display:flex; gap:10px; margin-bottom:5px;">
                        <a href="{{ route('sale.leads.edit', ['id' => $lead->id]) }}" class="btn-primary-ghost sm">
                            <i class="bi bi-pencil-square"></i> Edit Lead
                        </a>
                    </div> -->
                </div>

                <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                    <!-- <div style="display:flex; gap:10px; margin-bottom:5px;">
                        <a href="{{ route('sale.leads.edit', ['id' => $lead->id]) }}" class="btn-primary-solid">
                            <i class="bi bi-pencil-square"></i> Edit Lead
                        </a>
                    </div> -->
                    <div style="display:flex; gap:10px; margin-bottom:5px;">
                        <a href="{{ route('sale.orders.create', ['lead_id' => $lead->id]) }}" class="btn-primary-solid">
                            <i class="bi bi-box-arrow-in-right"></i> Convert To Order
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── TOP QUICK STATS ── -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
            @php 
                $pColor = $lead->priority == 'Hot 🔥' ? '#ef4444' : ($lead->priority == 'Warm' ? '#f59e0b' : '#06b6d4');
                $pBg = $lead->priority == 'Hot 🔥' ? 'rgba(239,68,68,.12)' : ($lead->priority == 'Warm' ? 'rgba(245,158,11,.12)' : 'rgba(6,182,212,.12)');
            @endphp
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:{{ $pBg }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-fire" style="font-size:18px;color:{{ $pColor }};"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Priority</div>
                    <div style="font-size:16px;font-weight:800;color:{{ $pColor }};margin-top:2px;">{{ $lead->priority }}</div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(16,185,129,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-check2-circle" style="font-size:18px;color:#10b981;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Status</div>
                    <div style="font-size:16px;font-weight:800;color:#10b981;margin-top:2px;">{{ $lead->status->name ?? 'Prospect' }}</div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-box-arrow-in-right" style="font-size:18px;color:#6366f1;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Source</div>
                    <div style="font-size:16px;font-weight:800;color:var(--t1);margin-top:2px;">{{ $lead->source->name ?? 'Direct' }}</div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(236,72,153,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-check" style="font-size:18px;color:#ec4899;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Acquired</div>
                    <div style="font-size:16px;font-weight:800;color:#ec4899;margin-top:2px;">{{ $lead->created_at->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <!-- ── MAIN CONTENT GRID ── -->
        <div class="dash-grid">
            
            <!-- LEFT COLUMN: Profile & History (span-4) -->
            <div class="span-4" style="display:flex; flex-direction:column; gap:16px;">
                
                {{-- Identity & Contact Card --}}
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px;">
                        <div class="card-title">Identity & Contact</div>
                    </div>
                    <div class="card-body" style="padding:0 18px 24px;">
                        @php 
                            $emails = $lead->emails ?? [];
                            $phones = $lead->phones ?? [];
                            $initials = strtoupper(substr($lead->company, 0, 1) . substr($lead->contact_person, 0, 1));
                        @endphp
                        <div style="display:flex;flex-direction:column;align-items:center;padding:24px 0 20px;border-bottom:1px solid var(--b1);text-align:center;">
                            <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;margin-bottom:14px;box-shadow:0 8px 30px rgba(99,102,241,.3);">{{ $initials }}</div>
                            <div style="font-size:19px;font-weight:800;color:var(--t1);letter-spacing:-.4px;">{{ $lead->company }}</div>
                            <div style="font-size:13px;color:var(--t3);margin-top:4px;">{{ $emails[0] ?? 'No primary email' }}</div>
                            <div style="margin-top:12px;display:flex;gap:6px; flex-wrap:wrap; justify-content:center;">
                                <span class="src-tag {{ strtolower($lead->source->name ?? 'none') }}">{{ $lead->source->name ?? 'N/A' }}</span>
                                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;background:rgba(99,102,241,.1);color:var(--accent);">{{ $lead->service->name ?? 'N/A' }}</span>
                            </div>
                        </div>
                        <div style="display:flex;flex-direction:column;gap:4px;margin-top:16px;">
                            <div class="detail-row"><div class="detail-icon"><i class="bi bi-person-fill"></i></div><div><div class="detail-lbl">Contact Person</div><div class="detail-val">{{ $lead->contact_person }}</div></div></div>
                            <div class="detail-row"><div class="detail-icon"><i class="bi bi-building"></i></div><div><div class="detail-lbl">Business Type</div><div class="detail-val">{{ $lead->business_type ?? 'N/A' }}</div></div></div>
                            <div class="detail-row"><div class="detail-icon"><i class="bi bi-megaphone-fill"></i></div><div><div class="detail-lbl">Campaign</div><div class="detail-val">{{ $lead->campaign->name ?? 'Organic' }}</div></div></div>
                            <div class="detail-row"><div class="detail-icon"><i class="bi bi-geo-alt-fill"></i></div><div><div class="detail-lbl">Address</div><div class="detail-val">{{ $lead->address ?? 'No address set' }}</div></div></div>
                        </div>
                    </div>
                </div>

                {{-- Lead Notes History Card --}}
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px; border-bottom:1px solid var(--b1);">
                        <div class="card-title">Notes History</div>
                    </div>
                    <div class="card-body" style="padding:18px;">
                        <form action="{{ route('sale.lead-notes.store', $lead->id) }}" method="POST" style="margin-bottom:20px;">
                            @csrf
                            <div style="position:relative;">
                                <textarea name="notes" class="form-inp" rows="3" placeholder="Add internal note..." style="padding-right:45px; border-radius:12px; font-size:13px; min-height:80px;"></textarea>
                                <button type="submit" style="position:absolute; bottom:10px; right:12px; width:32px; height:32px; border-radius:50%; background:var(--accent); border:none; color:#fff; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all 0.2s; box-shadow:0 4px 12px rgba(99,102,241,0.3);">
                                    <i class="bi bi-send-fill" style="font-size:14px;"></i>
                                </button>
                            </div>
                        </form>

                        <div class="notes-timeline">
                            @forelse($lead->notes_history as $note)
                                <div class="note-item">
                                    <div class="note-header">
                                        <div class="note-author">
                                            <div class="mini-ava">{{ strtoupper(substr($note->createdBy->name ?? 'S', 0, 1)) }}</div>
                                            <div class="author-info">
                                                <span class="name">{{ $note->createdBy->name ?? 'System' }}</span>
                                                <span class="role">{{ $note->created_by_type == \App\Models\Admin::class ? 'Admin' : 'Sale' }}</span>
                                            </div>
                                        </div>
                                        <div class="note-actions">
                                            <button type="button" class="not-btn" onclick="openEditNoteModal({{ $note->id }}, '{{ addslashes($note->notes) }}')"><i class="bi bi-pencil"></i></button>
                                            <form action="{{ route('sale.lead-notes.destroy', $note->id) }}" method="POST" style="display:inline;">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="not-btn danger" onclick="return confirm('Delete note?')"><i class="bi bi-trash"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="note-content">{{ $note->notes }}</div>
                                    <div class="note-footer">
                                        {{ $note->created_at->diffForHumans() }}
                                        @if($note->updated_at > $note->created_at)
                                            <span class="ed">• Ed</span>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; padding:10px; color:var(--t4); font-size:12px;">No notes recorded.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div> <!-- END LEFT -->

            <!-- RIGHT COLUMN: Actions & Updates (span-8) -->
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                
                @if(session('success'))
                    <div class="alert alert-success" style="padding:14px 18px;background:rgba(16,185,129,.1);color:#10b981;border-radius:var(--r);border:1px solid rgba(16,185,129,.2);display:flex;align-items:center;gap:10px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span style="font-size:14px;font-weight:600;">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Row 1: Quick Actions & Primary Communication -->
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="dash-card" style="padding:20px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px;">Communication</div>
                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @if(count($emails) > 1)
                                <button class="btn-primary-ghost" style="width:100%; justify-content:center;" onclick="openModal('emailModal')">
                                    <i class="bi bi-envelope"></i> Choose Email ({{ count($emails) }})
                                </button>
                            @else
                                <a href="mailto:{{ $emails[0] ?? '' }}" class="btn-primary-ghost" style="width:100%; justify-content:center;">
                                    <i class="bi bi-envelope"></i> Send Primary Email
                                </a>
                            @endif

                            @if(count($phones) > 1)
                                <button class="btn-primary-ghost" style="width:100%; justify-content:center;" onclick="openModal('phoneModal')">
                                    <i class="bi bi-telephone"></i> Choose Phone ({{ count($phones) }})
                                </button>
                            @else
                                <a href="tel:{{ $phones[0]['number'] ?? '' }}" class="btn-primary-ghost" style="width:100%; justify-content:center;">
                                    <i class="bi bi-telephone"></i> Call Primary Number
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="dash-card" style="padding:20px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px;">Assigned Team</div>
                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @forelse($lead->assignments as $assign)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--t2);">{{ strtoupper(substr($assign->sale->name, 0, 1)) }}</div>
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-size:13px;font-weight:700;color:var(--t1);">{{ $assign->sale->name }}</span>
                                        <span style="font-size:11px;color:var(--t3);">{{ $assign->sale->email }}</span>
                                    </div>
                                </div>
                            @empty
                                <div style="font-size:13px; font-style:italic; color:var(--t4)">No sales staff assigned yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- QUICK UPDATE CARD -->
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px;">
                        <div>
                            <div class="card-title">Quick Intelligence Update</div>
                            <div class="card-sub">Fast update status, priority and brief notes</div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:14px 18px 20px;">
                        <form action="{{ route('sale.leads.updateStatus', $lead->id) }}" method="POST" id="quickUpdateForm">
                            @csrf
                            @method('PATCH')
                            
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Change Status</label>
                                    <select name="status_id" class="form-inp">
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ $lead->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Set Priority</label>
                                    <select name="priority" class="form-inp">
                                        <option value="Hot 🔥" {{ $lead->priority == 'Hot 🔥' ? 'selected' : '' }}>Hot 🔥</option>
                                        <option value="Warm" {{ $lead->priority == 'Warm' ? 'selected' : '' }}>Warm</option>
                                        <option value="Cold" {{ $lead->priority == 'Cold' ? 'selected' : '' }}>Cold</option>
                                    </select>
                                </div>
                                <!-- <div class="form-row" style="grid-column:1/-1; margin-bottom:0;">
                                    <label class="form-lbl">Lead Notes / Internal Briefing</label>
                                    <textarea name="notes" class="form-inp" rows="5" placeholder="Add private notes about this lead here...">{{ $lead->notes }}</textarea>
                                </div> -->
                            </div>
                            <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                                <button type="submit" class="btn-primary-solid">
                                    <i class="bi bi-save"></i> Synchronize Updates
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ALL CONTACT POINTS -->
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;">Email Directory</div>
                        @foreach($emails as $email)
                            <div style="font-size:13px; color:var(--t2); padding:8px 10px; background:var(--bg3); border-radius:8px; border:1px solid var(--b1); margin-bottom:6px; display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-envelope-at" style="color:var(--t3)"></i> {{ $email }}
                            </div>
                        @endforeach
                    </div>
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;">Phone Directory</div>
                        @foreach($phones as $phone)
                            <div style="font-size:13px; color:var(--t2); padding:8px 10px; background:var(--bg3); border-radius:8px; border:1px solid var(--b1); margin-bottom:6px; display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-telephone-outbound" style="color:var(--t3)"></i> {{ $phone['number'] }}
                                @if($phone['code_idx']) <small style="color:var(--t4)">Code: {{ $phone['code_idx'] }}</small> @endif
                            </div>
                        @endforeach
                    </div>
                </div>

            </div><!-- end span-8 -->
        </div><!-- end dash-grid -->
    </div>
</main>

<!-- ── MODALS ── -->
<div class="modal-backdrop" id="emailModal" onclick="closeModal('emailModal')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:440px;">
        <div class="modal-hd">
            <span>Select Recipient Email</span>
            <button class="modal-close" onclick="closeModal('emailModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($emails as $email)
                    <a href="mailto:{{ $email }}" class="modal-btn-list" style="text-decoration:none; display:flex; align-items:center; gap:12px; padding:14px; background:var(--bg3); border:1px solid var(--b1); border-radius:12px; color:var(--t1); font-weight:700; transition:all 0.2s;">
                        <i class="bi bi-envelope-check" style="color:var(--accent)"></i> {{ $email }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="modal-backdrop" id="phoneModal" onclick="closeModal('phoneModal')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:440px;">
        <div class="modal-hd">
            <span>Select Phone Line</span>
            <button class="modal-close" onclick="closeModal('phoneModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($phones as $phone)
                    <a href="tel:{{ $phone['number'] }}" class="modal-btn-list" style="text-decoration:none; display:flex; align-items:center; gap:12px; padding:14px; background:var(--bg3); border:1px solid var(--b1); border-radius:12px; color:var(--t1); font-weight:700; transition:all 0.2s;">
                        <i class="bi bi-telephone-plus" style="color:#10b981;"></i> {{ $phone['number'] }}
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
    /* Lead Info Details styling */
    .detail-row { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icon { width:32px; height:32px; border-radius:10px; background:var(--bg4); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--t3); font-size:14px; }
    .detail-lbl { font-size:10.5px; color:var(--t3); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
    .detail-val { font-size:13.5px; font-weight:700; color:var(--t1); }

    /* Modal List Items Hover */
    .modal-btn-list:hover { border-color:var(--accent) !important; background:var(--bg4) !important; transform:translateX(5px); }

    .src-tag { font-size:10.5px; font-weight:700; padding:3px 10px; border-radius:6px; }
    /* Source tag colors matching index.blade.php patterns if possible */
    .src-tag.instagram, .src-tag.instagraam { background:rgba(236,72,153,.12); color:#ec4899; }
    .src-tag.facebook { background:rgba(59,130,246,.12); color:#3b82f6; }
    .src-tag.website { background:rgba(16,185,129,.12); color:#10b981; }
    .src-tag.direct { background:rgba(99,102,241,.12); color:#6366f1; }

    /* Notes Timeline Styling */
    .notes-timeline { display: flex; flex-direction: column; gap: 12px; }
    .note-item { padding: 12px; background: var(--bg3); border: 1px solid var(--b1); border-radius: 12px; }
    .note-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 6px; }
    .note-author { display: flex; align-items: center; gap: 8px; }
    .mini-ava { width: 24px; height: 24px; border-radius: 6px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-size: 10px; font-weight: 800; color: var(--t2); }
    .author-info { display: flex; flex-direction: column; }
    .author-info .name { font-size: 12px; font-weight: 700; color: var(--t1); line-height: 1; }
    .author-info .role { font-size: 9px; font-weight: 600; color: var(--t4); text-transform: uppercase; margin-top: 1px; }
    .note-actions { display: flex; gap: 2px; }
    .not-btn { width: 22px; height: 22px; border-radius: 4px; border: none; background: transparent; color: var(--t4); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 11px; }
    .not-btn:hover { background: var(--bg4); color: var(--accent); }
    .not-btn.danger:hover { color: #ef4444; }
    .note-content { font-size: 12.5px; line-height: 1.5; color: var(--t2); white-space: pre-wrap; margin-bottom: 6px; }
    .note-footer { font-size: 10px; color: var(--t4); font-weight: 600; display: flex; align-items: center; gap: 4px; }
    .note-footer .ed { color: var(--accent); }
</style>

<script>
    function openModal(id) { document.getElementById(id).style.display = 'flex'; }
    function closeModal(id) { document.getElementById(id).style.display = 'none'; }

    function openEditNoteModal(id, notes) {
        const modal = document.getElementById('editNoteModal');
        const form = document.getElementById('editNoteForm');
        const textarea = form.querySelector('textarea');
        
        textarea.value = notes;
        form.action = `/sale/lead-notes/${id}`;
        modal.style.display = 'flex';
    }
</script>

{{-- Edit Note Modal --}}
<div class="modal-backdrop" id="editNoteModal" onclick="closeModal('editNoteModal')">
    <div class="modal-box" onclick="event.stopPropagation()">
        <div class="modal-hd">
            <span>Edit Note</span>
            <button class="modal-close" onclick="closeModal('editNoteModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <form id="editNoteForm" method="POST">
                @csrf
                @method('PUT')
                <div class="form-row">
                    <label class="form-lbl">Update your note</label>
                    <textarea name="notes" class="form-inp" rows="5" required></textarea>
                </div>
                <div style="display:flex; justify-content:flex-end; gap:10px; margin-top:20px;">
                    <button type="button" class="btn-ghost" onclick="closeModal('editNoteModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">Update Note</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
