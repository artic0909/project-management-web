@extends('developer.layout.app')

@section('title', 'My Projects')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-projects">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 30px;">
            <div>
                <h1 class="page-title" style="font-size: 28px; font-weight: 800; letter-spacing: -0.5px;">My Assigned Projects</h1>
                <p class="page-desc" style="color: var(--t3); font-size: 14px;">Managing {{ $projects->count() }} active workspaces and client deliverables</p>
            </div>
            <div class="header-actions">
                <form action="{{ route('developer.projects.index') }}" method="GET" class="search-form">
                    <div class="search-inp-group premium-search">
                        <i class="bi bi-search"></i>
                        <input type="text" name="search" class="search-inp" placeholder="Search by name, client or domain..." value="{{ request('search') }}">
                        @if(request('search'))
                            <a href="{{ route('developer.projects.index') }}" class="search-clear"><i class="bi bi-x-circle-fill"></i></a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Project Grid -->
        <div class="dash-grid">
            @forelse($projects as $project)
                <div class="span-4">
                    <div class="dash-card premium-project-card">
                        <div class="card-body" style="padding: 24px;">
                            <div class="proj-top-strip">
                                <div class="proj-badge" style="display:flex;align-items:center;gap:6px;">
                                    <i class="bi bi-hash"></i> {{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}
                                    @php
                                        $myTasks = $project->tasks()->whereHas('assignments', function($q) {
                                            $q->where('developer_id', auth()->guard('developer')->id());
                                        })->get();
                                        $done = $myTasks->where('status', 'Completed')->count();
                                        $total = $myTasks->count();
                                        $perc = $total > 0 ? round(($done / $total) * 100) : 0;
                                        $needsAttention = ($total > $done);
                                    @endphp
                                    @if($total > 0 && $total == $done)
                                        <div class="completion-dot" title="All Tasks Completed"></div>
                                    @elseif($total > $done)
                                        <div class="attention-dot" title="Attention Required: Uncompleted Tasks"></div>
                                    @endif
                                </div>
                                @php
                                    $statusName = $project->projectStatus->name ?? 'New';
                                    $statusClass = strtolower(str_replace([' ', '/'], '-', $statusName));
                                @endphp
                                <span class="proj-status-pill {{ $statusClass }}">
                                    {{ $statusName }}
                                </span>
                            </div>

                            <div class="proj-main-info">
                                <h3 class="proj-display-title" title="{{ $project->project_name }}">{{ $project->project_name }}</h3>
                                <div class="proj-client-row">
                                    <div class="client-avatar" style="background: var(--accent-bg); color: var(--accent);">
                                        {{ strtoupper(substr($project->company_name ?? $project->client_name, 0, 1)) }}
                                    </div>
                                    <span class="client-name">{{ $project->company_name ?? $project->client_name }}</span>
                                </div>
                            </div>

                            <div class="proj-metrics">
                                <div class="metric-item">
                                    <div class="metric-label">Domain</div>
                                    <div class="metric-val text-truncate"><i class="bi bi-globe"></i> {{ $project->domain_name ?? 'N/A' }}</div>
                                </div>
                                <div class="metric-item">
                                    <div class="metric-label">Deadline</div>
                                    <div class="metric-val" style="color: {{ $project->expected_delivery_date && $project->expected_delivery_date->isPast() && $statusName != 'Completed' ? '#ef4444' : 'var(--t2)' }}">
                                        <i class="bi bi-calendar3"></i> {{ $project->expected_delivery_date ? $project->expected_delivery_date->format('d M, Y') : 'N/A' }}
                                    </div>
                                </div>
                            </div>

                            <div class="proj-progress-section">
                                <div class="prog-header">
                                    <span>Task Progress</span>
                                    <span class="prog-perc">{{ $perc }}%</span>
                                </div>
                                <div class="prog-bar-container">
                                    <div class="prog-bar-fill" style="width:{{ $perc }}%"></div>
                                </div>
                            </div>

                            <div class="proj-action-area">
                                <a href="{{ route('developer.projects.show', $project->id) }}" class="btn-premium-view">
                                    View Workspace <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="span-12">
                    <div class="empty-state-card">
                        <div class="empty-icon-wrap">
                            <i class="bi bi-kanban"></i>
                        </div>
                        <h2>No Projects Found</h2>
                        <p>We couldn't find any projects matching your search criteria or assigned to you yet.</p>
                        <a href="{{ route('developer.projects.index') }}" class="btn-primary-solid">Clear Filters</a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</main>

<style>
    /* ─── PREMIUM SEARCH ─── */
    .premium-search {
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: 12px;
        padding: 4px 14px;
        display: flex;
        align-items: center;
        gap: 12px;
        width: 380px;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }
    .premium-search:focus-within {
        border-color: var(--accent);
        box-shadow: 0 0 0 4px var(--accent-bg);
        background: var(--bg);
    }
    .premium-search i { color: var(--t4); font-size: 14px; }
    .premium-search input {
        border: none;
        background: none;
        outline: none;
        color: var(--t1);
        font-size: 14px;
        font-weight: 500;
        width: 100%;
        padding: 8px 0;
    }
    .search-clear { color: var(--t4); transition: var(--transition); }
    .search-clear:hover { color: #ef4444; }

    /* ─── PREMIUM PROJECT CARD ─── */
    .premium-project-card {
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: 20px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        height: 100%;
    }
    .premium-project-card:hover {
        transform: translateY(-8px);
        border-color: var(--accent);
        box-shadow: var(--shadow-lg);
    }
    .premium-project-card::after {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 4px;
        background: linear-gradient(90deg, var(--accent), var(--accent2));
        opacity: 0;
        transition: var(--transition);
    }
    .premium-project-card:hover::after { opacity: 1; }

    .proj-top-strip { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .proj-badge {
        font-family: var(--mono);
        font-size: 11px;
        font-weight: 700;
        color: var(--t3);
        background: var(--bg4);
        padding: 4px 10px;
        border-radius: 6px;
        border: 1px solid var(--b2);
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .attention-dot {
        width: 8px;
        height: 8px;
        background: #ef4444;
        border-radius: 50%;
        box-shadow: 0 0 0 rgba(239, 68, 68, 0.4);
        animation: pulseRed 2s infinite;
    }

    .completion-dot {
        width: 8px;
        height: 8px;
        background: #10b981;
        border-radius: 50%;
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.4);
    }

    @keyframes pulseRed {
        0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }

    .proj-status-pill {
        font-size: 10px;
        font-weight: 800;
        padding: 4px 12px;
        border-radius: 50px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .proj-status-pill::before {
        content: ''; width: 6px; height: 6px; border-radius: 50%;
    }

    /* Colors from Admin standard */
    .proj-status-pill.new { background: rgba(245, 158, 11, .12); color: #f59e0b; }
    .proj-status-pill.new::before { background: #f59e0b; }
    .proj-status-pill.design-phase { background: rgba(6, 182, 212, .12); color: #06b6d4; }
    .proj-status-pill.design-phase::before { background: #06b6d4; }
    .proj-status-pill.development { background: rgba(99, 102, 241, .12); color: #6366f1; }
    .proj-status-pill.development::before { background: #6366f1; }
    .proj-status-pill.testing { background: rgba(139, 92, 246, .12); color: #8b5cf6; }
    .proj-status-pill.testing::before { background: #8b5cf6; }
    .proj-status-pill.completed { background: rgba(16, 185, 129, .12); color: #10b981; }
    .proj-status-pill.completed::before { background: #10b981; }
    .proj-status-pill.on-hold { background: rgba(100, 116, 139, .12); color: #64748b; }
    .proj-status-pill.on-hold::before { background: #64748b; }

    .proj-main-info { margin-bottom: 20px; }
    .proj-display-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        letter-spacing: -0.3px;
    }
    .proj-client-row { display: flex; align-items: center; gap: 10px; }
    .client-avatar {
        width: 24px; height: 24px; border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 800; flex-shrink: 0;
    }
    .client-name { font-size: 14px; font-weight: 600; color: var(--t3); }

    .proj-metrics {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
        padding: 16px 0;
        border-top: 1px solid var(--b1);
        border-bottom: 1px solid var(--b1);
        margin-bottom: 20px;
    }
    .metric-label { font-size: 10px; font-weight: 800; color: var(--t4); text-transform: uppercase; margin-bottom: 4px; }
    .metric-val { font-size: 13px; font-weight: 600; color: var(--t2); display: flex; align-items: center; gap: 6px; }
    .metric-val i { color: var(--accent); font-size: 14px; }

    .proj-progress-section { margin-bottom: 24px; }
    .prog-header { display: flex; justify-content: space-between; font-size: 12px; font-weight: 700; color: var(--t2); margin-bottom: 8px; }
    .prog-perc { color: var(--accent); }
    .prog-bar-container { height: 6px; background: var(--bg4); border-radius: 10px; overflow: hidden; }
    .prog-bar-fill { height: 100%; background: linear-gradient(90deg, var(--accent), var(--accent2)); border-radius: 10px; transition: width 1s ease; }

    .btn-premium-view {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: 12px;
        color: var(--t2);
        font-size: 14px;
        font-weight: 700;
        transition: var(--transition);
    }
    .btn-premium-view:hover {
        background: var(--accent);
        color: #fff;
        border-color: var(--accent);
        box-shadow: var(--accent-glow);
    }

    /* ─── EMPTY STATE ─── */
    .empty-state-card {
        padding: 80px 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        background: var(--bg2);
        border-radius: 24px;
        border: 1px dashed var(--b1);
    }
    .empty-icon-wrap {
        width: 80px; height: 80px;
        background: var(--bg4);
        border-radius: 24px;
        display: flex; align-items: center; justify-content: center;
        font-size: 40px; color: var(--t4);
        margin-bottom: 24px;
        border: 1px solid var(--b2);
    }
    .empty-state-card h2 { font-size: 24px; font-weight: 800; color: var(--t1); margin-bottom: 12px; }
    .empty-state-card p { color: var(--t3); font-size: 16px; max-width: 400px; margin-bottom: 30px; }

    @media (max-width: 992px) {
        .premium-search { width: 100%; }
        .dash-grid { grid-template-columns: 1fr; }
        .span-4 { grid-column: span 12; }
    }
</style>
@endsection
