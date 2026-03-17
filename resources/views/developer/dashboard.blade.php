@extends('developer.layout.app')

@section('title', 'Developer Dashboard')

@section('content')


<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">


    <!-- Sales DASHBOARD PAGE -->
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <h1 class="page-title">Developer Dashboard</h1>
            <p class="page-desc">My workspace · Arjun Kumar · Frontend Dev</p>
        </div>


        <!-- KPI STRIP -->
        <div class="kpi-grid">

            <div class="kpi-card" style="--kpi-accent:#6366f1">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-kanban-fill"></i></div>
                </div>
                <div class="kpi-value">6</div>
                <div class="kpi-label">Active Projects</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#10b981">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-check2-all"></i></div>
                </div>
                <div class="kpi-value">14</div>
                <div class="kpi-label">Open Tasks</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#f59e0b">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-git"></i></div>
                </div>
                <div class="kpi-value">47</div>
                <div class="kpi-label">Commits (Month)</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#06b6d4">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-clock-fill"></i></div>
                </div>
                <div class="kpi-value">168h</div>
                <div class="kpi-label">Hours Logged</div>
            </div>

        </div>
    </div>

</main>


@endsection