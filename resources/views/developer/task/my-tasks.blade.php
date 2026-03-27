@extends('developer.layout.app')

@section('title', 'My Completed Tasks')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-my-tasks">
        <!-- Page Header -->
        <div class="page-header" style="margin-bottom: 24px;">
            <div>
                <h1 class="page-title">Activity History</h1>
                <p class="page-desc">Review your milestones and completed deliverables</p>
            </div>
        </div>

        <!-- KPI Cards -->
        <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:14px;margin-bottom:22px;">
            {{-- Total Completed --}}
            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-patch-check-fill" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Done</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $total_completed }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Completed Tasks</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:100%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            {{-- In Process --}}
            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(99,102,241,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-play-circle-fill" style="font-size:17px;color:#6366f1;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(99,102,241,.1);color:#818cf8;">Process</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $total_in_progress }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Tasks In Process</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ ($total_completed + $total_pending + $total_in_progress) > 0 ? ($total_in_progress / ($total_completed + $total_pending + $total_in_progress)) * 100 : 0 }}%;background:#6366f1;border-radius:3px;"></div>
                </div>
            </div>

            {{-- Total Pending --}}
            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-hourglass-split" style="font-size:17px;color:#f59e0b;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(245,158,11,.1);color:#f59e0b;">Pending</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $total_pending }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Pending Tasks</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ ($total_completed + $total_pending + $total_in_progress) > 0 ? ($total_pending / ($total_completed + $total_pending + $total_in_progress)) * 100 : 0 }}%;background:#f59e0b;border-radius:3px;"></div>
                </div>
            </div>
        </div>

        <!-- Filters & Table -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Completed Milestones</div>
                        <div class="card-sub">{{ $tasks->total() }} total records found</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form action="{{ route('developer.tasks.completed') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search" style="width: auto;">
                                <i class="bi bi-calendar3"></i>
                                <input type="date" name="date" value="{{ request('date') }}" style="padding-left: 36px;">
                                <button type="submit" class="btn-primary-solid sm">Filter</button>
                            </div>
                            @if(request()->has('date'))
                                <a href="{{ route('developer.tasks.completed') }}" class="btn-ghost sm">Reset</a>
                            @endif
                        </form>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th style="width: 60px;">SL</th>
                                <th>Project / Domain</th>
                                <th>Task Detail</th>
                                <th>Completion Date</th>
                                <th>Developer Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tasks as $index => $task)
                                <tr class="history-row">
                                    <td>
                                        <span class="mono" style="font-weight:700;color:var(--t4);">{{ str_pad($tasks->firstItem() + $index, 2, '0', STR_PAD_LEFT) }}</span>
                                    </td>
                                    <td>
                                        <div class="lead-cell">
                                            @php
                                                $nameParts = explode('.', $task->project->project_name);
                                                $initials = strtoupper(substr($nameParts[0], 0, 2));
                                            @endphp
                                            <div class="mini-ava" style="background:linear-gradient(135deg,var(--accent),var(--accent2))">{{ $initials }}</div>
                                            <div>
                                                <div class="ln">
                                                    <a href="{{ route('developer.projects.show', $task->project->id) }}" style="color:var(--t1);text-decoration:none;">
                                                        {{ $task->project->project_name }}
                                                    </a>
                                                </div>
                                                <div class="ls">{{ $task->project->company_name ?? $task->project->client_name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ln">{{ $task->title }}</div>
                                        <div class="ls">#TSK-{{ str_pad($task->id, 4, '0', STR_PAD_LEFT) }}</div>
                                    </td>
                                    <td>
                                        <div class="ln" style="display:flex;align-items:center;gap:6px;">
                                            <i class="bi bi-calendar-check" style="color:#10b981;"></i>
                                            {{ $task->updated_at->format('d M Y') }}
                                        </div>
                                        <div class="ls">{{ $task->updated_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="ls" style="max-width:300px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;" title="{{ $task->assignments->first()->remarks }}">
                                            {{ !empty($task->assignments->first()->remarks) ? 'Has Remark' : 'No Remark' }}
                                        </div>
                                    </td>
                                    <td style="text-align: right;">
                                        <div class="row-actions">
                                            <a href="{{ route('developer.tasks.show', $task->id) }}" class="ra-btn" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" style="text-align:center;padding:60px;color:var(--t4);">
                                        <i class="bi bi-journal-x" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                                        No completed activity recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($tasks->hasPages())
                    <div class="table-footer">
                        <span class="tf-info">Showing {{ $tasks->firstItem() }} to {{ $tasks->lastItem() }} of {{ $tasks->total() }} Records</span>
                        <div class="tf-pagination">
                            {{ $tasks->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>

<style>
    .lead-cell { display: flex; align-items: center; gap: 12px; }
    .mini-ava { width: 34px; height: 34px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 800; color: #fff; flex-shrink: 0; }
    .ln { font-size: 13.5px; font-weight: 700; color: var(--t1); line-height: 1.3; }
    .ls { font-size: 11.5px; color: var(--t4); font-weight: 500; margin-top: 2px; }
    
    .data-table th { padding: 14px 12px; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; color: var(--t4); }
    .data-table td { padding: 14px 12px; }

    @media (max-width: 992px) {
        div[style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: 1fr !important; }
    }
</style>
@endsection
