@extends('developer.layout.app')

@section('title', 'My Completed Tasks')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-my-tasks">
        <div class="page-header">
            <div>
                <h1 class="page-title">My Completed Tasks</h1>
                <p class="page-desc">History of all tasks successfully completed by you</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-12">
                <div class="dash-card">
                    <div class="card-body" style="padding:0;">
                         <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Task Title</th>
                                        <th>Completed On</th>
                                        <th>My Remarks</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tasks as $task)
                                        <tr>
                                            <td style="font-weight:600;color:var(--t1);">
                                                <a href="{{ route('developer.projects.show', $task->project->id) }}" style="color:var(--accent)">
                                                    {{ $task->project->project_name }}
                                                </a>
                                            </td>
                                            <td>{{ $task->title }}</td>
                                            <td><span class="text-xs text-muted">{{ $task->updated_at->format('d M Y, h:i A') }}</span></td>
                                            <td style="max-width:300px;"><div class="truncate-text" title="{{ $task->assignments->first()->remarks }}">{{ $task->assignments->first()->remarks ?? 'N/A' }}</div></td>
                                            <td>
                                                <a href="{{ route('developer.tasks.show', $task->id) }}" class="btn-ghost sm" title="View Task Details">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">No completed tasks yet.</td>
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
    .truncate-text { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px; color: var(--t3); }
    .text-xs { font-size: 12px; }
    .text-muted { color: var(--t4); }
</style>
@endsection
