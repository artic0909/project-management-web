@extends('admin.layout.app')

@section('title', ($model->company_name ?? $model->company) . ' — ' . $typeLabel . ' Followup History')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-followup">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ $backRoute }}"
                        style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);transition:var(--transition);text-decoration:none;"
                        onmouseover="this.style.color='var(--accent)'"
                        onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All {{ $typeLabel }}s
                    </a>
                    <span style="color:var(--t4);font-size:12px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">{{ $model->company_name ?? $model->company }}</span>
                </div>
                <h1 class="page-title">{{ $typeLabel }} Followup Command Center</h1>
                <p class="page-desc">Communication intelligence and lifecycle tracking</p>
            </div>
        </div>

        <!-- TOP STATS ROW -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:20px;">
            @php 
                $priority = $isOrder ? 'Warm' : $model->priority; // Orders might not have priority yet
                $pColor = $priority == 'Hot 🔥' ? '#ef4444' : ($priority == 'Warm' ? '#f59e0b' : '#06b6d4');
            @endphp
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(239,68,68,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-fire" style="font-size:18px;color:{{ $pColor }};"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Priority</div>
                    <div style="font-size:16px;font-weight:800;color:{{ $pColor }};margin-top:2px;">{{ $priority }}</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(16,185,129,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-check2-circle" style="font-size:18px;color:#10b981;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Current Status</div>
                    <div style="font-size:16px;font-weight:800;color:#10b981;margin-top:2px;">{{ $model->status->name ?? 'N/A' }}</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(99,102,241,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-arrow-counterclockwise" style="font-size:18px;color:#6366f1;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">History Count</div>
                    <div style="font-size:16px;font-weight:800;color:var(--accent);margin-top:2px;">{{ $totalFollowups }}</div>
                </div>
            </div>
            <div class="dash-card" style="padding:16px;display:flex;align-items:center;gap:14px;">
                <div style="width:42px;height:42px;border-radius:10px;background:rgba(245,158,11,.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                    <i class="bi bi-calendar-event" style="font-size:18px;color:#f59e0b;"></i>
                </div>
                <div>
                    <div style="font-size:11px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:.05em;">Recent Touch</div>
                    <div style="font-size:14px;font-weight:800;color:#f59e0b;margin-top:2px;">{{ $lastFollowup ? $lastFollowup->followup_date->format('d M Y') : 'No history' }}</div>
                </div>
            </div>
        </div>

        <!-- MAIN GRID -->
        <div class="dash-grid">

            <!-- LEFT: Profile & Identity (Lead/Order Info Card) -->
            <div class="dash-card span-4" style="height:fit-content;">
                <div class="card-head" style="padding:16px 18px;">
                    <div class="card-title">Identity & Profile</div>
                </div>
                <div class="card-body" style="padding:0 18px 24px;">

                    @php 
                        $initials = strtoupper(substr($model->company_name ?? $model->company, 0, 1) . substr($model->client_name ?? $model->contact_person, 0, 1));
                    @endphp

                    <!-- Avatar + Name -->
                    <div style="display:flex;flex-direction:column;align-items:center;padding:24px 0 20px;border-bottom:1px solid var(--b1);text-align:center;">
                        <div style="width:68px;height:68px;border-radius:20px;background:linear-gradient(135deg,#8b5cf6,#ec4899);display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:800;color:#fff;margin-bottom:12px;box-shadow:0 8px 24px rgba(139,92,246,.3);">{{ $initials }}</div>
                        <div style="font-size:18px;font-weight:800;color:var(--t1);letter-spacing:-.4px;">{{ $model->company_name ?? $model->company }}</div>
                        <div style="font-size:13px;color:var(--t3);margin-top:4px;">{{ $model->emails[0] ?? 'No primary email' }}</div>
                        <div style="margin-top:12px;display:flex;gap:6px; flex-wrap:wrap; justify-content:center;">
                            @if(!$isOrder)
                                <span class="src-tag {{ strtolower($model->source->name ?? 'none') }}">{{ $model->source->name ?? 'Direct' }}</span>
                            @else
                                <span class="src-tag website" style="background:rgba(99,102,241,.1);color:#6366f1;">Order</span>
                            @endif
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;background:rgba(16,185,129,.1);color:#10b981;">{{ $model->service->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <!-- Details List -->
                    <div style="display:flex;flex-direction:column;gap:4px;margin-top:16px;">
                        
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $model->client_name ?? $model->contact_person }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-telephone-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Primary Phone</div>
                                <div class="detail-val">{{ ($model->phones[0]['number'] ?? 'N/A') }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-building"></i></div>
                            <div>
                                <div class="detail-lbl">Business Type</div>
                                <div class="detail-val">{{ $model->business_type ?? ($isOrder ? 'Converted Lead' : 'N/A') }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-people-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Assigned To</div>
                                <div class="detail-val">
                                    @php $names = $model->assignments->pluck('sale.name')->toArray(); @endphp
                                    {{ !empty($names) ? implode(', ', $names) : 'Unassigned' }}
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-calendar-check"></i></div>
                            <div>
                                <div class="detail-lbl">Created At</div>
                                <div class="detail-val">{{ $model->created_at->format('d M Y') }}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- RIGHT: Followup History + Add Form -->
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                @if(session('success'))
                    <div class="alert alert-success" style="padding:14px 18px;background:rgba(16,185,129,.1);color:#10b981;border-radius:var(--r);border:1px solid rgba(16,185,129,.2);display:flex;align-items:center;gap:10px;">
                        <i class="bi bi-check-circle-fill"></i>
                        <span style="font-size:14px;font-weight:600;">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Add Followup Card -->
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px;">
                        <div>
                            <div class="card-title">Log New Interaction</div>
                            <div class="card-sub">Record communication and schedule future contact</div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:14px 18px 20px;">
                        <form action="{{ $isOrder ? route('sale.orders.followup.store', $model->id) : route('sale.leads.followup.store', $model->id) }}" method="POST">
                            @csrf
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Transaction Date *</label>
                                    <input type="datetime-local" name="followup_date" class="form-inp" value="{{ date('Y-m-d\TH:i') }}" max="{{ date('Y-m-d\TH:i') }}" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Interaction Vector</label>
                                    <select name="followup_type" class="form-inp">
                                        <option value="Calling">Calling</option>
                                        <option value="Message">Message</option>
                                        <option value="Both">Both (Call & Message)</option>
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1;">
                                    <label class="form-lbl">Voice Communication Intelligence (Calling Note)</label>
                                    <textarea name="calling_note" class="form-inp" rows="2" placeholder="What was discussed during the call?"></textarea>
                                </div>
                                <div class="form-row" style="grid-column:1/-1;margin-bottom:0;">
                                    <label class="form-lbl">Text Communication Records (Message Note)</label>
                                    <textarea name="message_note" class="form-inp" rows="2" placeholder="Summary of messages, emails, or drafts sent…"></textarea>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;margin-top:16px;">
                                <button type="submit" class="btn-primary-solid">
                                    <i class="bi bi-plus-lg"></i> Record Followup
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Followup History Card -->
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 18px 12px;">
                        <div>
                            <div class="card-title">Engagement Timeline</div>
                            <div class="card-sub">Complete log of all touches for this {{ strtolower($typeLabel) }}</div>
                        </div>
                    </div>
                    <div class="card-body" style="padding:10px 18px 24px;display:flex;flex-direction:column;gap:12px;">

                        <!-- Timeline Wrapper -->
                        <div style="position:relative;padding-left:26px;">
                            <div style="position:absolute;left:10px;top:4px;bottom:0;width:2px;background:var(--b2);border-radius:2px;"></div>

                            @forelse($model->followups as $followup)
                                @php 
                                    $typeColor = $followup->followup_type == 'Calling' ? '#10b981' : ($followup->followup_type == 'Message' ? '#f59e0b' : '#6366f1');
                                    $typeBg = $followup->followup_type == 'Calling' ? 'rgba(16,185,129,.1)' : ($followup->followup_type == 'Message' ? 'rgba(245,158,11,.1)' : 'rgba(99,102,241,.1)');
                                @endphp
                                <div style="position:relative;margin-bottom:20px;">
                                    <!-- Marker Dot -->
                                    <div style="position:absolute;left:-23px;top:14px;width:14px;height:14px;border-radius:50%;background:var(--bg1);border:3px solid {{ $typeColor }};"></div>
                                    
                                    <div class="history-item-box" style="background:var(--bg3);border:1px solid var(--b1);border-radius:14px;overflow:hidden;">
                                        <div style="padding:10px 14px;background:var(--bg2);border-bottom:1px solid var(--b1);display:flex;align-items:center;justify-content:space-between;">
                                            <div style="display:flex;align-items:center;gap:8px;">
                                                <div style="font-size:12px;font-weight:700;color:{{ $typeColor }};background:{{ $typeBg }};padding:3px 10px;border-radius:6px;border:1px solid {{ str_replace('0.1','0.2',$typeBg) }};">
                                                    {{ $followup->followup_date->format('d M Y, h:i A') }}
                                                </div>
                                                <span style="font-size:11px;color:var(--t3);font-weight:600;">
                                                    @if($followup->followup_type == 'Calling') <i class="bi bi-telephone-outbound"></i> Call 
                                                    @elseif($followup->followup_type == 'Message') <i class="bi bi-chat-dots"></i> Message
                                                    @else <i class="bi bi-intersect"></i> Unified
                                                    @endif
                                                </span>
                                            </div>
                                            <div style="font-size:10px;color:var(--t4);">
                                                Logged by: 
                                                @if($followup->creator)
                                                    {{ $followup->creator->name }} - {{ $followup->creator->email }}
                                                @else
                                                    System
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">
                                            @if($followup->calling_note)
                                                <div style="border-left:3px solid #10b981;padding-left:10px;">
                                                    <p style="font-size:10.5px;font-weight:800;color:var(--t4);margin:0 0 2px;text-transform:uppercase;">Call Intelligence</p>
                                                    <p style="font-size:13.5px;color:var(--t2);margin:0;line-height:1.6;font-weight:500;">{{ $followup->calling_note }}</p>
                                                </div>
                                            @endif
                                            @if($followup->message_note)
                                                <div style="border-left:3px solid #f59e0b;padding-left:10px;">
                                                    <p style="font-size:10.5px;font-weight:800;color:var(--t4);margin:0 0 2px;text-transform:uppercase;">Messengers Records</p>
                                                    <p style="font-size:13.5px;color:var(--t2);margin:0;line-height:1.6;font-weight:500;">{{ $followup->message_note }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div style="padding:20px;text-align:center;color:var(--t4);font-style:italic;">No interactions recorded yet. Use the form above to log the first followup.</div>
                            @endforelse

                            <!-- Lead/Order Created Milestone -->
                            <div style="position:relative;margin-top:10px;">
                                <div style="position:absolute;left:-23px;top:4px;width:14px;height:14px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;">
                                    <i class="bi bi-star-fill" style="font-size:7px;color:#fff;"></i>
                                </div>
                                <div style="padding-left:2px;">
                                    <span style="font-size:12px;color:var(--t3);font-weight:600;">{{ $typeLabel }} journey started on <strong style="color:var(--t1);">{{ $model->created_at->format('d M Y') }}</strong></span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div><!-- end span-8 -->
        </div><!-- end dash-grid -->
    </div>
</main>

<style>
    .detail-row { display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icon { width:32px; height:32px; border-radius:10px; background:var(--bg4); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--t3); font-size:14px; }
    .detail-lbl { font-size:10px; color:var(--t3); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; }
    .detail-val { font-size:13px; font-weight:700; color:var(--t1); }

    .src-tag { font-size:10px; font-weight:700; padding:3px 9px; border-radius:6px; }
    .src-tag.instagram { background:rgba(236,72,153,.1); color:#ec4899; }
    .src-tag.facebook { background:rgba(59,130,246,.1); color:#3b82f6; }
    .src-tag.website { background:rgba(16,185,129,.1); color:#10b981; }
    .src-tag.direct { background:rgba(99,102,241,.1); color:#6366f1; }

    .history-item-box { transition: var(--transition); }
    .history-item-box:hover { transform: translateX(5px); border-color: var(--accent) !important; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const typeSelect = document.querySelector('select[name="followup_type"]');
    const callingArea = document.querySelector('textarea[name="calling_note"]').closest('.form-row');
    const messageArea = document.querySelector('textarea[name="message_note"]').closest('.form-row');

    function toggleAreas() {
        const val = typeSelect.value;
        if (val === 'Calling') {
            callingArea.style.display = 'block';
            messageArea.style.display = 'none';
        } else if (val === 'Message') {
            callingArea.style.display = 'none';
            messageArea.style.display = 'block';
        } else if (val === 'Both') {
            callingArea.style.display = 'block';
            messageArea.style.display = 'block';
        }
    }

    if (typeSelect) {
        typeSelect.addEventListener('change', toggleAreas);
        toggleAreas();
    }
});
</script>

@endsection
