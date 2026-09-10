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
                                                <div class="task-title-row">
                                                    <span class="task-badge">#TSK-{{ $task->id }}</span>
                                                    <h3 class="task-title">{{ $task->title }}</h3>
                                                </div>
                                                <div class="task-meta">
                                                    <span class="meta-item"><i class="bi bi-person-circle"></i> Created by: <strong>{{ $task->creator->name ?? 'Admin' }}</strong></span>
                                                    <span class="meta-item"><i class="bi bi-clock"></i> {{ $task->created_at->format('d M, Y h:i A') }}</span>
                                                </div>
                                            </div>
                                            <div class="task-actions-wrap">
                                                @php $sClass = strtolower(str_replace(' ', '-', $task->status)); @endphp
                                                <span class="status-pill {{ $sClass }}">{{ $task->status }}</span>
                                                <a href="{{ route($routePrefix . '.tasks.show', $task->id) }}" class="btn-primary-ghost sm btn-task-view" title="View Task Details">
                                                    <i class="bi bi-eye"></i> Details
                                                </a>
                                            </div>
                                        </div>
                                        
                                        <div class="task-body-section">
                                            <div class="task-desc-container">
                                                <div class="section-label"><i class="bi bi-card-text"></i> Task Description / Requirements</div>
                                                <div class="task-desc-body">{{ trim($task->task) }}</div>
                                            </div>
                                            
                                            @if($task->assignments->count() > 0)
                                                <div class="task-assignments-container">
                                                    <div class="section-label"><i class="bi bi-people-fill"></i> Assigned Developer(s) & Progress</div>
                                                    <div class="dev-assign-list">
                                                        @foreach($task->assignments as $assign)
                                                            <div class="dev-assign-card">
                                                                <div class="dev-assign-hd">
                                                                    <div class="dev-profile">
                                                                        <div class="dev-avatar">
                                                                            {{ strtoupper(substr($assign->developer->name ?? 'D', 0, 1)) }}
                                                                        </div>
                                                                        <div>
                                                                            <span class="dev-name">{{ $assign->developer->name ?? 'Developer' }}</span>
                                                                            <span class="dev-designation">({{ $assign->developer->designation ?? 'Developer' }})</span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="dev-timestamp">
                                                                        <i class="bi bi-clock-history"></i> {{ $assign->updated_at ? $assign->updated_at->format('d M, Y h:i A') : 'No update yet' }}
                                                                    </div>
                                                                </div>
                                                                @if(!empty(trim($assign->remarks)))
                                                                    <div class="dev-remarks-box">
                                                                        <div class="dev-remarks-label"><i class="bi bi-chat-left-dots-fill"></i> Developer Progress / Response:</div>
                                                                        <div class="dev-remarks-text">{{ trim($assign->remarks) }}</div>
                                                                    </div>
                                                                @else
                                                                    <div class="dev-remarks-empty">
                                                                        <i class="bi bi-pencil-square"></i> No notes logged by developer yet.
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
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
            background: var(--bg2);
            border: 1px solid var(--b1);
            border-radius: 14px;
            padding: 20px 22px;
            transition: all 0.2s ease;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .task-item:hover {
            border-color: var(--accent);
            box-shadow: 0 4px 16px rgba(0,0,0,0.06);
        }
        .task-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 16px;
            padding-bottom: 14px;
            border-bottom: 1px solid var(--b1);
            flex-wrap: wrap;
            gap: 12px;
        }
        .task-title-row {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 6px;
            flex-wrap: wrap;
        }
        .task-badge {
            font-size: 11px;
            font-weight: 800;
            color: var(--accent);
            background: rgba(99, 102, 241, 0.12);
            padding: 3px 10px;
            border-radius: 6px;
            letter-spacing: 0.5px;
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
        .task-title {
            font-size: 16px;
            font-weight: 700;
            color: var(--t1);
            margin: 0;
            line-height: 1.3;
        }
        .task-meta {
            display: flex;
            align-items: center;
            gap: 16px;
            font-size: 11.5px;
            color: var(--t4);
            flex-wrap: wrap;
        }
        .task-meta strong {
            color: var(--t2);
        }
        .meta-item {
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .task-actions-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .btn-task-view {
            padding: 5px 12px;
            font-size: 12px;
            font-weight: 600;
            height: 30px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 8px;
        }

        .section-label {
            font-size: 10.5px;
            font-weight: 800;
            color: var(--t4);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .task-desc-container {
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 16px;
        }
        .task-desc-body {
            font-size: 13.5px;
            color: var(--t1);
            line-height: 1.65;
            white-space: pre-wrap;
            word-break: break-word;
            text-align: left;
        }

        .task-assignments-container {
            margin-top: 10px;
        }
        .dev-assign-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }
        .dev-assign-card {
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: 10px;
            padding: 14px 16px;
        }
        .dev-assign-hd {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 8px;
        }
        .dev-profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .dev-avatar {
            width: 28px;
            height: 28px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #06b6d4);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11.5px;
            font-weight: 700;
            flex-shrink: 0;
        }
        .dev-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--t1);
        }
        .dev-designation {
            font-size: 11.5px;
            color: var(--t4);
            margin-left: 4px;
        }
        .dev-timestamp {
            font-size: 11px;
            color: var(--t4);
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dev-remarks-box {
            margin-top: 10px;
            background: var(--bg2);
            border: 1px solid var(--b1);
            border-left: 3.5px solid var(--accent);
            border-radius: 8px;
            padding: 12px 14px;
            text-align: left;
        }
        .dev-remarks-label {
            font-size: 10px;
            font-weight: 800;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 5px;
            margin-bottom: 6px;
        }
        .dev-remarks-text {
            font-size: 13px;
            color: var(--t1);
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
            text-align: left;
            margin: 0;
            padding: 0;
        }
        .dev-remarks-empty {
            margin-top: 10px;
            font-size: 12px;
            color: var(--t4);
            font-style: italic;
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 8px 12px;
            background: var(--bg2);
            border-radius: 8px;
            border: 1px dashed var(--b1);
        }

        .status-pill {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 20px;
            letter-spacing: 0.3px;
        }
        .status-pill.pending { background: rgba(245, 158, 11, .12); color: #f59e0b; border: 1px solid rgba(245,158,11,0.25); }
        .status-pill.in-progress { background: rgba(99, 102, 241, .12); color: #6366f1; border: 1px solid rgba(99,102,241,0.25); }
        .status-pill.completed { background: rgba(16, 185, 129, .12); color: #10b981; border: 1px solid rgba(16,185,129,0.25); }
    </style>
    @include('admin.project._multiselect_assets')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            updateMs('taskAssignWrap');
        });
    </script>
@endsection
