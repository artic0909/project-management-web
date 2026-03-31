@extends('admin.layout.app')

@section('title', 'Project Meetings')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-dev">
        
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <h1 class="page-title">Assigned Meetings</h1>
                <p class="page-desc">View upcoming project briefings and technical discussions.</p>
            </div>
        </div>

        <div class="dash-card">
            <div class="card-head" style="padding:16px 20px;">
                <div class="card-title">Schedule List</div>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="meeting-list">
                    @forelse($meetings as $meeting)
                        <div class="meeting-item {{ strtolower($meeting->status) }}">
                            <div class="m-date">
                                <span class="d">{{ $meeting->meeting_date->format('d') }}</span>
                                <span class="m">{{ $meeting->meeting_date->format('M') }}</span>
                            </div>
                            <div class="m-info">
                                <div class="m-top">
                                    <span class="m-type-tag {{ strtolower($meeting->meeting_type) }}">{{ strtoupper($meeting->meeting_type) }}</span>
                                    <span class="m-time"><i class="bi bi-clock"></i> {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}</span>
                                    <span class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                                </div>
                                <h3 class="m-title">{{ $meeting->meeting_title }}</h3>
                                
                                <div class="m-links">
                                    @if($meeting->meeting_type == 'project' && $meeting->project)
                                        <a href="{{ route('developer.projects.show', $meeting->project_id) }}" class="m-link"><i class="bi bi-kanban"></i> {{ $meeting->project->name }}</a>
                                    @endif

                                    @if($meeting->meeting_link)
                                        <a href="{{ $meeting->meeting_link }}" target="_blank" class="m-link active"><i class="bi bi-camera-video"></i> Join Briefing</a>
                                    @endif
                                </div>
                            </div>
                            <div class="m-footer">
                                <div class="m-actions">
                                    <a href="{{ route('developer.meetings.show', $meeting->id) }}" class="act-btn" title="View briefing Details"><i class="bi bi-eye"></i></a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div style="padding:40px; text-align:center; color:var(--t4);">
                            No project meetings assigned to you yet.
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
    /* Reuse styles from admin index for consistency */
    .meeting-list { display: flex; flex-direction: column; }
    .meeting-item { display: grid; grid-template-columns: 80px 1fr 60px; gap: 20px; padding: 20px; border-bottom: 1px solid var(--b1); }
    .meeting-item:hover { background: var(--bg3); }
    .m-date { display: flex; flex-direction: column; align-items: center; justify-content: center; background: var(--bg4); border-radius: 12px; height: 60px; width: 60px; border: 1px solid var(--b1); }
    .m-date .d { font-size: 18px; font-weight: 800; color: var(--t1); line-height: 1; }
    .m-date .m { font-size: 9px; font-weight: 700; color: var(--t3); text-transform: uppercase; }
    .m-info { display: flex; flex-direction: column; gap: 4px; }
    .m-top { display: flex; align-items: center; gap: 10px; }
    .m-type-tag { font-size: 9px; font-weight: 800; padding: 2px 8px; border-radius: 20px; background: rgba(var(--accent-rgb), 0.1); color: var(--accent); }
    .m-time { font-size: 11px; font-weight: 700; color: var(--t2); }
    .m-status-pill { font-size: 9px; font-weight: 700; padding: 2px 8px; border-radius: 4px; text-transform: capitalize; }
    .m-status-pill.pending { background: #fef3c7; color: #92400e; }
    .m-status-pill.completed { background: #d1fae5; color: #065f46; }
    .m-title { font-size: 15px; font-weight: 800; color: var(--t1); margin: 2px 0; }
    .m-links { display: flex; gap: 12px; margin-top: 5px; }
    .m-link { font-size: 11px; font-weight: 700; color: var(--t4); text-decoration: none; display: flex; align-items: center; gap: 4px; }
    .m-link.active { color: var(--accent); }
    .m-footer { display: flex; align-items: center; justify-content: flex-end; }
    .act-btn { width: 34px; height: 34px; border-radius: 10px; background: var(--bg4); color: var(--t3); display: flex; align-items: center; justify-content: center; }
    .act-btn:hover { background: var(--bg3); color: var(--accent); }
</style>

@endsection
