@extends('developer.layout.app')

@section('title', 'Task Details')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-task-show">
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                    <span class="status-pill completed">Completed</span>
                    <span style="font-size:12px;color:var(--t4);font-weight:500;">Project: {{ $task->project->project_name }}</span>
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
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-info-circle-fill" style="color:var(--accent);margin-right:8px;"></i>Task Instructions</div>
                    </div>
                    <div class="card-body">
                        <div class="task-desc" style="font-size: 15px; color: var(--t2); line-height: 1.8; white-space: pre-wrap; background: var(--bg3); padding: 25px; border-radius: 12px; border-left: 5px solid var(--accent);">
                            {{ $task->task }}
                        </div>
                        
                        <div style="margin-top:24px;">
                            <label style="display:block; font-size:11px; text-transform:uppercase; color:var(--t4); font-weight:700; margin-bottom:8px;">My Final Remarks</label>
                            <div style="padding:15px; background:var(--bg4); border-radius:8px; border:1px solid var(--b1); color:var(--t2); font-size:14px; line-height:1.6;">
                                {{ $task->assignments->where('developer_id', auth()->guard('developer')->id())->first()->remarks ?? 'No remarks provided.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="span-4">
                <div class="dash-card">
                    <div class="card-head">
                         <div class="card-title">Completion Details</div>
                    </div>
                    <div class="card-body">
                         <div class="kv-item"><label>Assigned By</label><div>{{ $task->creator->name ?? 'Admin' }}</div></div>
                         <div class="kv-item" style="margin-top:16px;"><label>Assigned On</label><div>{{ $task->created_at->format('d M Y') }}</div></div>
                         <div class="kv-item" style="margin-top:16px;"><label>Completed On</label><div>{{ $task->updated_at->format('d M Y, h:i A') }}</div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .kv-item label { display: block; font-size: 11px; text-transform: uppercase; color: var(--t4); font-weight: 700; margin-bottom: 4px; }
    .kv-item div { font-size: 14px; color: var(--t2); font-weight: 500; }
    .status-pill { font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 20px; text-transform: uppercase; }
    .status-pill.completed { background: #ecfdf5; color: #065f46; }
</style>
@endsection
