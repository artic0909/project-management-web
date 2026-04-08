@extends('admin.layout.app')

@section('title', 'Meeting Details: ' . $meeting->meeting_title)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-show">
        
        <!-- ── PAGE HEADER ── -->
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.meetings.index') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;">
                        <i class="bi bi-arrow-left"></i> Back to Schedule
                    </a>
                </div>
                <h1 class="page-title">{{ $meeting->meeting_title }}</h1>
                <p class="page-desc">Detailed meeting briefing and participants.</p>
            </div>
            <div>
                <a href="{{ route($routePrefix . '.meetings.edit', $meeting->id) }}" class="btn-primary-ghost">
                    <i class="bi bi-pencil"></i> Edit Details
                </a>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head" style="padding:18px 24px;">
                        <div class="card-title">General Briefing</div>
                        <span class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <div style="display:grid; grid-template-columns: 1fr 1fr; gap:30px; margin-bottom:30px;">
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-calendar3"></i> Scheduled Date</span>
                                <span class="val">{{ $meeting->meeting_date->format('l, d M Y') }}</span>
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-clock"></i> Scheduled Time</span>
                                <span class="val">{{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-tag"></i> Meeting Type</span>
                                <span class="val" style="text-transform:capitalize;">{{ $meeting->meeting_type }} Discussion</span>
                            </div>
                            <div class="info-block">
                                <span class="lbl"><i class="bi bi-link-45deg"></i> Meeting Link</span>
                                @if($meeting->meeting_link)
                                    <a href="{{ $meeting->meeting_link }}" target="_blank" class="val link">{{ $meeting->meeting_link }}</a>
                                @else
                                    <span class="val">No link provided</span>
                                @endif
                            </div>
                        </div>

                        <div class="info-block full">
                            <span class="lbl">Meeting Objective / Description</span>
                            <div class="val-box">{{ $meeting->meeting_description ?? 'No description provided.' }}</div>
                        </div>

                        <!-- ── TARGET ENTITY CARD ── -->
                        <div style="margin-top:30px;">
                            <span class="lbl" style="margin-bottom:12px; display:block;">Target Entity</span>
                            @if($meeting->meeting_type == 'lead' && $meeting->lead)
                                <div class="target-card lead">
                                    <div class="t-icon"><i class="bi bi-person-badge"></i></div>
                                    <div class="t-info">
                                        <div class="t-name">{{ $meeting->lead->company }}</div>
                                        <div class="t-sub">Contact: {{ $meeting->lead->contact_person }} | {{ $meeting->lead->source->name ?? 'Direct' }}</div>
                                    </div>
                                    <a href="{{ route($routePrefix . '.leads.show', $meeting->lead_id) }}" class="t-btn">View Lead Profile</a>
                                </div>
                            @elseif($meeting->meeting_type == 'order' && $meeting->order)
                                <div class="target-card order">
                                    <div class="t-icon"><i class="bi bi-bag-check"></i></div>
                                    <div class="t-info">
                                        <div class="t-name">Order #{{ $meeting->order->id }}</div>
                                        <div class="t-sub">{{ $meeting->order->lead->company ?? $meeting->order->company_name ?? 'Unknown' }} | {{ $meeting->order->status->name ?? 'Pending' }}</div>
                                    </div>
                                    <a href="{{ route($routePrefix . '.orders.index') }}" class="t-btn">View Order</a>
                                </div>
                            @elseif($meeting->meeting_type == 'project' && $meeting->project)
                                <div class="target-card project">
                                    <div class="t-icon"><i class="bi bi-kanban"></i></div>
                                    <div class="t-info">
                                        <div class="t-name">{{ $meeting->project->name }}</div>
                                        <div class="t-sub">Timeline: {{ $meeting->project->start_date }} - {{ $meeting->project->deadline }}</div>
                                    </div>
                                    <a href="{{ route($routePrefix . '.projects.show', $meeting->project_id) }}" class="t-btn">View Project Details</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="span-4">
                {{-- Quick Status Update --}}
                <div class="dash-card" style="margin-bottom:16px; border-top: 4px solid var(--accent);">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Quick Status Update</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <form action="{{ route($routePrefix . '.meetings.updateStatus', $meeting->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="status-btn-group">
                                <button type="submit" name="status" value="completed" class="status-btn completed {{ $meeting->status == 'completed' ? 'active' : '' }}">
                                    <i class="bi bi-check-circle-fill"></i> Mark Completed
                                </button>
                                <button type="submit" name="status" value="canceled" class="status-btn canceled {{ $meeting->status == 'canceled' ? 'active' : '' }}">
                                    <i class="bi bi-x-circle-fill"></i> Mark Canceled
                                </button>
                                <!-- <button type="submit" name="status" value="rescheduled" class="status-btn rescheduled {{ $meeting->status == 'rescheduled' ? 'active' : '' }}">
                                    <i class="bi bi-calendar2-range-fill"></i> Rescheduled
                                </button> -->
                                <button type="submit" name="status" value="pending" class="status-btn pending {{ $meeting->status == 'pending' ? 'active' : '' }}">
                                    <i class="bi bi-hourglass-split"></i> Still Pending
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Participants Card --}}
                <div class="dash-card" style="margin-bottom:16px;">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Assignees (Internal)</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <span class="sub-lbl">Sales Personnel</span>
                        <div class="p-list">
                            @forelse($meeting->sales() as $sale)
                                <div class="p-item">
                                    <div class="p-ava">{{ strtoupper(substr($sale->name, 0, 1)) }}</div>
                                    <div class="p-name">{{ $sale->name }}</div>
                                </div>
                            @empty
                                <div class="p-none">No sales assigned</div>
                            @endforelse
                        </div>

                        <span class="sub-lbl" style="margin-top:20px;">Development Team</span>
                        <div class="p-list">
                            @forelse($meeting->developers() as $dev)
                                <div class="p-item dev">
                                    <div class="p-ava">{{ strtoupper(substr($dev->name, 0, 1)) }}</div>
                                    <div class="p-name">{{ $dev->name }}</div>
                                </div>
                            @empty
                                <div class="p-none">No developers assigned</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Authorship Card --}}
                <div class="dash-card">
                    <div class="card-body" style="padding:20px; display:flex; align-items:center; gap:15px;">
                        <div style="width:40px; height:40px; border-radius:12px; background:var(--bg4); display:flex; align-items:center; justify-content:center; color:var(--accent); border:1px solid var(--b1);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <div>
                            <div style="font-size:10px; color:var(--t4); font-weight:700; text-transform:uppercase;">Created By</div>
                            <div style="font-size:13px; color:var(--t1); font-weight:700;">{{ $meeting->createdBy->name ?? 'System Admin' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<style>
    .m-status-pill { font-size: 10px; font-weight: 700; padding: 4px 14px; border-radius: 8px; text-transform: capitalize; }
    .m-status-pill.pending { background: #fef3c7; color: #92400e; }
    .m-status-pill.completed { background: #d1fae5; color: #065f46; }
    .m-status-pill.canceled { background: #fee2e2; color: #991b1b; }

    .info-block { display: flex; flex-direction: column; gap: 5px; }
    .info-block.full { grid-column: 1 / -1; }
    .info-block .lbl { font-size: 11px; font-weight: 700; color: var(--t4); text-transform: uppercase; display: flex; align-items: center; gap: 6px; }
    .info-block .val { font-size: 15px; font-weight: 800; color: var(--t1); }
    .info-block .val.link { color: var(--accent); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .val-box { background: var(--bg3); border: 1px solid var(--b1); border-radius: 12px; padding: 15px; font-size: 14px; color: var(--t2); line-height: 1.6; white-space: pre-wrap; }

    .target-card { display: flex; align-items: center; gap: 15px; padding: 16px; border-radius: 16px; border: 1px solid var(--b1); background: var(--bg3); }
    .target-card.lead { border-left: 4px solid #6366f1; }
    .target-card.order { border-left: 4px solid #f59e0b; }
    .target-card.project { border-left: 4px solid #10b981; }
    .t-icon { width: 44px; height: 44px; border-radius: 12px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-size: 18px; color: var(--t3); }
    .t-info { flex: 1; }
    .t-name { font-size: 15px; font-weight: 800; color: var(--t1); }
    .t-sub { font-size: 12px; color: var(--t4); margin-top: 2px; }
    .t-btn { font-size: 12px; font-weight: 700; color: var(--accent); text-decoration: none; padding: 8px 14px; background: var(--bg4); border-radius: 8px; transition: 0.2s; }
    .t-btn:hover { background: var(--accent); color: #fff; }

    .sub-lbl { font-size: 10px; font-weight: 700; color: var(--t4); text-transform: uppercase; display: block; margin-bottom: 10px; }
    .p-list { display: flex; flex-direction: column; gap: 8px; }
    .p-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; background: var(--bg4); border-radius: 10px; border: 1px solid var(--b1); }
    .p-item.dev { border-left: 3px solid var(--accent); }
    .p-ava { width: 26px; height: 26px; border-radius: 6px; background: var(--bg3); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 800; color: var(--t2); }
    .p-name { font-size: 13px; font-weight: 700; color: var(--t1); }
    .p-none { font-size: 12px; font-style: italic; color: var(--t4); }

    .status-btn-group { display: flex; flex-direction: column; gap: 8px; }
    .status-btn { display: flex; align-items: center; gap: 10px; padding: 12px 16px; border-radius: 12px; border: 1px solid var(--b1); background: var(--bg3); color: var(--t2); font-size: 13px; font-weight: 700; cursor: pointer; transition: 0.2s; text-align: left; width: 100%; }
    .status-btn i { font-size: 16px; }
    .status-btn:hover { transform: translateY(-2px); border-color: var(--accent); }
    
    .status-btn.completed:hover, .status-btn.completed.active { background: #d1fae5; color: #065f46; border-color: #34d399; }
    .status-btn.canceled:hover, .status-btn.canceled.active { background: #fee2e2; color: #991b1b; border-color: #f87171; }
    .status-btn.rescheduled:hover, .status-btn.rescheduled.active { background: #fef3c7; color: #92400e; border-color: #fbbf24; }
    .status-btn.pending:hover, .status-btn.pending.active { background: #e0e7ff; color: #3730a3; border-color: #818cf8; }
    
    .status-btn.active { box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
</style>

@endsection
