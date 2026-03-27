@extends('developer.layout.app')

@section('title', 'My Completed Tasks')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-my-tasks">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 30px;">
            <div>
                <h1 class="page-title" style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">Activity History</h1>
                <p class="page-desc" style="color: var(--t3); font-size: 14px;">Review {{ $tasks->count() }} milestones you've successfully delivered</p>
            </div>
            <div class="header-actions">
                <div class="history-stats">
                    <div class="stat-pill">
                        <i class="bi bi-patch-check-fill" style="color:#10b981;"></i>
                        <span>{{ $tasks->count() }} Verified Completions</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-12">
                <div class="history-card dash-card">
                    <div class="card-body" style="padding:0;">
                         <div class="premium-table-container">
                            <table class="premium-table">
                                <thead>
                                    <tr>
                                        <th style="padding-left: 24px;">Project / Workspace</th>
                                        <th>Task Identifer</th>
                                        <th>Completion Log</th>
                                        <th>Outcome / Remarks</th>
                                        <th style="padding-right: 24px; text-align: right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                        <tr class="history-row">
                                            <td style="padding-left: 24px;">
                                                <div class="project-info-row">
                                                    <div class="proj-mini-avatar">
                                                        {{ strtoupper(substr($task->project->project_name, 0, 1)) }}
                                                    </div>
                                                    <div style="display:flex; flex-direction:column;">
                                                        <a href="{{ route('developer.projects.show', $task->project->id) }}" class="proj-link">
                                                            {{ $task->project->project_name }}
                                                        </a>
                                                        <span class="client-sub">{{ $task->project->company_name ?? $task->project->client_name }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="task-id-badge">
                                                    <i class="bi bi-hash"></i> {{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}
                                                </div>
                                                <div class="task-title-sub">{{ $task->title }}</div>
                                            </td>
                                            <td>
                                                <div class="log-time">
                                                    <i class="bi bi-clock-history"></i>
                                                    {{ $task->updated_at->format('d M Y') }}
                                                </div>
                                                <div class="log-exact">{{ $task->updated_at->format('h:i A') }}</div>
                                            </td>
                                            <td>
                                                <div class="remarks-box" title="{{ $task->assignments->where('developer_id', auth()->guard('developer')->id())->first()->remarks }}">
                                                    {{ $task->assignments->where('developer_id', auth()->guard('developer')->id())->first()->remarks ?? 'N/A' }}
                                                </div>
                                            </td>
                                            <td style="padding-right: 24px; text-align: right;">
                                                <a href="{{ route('developer.tasks.show', $task->id) }}" class="btn-action-view" title="Deep View">
                                                    <i class="bi bi-arrow-up-right-square"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">
                                                <div class="empty-history">
                                                    <div class="empty-icon"><i class="bi bi-journal-x"></i></div>
                                                    <h3>No activity recorded</h3>
                                                    <p>Your completed tasks will appear here once they are verified.</p>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    /* ─── HEADER STATS ─── */
    .stat-pill { background: var(--bg2); border: 1px solid var(--b1); padding: 8px 16px; border-radius: 50px; display: flex; align-items: center; gap: 10px; font-size: 13px; font-weight: 700; color: var(--t2); box-shadow: var(--shadow-sm); }

    /* ─── PREMIUM TABLE ─── */
    .history-card { background: var(--bg2); border: 1px solid var(--b1); border-radius: 20px; overflow: hidden; }
    .premium-table { width: 100%; border-collapse: collapse; }
    .premium-table th { background: var(--bg); color: var(--t4); font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; padding: 16px 12px; border-bottom: 1px solid var(--b1); text-align: left; }
    
    .history-row { transition: var(--transition); border-bottom: 1px solid var(--b1); }
    .history-row:last-child { border-bottom: none; }
    .history-row:hover { background: var(--bg3); }

    /* Project Cell */
    .project-info-row { display: flex; align-items: center; gap: 12px; }
    .proj-mini-avatar { width: 32px; height: 32px; border-radius: 8px; background: linear-gradient(135deg, var(--accent), var(--accent2)); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; font-weight: 800; flex-shrink: 0; }
    .proj-link { font-size: 14px; font-weight: 700; color: var(--t1); transition: var(--transition); text-decoration: none; }
    .proj-link:hover { color: var(--accent); }
    .client-sub { font-size: 11px; color: var(--t4); font-weight: 500; }

    /* Task Cell */
    .task-id-badge { display: inline-flex; align-items: center; gap: 4px; font-family: var(--mono); font-size: 10px; color: var(--accent); background: var(--accent-bg); padding: 2px 6px; border-radius: 4px; font-weight: 700; margin-bottom: 4px; }
    .task-title-sub { font-size: 13px; color: var(--t2); font-weight: 600; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 250px; }

    /* Log Cell */
    .log-time { font-size: 13px; font-weight: 700; color: var(--t1); display: flex; align-items: center; gap: 6px; }
    .log-time i { color: #10b981; font-size: 14px; }
    .log-exact { font-size: 11px; color: var(--t4); margin-top: 2px; }

    /* Remarks Cell */
    .remarks-box { font-size: 13px; color: var(--t3); max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 8px 12px; background: var(--bg4); border-radius: 8px; border: 1px solid var(--b1); }

    /* Action Cell */
    .btn-action-view { color: var(--t4); font-size: 18px; transition: var(--transition); background: none; border: none; cursor: pointer; display: inline-flex; align-items: center; }
    .btn-action-view:hover { color: var(--accent); transform: scale(1.1); }

    /* ─── EMPTY STATE ─── */
    .empty-history { padding: 60px; text-align: center; }
    .empty-icon { font-size: 40px; color: var(--t4); margin-bottom: 16px; opacity: 0.5; }
    .empty-history h3 { color: var(--t2); margin-bottom: 8px; }
    .empty-history p { color: var(--t4); font-size: 14px; }

    @media (max-width: 992px) {
        .remarks-box, .log-exact { display: none; }
        .task-title-sub { max-width: 150px; }
    }
</style>
@endsection
