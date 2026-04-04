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
        <div class="stats-row" style="display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:30px;">
            <div class="dash-card {{ request('status') == 'Pending' ? 'active' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'Pending', 'project_id' => request('project_id')]) }}'" 
                style="cursor:pointer; position:relative; overflow:hidden; border:1px solid rgba(245,158,11,0.2); box-shadow:0 10px 20px -10px rgba(245,158,11,0.15);">
                <div style="display:flex;align-items:center;gap:15px; position:relative; z-index:2;">
                    <div style="width:52px;height:52px;border-radius:16px;background:rgba(245,158,11,0.1);display:flex;align-items:center;justify-content:center;color:#f59e0b; flex-shrink:0;">
                        <i class="bi bi-clock-history" style="font-size:24px;"></i>
                    </div>
                    <div>
                        <div style="font-size:30px;font-weight:800;color:var(--t1);line-height:1.2;">{{ $total_pending }}</div>
                        <div style="font-size:12.5px;color:var(--t3);font-weight:700;letter-spacing:0.4px;text-transform:uppercase;">Pending Tasks</div>
                    </div>
                </div>
            </div>

            <div class="dash-card {{ request('status') == 'In Progress' ? 'active' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'In Progress', 'project_id' => request('project_id')]) }}'" 
                style="cursor:pointer; position:relative; overflow:hidden; border:1px solid rgba(99,102,241,0.2); box-shadow:0 10px 20px -10px rgba(99,102,241,0.15);">
                <div style="display:flex;align-items:center;gap:15px; position:relative; z-index:2;">
                    <div style="width:52px;height:52px;border-radius:16px;background:rgba(99,102,241,0.1);display:flex;align-items:center;justify-content:center;color:#6366f1; flex-shrink:0;">
                        <i class="bi bi-play-circle-fill" style="font-size:24px;"></i>
                    </div>
                    <div>
                        <div style="font-size:30px;font-weight:800;color:var(--t1);line-height:1.2;">{{ $total_in_progress }}</div>
                        <div style="font-size:12.5px;color:var(--t3);font-weight:700;letter-spacing:0.4px;text-transform:uppercase;">In Progress</div>
                    </div>
                </div>
            </div>

            <div class="dash-card {{ request('status') == 'Completed' ? 'active' : '' }}" onclick="window.location.href='{{ route($routePrefix . '.tasks.completed', ['status'=>'Completed', 'project_id' => request('project_id')]) }}'" 
                style="cursor:pointer; position:relative; overflow:hidden; border:1px solid rgba(16,185,129,0.2); box-shadow:0 10px 20px -10px rgba(16,185,129,0.15);">
                <div style="display:flex;align-items:center;gap:15px; position:relative; z-index:2;">
                    <div style="width:52px;height:52px;border-radius:16px;background:rgba(16,185,129,0.1);display:flex;align-items:center;justify-content:center;color:#10b981; flex-shrink:0;">
                        <i class="bi bi-check-circle-fill" style="font-size:24px;"></i>
                    </div>
                    <div>
                        <div style="font-size:30px;font-weight:800;color:var(--t1);line-height:1.2;">{{ $total_completed }}</div>
                        <div style="font-size:12.5px;color:var(--t3);font-weight:700;letter-spacing:0.4px;text-transform:uppercase;">Fulfilled Tasks</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="dash-card" style="padding:0; overflow:visible;">
            <div class="table-wrap" style="overflow-x:auto;">
                <table class="dash-table">
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
                <div class="tf-pagination">{{ $tasks->links() }}</div>
            </div>
            @endif
        </div>
    </div>
</main>


<style>
    .page-area { padding: 30px; }
    .dash-table { width: 100%; border-collapse: separate; border-spacing: 0; table-layout: auto !important; }
    .dash-table th { 
        padding: 18px 24px; 
        background: var(--bg2); 
        color: var(--t3); 
        font-weight: 700; 
        font-size: 11px; 
        text-transform: uppercase; 
        letter-spacing: 0.8px;
        border-bottom: 2px solid var(--b1);
        text-align: left;
    }
    .dash-table td { 
        padding: 20px 24px; 
        vertical-align: middle; 
        border-bottom: 1px solid var(--b1);
        background: var(--bg1);
        color: var(--t2);
    }
    .dash-table tr:last-child td { border-bottom: none; }
    .dash-table tr:hover td { background: var(--bg2); }
    
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
