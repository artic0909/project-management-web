@extends('admin.layout.app')

@section('title', 'Task Details - #' . $task->id)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-task-view">
        
        <!-- Header Section -->
        <div class="page-header-premium" style="margin-bottom: 24px;">
            <div class="h-top-labels" style="display:flex;gap:10px;margin-bottom:12px;flex-wrap:wrap;align-items:center;">
                <span class="h-badge accent"><i class="bi bi-circle-fill" style="font-size:6px;margin-right:6px;vertical-align:middle;"></i>#TSK-{{ $task->id }}</span>
                <span class="h-badge gray"><i class="bi bi-calendar3" style="margin-right:5px;"></i>Created: {{ $task->created_at->format('d M, Y') }}</span>
                @if($task->project)
                    <span class="h-badge gray"><i class="bi bi-folder2" style="margin-right:5px;"></i>#PRJ-{{ $task->project->id }}</span>
                @endif
            </div>
            
            <div class="header-actions-wrap" style="display:flex; justify-content:space-between; align-items:flex-start; gap: 16px; flex-wrap: wrap;">
                <div>
                    <h1 style="font-size: clamp(22px, 4vw, 30px); font-weight:800; color:var(--t1); margin:0;">{{ $task->title }}</h1>
                    <div style="display:flex; gap:10px; margin-top:10px; flex-wrap: wrap; align-items:center;">
                        @php
                            $sClass = strtolower(str_replace(' ', '-', $task->status));
                        @endphp
                        <span class="status-pill {{ $sClass }}">{{ $task->status }}</span>
                        <span class="h-pill"><i class="bi bi-person-check-fill" style="color:var(--accent);"></i> {{ $task->assignments->count() }} Developer Assigned</span>
                    </div>
                </div>
                <div class="header-actions">
                    @php
                        $backUrl = ($routePrefix === 'developer') 
                            ? route('developer.tasks.completed') 
                            : ($task->project ? route($routePrefix . '.projects.tasks', $task->project->id) : route($routePrefix . '.dashboard'));
                    @endphp
                    <a href="{{ $backUrl }}" class="btn-primary-ghost sm" style="height:40px; padding:0 20px; display:inline-flex; align-items:center; gap:8px;">
                        <i class="bi bi-arrow-left"></i> <span>Back to {{ $routePrefix === 'developer' ? 'Tasks' : 'Project Tasks' }}</span>
                    </a>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding:14px 18px; background:rgba(16,185,129,0.12); color:#10b981; border:1px solid rgba(16,185,129,0.25); border-radius:12px; margin-bottom:24px; display:flex; align-items:center; gap:10px; font-weight:600; font-size:14px;">
                <i class="bi bi-check-circle-fill" style="font-size:18px;"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="padding:14px 18px; background:rgba(239,68,68,0.12); color:#ef4444; border:1px solid rgba(239,68,68,0.25); border-radius:12px; margin-bottom:24px;">
                @foreach($errors->all() as $error)
                    <div style="display:flex; align-items:center; gap:8px; font-weight:600; font-size:13.5px;"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="task-view-grid">
            
            {{-- Left Column: Details & Progress --}}
            <div style="display:flex; flex-direction:column; gap:24px;">
                
                {{-- Task Instructions / Requirements --}}
                <div class="dash-card premium-card">
                    <div class="card-hd-premium">
                        <div class="p-title"><i class="bi bi-info-circle-fill"></i> Task Instructions & Requirements</div>
                        @if($routePrefix == 'sale')
                        <div style="display:flex; gap:8px;">
                            <button class="call-btn"><i class="bi bi-telephone-fill"></i> Call</button>
                            <button class="email-btn"><i class="bi bi-envelope-fill"></i> Email</button>
                        </div>
                        @endif
                    </div>
                    <div class="card-body p-30">
                        <div class="info-grid-2">
                            <div class="info-group">
                                <label class="info-label">TASK TITLE</label>
                                <div class="info-value-lg">{{ $task->title }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">ASSIGNED BY</label>
                                <div class="v-user">
                                    <div class="v-ava">{{ strtoupper(substr($task->creator->name ?? 'A',0,1)) }}</div>
                                    <div>
                                        <div style="color:var(--t1); font-weight:700;">{{ $task->creator->name ?? 'System Admin' }}</div>
                                        <div style="font-size:11px; color:var(--t4);">{{ $task->creator->email ?? '' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="info-group mt-24">
                            <label class="info-label">INSTRUCTIONS / REQUIREMENTS</label>
                            <div class="desc-box">{{ $task->task }}</div>
                        </div>
                    </div>
                </div>

                {{-- Developer Response & Progress Log Card --}}
                <div class="dash-card premium-card">
                    <div class="card-hd-premium">
                        <div class="p-title"><i class="bi bi-chat-left-text-fill" style="color:var(--accent);"></i> Developer Response & Progress Log</div>
                        <span class="h-badge accent"><i class="bi bi-clock-history" style="margin-right:4px;"></i> Live Updates</span>
                    </div>
                    <div class="card-body p-30">
                        @if($task->assignments->count() > 0)
                            <div style="display: flex; flex-direction: column; gap: 16px;">
                                @foreach($task->assignments as $assign)
                                    <div class="dev-log-card">
                                        <div class="dev-log-hd">
                                            <div class="v-user">
                                                <div class="v-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4);">{{ strtoupper(substr($assign->developer->name ?? 'D', 0, 1)) }}</div>
                                                <div>
                                                    <div style="font-weight: 700; color: var(--t1); font-size: 14px;">{{ $assign->developer->name ?? 'Assigned Developer' }}</div>
                                                    <div style="font-size: 11.5px; color: var(--t4); font-weight: 500;">{{ $assign->developer->email ?? '' }} · {{ $assign->developer->designation ?? 'Developer' }}</div>
                                                </div>
                                            </div>
                                            <div class="dev-log-time">
                                                <i class="bi bi-clock"></i> Updated: {{ $assign->updated_at ? $assign->updated_at->format('d M, Y h:i A') : 'N/A' }}
                                            </div>
                                        </div>
                                        <div class="info-group" style="margin-top: 10px;">
                                            <label class="info-label">WRITTEN PROGRESS / REMARKS AGAINST TASK</label>
                                            @if(!empty(trim($assign->remarks)))
                                                <div class="dev-remarks-content">{{ $assign->remarks }}</div>
                                            @else
                                                <div class="dev-remarks-empty">
                                                    <i class="bi bi-pencil-square"></i> Developer has not logged any notes or remarks against this task yet.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div style="text-align: center; padding: 24px; color: var(--t4);">
                                <i class="bi bi-person-x" style="font-size:28px; display:block; margin-bottom:8px; opacity:0.4;"></i>
                                No developer assigned to this task.
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Related Project Detail --}}
                @if($task->project)
                <div class="dash-card premium-card">
                    <div class="card-hd-premium">
                        <div class="p-title"><i class="bi bi-folder2-open" style="color:#f59e0b;"></i> Related Project Detail</div>
                        <a href="{{ route($routePrefix . '.projects.show', $task->project->id) }}" class="p-link">View Project <i class="bi bi-arrow-right"></i></a>
                    </div>
                    <div class="card-body p-30">
                        <div class="info-grid-3">
                            <div class="info-group">
                                <label class="info-label">PROJECT NAME</label>
                                <div class="info-value">{{ $task->project->project_name }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">CLIENT / COMPANY</label>
                                <div class="info-value">{{ $task->project->company_name ?? $task->project->client_name }}</div>
                            </div>
                            <div class="info-group">
                                <label class="info-label">PROJECT ID</label>
                                <div class="info-value accent">#PRJ-{{ $task->project->id }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            {{-- Right Column: Updates & Side Info --}}
            <div style="display:flex; flex-direction:column; gap:24px; position:sticky; top:24px;">
                
                {{-- Quick Update Panel --}}
                <div class="dash-card update-panel-premium">
                    <div class="p-hd">
                        <div class="p-title"><i class="bi bi-lightning-charge-fill" style="color:var(--accent);"></i> Quick Update</div>
                    </div>
                    <div class="card-body p-24">
                        <form action="{{ route($routePrefix . '.tasks.update', $task->id) }}" method="POST">
                            @csrf
                            <div class="form-row">
                                <label class="form-label-sm">TASK STATUS</label>
                                <select name="status" class="form-select-pm" required>
                                    <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                    <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            @php
                                $devId = auth()->guard('developer')->id();
                                $currentDevAssign = $devId ? $task->assignments->where('developer_id', $devId)->first() : $task->assignments->first();
                            @endphp
                            <div class="form-row mt-15">
                                <label class="form-label-sm">LOG PROGRESS / NOTES</label>
                                <textarea name="remarks" class="form-area-pm" rows="6" placeholder="Write developer progress, updates, or notes against this task...">{{ $currentDevAssign->remarks ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn-update-pm">
                                <i class="bi bi-check2-circle" style="font-size:16px;"></i> Update Now
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Project Handlers --}}
                <div class="dash-card handler-panel">
                    <div class="p-hd-sub">IDENTIFIED HANDLERS</div>
                    <div class="card-body p-20" style="padding-top:0;">
                         <div class="handler-list">
                            @forelse($task->assignments as $assign)
                            <div class="h-item">
                                <div class="h-ava">{{ strtoupper(substr($assign->developer->name ?? 'D',0,1)) }}</div>
                                <div class="h-info">
                                    <div class="h-name">{{ $assign->developer->name ?? 'Developer' }}</div>
                                    <div class="h-mail">{{ $assign->developer->email ?? '' }}</div>
                                </div>
                            </div>
                            @empty
                            <div style="font-size:12px; color:var(--t4);">No handlers assigned</div>
                            @endforelse
                         </div>
                    </div>
                </div>

                {{-- Timeline Summary --}}
                <div class="dash-card timeline-summary">
                    <div class="p-hd-sub">TASKS TIMELINE</div>
                    <div class="card-body p-20" style="padding-top:10px;">
                        <div class="t-line">
                            <div class="t-node">
                                <div class="t-dot"></div>
                                <div class="t-content">
                                    <div class="t-label">ASSIGNED DATE</div>
                                    <div class="t-val">{{ $task->created_at->format('d M, Y h:i A') }}</div>
                                </div>
                            </div>
                            <div class="t-node">
                                <div class="t-dot active"></div>
                                <div class="t-content">
                                    <div class="t-label">LAST UPDATED</div>
                                    <div class="t-val">{{ $task->updated_at->format('d M, Y h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

<style>
    .page-area { padding: 30px; }
    .p-30 { padding: 24px 30px !important; }
    .p-24 { padding: 20px 24px !important; }
    .p-20 { padding: 18px 20px !important; }
    .mt-24 { margin-top: 24px !important; }
    .mt-15 { margin-top: 15px !important; }

    /* Header Premium */
    .h-badge { padding: 5px 12px; border-radius: 8px; font-size: 11.5px; font-weight: 700; border: 1px solid transparent; display: inline-flex; align-items: center; }
    .h-badge.accent { background: rgba(99,102,241,0.1); color: var(--accent); border-color: rgba(99,102,241,0.25); }
    .h-badge.gray { background: var(--bg3); color: var(--t2); border-color: var(--b1); }
    .h-pill { font-size: 13px; font-weight: 600; color: var(--t3); display: flex; align-items: center; gap: 8px; }

    .status-pill {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 14px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .status-pill.pending { background: rgba(245, 158, 11, .12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
    .status-pill.in-progress { background: rgba(99, 102, 241, .12); color: #6366f1; border: 1px solid rgba(99,102,241,0.25); }
    .status-pill.completed { background: rgba(16, 185, 129, .12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }

    /* Layout Grid */
    .task-view-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
        align-items: flex-start;
    }

    /* Premium Cards */
    .premium-card { border-radius: 18px; border: 1px solid var(--b1); background: var(--bg2); box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
    .card-hd-premium { padding: 20px 28px; border-bottom: 1px solid var(--b1); display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px; }
    .p-title { font-size: 15px; font-weight: 800; color: var(--t1); display: flex; align-items: center; gap: 10px; }
    .p-title i { font-size: 17px; }
    .p-link { font-size: 12.5px; font-weight: 700; color: var(--accent); display: flex; align-items: center; gap: 6px; transition: 0.2s; text-decoration: none; }
    .p-link:hover { gap: 10px; color: var(--accent); }

    /* Info Grid */
    .info-grid-2 { display: grid; grid-template-columns: 1.5fr 1fr; gap: 24px; }
    .info-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
    .info-label { font-size: 10px; font-weight: 700; color: var(--t4); letter-spacing: 0.8px; margin-bottom: 8px; display: block; text-transform: uppercase; }
    .info-value-lg { font-size: 18px; font-weight: 800; color: var(--t1); word-break: break-word; }
    .info-value { font-size: 14px; font-weight: 700; color: var(--t2); word-break: break-word; }
    .info-value.accent { color: var(--accent); }
    .desc-box { background: var(--bg3); padding: 20px 22px; border-radius: 12px; border: 1px solid var(--b1); line-height: 1.7; color: var(--t1); font-size: 14px; white-space: pre-wrap; word-break: break-word; }

    /* Developer Log Component */
    .dev-log-card {
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: 14px;
        padding: 18px 20px;
    }
    .dev-log-hd {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 12px;
        flex-wrap: wrap;
        gap: 8px;
    }
    .dev-log-time {
        font-size: 11.5px;
        color: var(--t3);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .dev-remarks-content {
        font-size: 13.5px;
        color: var(--t1);
        line-height: 1.7;
        background: var(--bg2);
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid var(--b1);
        border-left: 3px solid var(--accent);
        white-space: pre-wrap;
        word-break: break-word;
    }
    .dev-remarks-empty {
        font-size: 12.5px;
        color: var(--t4);
        font-style: italic;
        background: var(--bg2);
        padding: 12px 16px;
        border-radius: 10px;
        border: 1px solid var(--b1);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Action Buttons */
    .call-btn, .email-btn { height: 36px; padding: 0 14px; border-radius: 8px; font-size: 12.5px; font-weight: 700; display: flex; align-items: center; gap: 6px; border: none; cursor: pointer; transition: 0.2s; }
    .call-btn { background: #4f46e5; color: #fff; }
    .email-btn { background: var(--bg3); color: var(--t1); border: 1px solid var(--b1); }
    .call-btn:hover { background: #4338ca; transform: translateY(-2px); }
    .email-btn:hover { background: var(--bg4); color: var(--accent); border-color: var(--accent); transform: translateY(-2px); }

    /* Side Panels */
    .dash-card { border-radius: 18px; border: 1px solid var(--b1); background: var(--bg2); }
    .update-panel-premium { background: var(--bg2); border: 1px solid var(--accent); box-shadow: 0 15px 40px -10px rgba(99,102,241,0.12); }
    .update-panel-premium .p-hd { padding: 20px 24px; padding-bottom: 0px; }
    .form-label-sm { font-size: 10px; font-weight: 700; color: var(--t4); margin-bottom: 8px; display: block; letter-spacing: 0.6px; }

    /* ── FIX DARK & LIGHT MODE SELECT & TEXTAREA ── */
    .form-select-pm {
        height: 46px;
        width: 100%;
        border-radius: 10px;
        border: 1px solid var(--b1);
        padding: 0 14px;
        font-weight: 600;
        background: var(--bg3);
        color: var(--t1);
        font-family: inherit;
        font-size: 13.5px;
        outline: none;
        cursor: pointer;
        transition: var(--transition);
    }
    .form-select-pm:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        background: var(--bg2);
    }
    .form-select-pm option {
        background: var(--bg2);
        color: var(--t1);
    }

    .form-area-pm {
        width: 100%;
        border-radius: 10px;
        border: 1px solid var(--b1);
        padding: 12px 14px;
        font-weight: 500;
        line-height: 1.6;
        background: var(--bg3);
        color: var(--t1);
        font-family: inherit;
        font-size: 13.5px;
        outline: none;
        resize: vertical;
        transition: var(--transition);
    }
    .form-area-pm::placeholder {
        color: var(--t4);
    }
    .form-area-pm:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
        background: var(--bg2);
    }

    .btn-update-pm {
        width: 100%;
        height: 46px;
        background: #6366f1;
        border: none;
        border-radius: 10px;
        color: #fff;
        font-weight: 700;
        font-size: 13.5px;
        cursor: pointer;
        transition: 0.2s;
        margin-top: 15px;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        box-shadow: 0 8px 20px -5px rgba(99,102,241,0.4);
    }
    .btn-update-pm:hover { background: #4f46e5; transform: translateY(-2px); }

    .p-hd-sub { padding: 18px 20px; font-size: 11px; font-weight: 800; color: var(--t1); letter-spacing: 0.5px; }
    .handler-list { display: flex; flex-direction: column; gap: 12px; }
    .h-item { display: flex; align-items: center; gap: 12px; }
    .h-ava { width: 36px; height: 36px; border-radius: 10px; background: rgba(99,102,241,0.12); color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 14px; flex-shrink: 0; }
    .h-name { font-size: 13px; font-weight: 700; color: var(--t1); }
    .h-mail { font-size: 11px; color: var(--t4); }

    /* Timeline */
    .t-line { display: flex; flex-direction: column; gap: 16px; position: relative; padding-left: 6px; }
    .t-line::before { content: ''; position: absolute; left: 10px; top: 5px; bottom: 5px; width: 2px; background: var(--b1); }
    .t-node { display: flex; align-items: flex-start; gap: 16px; position: relative; }
    .t-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--b1); border: 2px solid var(--bg1); position: relative; z-index: 2; margin-top: 4px; flex-shrink: 0; }
    .t-dot.active { background: var(--accent); box-shadow: 0 0 0 4px rgba(99,102,241,0.15); }
    .t-label { font-size: 9px; font-weight: 700; color: var(--t4); letter-spacing: 0.5px; }
    .t-val { font-size: 12.5px; font-weight: 700; color: var(--t2); margin-top: 2px; }

    /* Shared User Style */
    .v-user { display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--t1); font-size: 14px; }
    .v-ava { width: 34px; height: 34px; border-radius: 50%; background: var(--accent); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; flex-shrink: 0; }

    @media (max-width: 992px) {
        .task-view-grid {
            grid-template-columns: 1fr;
        }
        .info-grid-2, .info-grid-3 {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 768px) {
        .page-area { padding: 14px; }
        .p-30, .card-hd-premium, .update-panel-premium .p-hd, .card-body.p-24 {
            padding: 16px !important;
        }
    }
</style>
@endsection
