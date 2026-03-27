@extends('developer.layout.app')

@section('title', 'Tasks - ' . $project->project_name)

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-tasks">
        <div class="page-header">
            <div>
                <h1 class="page-title">Project Tasks</h1>
                <p class="page-desc">{{ $project->project_name }} · {{ count($tasks) }} tasks assigned to you</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('developer.projects.show', $project->id) }}" class="btn-ghost sm">
                    <i class="bi bi-arrow-left"></i> Back to Project
                </a>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-12">
                @if(session('success'))
                    <div class="alert alert-success" style="padding:12px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                    </div>
                @endif

                <div class="task-list">
                    @forelse($tasks as $task)
                        <div class="dash-card task-item">
                            <div class="card-body">
                                <div class="task-info">
                                    <div class="task-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                                        <h3 class="task-title" style="margin:0;">{{ $task->title }}</h3>
                                        <span class="status-pill {{ strtolower(str_replace(' ', '-', $task->status)) }}">{{ $task->status }}</span>
                                    </div>
                                    <div class="task-meta" style="display:flex; gap:20px; font-size:12px; color:var(--t4); margin-bottom:16px; font-weight:500;">
                                        <span><i class="bi bi-person-circle"></i> Assigned by: {{ $task->creator->name ?? 'Admin' }}</span>
                                        <span><i class="bi bi-calendar"></i> Added: {{ $task->created_at->format('d M Y') }}</span>
                                    </div>
                                    <div class="task-desc" style="font-size: 14px; color: var(--t2); line-height: 1.6; white-space: pre-wrap; background: var(--bg3); padding: 15px; border-radius: 8px; border-left: 4px solid var(--accent);">
                                        {{ $task->task }}
                                    </div>
                                </div>
                                
                                <div class="task-action-form" style="background: var(--bg4); padding: 20px; border-radius: 12px; height: fit-content;">
                                    <form action="{{ route('developer.tasks.update', $task->id) }}" method="POST">
                                        @csrf
                                        <div class="form-row">
                                            <label class="form-lbl">My Remarks</label>
                                            <textarea name="remarks" class="form-inp" rows="2" placeholder="Update your progress or any blockers...">{{ $task->assignments->first()->remarks }}</textarea>
                                        </div>
                                        <div class="form-row">
                                            <label class="form-lbl">Status Toggle</label>
                                            <select name="status" class="form-inp">
                                                <option value="Pending" {{ $task->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="In Progress" {{ $task->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="Completed" {{ $task->status == 'Completed' ? 'selected' : '' }}>Complete (Mark as Done)</option>
                                            </select>
                                        </div>
                                        <button type="submit" class="btn-primary-solid sm" style="width:100%; justify-content:center; margin-top:10px;">
                                            <i class="bi bi-arrow-repeat"></i> Update Task Status
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="dash-card">
                            <div class="card-body text-center" style="padding:40px;">
                                <i class="bi bi-check2-circle" style="font-size:48px;color:var(--t4);margin-bottom:16px;display:block;"></i>
                                <h3 style="color:var(--t2);">All clear!</h3>
                                <p style="color:var(--t4);">No tasks found for you in this project.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>

<style>
    .task-item { margin-bottom: 20px; border: 1px solid var(--b1); }
    .task-item .card-body { display: grid; grid-template-columns: 1fr 320px; gap: 40px; }
    .status-pill { font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 20px; text-transform: uppercase; }
    .status-pill.pending { background: #fef2f2; color: #991b1b; }
    .status-pill.in-progress { background: #eff6ff; color: #1e40af; }
    .status-pill.completed { background: #ecfdf5; color: #065f46; }

    @media (max-width: 992px) {
        .task-item .card-body { grid-template-columns: 1fr; gap: 20px; }
    }
</style>
@endsection
