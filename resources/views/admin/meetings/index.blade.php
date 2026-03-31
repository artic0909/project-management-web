@extends('admin.layout.app')

@section('title', 'Meetings Management')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings">
        
        <!-- ── PAGE HEADER ── -->
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <h1 class="page-title">Meeting Schedule</h1>
                <p class="page-desc">Track and manage upcoming discussions with clients and teams.</p>
            </div>
            <div>
                <a href="{{ route('admin.meetings.create') }}" class="btn-primary-solid">
                    <i class="bi bi-plus-lg"></i> Schedule Meeting
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding:14px 18px;background:rgba(16,185,129,.1);color:#10b981;border-radius:var(--r);border:1px solid rgba(16,185,129,.2);display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                <i class="bi bi-check-circle-fill"></i>
                <span style="font-size:14px;font-weight:600;">{{ session('success') }}</span>
            </div>
        @endif

        <!-- ── MEETINGS TIMELINE ── -->
        <div class="dash-card">
            <div class="card-head" style="padding:16px 20px;">
                <div class="card-title">Timeline History</div>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="meeting-list">
                    @forelse($meetings as $meeting)
                        <div class="meeting-item {{ strtolower($meeting->status) }}">
                            <div class="m-date">
                                <span class="d">{{ $meeting->meeting_date->format('d') }}</span>
                                <span class="m">{{ $meeting->meeting_date->format('M') }}</span>
                                <span class="y">{{ $meeting->meeting_date->format('Y') }}</span>
                            </div>
                            <div class="m-info">
                                <div class="m-top">
                                    <span class="m-type-tag {{ strtolower($meeting->meeting_type) }}">{{ strtoupper($meeting->meeting_type) }}</span>
                                    <span class="m-time"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                                    <span class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                                </div>
                                <h3 class="m-title">{{ $meeting->meeting_title }}</h3>
                                <p class="m-desc">{{ Str::limit($meeting->meeting_description, 120) }}</p>
                                
                                <div class="m-links">
                                    @if($meeting->meeting_type == 'lead' && $meeting->lead)
                                        <a href="{{ route('admin.leads.show', $meeting->lead_id) }}" class="m-link"><i class="bi bi-person-circle"></i> {{ $meeting->lead->company }}</a>
                                    @elseif($meeting->meeting_type == 'order' && $meeting->order)
                                        <a href="{{ route('admin.orders.index') }}" class="m-link"><i class="bi bi-bag-check"></i> Order #{{ $meeting->order->id }}</a>
                                    @elseif($meeting->meeting_type == 'project' && $meeting->project)
                                        <a href="{{ route('admin.projects.show', $meeting->project_id) }}" class="m-link"><i class="bi bi-kanban"></i> {{ $meeting->project->name }}</a>
                                    @endif

                                    @if($meeting->meeting_link)
                                        <a href="{{ $meeting->meeting_link }}" target="_blank" class="m-link active"><i class="bi bi-camera-video"></i> Join Meeting</a>
                                    @endif
                                </div>
                            </div>
                            <div class="m-footer">
                                <div class="m-authors">
                                    <div class="author-ava" title="Created by: {{ $meeting->createdBy->name ?? 'System' }}">
                                        {{ strtoupper(substr($meeting->createdBy->name ?? 'S', 0, 1)) }}
                                    </div>
                                    <div class="author-meta">
                                        <span class="lbl">Created By</span>
                                        <span class="val">{{ $meeting->createdBy->name ?? 'System' }}</span>
                                    </div>
                                </div>
                                <div class="m-actions">
                                    <a href="{{ route('admin.meetings.show', $meeting->id) }}" class="act-btn" title="View Details"><i class="bi bi-eye"></i></a>
                                    <a href="{{ route('admin.meetings.edit', $meeting->id) }}" class="act-btn" title="Edit Meeting"><i class="bi bi-pencil"></i></a>
                                    <form action="{{ route('admin.meetings.destroy', $meeting->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Archive this meeting?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="act-btn danger" title="Delete"><i class="bi bi-trash"></i></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:40px; text-align:center; color:var(--t4);">
                            <i class="bi bi-calendar-x" style="font-size:32px; display:block; margin-bottom:10px;"></i>
                            No meetings scheduled yet. Create your first one to get started!
                        </div>
                    @endforelse
                </div>
                
                <div style="padding:20px;">
                    {{ $meetings->links() }}
                </div>
            </div>
        </div>

    </div>
