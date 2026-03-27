@extends('admin.layout.app')

@section('title', 'Sales Dashboard')

@section('content')

@php
    $user = auth()->guard('sale')->user();
@endphp

<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">


    <!-- SalesDASHBOARD PAGE -->
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header"><h1 class="page-title">My Sales Dashboard</h1><p class="page-desc">Personal performance · {{ $user->name }} · Sales Executive</p></div>

        <!-- KPI STRIP -->
        <div class="kpi-grid">
      
        <div class="kpi-card" style="--kpi-accent:#6366f1"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-trophy-fill"></i></div></div><div class="kpi-value">₹{{ number_format($revenue, 2) }}</div><div class="kpi-label">My Revenue</div></div>
        <div class="kpi-card" style="--kpi-accent:#10b981"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-person-lines-fill"></i></div></div><div class="kpi-value">{{ $totalLeads }}</div><div class="kpi-label">My Leads</div></div>
        <div class="kpi-card" style="--kpi-accent:#f59e0b"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-bag-fill"></i></div></div><div class="kpi-value">{{ $totalOrders }}</div><div class="kpi-label">My Orders</div></div>
        <div class="kpi-card" style="--kpi-accent:#06b6d4"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-bullseye"></i></div></div><div class="kpi-value">{{ $marketingOrders }}</div><div class="kpi-label">My Marketing Orders</div></div>
        <div class="kpi-card" style="--kpi-accent:#8b5cf6"><div class="kpi-top"><div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i class="bi bi-kanban-fill"></i></div></div><div class="kpi-value">{{ $totalProjects }}</div><div class="kpi-label">My Projects</div></div>
        </div>
    </div>

</main>


@endsection
