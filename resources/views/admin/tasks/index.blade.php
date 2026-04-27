@extends('admin.layout.app')

@section('title', 'My Assigned Tasks')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-my-tasks">
        
        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">My Assigned Tasks</h1>
                <p class="page-desc">Track and manage tasks across your projects</p>
            </div>
            <div class="header-actions">
                <form action="{{ route($routePrefix . '.tasks.completed') }}" method="GET" style="display:flex;gap:10px;">
                    @if(request('project_id'))
                        <input type="hidden" name="project_id" value="{{ request('project_id') }}">
                    @endif
                    <input type="date" name="date" class="form-inp sm" value="{{ request('date') }}" onchange="this.form.submit()">
                    <select name="status" class="form-inp sm" onchange="this.form.submit()">
                        <option value="">All Statuses</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="Completed" {{ request('status') == 'Completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </form>
            </div>
        </div>

        {{-- KPI CARDS --}}
        <div class="kpi-grid" style="grid-template-columns: repeat(3, 1fr); margin-bottom: 24px;">
            <div class="kpi-card {{ request('status') == 'Pending' ? 'active-kpi' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'Pending', 'project_id' => request('project_id')]) }}'" 
                style="--kpi-accent:#f59e0b; cursor:pointer;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(245,158,11,0.15);color:#f59e0b"><i class="bi bi-clock-history"></i></div>
                </div>
                <div class="kpi-value">{{ $total_pending }}</div>
                <div class="kpi-label">Pending Tasks</div>
            </div>

            <div class="kpi-card {{ request('status') == 'In Progress' ? 'active-kpi' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'In Progress', 'project_id' => request('project_id')]) }}'" 
                style="--kpi-accent:#6366f1; cursor:pointer;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(99,102,241,0.15);color:#6366f1"><i class="bi bi-play-circle-fill"></i></div>
                </div>
                <div class="kpi-value">{{ $total_in_progress }}</div>
                <div class="kpi-label">In Progress</div>
            </div>

            <div class="kpi-card {{ request('status') == 'Completed' ? 'active-kpi' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'Completed', 'project_id' => request('project_id')]) }}'" 
                style="--kpi-accent:#10b981; cursor:pointer;">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(16,185,129,0.15);color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
                </div>
                <div class="kpi-value">{{ $total_completed }}</div>
                <div class="kpi-label">Fulfilled Tasks</div>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="dash-card">
            <div class="card-body">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="min-width:60px;">SL.</th>
                                <th style="min-width:100px;">Task ID</th>
                                <th style="min-width:200px;">Project Details</th>
                                <th style="min-width:250px;">Task Information</th>
                                <th style="min-width:120px;">Status</th>
                                <th style="min-width:150px;">Assigned Date</th>
                                <th style="min-width:100px;text-align:right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $task)
                                <tr>
                                    <td>
                                        <div style="font-size:12px; font-weight:600; color:var(--t4);">{{ $tasks->firstItem() + $loop->index }}</div>
                                    </td>
                                    <td>
                                        <div class="ls" style="font-size:12px; font-weight:700; color:var(--accent);">#TSK-{{ $task->id }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:2px;">
                                            <a href="{{ route($routePrefix . '.projects.show', $task->project->id) }}" class="lead-name" style="font-size:13.5px;">{{ $task->project->project_name }}</a>
                                            <span style="font-size:11px;color:var(--t3);font-weight:500;"><i class="bi bi-building"></i> {{ $task->project->company_name ?? $task->project->client_name }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display:flex;flex-direction:column;gap:5px;">
                                            <span style="font-weight:700;color:var(--t1);font-size:13px;">{{ $task->title }}</span>
                                            <p style="font-size:11.5px;color:var(--t3);margin:0;max-width:320px;line-height:1.4;white-space:normal;">{{ Str::limit($task->task, 80) }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        @php $sClass = strtolower(str_replace(' ', '-', $task->status)); @endphp
                                        <span class="status-pill {{ $sClass }}">{{ $task->status }}</span>
                                    </td>
                                    <td>
                                        <div style="font-size:12px;font-weight:600;color:var(--t2);">{{ $task->created_at->format('d M, Y') }}</div>
                                        <div style="font-size:10px;color:var(--t4);font-weight:500;">{{ $task->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div style="display:flex;gap:6px;justify-content:flex-end;">
                                            <a href="{{ route($routePrefix . '.tasks.show', $task->id) }}" class="ra-btn" title="View Details" style="background:rgba(236,72,153,0.08);color:#ec4899;"><i class="bi bi-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align:center;padding:100px 0;background:var(--bg2);">
                                        <div style="opacity:0.3;margin-bottom:15px;">
                                            <i class="bi bi-inbox" style="font-size:48px;"></i>
                                        </div>
                                        <div style="font-weight:700;color:var(--t4);font-size:15px;">No active tasks found</div>
                                        <div style="font-size:12px;color:var(--t4);margin-top:5px;">Check back later or adjust your filters</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                <div class="table-footer">
                    <div class="tf-info">Showing {{ $tasks->firstItem() }} - {{ $tasks->lastItem() }} of {{ $tasks->total() }} tasks</div>
                    <div class="tf-pagination">{{ $tasks->links('admin.includes.pagination') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</main>


<style>
    .page-area { padding: 24px; }
    
    .active-kpi {
        border-color: var(--kpi-accent) !important;
        background: var(--bg3) !important;
        box-shadow: 0 0 0 1px var(--kpi-accent), 0 8px 24px rgba(0, 0, 0, .12) !important;
    }
    .active-kpi::after { transform: scaleX(1) !important; }
    
    .status-pill { 
        font-size: 10.5px; 
        font-weight: 700; 
        padding: 3px 12px; 
        border-radius: 20px; 
        display: inline-flex; 
        align-items: center; 
        gap: 5px;
        white-space: nowrap; 
    }
    .status-pill.pending { background: rgba(245, 158, 11, .1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
    .status-pill.in-progress { background: rgba(99, 102, 241, .1); color: #6366f1; border: 1px solid rgba(99,102,241,0.2); }
    .status-pill.completed { background: rgba(16, 185, 129, .1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }

    .ra-btn {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .ra-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
</style>
@endsection