</main>

<style>
    .meeting-list { display: flex; flex-direction: column; }
    .meeting-item { display: grid; grid-template-columns: 80px 1fr 240px; gap: 20px; padding: 20px; border-bottom: 1px solid var(--b1); transition: all 0.2s; position: relative; }
    .meeting-item:last-child { border-bottom: none; }
    .meeting-item:hover { background: var(--bg3); }
    
    .m-date { display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg4); border-radius: 12px; height: 80px; width: 80px; text-align: center; border: 1px solid var(--b1); }
    .m-date .d { font-size: 22px; font-weight: 800; color: var(--t1); line-height: 1; }
    .m-date .m { font-size: 11px; font-weight: 700; color: var(--t3); text-transform: uppercase; margin-top: 2px; }
    .m-date .y { font-size: 10px; font-weight: 600; color: var(--t4); margin-top: 1px; }

    .m-info { display: flex; flex-direction: column; gap: 6px; }
    .m-top { display: flex; align-items: center; gap: 10px; }
    .m-type-tag { font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 20px; letter-spacing: 0.5px; }
    .m-type-tag.lead { background: rgba(99,102,241,0.12); color: #6366f1; }
    .m-type-tag.order { background: rgba(245,158,11,0.12); color: #f59e0b; }
    .m-type-tag.project { background: rgba(16,185,129,0.12); color: #10b981; }

    .m-time { font-size: 12px; font-weight: 700; color: var(--t2); display: flex; align-items: center; gap: 5px; }
    .m-time i { font-size: 11px; color: var(--t4); }

    .m-status-pill { font-size: 10px; font-weight: 700; padding: 2px 10px; border-radius: 6px; text-transform: capitalize; }
    .m-status-pill.pending { background: #fef3c7; color: #92400e; }
    .m-status-pill.completed { background: #d1fae5; color: #065f46; }
    .m-status-pill.canceled { background: #fee2e2; color: #991b1b; }

    .m-title { font-size: 16px; font-weight: 800; color: var(--t1); margin: 4px 0 2px; }
    .m-desc { font-size: 13px; color: var(--t3); line-height: 1.5; margin: 0; }
    
    .m-links { display: flex; gap: 14px; margin-top: 8px; }
    .m-link { font-size: 11px; font-weight: 700; color: var(--t4); text-decoration: none; display: flex; align-items: center; gap: 6px; transition: 0.2s; }
    .m-link i { font-size: 13px; }
    .m-link:hover { color: var(--accent); }
    .m-link.active { color: var(--accent); }

    .m-footer { display: flex; flex-direction: column; justify-content: space-between; align-items: flex-end; padding-left: 20px; border-left: 1px dashed var(--b1); }
    .m-authors { display: flex; align-items: center; gap: 10px; width: 100%; }
    .author-ava { width: 34px; height: 34px; border-radius: 10px; background: var(--bg4); display: flex; align-items: center; justify-content: center; font-weight: 800; color: var(--t2); font-size: 13px; border: 1px solid var(--b1); }
    .author-meta { display: flex; flex-direction: column; }
    .author-meta .lbl { font-size: 10px; color: var(--t4); font-weight: 600; text-transform: uppercase; }
    .author-meta .val { font-size: 12px; color: var(--t2); font-weight: 700; }

    .m-actions { display: flex; gap: 6px; margin-top: auto; }
    .act-btn { width: 32px; height: 32px; border-radius: 8px; border: none; background: var(--bg4); color: var(--t3); display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s; font-size: 14px; text-decoration: none; }
    .act-btn:hover { background: var(--bg3); color: var(--accent); }
    .act-btn.danger:hover { background: #fee2e2; color: #dc2626; }

    @media (max-width: 900px) {
        .meeting-item { grid-template-columns: 80px 1fr; }
        .m-footer { grid-column: 1 / -1; flex-direction: row; padding-left: 0; border-left: none; border-top: 1px dashed var(--b1); padding-top: 15px; }
    }
</style>

@endsection
