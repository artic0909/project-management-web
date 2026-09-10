@extends('admin.layout.app')

@section('title', 'Project Tasks - ' . $project->project_name)

@section('content')
    <main class="page-area" id="pageArea">
        <div class="page" id="page-project-tasks">
            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Project Tasks: {{ $project->project_name }}</h1>
                    <p class="page-desc">{{ $project->company_name ?? 'Client: ' . $project->client_name }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route($routePrefix . '.projects.index') }}" class="btn-ghost sm">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="dash-grid">
                <!-- Left Column: Task Form -->
                <div class="span-12">
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-plus-circle-fill" style="color:var(--accent);margin-right:8px;"></i>Create New Task</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route($routePrefix . '.projects.tasks.store', $project->id) }}" method="POST">
                                @csrf
                                <div class="form-row">
                                    <label class="form-lbl">Task Title</label>
                                    <input type="text" name="title" class="form-inp" placeholder="Brief title of the task" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Task Description</label>
                                    <textarea name="task" class="form-inp" rows="4" placeholder="Detailed instructions..." required></textarea>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Assign to Developer(s)</label>
                                    <div class="ms-wrap" id="taskAssignWrap">
                                        <div class="ms-trigger" onclick="toggleMs('taskAssignWrap')">
                                            <div class="ms-pills" id="taskAssignPills">
                                                <span class="ms-placeholder">Select developers…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="taskAssignDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'taskAssignDropdown')">
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($developers as $index => $dev)
                                                    @php
                                                        $words = explode(' ', $dev->name);
                                                        $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                                    @endphp
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="developer_ids[]" value="{{ $dev->id }}" 
                                                            data-name="{{ $dev->name }}" data-initials="{{ $initials }}"
                                                            {{ in_array($dev->id, $project->developers->pluck('id')->toArray()) ? 'checked' : '' }}
                                                            onchange="updateMs('taskAssignWrap')">
                                                        <span class="ms-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ $initials }}</span>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $dev->name }}</span>
                                                            <span style="font-size:11px;color:var(--t3);">{{ $dev->designation }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Internal Remarks (Optional)</label>
                                    <textarea name="remarks" class="form-inp" rows="2" placeholder="Private notes for the team..."></textarea>
                                </div>
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:12px;"> Create & Assign Task </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Task History -->
                <div class="span-12">
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-history" style="color:#ec4899;margin-right:8px;"></i>Task History</div>
                            <div class="card-sub">{{ $project->tasks->count() }} tasks recorded</div>
                        </div>
                        <div class="card-body">
                            <div class="task-timeline">
                                @forelse($project->tasks()->with(['assignments.developer', 'creator'])->latest()->get() as $task)
                                    <div class="task-item">
                                        <div class="task-header">
                                            <div class="task-main">
                                                <div style="display:flex; align-items:center; gap:8px; margin-bottom:4px; flex-wrap:wrap;">
                                                    <span style="font-size:11px; font-weight:700; color:var(--accent); background:rgba(99,102,241,0.1); padding:2px 8px; border-radius:6px;">#TSK-{{ $task->id }}</span>
                                                    <h3 class="task-title" style="margin:0;">{{ $task->title }}</h3>
                                                </div>
                                                <div class="task-meta">
                                                    <span class="meta-item"><i class="bi bi-person-circle"></i> Created by: {{ $task->creator->name ?? 'Admin' }}</span>
                                                    <span class="meta-item"><i class="bi bi-clock"></i> {{ $task->created_at->format('d M, Y h:i A') }}</span>
                                                </div>
                                            </div>
                                            <div style="display:flex; align-items:center; gap:8px;">
                                                @php $sClass = strtolower(str_replace(' ', '-', $task->status)); @endphp
                                                <span class="status-pill {{ $sClass }}">{{ $task->status }}</span>
                                                <a href="{{ route($routePrefix . '.tasks.show', $task->id) }}" class="btn-primary-ghost sm" style="padding:4px 10px; font-size:12px; height:28px; display:inline-flex; align-items:center; gap:5px;" title="View Task Details">
                                                    <i class="bi bi-eye"></i> Details
                                                </a>
                                            </div>
                                        </div>
                                        <div class="task-body">
                                            <p class="task-desc" style="white-space: pre-wrap; margin-bottom:14px;">{{ $task->task }}</p>
                                            
                                            @if($task->assignments->count() > 0)
                                                <div class="task-assignments" style="display:flex; flex-direction:column; gap:10px;">
                                                    @foreach($task->assignments as $assign)
                                                        <div style="background:var(--bg2); border:1px solid var(--b1); border-radius:10px; padding:12px 14px;">
                                                            <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">
                                                                <div style="display:flex; align-items:center; gap:8px;">
                                                                    <div style="width:26px; height:26px; border-radius:50%; background:var(--accent); color:#fff; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:700;">
                                                                        {{ strtoupper(substr($assign->developer->name ?? 'D',0,1)) }}
                                                                    </div>
                                                                    <div>
                                                                        <strong style="color:var(--t1); font-size:13px;">{{ $assign->developer->name ?? 'Developer' }}</strong>
                                                                        <span style="font-size:11px; color:var(--t4); margin-left:4px;">({{ $assign->developer->designation ?? 'Developer' }})</span>
                                                                    </div>
                                                                </div>
                                                                <span style="font-size:11px; color:var(--t4);"><i class="bi bi-clock"></i> {{ $assign->updated_at ? $assign->updated_at->format('d M, Y h:i A') : '' }}</span>
                                                            </div>
                                                            @if(!empty(trim($assign->remarks)))
                                                                <div style="margin-top:8px; font-size:13px; color:var(--t1); background:var(--bg3); padding:10px 14px; border-radius:8px; border-left:3px solid var(--accent); line-height:1.5; white-space:pre-wrap;">
                                                                    <span style="font-size:10.5px; font-weight:700; color:var(--accent); display:block; margin-bottom:4px; text-transform:uppercase;">Developer Progress / Response:</span>
                                                                    {{ $assign->remarks }}
                                                                </div>
                                                            @else
                                                                <div style="margin-top:6px; font-size:12px; color:var(--t4); font-style:italic;">
                                                                    No notes logged by developer yet.
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @empty
                                    <div class="timeline-empty" style="text-align:center;padding:40px;color:var(--t4);">
                                        <i class="bi bi-list-check" style="font-size:32px;display:block;margin-bottom:10px;opacity:0.3;"></i>
                                        No tasks created for this project yet.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <style>
        .task-timeline {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        .task-item {
            background: var(--bg1);
            border: 1px solid var(--b1);
            border-radius: 12px;
            padding: 16px;
            transition: all 0.2s;
        }
        .task-item:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
            padding-bottom: 12px;
            border-bottom: 1px solid var(--bg3);
        }
        .task-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--t1);
            margin: 0 0 4px 0;
        }
        .task-meta {
            display: flex;
            gap: 12px;
            font-size: 11px;
            color: var(--t4);
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .task-desc {
            font-size: 13px;
            color: var(--t2);
            line-height: 1.5;
            margin-bottom: 12px;
        }
        .task-assignments {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .assignment-pill {
            background: var(--bg3);
            border: 1px solid var(--b1);
            padding: 4px 12px;
            border-radius: 8px;
            font-size: 12px;
            color: var(--t1);
        }
        .assign-remarks {
            color: var(--t3);
            font-style: italic;
        }

        .status-pill {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
        }
        .status-pill.pending { background: rgba(245, 158, 11, .1); color: #f59e0b; border: 1px solid rgba(245,158,11,0.2); }
        .status-pill.in-progress { background: rgba(99, 102, 241, .1); color: #6366f1; border: 1px solid rgba(99,102,241,0.2); }
        .status-pill.completed { background: rgba(16, 185, 129, .1); color: #10b981; border: 1px solid rgba(16,185,129,0.2); }
    </style>
    @include('admin.project._multiselect_assets')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateMs('taskAssignWrap');
        });
    </script>
@endsection
