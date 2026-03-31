@extends('developer.layout.app')

@section('title', 'Meeting Details')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-dev-show">
        
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route('developer.meetings.index') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;">
                        <i class="bi bi-arrow-left"></i> Back to Schedule
                    </a>
                </div>
                <h1 class="page-title">{{ $meeting->meeting_title }}</h1>
                <p class="page-desc">Project briefing and meeting notes.</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head" style="padding:18px 24px;">
                        <div class="card-title">Briefing Info</div>
                        <span class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-calendar3"></i> Date</span>
                                <span class="val">{{ $meeting->meeting_date->format('l, d M Y') }}</span>
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-clock"></i> Time</span>
                                <span class="val">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-link-45deg"></i> Meeting Link</span>
                                @if($meeting->meeting_link)
                                    <a href="{{ $meeting->meeting_link }}" target="_blank" class="val link">{{ Str::limit($meeting->meeting_link, 50) }}</a>
                                @else
                                    <span class="val text-muted">No link provided</span>
                                @endif
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-shield-check"></i> Creator</span>
                                <span class="val">{{ $meeting->createdBy->name ?? 'System' }}</span>
                            </div>
                        </div>

                        <div class="info-block full">
                            <span class="lbl">Meeting Objective</span>
                            <div class="val-box">{{ $meeting->meeting_description ?? 'No detailed notes provided.' }}</div>
                        </div>

                        @if($meeting->project)
                            <div style="margin-top:30px;">
                                <span class="lbl" style="margin-bottom:12px; display:block;">Related Project</span>
                                <div class="target-card project">
                                    <div class="t-icon"><i class="bi bi-kanban"></i></div>
                                    <div class="t-info">
                                        <div class="t-name">{{ $meeting->project->name }}</div>
                                        <div class="t-sub">Status: {{ $meeting->project->status->name ?? 'Active' }}</div>
                                    </div>
                                    <a href="{{ route('developer.projects.show', $meeting->project_id) }}" class="t-btn">Go to Project</a>
                                </div>
                            </div>
                        @elseif($meeting->order)
                            <div style="margin-top:30px;">
                                <span class="lbl" style="margin-bottom:12px; display:block;">Related Order</span>
                                <div class="target-card order">
                                    <div class="t-icon"><i class="bi bi-bag-check"></i></div>
                                    <div class="t-info">
                                        <div class="t-name">Order #{{ $meeting->order->id }}</div>
                                        <div class="t-sub">{{ $meeting->order->status->name ?? 'Confirmed' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="span-4">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Team Participation</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <span class="sub-lbl">Engineering Team (You)</span>
                        <div class="p-list">
                            @foreach($meeting->developers() as $dev)
                                <div class="p-item dev {{ $dev->id == auth()->guard('developer')->id() ? 'active' : '' }}">
                                    <div class="p-ava">{{ strtoupper(substr($dev->name, 0, 1)) }}</div>
                                    <div class="p-name">{{ $dev->name }} @if($dev->id == auth()->guard('developer')->id()) <small>(You)</small> @endif</div>
                                </div>
                            @endforeach
                        </div>

                        <span class="sub-lbl" style="margin-top:20px;">Sales / Account Management</span>
                        <div class="p-list">
                            @foreach($meeting->sales() as $sale)
                                <div class="p-item">
                                    <div class="p-ava">{{ strtoupper(substr($sale->name, 0, 1)) }}</div>
                                    <div class="p-name">{{ $sale->name }}</div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* Consistency with previous show views */
    .m-status-pill { font-size: 10px; font-weight: 700; padding: 4px 14px; border-radius: 8px; text-transform: capitalize; }
    .m-status-pill.pending { background: #fef3c7; color: #92400e; }
    .m-status-pill.completed { background: #d1fae5; color: #065f46; }
    .info-block { display: flex; flex-direction: column; gap: 4px; }
    .info-block.full { grid-column: 1 / -1; }
    .info-block .lbl { font-size: 11px; font-weight: 700; color: var(--t4); text-transform: uppercase; display: flex; align-items: center; gap: 6px; }
    .info-block .val { font-size: 14px; font-weight: 800; color: var(--t1); }
    .info-block .val.link { color: var(--accent); }
    .val-box { background: var(--bg3); border: 1px solid var(--b1); border-radius: 12px; padding: 15px; font-size: 14px; line-height: 1.6; color: var(--t2); }
    .target-card { display: flex; align-items: center; gap: 15px; padding: 15px; border-radius: 15px; border: 1px solid var(--b1); background: var(--bg3); border-left: 4px solid var(--accent); }
    .t-icon { width: 40px; height: 40px; border-radius: 10px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-size: 16px; color: var(--t3); }
    .t-info { flex: 1; }
    .t-name { font-size: 14px; font-weight: 800; color: var(--t1); }
    .t-sub { font-size: 11px; color: var(--t4); }
    .t-btn { font-size: 11px; font-weight: 700; color: var(--accent); text-decoration: none; padding: 7px 12px; background: var(--bg4); border-radius: 8px; }
    .sub-lbl { font-size: 10px; font-weight: 700; color: var(--t4); text-transform: uppercase; display: block; margin-bottom: 10px; }
    .p-list { display: flex; flex-direction: column; gap: 6px; }
    .p-item { display: flex; align-items: center; gap: 10px; padding: 8px 10px; background: var(--bg4); border-radius: 8px; border: 1px solid var(--b1); }
    .p-item.dev.active { border-left: 3px solid var(--accent); }
    .p-name { font-size: 13px; font-weight: 700; color: var(--t1); }
    .p-ava { width: 24px; height: 24px; border-radius: 6px; background: var(--bg3); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: var(--t2); }
</style>

@endsection
