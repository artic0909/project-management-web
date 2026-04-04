@extends('admin.layout.app')

@section('title', 'Task Details - #' . $task->id)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-task-view">
        
        <!-- Header Section -->
        <div class="page-header-premium" style="margin-bottom: 30px;">
            <div class="h-top-labels" style="display:flex;gap:10px;margin-bottom:12px;">
                <span class="h-badge accent"><i class="bi bi-circle-fill" style="font-size:6px;margin-right:6px;vertical-align:middle;"></i>#TSK-{{ $task->id }}</span>
                <span class="h-badge gray">Created at: {{ $task->created_at->format('Y-m-d') }}</span>
            </div>
            
            <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                <div>
                    <h1 style="font-size:32px; font-weight:800; color:var(--t1); margin:0;">{{ $task->title }}</h1>
                    <div style="display:flex; gap:15px; margin-top:10px;">
                        <span class="h-pill">Status: {{ $task->status }}</span>
                        <span class="h-pill green">Priority: High</span>
                    </div>
                </div>
                <a href="{{ route($routePrefix . '.tasks.completed') }}" class="btn-primary-ghost sm" style="height:40px; padding:0 20px;">
                    <i class="bi bi-arrow-left"></i> <span>Back to List</span>
                </a>
            </div>
        </div>

        <div class="dash-grid" style="display:grid; grid-template-columns: 1fr 400px; gap:30px; align-items: flex-start;">
            
            {{-- Left Column: Details --}}
            <div style="display:flex; flex-direction:column; gap:30px;">
                
                {{-- Client & Identity --}}
                <div class="dash-card premium-card">
                    <div class="card-hd-premium">
                        <div class="p-title"><i class="bi bi-info-circle-fill"></i> Assignment & Identity</div>
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
                                <label class="info-label">CREATED BY</label>
                                <div class="v-user">
                                    <div class="v-ava">{{ strtoupper(substr($task->creator->name,0,1)) }}</div>
                                    <span>{{ $task->creator->name }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="info-group mt-24">
                            <label class="info-label">INSTRUCTIONS / REQUIREMENTS</label>
                            <div class="desc-box">{{ $task->task }}</div>
                        </div>
                    </div>
                </div>

                {{-- Related Project Detail --}}
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
            </div>

            {{-- Right Column: Updates & Side Info --}}
            <div style="display:flex; flex-direction:column; gap:30px; position:sticky; top:30px;">
                
                {{-- Quick Update Panel --}}
                <div class="dash-card update-panel-premium">
                    <div class="p-hd">
                        <div class="p-title"><i class="bi bi-lightning-charge-fill"></i> Quick Update</div>
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
                            <div class="form-row mt-15">
                                <label class="form-label-sm">LOG PROGRESS / NOTES</label>
                                <textarea name="remarks" class="form-area-pm" rows="5" placeholder="Add a quick note...">{{ $task->assignments->first()->remarks ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="btn-update-pm">Update Now</button>
                        </form>
                    </div>
                </div>

                {{-- Project Handlers --}}
                <div class="dash-card handler-panel">
                    <div class="p-hd-sub">IDENTIFIED HANDLERS</div>
                    <div class="card-body p-20" style="padding-top:0;">
                         <div class="handler-list">
                            @foreach($task->assignments as $assign)
                            <div class="h-item">
                                <div class="h-ava">{{ strtoupper(substr($assign->developer->name,0,1)) }}</div>
                                <div class="h-info">
                                    <div class="h-name">{{ $assign->developer->name }}</div>
                                    <div class="h-mail">{{ $assign->developer->email }}</div>
                                </div>
                            </div>
                            @endforeach
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
                                    <div class="t-val">{{ $task->created_at->format('d M, Y') }}</div>
                                </div>
                            </div>
                            <div class="t-node">
                                <div class="t-dot active"></div>
                                <div class="t-content">
                                    <div class="t-label">LAST UPDATED</div>
                                    <div class="t-val">{{ $task->updated_at->format('d M, Y') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
</main>

        </div>
    </div>
</main>

<style>
    .page-area { padding: 30px; }
    .p-30 { padding: 30px !important; }
    .p-24 { padding: 24px !important; }
    .p-20 { padding: 20px !important; }
    .mt-24 { margin-top: 24px !important; }
    .mt-15 { margin-top: 15px !important; }

    /* Header Premium */
    .h-badge { padding: 4px 12px; border-radius: 6px; font-size: 11px; font-weight: 700; border: 1px solid transparent; }
    .h-badge.accent { background: rgba(99,102,241,0.08); color: var(--accent); border-color: rgba(99,102,241,0.15); }
    .h-badge.gray { background: rgba(0,0,0,0.03); color: var(--t4); border-color: rgba(0,0,0,0.05); }
    .h-pill { font-size: 13px; font-weight: 600; color: var(--t3); display: flex; align-items: center; gap: 8px; }
    .h-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: var(--accent); }
    .h-pill.green::before { background: #10b981; }

    /* Premium Cards */
    .premium-card { border-radius: 20px; border: 1px solid var(--b1); background: var(--bg2); box-shadow: 0 10px 30px -15px rgba(0,0,0,0.05); }
    .card-hd-premium { padding: 24px 30px; border-bottom: 1px solid var(--b1); display: flex; justify-content: space-between; align-items: center; }
    .p-title { font-size: 15px; font-weight: 800; color: var(--t1); display: flex; align-items: center; gap: 12px; }
    .p-title i { font-size: 18px; color: var(--accent); }
    .p-link { font-size: 12px; font-weight: 700; color: var(--accent); display: flex; align-items: center; gap: 6px; transition: 0.2s; }
    .p-link:hover { gap: 10px; }

    /* Info Grid */
    .info-grid-2 { display: grid; grid-template-columns: 1.5fr 1fr; gap: 30px; }
    .info-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; }
    .info-label { font-size: 10px; font-weight: 700; color: var(--t4); letter-spacing: 0.8px; margin-bottom: 10px; display: block; }
    .info-value-lg { font-size: 20px; font-weight: 800; color: var(--t1); }
    .info-value { font-size: 14px; font-weight: 700; color: var(--t2); }
    .info-value.accent { color: var(--accent); }
    .desc-box { background: var(--bg3); padding: 24px; border-radius: 14px; border: 1px solid var(--b1); line-height: 1.8; color: var(--t2); font-size: 14.5px; white-space: pre-wrap; }

    /* Action Buttons */
    .call-btn, .email-btn { height: 38px; padding: 0 16px; border-radius: 10px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; border: none; cursor: pointer; transition: 0.2s; }
    .call-btn { background: #4f46e5; color: #fff; }
    .email-btn { background: #ffffff; color: var(--t1); border: 1px solid var(--b1); }
    .call-btn:hover { background: #4338ca; transform: translateY(-2px); }
    .email-btn:hover { background: var(--bg3); transform: translateY(-2px); }

    /* Side Panels */
    .dash-card { border-radius: 20px; border: 1px solid var(--b1); background: var(--bg2); }
    .update-panel-premium { background: var(--bg2); border: 1px solid var(--accent); box-shadow: 0 15px 40px -10px rgba(99,102,241,0.15); }
    .update-panel-premium .p-hd { padding: 24px; padding-bottom: 0px; }
    .form-label-sm { font-size: 10px; font-weight: 700; color: var(--t4); margin-bottom: 8px; display: block; }
    .form-select-pm { height: 48px; width: 100%; border-radius: 12px; border: 1px solid var(--b1); padding: 0 15px; font-weight: 600; }
    .form-area-pm { width: 100%; border-radius: 12px; border: 1px solid var(--b1); padding: 15px; font-weight: 500; line-height: 1.6; }
    .btn-update-pm { width: 100%; height: 48px; background: #6366f1; border: none; border-radius: 12px; color: #fff; font-weight: 800; font-size: 14px; cursor: pointer; transition: 0.2s; margin-top:15px; box-shadow: 0 8px 20px -5px rgba(99,102,241,0.4); }
    .btn-update-pm:hover { background: #4f46e5; transform: translateY(-2px); }

    .p-hd-sub { padding: 20px 24px; font-size: 11px; font-weight: 800; color: var(--t1); letter-spacing: 0.5px; }
    .handler-list { display: flex; flex-direction: column; gap: 15px; }
    .h-item { display: flex; align-items: center; gap: 12px; }
    .h-ava { width: 40px; height: 40px; border-radius: 12px; background: rgba(99,102,241,0.1); color: var(--accent); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 15px; }
    .h-name { font-size: 13.5px; font-weight: 700; color: var(--t1); }
    .h-mail { font-size: 11px; color: var(--t4); }

    /* Timeline */
    .t-line { display: flex; flex-direction: column; gap: 20px; position: relative; padding-left: 10px; }
    .t-line::before { content: ''; position: absolute; left: 14px; top: 5px; bottom: 5px; width: 2px; background: var(--b1); }
    .t-node { display: flex; align-items: flex-start; gap: 20px; position: relative; }
    .t-dot { width: 10px; height: 10px; border-radius: 50%; background: var(--b1); border: 2px solid var(--bg1); position: relative; z-index: 2; margin-top: 5px; }
    .t-dot.active { background: var(--accent); box-shadow: 0 0 0 4px rgba(99,102,241,0.1); }
    .t-label { font-size: 9px; font-weight: 700; color: var(--t4); letter-spacing: 0.5px; }
    .t-val { font-size: 13px; font-weight: 700; color: var(--t2); }

    /* Shared User Style */
    .v-user { display: flex; align-items: center; gap: 10px; font-weight: 700; color: var(--t1); font-size: 14px; }
    .v-ava { width: 34px; height: 34px; border-radius: 50%; background: var(--accent); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 12px; }
</style>
@endsection
