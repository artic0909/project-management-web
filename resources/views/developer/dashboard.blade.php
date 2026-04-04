@extends('admin.layout.app')

@section('title', 'Developer Dashboard')

@section('content')


    <!-- ═══ PAGE CONTENT AREA ═══ -->
    <main class="page-area" id="pageArea">


        <!-- Sales DASHBOARD PAGE -->
        <div class="page" id="page-dashboard">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Developer Dashboard</h1>
                    <p class="page-desc">My workspace · {{ auth()->guard('developer')->user()->name }} ·
                        {{ auth()->guard('developer')->user()->designation }}</p>
                </div>

                <!-- Filters -->
                <div class="page-actions">
                    <form action="{{ route('developer.dashboard') }}" method="GET" class="filter-form" id="filterForm">
                        <div class="filter-group">
                            <select name="month" class="filter-select" onchange="this.form.submit()">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $m, 1)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="filter-select" onchange="this.form.submit()">
                                @foreach ($availableYears as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <style>
                .filter-form {
                    display: flex;
                    gap: 12px;
                    align-items: center;
                }

                .filter-group {
                    display: flex;
                    gap: 8px;
                    background: var(--b2);
                    padding: 4px;
                    border-radius: 10px;
                    border: 1px solid var(--b3);
                }

                .filter-select {
                    background: transparent;
                    color: var(--t1);
                    border: none;
                    font-size: 13px;
                    font-weight: 600;
                    padding: 6px 12px;
                    border-radius: 6px;
                    cursor: pointer;
                    outline: none;
                    transition: 0.2s;
                }

                .filter-select:hover {
                    background: var(--b3);
                }

                .filter-select option {
                    background: var(--b1);
                    color: var(--t1);
                }
            </style>


            <!-- KPI STRIP -->
            <div class="kpi-grid">

                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i
                                class="bi bi-kanban-fill"></i></div>
                    </div>
                    <div class="kpi-value">{{ $totalRunningProjects }}</div>
                    <div class="kpi-label">Total Running Projects</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-check-all"></i></div>
                    </div>
                    <div class="kpi-value">{{ $totalCompletedProjects }}</div>
                    <div class="kpi-label">Total Completed Projects</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#f59e0b">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i
                                class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="kpi-value">{{ $pendingTasks }}</div>
                    <div class="kpi-label">Pending Tasks</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-check2-square"></i></div>
                    </div>
                    <div class="kpi-value">{{ $completedTasks }}</div>
                    <div class="kpi-label">Completed Tasks</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#8b5cf6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i
                                class="bi bi-calendar-event"></i></div>
                    </div>
                    <div class="kpi-value">{{ $pendingMeetings }}</div>
                    <div class="kpi-label">Pending Meetings</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-calendar-check"></i></div>
                    </div>
                    <div class="kpi-value">{{ $completedMeetings }}</div>
                    <div class="kpi-label">Completed Meetings</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#06b6d4">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i
                                class="bi bi-clock-fill"></i></div>
                    </div>
                    <div class="kpi-value">168h</div>
                    <div class="kpi-label">Hours Logged</div>
                </div>

            </div>
        </div>

    </main>


@endsection