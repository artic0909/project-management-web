@extends('developer.layout.app')

@section('title', 'Task Details - ' . $task->title)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-task-show">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 24px;">
            <div>
                <nav class="breadcrumb-nav">
                    <a href="{{ route('developer.tasks.completed') }}">Activity History</a>
                    <i class="bi bi-chevron-right"></i>
                    <span style="color:var(--t4);">{{ $task->title }}</span>
                </nav>
                <div style="display:flex; align-items:center; gap:12px; margin-top:12px; margin-bottom:8px;">
                    <span class="status-pill-solid completed">Completed</span>
                    <span class="project-tag"><i class="bi bi-folder2-open"></i> {{ $task->project->project_name }}</span>
                </div>
                <h1 class="page-title">{{ $task->title }}</h1>
            </div>
            <div class="header-actions">
                <a href="{{ route('developer.tasks.completed') }}" class="btn-ghost sm">
                    <i class="bi bi-arrow-left"></i> Back to History
                </a>
            </div>
        </div>

        <div class="dash-grid">
            {{-- Left Column: Project Info & Technical --}}
            <div class="span-8" style="display:flex; flex-direction:column; gap:20px;">
                
                {{-- Task Instructions --}}
                <div class="dash-card premium-detail-card">
                    <div class="card-head">
                        <div class="card-title">
                            <i class="bi bi-file-earmark-text-fill" style="color:var(--accent); margin-right:8px;"></i>
                            Original Instructions
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="task-instruction-detail">
                            {{ $task->task }}
                        </div>
                    </div>
                </div>

                {{-- Delivery Outcome --}}
                <div class="dash-card premium-detail-card" style="border-left: 4px solid #10b981;">
                    <div class="card-head" style="background: rgba(16, 185, 129, 0.03);">
                        <div class="card-title" style="color: #059669;">
                            <i class="bi bi-check-all" style="margin-right:8px;"></i>
                            Completion Log & Remarks
                        </div>
                    </div>
                    <div class="card-body">
                        @php
                            $assignment = $task->assignments->where('developer_id', auth()->guard('developer')->id())->first();
                        @endphp
                        <div class="remarks-display-box">
                            <div class="box-label">Your Final Remarks</div>
                            <div class="box-text">
                                {{ $assignment->remarks ?? 'No remarks provided during completion.' }}
                            </div>
                        </div>
                        
                        <div class="completion-metrics">
                            <div class="c-metric">
                                <label>Assigned On</label>
                                <span>{{ $task->created_at->format('d M Y, h:i A') }}</span>
                            </div>
                            <div class="c-metric">
                                <label>Delivered On</label>
                                <span style="color: #10b981; font-weight: 800;">{{ $task->updated_at->format('d M Y, h:i A') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Admin / Project Meta --}}
            <div class="span-4" style="display:flex; flex-direction:column; gap:20px;">
                
                {{-- Assigned By Card --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Assignment Context</div>
                    </div>
                    <div class="card-body">
                        <div class="kv-pair">
                            <label>Assigned By</label>
                            <div class="user-stamp-lg">
                                <div class="avatar-sm">
                                    {{ strtoupper(substr($task->creator->name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="stamp-info">
                                    <strong>{{ $task->creator->name ?? 'Administrator' }}</strong>
                                    <span>{{ $task->creator->email ?? 'System Generated' }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kv-pair" style="margin-top:24px;">
                            <label>Project Workspace</label>
                            <a href="{{ route('developer.projects.show', $task->project->id) }}" class="project-mini-lnk">
                                <i class="bi bi-box-arrow-up-right"></i>
                                {{ $task->project->project_name }}
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<style>
    /* ─── BREADCRUMB ─── */
    .breadcrumb-nav { display: flex; align-items: center; gap: 8px; font-size: 13px; font-weight: 500; }
    .breadcrumb-nav a { color: var(--t3); text-decoration: none; }
    .breadcrumb-nav a:hover { color: var(--accent); }

    /* ─── STATUS & TAGS ─── */
    .status-pill-solid { font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 50px; text-transform: uppercase; letter-spacing: 0.5px; }
    .status-pill-solid.completed { background: #10b981; color: #fff; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }
    
    .project-tag { font-size: 12px; font-weight: 600; color: var(--t3); background: var(--bg4); padding: 4px 12px; border-radius: 6px; border: 1px solid var(--b1); }

    /* ─── DETAIL CARDS ─── */
    .premium-detail-card { background: var(--bg2); border: 1px solid var(--b1); border-radius: 20px; transition: var(--transition); }
    .task-instruction-detail { font-size: 16px; color: var(--t2); line-height: 1.8; white-space: pre-wrap; padding: 10px 0; }

    /* Remarks Box */
    .remarks-display-box { background: var(--bg); border: 1px solid var(--b1); border-radius: 12px; padding: 20px; margin-bottom: 24px; }
    .box-label { font-size: 11px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 12px; }
    .box-text { font-size: 14px; color: var(--t2); line-height: 1.6; }

    /* Metrics */
    .completion-metrics { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; padding-top: 20px; border-top: 1px solid var(--b1); }
    .c-metric label { display: block; font-size: 10px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 4px; }
    .c-metric span { font-size: 14px; font-weight: 600; color: var(--t2); }

    /* ─── RIGHT COL STYLES ─── */
    .kv-pair label { display: block; font-size: 10px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 12px; letter-spacing: 0.5px; }
    
    .user-stamp-lg { display: flex; align-items: center; gap: 12px; }
    .avatar-sm { width: 40px; height: 40px; border-radius: 50%; background: var(--bg4); border: 1px solid var(--b1); display: flex; align-items: center; justify-content: center; font-size: 14px; font-weight: 800; color: var(--accent); }
    .stamp-info { display: flex; flex-direction: column; }
    .stamp-info strong { font-size: 14px; color: var(--t1); }
    .stamp-info span { font-size: 11px; color: var(--t4); }

    .project-mini-lnk { display: flex; align-items: center; gap: 8px; font-size: 14px; font-weight: 700; color: var(--accent); text-decoration: none; padding: 12px; background: var(--accent-bg); border-radius: 8px; transition: var(--transition); }
    .project-mini-lnk:hover { filter: brightness(0.95); transform: translateX(4px); }

    @media (max-width: 768px) {
        .completion-metrics { grid-template-columns: 1fr; }
    }
</style>
@endsection
