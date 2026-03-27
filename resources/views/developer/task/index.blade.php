@extends('developer.layout.app')

@section('title', 'Project Tasks - ' . $project->project_name)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-tasks">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 24px;">
            <div>
                <nav class="breadcrumb-nav">
                    <a href="{{ route('developer.projects.index') }}">Projects</a>
                    <i class="bi bi-chevron-right"></i>
                    <a href="{{ route('developer.projects.show', $project->id) }}">{{ $project->project_name }}</a>
                </nav>
                <h1 class="page-title" style="margin-top: 8px;">Project Tasks</h1>
                <p class="page-desc" style="color: var(--t3);">{{ $tasks->count() }} active tasks assigned to you in this workspace</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('developer.projects.show', $project->id) }}" class="btn-ghost sm">
                    <i class="bi bi-arrow-left"></i> Back to Project
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="premium-alert success">
                <div class="alert-icon"><i class="bi bi-check-all"></i></div>
                <div class="alert-content">
                    <strong>Excellent!</strong> {{ session('success') }}
                </div>
            </div>
        @endif

        <div class="dash-grid">
            <div class="span-12">
                <div class="task-container">
                    @forelse($tasks as $task)
                        <div class="dash-card premium-task-card">
                            <div class="card-body">
                                <div class="task-main-col">
                                    <div class="task-top">
                                        <div style="display:flex; align-items:center; gap:12px;">
                                            @php
                                                $statusClass = strtolower(str_replace(' ', '-', $task->status));
                                            @endphp
                                            <span class="status-pill-small {{ $statusClass }}">
                                                {{ $task->status }}
                                            </span>
                                            <span class="task-id">#TSK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <div class="task-assignment-info">
                                            <div class="creator-stamp">
                                                <div class="stamp-icon"><i class="bi bi-person-fill-gear"></i></div>
                                                <div class="stamp-text">
                                                    <span>Assigned by</span>
                                                    <strong>{{ $task->creator->name ?? 'System' }}</strong>
                                                </div>
                                            </div>
                                            <div class="date-stamp">
                                                <i class="bi bi-calendar-plus"></i> {{ $task->created_at->format('d M, Y') }}
                                            </div>
                                        </div>
                                    </div>

                                    <h3 class="task-display-title">{{ $task->title }}</h3>
                                    
                                    <div class="task-instruction-box">
                                        <div class="box-label">Task Instructions</div>
                                        <div class="box-content">{{ $task->task }}</div>
                                    </div>
                                </div>

                                <div class="task-update-col">
                                    <form action="{{ route('developer.tasks.update', $task->id) }}" method="POST" class="update-form">
                                        @csrf
                                        <div class="form-group-premium">
                                            <label>Status Update</label>
                                            <div class="custom-select-wrap">
                                                <select name="status" class="premium-select">
                                                    <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed (Mark as Finished)</option>
                                                </select>
                                                <i class="bi bi-chevron-down"></i>
                                            </div>
                                        </div>

                                        <div class="form-group-premium">
                                            <label>Developer Remarks</label>
                                            <textarea name="remarks" class="premium-textarea" rows="3" placeholder="Log progress or report blockers...">{{ $task->assignments->where('developer_id', auth()->guard('developer')->id())->first()->remarks ?? '' }}</textarea>
                                        </div>

                                        <button type="submit" class="btn-update-task">
                                            <span>Save Progress</span>
                                            <i class="bi bi-floppy2-fill"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="empty-workspace">
                            <div class="empty-art">
                                <i class="bi bi-check2-circle"></i>
                                <div class="art-ring"></div>
                            </div>
                            <h2>All Tasks Completed</h2>
                            <p>You have no pending tasks in this workspace. Great job staying on top of your deliverables!</p>
                            <a href="{{ route('developer.projects.show', $project->id) }}" class="btn-primary-solid">Return to Overview</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* ─── BREADCRUMB ─── */
    .breadcrumb-nav { display: flex; align-items: center; gap: 10px; font-size: 13px; color: var(--t4); font-weight: 500; }
    .breadcrumb-nav a { color: var(--t3); transition: var(--transition); }
    .breadcrumb-nav a:hover { color: var(--accent); }
    .breadcrumb-nav i { font-size: 10px; opacity: 0.5; }

    /* ─── PREMIUM ALERT ─── */
    .premium-alert {
        display: flex; align-items: center; gap: 15px; padding: 16px 20px; border-radius: 12px; margin-bottom: 24px;
        background: rgba(16, 185, 129, 0.08); border: 1px solid rgba(16, 185, 129, 0.2); animation: slideDown 0.4s ease;
    }
    .alert-icon { font-size: 20px; color: #10b981; }
    .alert-content { color: var(--t2); font-size: 14px; }
    @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    /* ─── TASK CARD LAYOUT ─── */
    .premium-task-card {
        background: var(--bg2); border: 1px solid var(--b1); border-radius: 20px; margin-bottom: 24px; transition: var(--transition);
    }
    .premium-task-card:hover { border-color: var(--accent); box-shadow: var(--shadow-sm); }
    .premium-task-card .card-body { display: grid; grid-template-columns: 1fr 340px; gap: 40px; padding: 30px; }

    .task-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .task-id { font-family: var(--mono); font-size: 11px; font-weight: 700; color: var(--t4); }
    
    .status-pill-small {
        font-size: 10px; font-weight: 800; padding: 4px 12px; border-radius: 50px; text-transform: uppercase;
        letter-spacing: 0.5px; border: 1px solid transparent;
    }
    .status-pill-small.pending { background: rgba(245, 158, 11, .1); color: #f59e0b; border-color: rgba(245, 158, 11, .2); }
    .status-pill-small.in-progress { background: rgba(99, 102, 241, .1); color: #6366f1; border-color: rgba(99, 102, 241, .2); }
    .status-pill-small.completed { background: rgba(16, 185, 129, .1); color: #10b981; border-color: rgba(16, 185, 129, .2); }

    .task-assignment-info { display: flex; align-items: center; gap: 24px; }
    .creator-stamp { display: flex; align-items: center; gap: 8px; }
    .stamp-icon { width: 32px; height: 32px; border-radius: 50%; background: var(--bg4); display: flex; align-items: center; justify-content: center; color: var(--t3); border: 1px solid var(--b1); }
    .stamp-text { display: flex; flex-direction: column; line-height: 1.2; }
    .stamp-text span { font-size: 10px; color: var(--t4); text-transform: uppercase; font-weight: 700; }
    .stamp-text strong { font-size: 13px; color: var(--t2); }
    .date-stamp { font-size: 12px; color: var(--t4); font-weight: 600; display: flex; align-items: center; gap: 6px; }

    .task-display-title { font-size: 22px; font-weight: 800; color: var(--t1); margin-bottom: 20px; letter-spacing: -0.5px; }
    
    .task-instruction-box { background: var(--bg3); border-radius: 16px; padding: 20px; border: 1px solid var(--b1); position: relative; }
    .box-label { font-size: 10px; font-weight: 800; color: var(--t4); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; }
    .box-content { font-size: 15px; color: var(--t2); line-height: 1.6; white-space: pre-wrap; }

    /* ─── UPDATE PANEL ─── */
    .task-update-col { background: var(--bg); border: 1px solid var(--b1); border-radius: 16px; padding: 20px; }
    .form-group-premium { margin-bottom: 16px; }
    .form-group-premium label { display: block; font-size: 11px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 8px; }
    
    .custom-select-wrap { position: relative; }
    .premium-select {
        width: 100%; padding: 10px 14px; background: var(--bg2); border: 1px solid var(--b1); border-radius: 8px;
        color: var(--t2); font-size: 14px; font-weight: 600; cursor: pointer; appearance: none;
    }
    .custom-select-wrap i { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); font-size: 12px; color: var(--t4); pointer-events: none; }
    
    .premium-textarea {
        width: 100%; padding: 12px; background: var(--bg2); border: 1px solid var(--b1); border-radius: 8px;
        color: var(--t2); font-size: 14px; line-height: 1.5; resize: none; transition: var(--transition);
    }
    .premium-textarea:focus { border-color: var(--accent); outline: none; background: var(--bg); }

    .btn-update-task {
        width: 100%; display: flex; align-items: center; justify-content: center; gap: 10px; padding: 12px;
        background: var(--accent); color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 700;
        cursor: pointer; transition: var(--transition); margin-top: 10px;
    }
    .btn-update-task:hover { filter: brightness(1.1); transform: translateY(-2px); box-shadow: var(--accent-glow); }

    /* ─── EMPTY STATE ─── */
    .empty-workspace { padding: 80px 40px; text-align: center; border-radius: 24px; background: var(--bg2); border: 1px dashed var(--b1); }
    .empty-art { position: relative; width: 80px; height: 80px; margin: 0 auto 24px; color: #10b981; font-size: 40px; display: flex; justify-content: center; align-items: center; }
    .art-ring { position: absolute; inset: 0; border: 2px solid #10b981; border-radius: 50%; opacity: 0.1; animation: ringPulse 2s infinite; }
    @keyframes ringPulse { 0% { transform: scale(1); opacity: 0.2; } 100% { transform: scale(1.5); opacity: 0; } }
    .empty-workspace h2 { font-size: 24px; font-weight: 800; color: var(--t1); margin-bottom: 12px; }
    .empty-workspace p { color: var(--t3); margin-bottom: 30px; font-size: 16px; }

    @media (max-width: 1100px) {
        .premium-task-card .card-body { grid-template-columns: 1fr; gap: 24px; }
        .task-update-col { order: -1; }
    }
</style>
@endsection
