@extends('admin.layout.app')

@section('title', 'All Leads')

@section('content')

<style>
    /* ── 6-column uniform grid ── */
    .stat-grid-wrap {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
    }

    /* ── Each box ── */
    .stat-box {
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 12px 14px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
        min-width: 0;
    }

    /* Accent underline on hover/active */
    .stat-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sb-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .22s ease;
    }

    .stat-box:hover {
        border-color: var(--sb-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
    }

    .stat-box:hover::after {
        transform: scaleX(1);
    }

    .stat-box.active {
        border-color: var(--sb-color);
        background: var(--bg3);
    }

    .stat-box.active::after {
        transform: scaleX(1);
    }

    /* Icon */
    .sb-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--sb-color) 14%, transparent);
        color: var(--sb-color);
    }

    /* Content stack */
    .sb-content {
        min-width: 0;
        flex: 1;
    }

    /* Category badge — tiny pill inside card */
    .sb-cat {
        display: inline-block;
        font-size: 9px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--cat-color);
        background: color-mix(in srgb, var(--cat-color) 12%, transparent);
        padding: 1px 6px;
        border-radius: 20px;
        margin-bottom: 3px;
        white-space: nowrap;
    }

    .sb-val {
        font-size: 18px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.3px;
        line-height: 1.1;
    }

    .sb-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 500;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* ── Responsive ── */
    @media (max-width: 1200px) {
        .stat-grid-wrap {
            grid-template-columns: repeat(4, 1fr);
        }
    }

    @media (max-width: 860px) {
        .stat-grid-wrap {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    @media (max-width: 560px) {
        .stat-grid-wrap {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    /* ══════════════════════════════
       DATE RANGE PICKER STYLES
    ══════════════════════════════ */
    .drp-trigger {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
        padding: 6px 12px;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--t2);
        cursor: pointer;
        transition: var(--transition);
        font-family: var(--font);
        white-space: nowrap;
        position: relative;
    }

    .drp-trigger:hover,
    .drp-trigger.open {
        border-color: var(--accent);
        color: var(--t1);
        background: var(--bg2);
    }

    .drp-trigger.open {
        box-shadow: 0 0 0 3px rgba(99, 102, 241, .1);
    }

    .drp-chevron {
        font-size: 10px;
        color: var(--t3);
        transition: transform .2s ease;
    }

    .drp-trigger.open .drp-chevron {
        transform: rotate(180deg);
    }

    .drp-panel {
        position: fixed;
        z-index: 9999;
        display: flex;
        background: var(--bg2);
        border: 1px solid var(--b2);
        border-radius: var(--r-lg);
        box-shadow: 0 20px 60px rgba(0, 0, 0, .28), 0 4px 16px rgba(0, 0, 0, .14);
        overflow: hidden;
        animation: drpIn .18s cubic-bezier(.34, 1.56, .64, 1);
        min-width: 760px;
    }

    @keyframes drpIn {
        from {
            opacity: 0;
            transform: translateY(-8px) scale(.98);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .drp-presets {
        width: 188px;
        flex-shrink: 0;
        border-right: 1px solid var(--b1);
        padding: 14px 10px;
        overflow-y: auto;
        max-height: 480px;
        scrollbar-width: thin;
        scrollbar-color: var(--b2) transparent;
    }

    .drp-presets::-webkit-scrollbar {
        width: 4px;
    }

    .drp-presets::-webkit-scrollbar-thumb {
        background: var(--b2);
        border-radius: 4px;
    }

    .drp-preset-group-label {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--t4);
        padding: 6px 8px 4px;
    }

    .drp-preset {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 10px;
        border-radius: var(--r-sm);
        font-size: 13px;
        color: var(--t2);
        font-weight: 500;
        cursor: pointer;
        transition: var(--transition);
        user-select: none;
    }

    .drp-preset:hover {
        background: var(--bg4);
        color: var(--t1);
    }

    .drp-preset.active {
        background: var(--accent-bg);
        color: var(--accent);
        font-weight: 600;
    }

    .drp-radio {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        border: 2px solid var(--b3);
        flex-shrink: 0;
        position: relative;
        transition: var(--transition);
    }

    .drp-preset.active .drp-radio {
        border-color: var(--accent);
        background: var(--accent);
    }

    .drp-preset.active .drp-radio::after {
        content: '';
        position: absolute;
        inset: 3px;
        border-radius: 50%;
        background: #fff;
    }

    .drp-calendars {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 16px 18px 14px;
        min-width: 0;
    }

    .drp-cal-row {
        display: flex;
        gap: 24px;
        flex: 1;
    }

    .drp-cal {
        flex: 1;
        min-width: 0;
    }

    .drp-cal-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .drp-cal-title {
        display: flex;
        align-items: center;
        gap: 4px;
        flex: 1;
        justify-content: center;
    }

    .drp-nav-btn {
        width: 28px;
        height: 28px;
        border-radius: var(--r-sm);
        background: var(--bg3);
        border: 1px solid var(--b1);
        color: var(--t2);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 11px;
        flex-shrink: 0;
        transition: var(--transition);
    }

    .drp-nav-btn:hover {
        background: var(--accent-bg);
        color: var(--accent);
        border-color: var(--accent);
    }

    .drp-month-sel,
    .drp-year-sel {
        background: transparent;
        border: none;
        font-size: 13px;
        font-weight: 700;
        color: var(--t1);
        cursor: pointer;
        outline: none;
        font-family: var(--font);
        padding: 2px 4px;
        border-radius: 5px;
    }

    .drp-month-sel:hover,
    .drp-year-sel:hover {
        background: var(--bg4);
    }

    .drp-month-sel option,
    .drp-year-sel option {
        background: var(--bg2);
        color: var(--t1);
    }

    .drp-cal-table {
        width: 100%;
        border-collapse: collapse;
    }

    .drp-cal-table th {
        font-size: 10.5px;
        font-weight: 700;
        color: var(--t3);
        text-align: center;
        padding: 4px 2px 6px;
        text-transform: uppercase;
        letter-spacing: .06em;
    }

    .drp-cal-table td {
        text-align: center;
        padding: 1.5px;
    }

    .drp-day {
        width: 30px;
        height: 30px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 12.5px;
        font-weight: 500;
        color: var(--t2);
        border-radius: 7px;
        cursor: pointer;
        transition: background .12s, color .12s;
        user-select: none;
        position: relative;
    }

    .drp-day:hover:not(.drp-day-disabled):not(.drp-day-selected) {
        background: var(--bg4);
        color: var(--t1);
    }

    .drp-day-disabled {
        color: var(--t4);
        cursor: default;
        pointer-events: none;
    }

    .drp-day-today {
        font-weight: 800;
        color: var(--accent);
    }

    .drp-day-today::after {
        content: '';
        position: absolute;
        bottom: 3px;
        left: 50%;
        transform: translateX(-50%);
        width: 4px;
        height: 4px;
        border-radius: 50%;
        background: var(--accent);
    }

    .drp-day-selected {
        background: var(--accent) !important;
        color: #fff !important;
        font-weight: 700;
        border-radius: 7px;
    }

    .drp-day-in-range {
        background: var(--accent-bg);
        color: var(--accent);
        border-radius: 0;
    }

    .drp-day-range-start {
        background: var(--accent) !important;
        color: #fff !important;
        border-radius: 7px 0 0 7px !important;
    }

    .drp-day-range-end {
        background: var(--accent) !important;
        color: #fff !important;
        border-radius: 0 7px 7px 0 !important;
    }

    .drp-day-range-start.drp-day-range-end {
        border-radius: 7px !important;
    }

    .drp-compare-row {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid var(--b1);
        flex-wrap: wrap;
    }

    .drp-compare-toggle {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        user-select: none;
    }

    .drp-compare-toggle input {
        display: none;
    }

    .drp-compare-chk {
        width: 36px;
        height: 20px;
        border-radius: 20px;
        background: var(--b2);
        position: relative;
        transition: var(--transition);
        flex-shrink: 0;
    }

    .drp-compare-chk::after {
        content: '';
        position: absolute;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--t3);
        top: 3px;
        left: 3px;
        transition: var(--transition);
    }

    .drp-compare-toggle input:checked+.drp-compare-chk {
        background: var(--accent);
    }

    .drp-compare-toggle input:checked+.drp-compare-chk::after {
        left: 19px;
        background: #fff;
    }

    .drp-compare-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .drp-compare-sel {
        background: var(--bg3);
        border: 1px solid var(--b1);
        color: var(--t2);
        border-radius: var(--r-sm);
        padding: 5px 8px;
        font-size: 12.5px;
        font-weight: 500;
        outline: none;
        cursor: pointer;
        font-family: var(--font);
    }

    .drp-date-inputs {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .drp-date-inp {
        background: var(--bg3);
        border: 1px solid var(--b1);
        color: var(--t2);
        border-radius: var(--r-sm);
        padding: 5px 9px;
        font-size: 12px;
        font-family: var(--font);
        width: 105px;
        outline: none;
    }

    .drp-dash {
        color: var(--t4);
        font-size: 13px;
    }

    .drp-range-display {
        margin-top: 12px;
        padding: 10px 12px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
    }

    .drp-range-fields {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .drp-range-field {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .drp-range-lbl {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--t3);
    }

    .drp-range-val {
        font-size: 13px;
        font-weight: 700;
        color: var(--t1);
        font-family: var(--mono);
    }

    .drp-footer {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid var(--b1);
    }
</style>


<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Leads</h1>
            </div>
            <div class="d-flex gap-2">
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                <a class="btn-primary-solid sm" href="{{route('admin.leads.create')}}">
                    <i class="bi bi-plus-lg"></i> Add Lead
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success" style="padding:12px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger" style="padding:12px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:16px;">
                @foreach($errors->all() as $error)
                    <p style="margin:0;"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- SUMMARY STAT BOXES -->
        <div class="stat-grid-wrap" style="margin-bottom:20px;">

            @if(request('assigned_to'))
                @php
                    $selectedSalesPerson = $sales->where('id', request('assigned_to'))->first();
                @endphp
                @if($selectedSalesPerson)
                <div class="stat-box" style="--sb-color:#10b981; border: 2px solid var(--accent);">
                    <div class="sb-icon"><i class="bi bi-person-badge-fill"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#10b981;">Filtered Sales Person</div>
                        <div class="sb-val">{{ $leads->total() }}</div>
                        <div class="sb-lbl">{{ $selectedSalesPerson->name }}</div>
                    </div>
                </div>

                <div class="stat-box" style="--sb-color:#8b5cf6; border: 2px solid #8b5cf6;">
                    <div class="sb-icon"><i class="bi bi-arrow-counterclockwise"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#8b5cf6;">Total Followup</div>
                        <div class="sb-val">{{ $totalFollowupsFiltered }}</div>
                        <div class="sb-lbl">Assigned Followups</div>
                    </div>
                </div>
                @endif
            @endif

            {{-- Row 1: Overview (2) + Priority (4) = 6 --}}

            <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-people-fill"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#6366f1;">Overview</div>
                    <div class="sb-val">{{ $totalLeads }}</div>
                    <div class="sb-lbl">Total Leads</div>
                </div>
            </div>

            <!-- <div class="stat-box" style="--sb-color:#10b981;">
                <div class="sb-icon"><i class="bi bi-person-check-fill"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#10b981;">Overview</div>
                    <div class="sb-val">{{ $convertedLeads }}</div>
                    <div class="sb-lbl">Converted</div>
                </div>
            </div> -->

            <div class="stat-box" style="--sb-color:#ef4444;">
                <div class="sb-icon"><i class="bi bi-fire"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#ef4444;">Priority</div>
                    <div class="sb-val" style="color:#ef4444;">{{ $priorityCounts['Hot 🔥'] ?? 0 }}</div>
                    <div class="sb-lbl">Hot 🔥</div>
                </div>
            </div>

            <div class="stat-box" style="--sb-color:#f59e0b;">
                <div class="sb-icon"><i class="bi bi-thermometer-half"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#f59e0b;">Priority</div>
                    <div class="sb-val" style="color:#f59e0b;">{{ $priorityCounts['Warm'] ?? 0 }}</div>
                    <div class="sb-lbl">Warm</div>
                </div>
            </div>

            <div class="stat-box" style="--sb-color:#06b6d4;">
                <div class="sb-icon"><i class="bi bi-snow"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#06b6d4;">Priority</div>
                    <div class="sb-val" style="color:#06b6d4;">{{ $priorityCounts['Cold'] ?? 0 }}</div>
                    <div class="sb-lbl">Cold</div>
                </div>
            </div>

            {{-- Row 2+: Status (Dynamic) --}}
            @foreach($statuses as $st)
            <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-hash"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#6366f1;">Status</div>
                    <div class="sb-val">{{ $st->leads_count }}</div>
                    <div class="sb-lbl">{{ $st->name }}</div>
                </div>
            </div>
            @endforeach

            {{-- Additional Dynamics --}}
            @foreach($sources as $src)
            <div class="stat-box" style="--sb-color:#8b5cf6;">
                <div class="sb-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#8b5cf6;">Source</div>
                    <div class="sb-val">{{ $src->leads_count }}</div>
                    <div class="sb-lbl">{{ $src->name }}</div>
                </div>
            </div>
            @endforeach

            @foreach($services as $srv)
            <div class="stat-box" style="--sb-color:#ec4899;">
                <div class="sb-icon"><i class="bi bi-briefcase"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#ec4899;">Service</div>
                    <div class="sb-val">{{ $srv->leads_count }}</div>
                    <div class="sb-lbl">{{ $srv->name }}</div>
                </div>
            </div>
            @endforeach

            @foreach($campaigns as $cmp)
            <div class="stat-box" style="--sb-color:#f59e0b;">
                <div class="sb-icon"><i class="bi bi-megaphone"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#f59e0b;">Campaign</div>
                    <div class="sb-val">{{ $cmp->leads_count }}</div>
                    <div class="sb-lbl">{{ $cmp->name }}</div>
                </div>
            </div>
            @endforeach
            

        </div>

        <!-- MAIN GRID -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Lead Pipeline</div>
                        <div class="card-sub" id="drpActiveSub">Last 7 Days · 147 total · 38 hot leads</div>
                    </div>
                    <form action="{{ route('admin.leads.index') }}" method="GET" class="card-actions mb-2">
                        <div class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </div>

                        <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                        <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                            <i class="bi bi-calendar3"></i>
                            <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'Last 7 Days' }}</span>
                            <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                        </button>

                        <!-- Hidden inputs for date range from the custom picker -->
                        <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                        <!-- {{-- Date Range Picker (replaces simple select) --}} -->
                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>

                        <select name="source_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">Lead Source</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ request('source_id') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                            @endforeach
                        </select>

                        <select name="service_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>

                        <select name="priority" class="filter-select" onchange="this.form.submit()">
                            <option value="">Priority</option>
                            <option value="Hot 🔥" {{ request('priority') == 'Hot 🔥' ? 'selected' : '' }}>Hot 🔥</option>
                            <option value="Warm" {{ request('priority') == 'Warm' ? 'selected' : '' }}>Warm</option>
                            <option value="Cold" {{ request('priority') == 'Cold' ? 'selected' : '' }}>Cold</option>
                        </select>
                        <select name="status_id" class="filter-select" onchange="this.form.submit()">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        <select name="assigned_to" class="filter-select" onchange="this.form.submit()">
                            <option value="">Sales Person</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}" {{ request('assigned_to') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Lead</th>
                                <th>Source</th>
                                <th>Contact Person</th>
                                <th>Service Need</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Sales Person</th>
                                <th>Followup</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($leads as $index => $lead)
                            <tr>
                                <td>{{ $leads->firstItem() + $index }}</td>
                                <td><div class="ls" style="font-size:12px; font-weight:600;">{{ $lead->created_at->format('d M Y') }}</div></td>
                                <td>
                                    <div class="lead-cell">
                                        @php
                                            $initials = strtoupper(substr($lead->company, 0, 1) . substr($lead->contact_person, 0, 1));
                                            $emails = is_array($lead->emails) ? $lead->emails[0] : (json_decode($lead->emails)[0] ?? 'N/A');
                                        @endphp
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ $initials }}</div>
                                        <div>
                                            <div class="ln">{{ $lead->company }}</div>
                                            <div class="ls">{{ $emails }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="src-tag">{{ $lead->source->name ?? 'N/A' }}</span></td>
                                <td><strong style="color:var(--t2)">{{ $lead->contact_person }}</strong></td>
                                <td><strong style="color:var(--t2)">{{ $lead->service->name ?? 'N/A' }}</strong></td>
                                <td>
                                    @php
                                        $pCls = strtolower(str_replace([' ', '🔥'], '', $lead->priority));
                                    @endphp
                                    <span class="lead-stage {{ $pCls }}">{{ $lead->priority }}</span>
                                </td>
                                <td><strong style="color:var(--accent)">{{ $lead->status->name ?? 'N/A' }}</strong></td>
                                <td>
                                    @if($lead->createdBy)
                                        <div class="ln">{{ $lead->createdBy->name }}</div>
                                        <div class="ls" style="font-size:10px">{{ $lead->createdBy->email }}</div>
                                    @else
                                        <div class="ln">System</div>
                                    @endif
                                </td>
                                <td>
                                    @foreach($lead->assignments as $assign)
                                        <div class="ln">{{ $assign->sale->name ?? 'N/A' }} - {{ $assign->sale->email ?? 'N/A' }}</div>
                                        
                                    @endforeach
                                    @if($lead->assignments->isEmpty())
                                        <span style="color:var(--t4)">Unassigned</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge" style="background:rgba(99, 102, 241, 0.1); color:var(--accent); padding:4px 10px; border-radius:6px; font-weight:700; font-family:var(--font-mono); font-size:12px;">
                                        {{ $lead->followups_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.leads.show', $lead->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <!-- <button class="ra-btn" title="Call"><i class="bi bi-telephone-fill"></i></button>
                                        <button class="ra-btn" title="Email"><i class="bi bi-envelope-fill"></i></button> -->
                                        <a href="{{route('admin.leads.followup', $lead->id)}}" class="ra-btn" title="Followup"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a class="ra-btn" title="Edit" href="{{route('admin.leads.edit', $lead->id)}}"><i class="bi bi-pencil-fill"></i></a>
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route('admin.leads.destroy', $lead->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="10" style="text-align:center;padding:40px;color:var(--t4);">No leads found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing {{ $leads->count() }} of {{ $leads->total() }} Leads</span>
                    <div class="tf-pagination">
                        {{ $leads->links('admin.includes.pagination') }}
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Lead</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this Lead?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete Lead
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- LEAD DETAIL MODAL -->
    <!-- LEAD DETAIL MODAL -->
    <div class="modal-backdrop" id="leadDetailModal" onclick="closeModal('leadDetailModal')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <span>TechCorp Solutions</span>
                    <span style="font-size:11px;font-weight:500;color:var(--t3);">Lead Detail</span>
                </div>
                <button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button>
            </div>

            <div class="modal-bd">

                {{-- ── KPI Strip ── --}}
                <div class="detail-kpis" style="margin-bottom:20px;">
                    <div class="dk-item">
                        <div class="dk-val">12-02-2026</div>
                        <div class="dk-lbl">Created Date</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#ef4444;">Hot 🔥</div>
                        <div class="dk-lbl">Priority</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Website</div>
                        <div class="dk-lbl">Service Need</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">LinkedIn</div>
                        <div class="dk-lbl">Lead Source</div>
                    </div>
                </div>

                {{-- ── Read-only info block ── --}}
                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r);padding:14px 16px;margin-bottom:16px;display:grid;grid-template-columns:1fr 1fr;gap:10px;">

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Company</span>
                        <span style="font-size:13px;font-weight:600;color:var(--t1);">TechCorp Solutions</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Contact Person</span>
                        <span style="font-size:13px;font-weight:600;color:var(--t1);">Vikram Bhatia</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Business Type</span>
                        <span style="font-size:13px;font-weight:600;color:var(--t1);">Technology</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Assigned To</span>
                        <div style="display:flex;align-items:center;gap:6px;margin-top:2px;">
                            <div style="width:20px;height:20px;border-radius:5px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#fff;">RK</div>
                            <span style="font-size:13px;font-weight:600;color:var(--t1);">Rahul Kumar</span>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Email</span>
                        <span style="font-size:13px;color:var(--t2);">vikram@techcorp.com</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Phone</span>
                        <span style="font-size:13px;color:var(--t2);">+91 98765 43210</span>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:2px;grid-column:1/-1;">
                        <span style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);">Address</span>
                        <span style="font-size:13px;color:var(--t2);">204, Orbit Tower, Andheri East, Mumbai — 400069</span>
                    </div>

                </div>

                {{-- ── Editable fields (Priority + Status only) ── --}}
                <div style="background:var(--accent-bg);border:1px solid rgba(99,102,241,.2);border-radius:var(--r);padding:14px 16px;margin-bottom:16px;">
                    <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--accent);margin-bottom:12px;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-pencil-fill"></i> Quick Update
                    </div>
                    <div class="form-grid">
                        <div class="form-row" style="margin-bottom:0;">
                            <label class="form-lbl">Change Priority</label>
                            <select class="form-inp">
                                <option selected>Hot 🔥</option>
                                <option>Warm</option>
                                <option>Cold</option>
                                <option>Lost</option>
                            </select>
                        </div>
                        <div class="form-row" style="margin-bottom:0;">
                            <label class="form-lbl">Change Lead Status</label>
                            <select class="form-inp">
                                <option>Not Responding</option>
                                <option>Not Interested</option>
                                <option>Not Required</option>
                                <option>Location Issue</option>
                                <option>Job</option>
                                <option>Not Inquired</option>
                                <option selected>Respond</option>
                                <option>Interested</option>
                                <option>Language Barrier</option>
                                <option>Booked</option>
                                <option>Budget Issue</option>
                            </select>
                        </div>
                        <div class="form-row" style="grid-column:1/-1;margin-bottom:0;">
                            <label class="form-lbl">Add Remark / Note</label>
                            <textarea class="form-inp" rows="2" placeholder="Add internal note…"></textarea>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ── Footer — 3 actions ── --}}
            <div class="modal-ft" style="justify-content:space-between;">

                {{-- Left: Close --}}
                <button class="btn-ghost" onclick="closeModal('leadDetailModal')">
                    <i class="bi bi-x-lg"></i> Close
                </button>

                {{-- Right: Convert + Update --}}
                <div style="display:flex;align-items:center;gap:8px;">
                    <a href="{{route('admin.orders.create')}}" class="btn-ghost"
                        style="border-color:#10b981;color:#10b981;"
                        onmouseover="this.style.background='rgba(16,185,129,.1)'"
                        onmouseout="this.style.background='transparent'"
                        onclick="closeModal('leadDetailModal');">
                        <i class="bi bi-arrow-right-circle-fill"></i> Convert to Order
                    </a>
                    <button class="btn-primary-solid"
                        onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">
                        <i class="bi bi-check2-circle"></i> Update Lead
                    </button>
                </div>

            </div>
        </div>
    </div>

</main>


<script>
    (function() {
        const MONTHS = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        const MONTHS_SHORT = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const DAYS = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        let view1 = new Date(today.getFullYear(), today.getMonth(), 1);
        let view2 = new Date(today.getFullYear(), today.getMonth() + 1, 1);
        let rangeStart = null,
            rangeEnd = null,
            hoverDate = null;
        let selecting = false,
            activePreset = 'last7';

        function fmt(d) {
            return d ? d.getDate() + ' ' + MONTHS_SHORT[d.getMonth()] + ' ' + d.getFullYear() : '—';
        }

        function sameDay(a, b) {
            return a && b && a.toDateString() === b.toDateString();
        }

        function between(d, a, b) {
            return a && b && d > a && d < b;
        }

        function clone(d) {
            return d ? new Date(d.getTime()) : null;
        }

        const presetMap = {
            today: () => {
                const d = clone(today);
                return [d, d];
            },
            yesterday: () => {
                const d = new Date(today);
                d.setDate(d.getDate() - 1);
                return [d, d];
            },
            today_yesterday: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - 1);
                return [a, clone(today)];
            },
            last7: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - 6);
                return [a, clone(today)];
            },
            last14: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - 13);
                return [a, clone(today)];
            },
            last28: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - 27);
                return [a, clone(today)];
            },
            last30: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - 29);
                return [a, clone(today)];
            },
            this_week: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - a.getDay());
                return [a, clone(today)];
            },
            last_week: () => {
                const a = new Date(today);
                a.setDate(a.getDate() - a.getDay() - 7);
                const b = new Date(a);
                b.setDate(b.getDate() + 6);
                return [a, b];
            },
            this_month: () => [new Date(today.getFullYear(), today.getMonth(), 1), clone(today)],
            last_month: () => [new Date(today.getFullYear(), today.getMonth() - 1, 1), new Date(today.getFullYear(), today.getMonth(), 0)],
            this_year: () => [new Date(today.getFullYear(), 0, 1), clone(today)],
            custom: () => [null, null],
        };

        const presetLabels = {
            today: 'Today',
            yesterday: 'Yesterday',
            today_yesterday: 'Today & Yesterday',
            last7: 'Last 7 Days',
            last14: 'Last 14 Days',
            last28: 'Last 28 Days',
            last30: 'Last 30 Days',
            this_week: 'This Week',
            last_week: 'Last Week',
            this_month: 'This Month',
            last_month: 'Last Month',
            this_year: 'This Year',
            custom: 'Custom Range',
        };

        function populateSelects() {
            ['month1Sel', 'month2Sel'].forEach(id => {
                const sel = document.getElementById(id);
                if (!sel || sel.options.length) return;
                MONTHS.forEach((m, i) => {
                    const o = document.createElement('option');
                    o.value = i;
                    o.textContent = m;
                    sel.appendChild(o);
                });
            });
            ['year1Sel', 'year2Sel'].forEach(id => {
                const sel = document.getElementById(id);
                if (!sel || sel.options.length) return;
                for (let y = today.getFullYear() - 10; y <= today.getFullYear() + 2; y++) {
                    const o = document.createElement('option');
                    o.value = y;
                    o.textContent = y;
                    sel.appendChild(o);
                }
            });
        }

        function syncSelects() {
            document.getElementById('month1Sel').value = view1.getMonth();
            document.getElementById('year1Sel').value = view1.getFullYear();
            document.getElementById('month2Sel').value = view2.getMonth();
            document.getElementById('year2Sel').value = view2.getFullYear();
        }

        function renderCal(tableId, viewDate) {
            const tbl = document.getElementById(tableId);
            tbl.innerHTML = '';
            const thead = document.createElement('thead');
            const hRow = document.createElement('tr');
            DAYS.forEach(d => {
                const th = document.createElement('th');
                th.textContent = d;
                hRow.appendChild(th);
            });
            thead.appendChild(hRow);
            tbl.appendChild(thead);

            const tbody = document.createElement('tbody');
            const year = viewDate.getFullYear(),
                month = viewDate.getMonth();
            const firstDay = new Date(year, month, 1).getDay();
            const daysInMonth = new Date(year, month + 1, 0).getDate();

            let day = 1,
                row = document.createElement('tr');
            for (let i = 0; i < firstDay; i++) row.appendChild(document.createElement('td'));

            while (day <= daysInMonth) {
                const cell = document.createElement('td');
                const d = new Date(year, month, day);
                const span = document.createElement('span');
                span.className = 'drp-day';
                span.textContent = day;
                span.dataset.ts = d.getTime();

                if (sameDay(d, today)) span.classList.add('drp-day-today');

                const effEnd = hoverDate && selecting && !rangeEnd ? (hoverDate >= rangeStart ? hoverDate : rangeStart) : rangeEnd;
                const effStart = hoverDate && selecting && !rangeEnd ? (hoverDate < rangeStart ? hoverDate : rangeStart) : rangeStart;

                if (sameDay(d, effStart) && sameDay(d, effEnd)) span.classList.add('drp-day-selected', 'drp-day-range-start', 'drp-day-range-end');
                else if (sameDay(d, effStart)) span.classList.add('drp-day-range-start');
                else if (sameDay(d, effEnd)) span.classList.add('drp-day-range-end');
                else if (effStart && effEnd && between(d, effStart, effEnd)) span.classList.add('drp-day-in-range');

                span.addEventListener('click', onDayClick);
                span.addEventListener('mouseenter', onDayHover);
                cell.appendChild(span);
                row.appendChild(cell);

                if ((firstDay + day) % 7 === 0) {
                    tbody.appendChild(row);
                    row = document.createElement('tr');
                }
                day++;
            }
            if (row.children.length) tbody.appendChild(row);
            tbl.appendChild(tbody);
        }

        function render() {
            populateSelects();
            syncSelects();
            renderCal('cal1', view1);
            renderCal('cal2', view2);
            updateRangeDisplay();
        }

        function onDayClick(e) {
            const d = new Date(parseInt(e.currentTarget.dataset.ts));
            if (!selecting || rangeEnd) {
                rangeStart = d;
                rangeEnd = null;
                selecting = true;
                setCustomPreset();
            } else {
                if (d < rangeStart) {
                    rangeEnd = rangeStart;
                    rangeStart = d;
                } else {
                    rangeEnd = d;
                }
                selecting = false;
                setCustomPreset();
            }
            render();
        }

        function onDayHover(e) {
            if (!selecting) return;
            hoverDate = new Date(parseInt(e.currentTarget.dataset.ts));
            render();
        }

        function setCustomPreset() {
            document.querySelectorAll('.drp-preset').forEach(el => el.classList.remove('active'));
            const el = document.querySelector('.drp-preset[data-preset="custom"]');
            if (el) {
                el.classList.add('active');
                activePreset = 'custom';
            }
        }

        function updateRangeDisplay() {
            document.getElementById('rangeStartDisplay').textContent = fmt(rangeStart);
            document.getElementById('rangeEndDisplay').textContent = fmt(rangeEnd);
            if (rangeStart && rangeEnd) updateCompareDisplay();
        }

        function updateCompareDisplay() {
            const sel = document.getElementById('comparePreset');
            if (!sel || !document.getElementById('compareToggle').checked) return;
            const diff = Math.round((rangeEnd - rangeStart) / 86400000);
            let cs, ce;
            if (sel.value === 'preceding') {
                ce = new Date(rangeStart);
                ce.setDate(ce.getDate() - 1);
                cs = new Date(ce);
                cs.setDate(cs.getDate() - diff);
            } else if (sel.value === 'prev_year') {
                cs = new Date(rangeStart);
                cs.setFullYear(cs.getFullYear() - 1);
                ce = new Date(rangeEnd);
                ce.setFullYear(ce.getFullYear() - 1);
            } else return;
            document.getElementById('cmpStart').value = fmt(cs);
            document.getElementById('cmpEnd').value = fmt(ce);
        }

        window.shiftMonths = function(dir) {
            view1 = new Date(view1.getFullYear(), view1.getMonth() + dir, 1);
            view2 = new Date(view2.getFullYear(), view2.getMonth() + dir, 1);
            render();
        };
        window.onMonthChange = function(idx) {
            if (idx === 0) view1 = new Date(view1.getFullYear(), parseInt(document.getElementById('month1Sel').value), 1);
            else view2 = new Date(view2.getFullYear(), parseInt(document.getElementById('month2Sel').value), 1);
            render();
        };
        window.onYearChange = function(idx) {
            if (idx === 0) view1 = new Date(parseInt(document.getElementById('year1Sel').value), view1.getMonth(), 1);
            else view2 = new Date(parseInt(document.getElementById('year2Sel').value), view2.getMonth(), 1);
            render();
        };

        document.querySelectorAll('.drp-preset').forEach(el => {
            el.addEventListener('click', function() {
                activePreset = this.dataset.preset;
                document.querySelectorAll('.drp-preset').forEach(p => p.classList.remove('active'));
                this.classList.add('active');
                const [s, e] = presetMap[activePreset]();
                rangeStart = s;
                rangeEnd = e;
                selecting = false;
                hoverDate = null;
                if (s) {
                    view1 = new Date(s.getFullYear(), s.getMonth(), 1);
                    view2 = new Date(s.getFullYear(), s.getMonth() + 1, 1);
                }
                render();
            });
        });

        window.toggleCompare = function() {
            const on = document.getElementById('compareToggle').checked;
            document.getElementById('compareInputs').style.display = on ? 'flex' : 'none';
            if (on) updateCompareDisplay();
        };

        window.toggleDatePicker = function() {
            const panel = document.getElementById('dateRangePanel');
            const trigger = document.getElementById('dateRangeTrigger');
            if (panel.style.display !== 'none') {
                closeDatePicker();
                return;
            }

            const rect = trigger.getBoundingClientRect();
            panel.style.top = (rect.bottom + 6) + 'px';
            panel.style.left = rect.left + 'px';
            panel.style.display = 'flex';

            // Clamp to viewport
            const pw = panel.offsetWidth;
            if (rect.left + pw > window.innerWidth - 16)
                panel.style.left = Math.max(8, window.innerWidth - pw - 16) + 'px';

            trigger.classList.add('open');
            render();
        };

        function closeDatePicker() {
            document.getElementById('dateRangePanel').style.display = 'none';
            document.getElementById('dateRangeTrigger').classList.remove('open');
        }

        window.cancelDatePicker = function() {
            closeDatePicker();
        };

        window.applyDatePicker = function() {
            let display = presetLabels[activePreset] || 'Custom Range';
            if (activePreset === 'custom' && rangeStart && rangeEnd)
                display = fmt(rangeStart) + ' — ' + fmt(rangeEnd);
            document.getElementById('drpLabel').textContent = display;

            if (rangeStart && rangeEnd) {
                document.getElementById('drpStartInput').value = fmt(rangeStart);
                document.getElementById('drpEndInput').value = fmt(rangeEnd);
            }

            closeDatePicker();
            
            // Submit the parent form
            const form = document.getElementById('drpStartInput').closest('form');
            if (form) form.submit();
        };

        document.addEventListener('click', function(e) {
            const panel = document.getElementById('dateRangePanel');
            const trigger = document.getElementById('dateRangeTrigger');
            if (panel && panel.style.display !== 'none' &&
                !panel.contains(e.target) && !trigger.contains(e.target))
                closeDatePicker();
        });

        document.addEventListener('DOMContentLoaded', function() {
            populateSelects();
            const [s, e] = presetMap['last7']();
            rangeStart = s;
            rangeEnd = e;
            document.getElementById('drpLabel').textContent = 'Last 7 Days';
        });
    })();


    /* ═══════════════════════════════════════════
       PHONE / EMAIL MULTI-ROW
    ═══════════════════════════════════════════ */
    const COUNTRIES = [{
            f: "🇦🇫",
            n: "Afghanistan",
            c: "+93"
        }, {
            f: "🇦🇱",
            n: "Albania",
            c: "+355"
        }, {
            f: "🇩🇿",
            n: "Algeria",
            c: "+213"
        },
        {
            f: "🇦🇩",
            n: "Andorra",
            c: "+376"
        }, {
            f: "🇦🇴",
            n: "Angola",
            c: "+244"
        }, {
            f: "🇦🇷",
            n: "Argentina",
            c: "+54"
        },
        {
            f: "🇦🇲",
            n: "Armenia",
            c: "+374"
        }, {
            f: "🇦🇺",
            n: "Australia",
            c: "+61"
        }, {
            f: "🇦🇹",
            n: "Austria",
            c: "+43"
        },
        {
            f: "🇦🇿",
            n: "Azerbaijan",
            c: "+994"
        }, {
            f: "🇧🇸",
            n: "Bahamas",
            c: "+1-242"
        }, {
            f: "🇧🇭",
            n: "Bahrain",
            c: "+973"
        },
        {
            f: "🇧🇩",
            n: "Bangladesh",
            c: "+880"
        }, {
            f: "🇧🇾",
            n: "Belarus",
            c: "+375"
        }, {
            f: "🇧🇪",
            n: "Belgium",
            c: "+32"
        },
        {
            f: "🇧🇿",
            n: "Belize",
            c: "+501"
        }, {
            f: "🇧🇯",
            n: "Benin",
            c: "+229"
        }, {
            f: "🇧🇹",
            n: "Bhutan",
            c: "+975"
        },
        {
            f: "🇧🇴",
            n: "Bolivia",
            c: "+591"
        }, {
            f: "🇧🇦",
            n: "Bosnia",
            c: "+387"
        }, {
            f: "🇧🇼",
            n: "Botswana",
            c: "+267"
        },
        {
            f: "🇧🇷",
            n: "Brazil",
            c: "+55"
        }, {
            f: "🇧🇳",
            n: "Brunei",
            c: "+673"
        }, {
            f: "🇧🇬",
            n: "Bulgaria",
            c: "+359"
        },
        {
            f: "🇧🇫",
            n: "Burkina Faso",
            c: "+226"
        }, {
            f: "🇧🇮",
            n: "Burundi",
            c: "+257"
        }, {
            f: "🇨🇻",
            n: "Cabo Verde",
            c: "+238"
        },
        {
            f: "🇰🇭",
            n: "Cambodia",
            c: "+855"
        }, {
            f: "🇨🇲",
            n: "Cameroon",
            c: "+237"
        }, {
            f: "🇨🇦",
            n: "Canada",
            c: "+1"
        },
        {
            f: "🇨🇫",
            n: "Central African Rep.",
            c: "+236"
        }, {
            f: "🇹🇩",
            n: "Chad",
            c: "+235"
        }, {
            f: "🇨🇱",
            n: "Chile",
            c: "+56"
        },
        {
            f: "🇨🇳",
            n: "China",
            c: "+86"
        }, {
            f: "🇨🇴",
            n: "Colombia",
            c: "+57"
        }, {
            f: "🇰🇲",
            n: "Comoros",
            c: "+269"
        },
        {
            f: "🇨🇩",
            n: "Congo (DRC)",
            c: "+243"
        }, {
            f: "🇨🇬",
            n: "Congo (Rep.)",
            c: "+242"
        }, {
            f: "🇨🇷",
            n: "Costa Rica",
            c: "+506"
        },
        {
            f: "🇭🇷",
            n: "Croatia",
            c: "+385"
        }, {
            f: "🇨🇺",
            n: "Cuba",
            c: "+53"
        }, {
            f: "🇨🇾",
            n: "Cyprus",
            c: "+357"
        },
        {
            f: "🇨🇿",
            n: "Czech Republic",
            c: "+420"
        }, {
            f: "🇩🇰",
            n: "Denmark",
            c: "+45"
        }, {
            f: "🇩🇯",
            n: "Djibouti",
            c: "+253"
        },
        {
            f: "🇩🇴",
            n: "Dominican Rep.",
            c: "+1-809"
        }, {
            f: "🇪🇨",
            n: "Ecuador",
            c: "+593"
        }, {
            f: "🇪🇬",
            n: "Egypt",
            c: "+20"
        },
        {
            f: "🇸🇻",
            n: "El Salvador",
            c: "+503"
        }, {
            f: "🇬🇶",
            n: "Equatorial Guinea",
            c: "+240"
        }, {
            f: "🇪🇷",
            n: "Eritrea",
            c: "+291"
        },
        {
            f: "🇪🇪",
            n: "Estonia",
            c: "+372"
        }, {
            f: "🇸🇿",
            n: "Eswatini",
            c: "+268"
        }, {
            f: "🇪🇹",
            n: "Ethiopia",
            c: "+251"
        },
        {
            f: "🇫🇯",
            n: "Fiji",
            c: "+679"
        }, {
            f: "🇫🇮",
            n: "Finland",
            c: "+358"
        }, {
            f: "🇫🇷",
            n: "France",
            c: "+33"
        },
        {
            f: "🇬🇦",
            n: "Gabon",
            c: "+241"
        }, {
            f: "🇬🇲",
            n: "Gambia",
            c: "+220"
        }, {
            f: "🇬🇪",
            n: "Georgia",
            c: "+995"
        },
        {
            f: "🇩🇪",
            n: "Germany",
            c: "+49"
        }, {
            f: "🇬🇭",
            n: "Ghana",
            c: "+233"
        }, {
            f: "🇬🇷",
            n: "Greece",
            c: "+30"
        },
        {
            f: "🇬🇹",
            n: "Guatemala",
            c: "+502"
        }, {
            f: "🇬🇳",
            n: "Guinea",
            c: "+224"
        }, {
            f: "🇬🇼",
            n: "Guinea-Bissau",
            c: "+245"
        },
        {
            f: "🇬🇾",
            n: "Guyana",
            c: "+592"
        }, {
            f: "🇭🇹",
            n: "Haiti",
            c: "+509"
        }, {
            f: "🇭🇳",
            n: "Honduras",
            c: "+504"
        },
        {
            f: "🇭🇺",
            n: "Hungary",
            c: "+36"
        }, {
            f: "🇮🇸",
            n: "Iceland",
            c: "+354"
        }, {
            f: "🇮🇳",
            n: "India",
            c: "+91"
        },
        {
            f: "🇮🇩",
            n: "Indonesia",
            c: "+62"
        }, {
            f: "🇮🇷",
            n: "Iran",
            c: "+98"
        }, {
            f: "🇮🇶",
            n: "Iraq",
            c: "+964"
        },
        {
            f: "🇮🇪",
            n: "Ireland",
            c: "+353"
        }, {
            f: "🇮🇱",
            n: "Israel",
            c: "+972"
        }, {
            f: "🇮🇹",
            n: "Italy",
            c: "+39"
        },
        {
            f: "🇯🇲",
            n: "Jamaica",
            c: "+1-876"
        }, {
            f: "🇯🇵",
            n: "Japan",
            c: "+81"
        }, {
            f: "🇯🇴",
            n: "Jordan",
            c: "+962"
        },
        {
            f: "🇰🇿",
            n: "Kazakhstan",
            c: "+7"
        }, {
            f: "🇰🇪",
            n: "Kenya",
            c: "+254"
        }, {
            f: "🇰🇼",
            n: "Kuwait",
            c: "+965"
        },
        {
            f: "🇰🇬",
            n: "Kyrgyzstan",
            c: "+996"
        }, {
            f: "🇱🇦",
            n: "Laos",
            c: "+856"
        }, {
            f: "🇱🇻",
            n: "Latvia",
            c: "+371"
        },
        {
            f: "🇱🇧",
            n: "Lebanon",
            c: "+961"
        }, {
            f: "🇱🇸",
            n: "Lesotho",
            c: "+266"
        }, {
            f: "🇱🇷",
            n: "Liberia",
            c: "+231"
        },
        {
            f: "🇱🇾",
            n: "Libya",
            c: "+218"
        }, {
            f: "🇱🇮",
            n: "Liechtenstein",
            c: "+423"
        }, {
            f: "🇱🇹",
            n: "Lithuania",
            c: "+370"
        },
        {
            f: "🇱🇺",
            n: "Luxembourg",
            c: "+352"
        }, {
            f: "🇲🇬",
            n: "Madagascar",
            c: "+261"
        }, {
            f: "🇲🇼",
            n: "Malawi",
            c: "+265"
        },
        {
            f: "🇲🇾",
            n: "Malaysia",
            c: "+60"
        }, {
            f: "🇲🇻",
            n: "Maldives",
            c: "+960"
        }, {
            f: "🇲🇱",
            n: "Mali",
            c: "+223"
        },
        {
            f: "🇲🇹",
            n: "Malta",
            c: "+356"
        }, {
            f: "🇲🇷",
            n: "Mauritania",
            c: "+222"
        }, {
            f: "🇲🇺",
            n: "Mauritius",
            c: "+230"
        },
        {
            f: "🇲🇽",
            n: "Mexico",
            c: "+52"
        }, {
            f: "🇲🇩",
            n: "Moldova",
            c: "+373"
        }, {
            f: "🇲🇨",
            n: "Monaco",
            c: "+377"
        },
        {
            f: "🇲🇳",
            n: "Mongolia",
            c: "+976"
        }, {
            f: "🇲🇪",
            n: "Montenegro",
            c: "+382"
        }, {
            f: "🇲🇦",
            n: "Morocco",
            c: "+212"
        },
        {
            f: "🇲🇿",
            n: "Mozambique",
            c: "+258"
        }, {
            f: "🇲🇲",
            n: "Myanmar",
            c: "+95"
        }, {
            f: "🇳🇦",
            n: "Namibia",
            c: "+264"
        },
        {
            f: "🇳🇵",
            n: "Nepal",
            c: "+977"
        }, {
            f: "🇳🇱",
            n: "Netherlands",
            c: "+31"
        }, {
            f: "🇳🇿",
            n: "New Zealand",
            c: "+64"
        },
        {
            f: "🇳🇮",
            n: "Nicaragua",
            c: "+505"
        }, {
            f: "🇳🇪",
            n: "Niger",
            c: "+227"
        }, {
            f: "🇳🇬",
            n: "Nigeria",
            c: "+234"
        },
        {
            f: "🇳🇴",
            n: "Norway",
            c: "+47"
        }, {
            f: "🇴🇲",
            n: "Oman",
            c: "+968"
        }, {
            f: "🇵🇰",
            n: "Pakistan",
            c: "+92"
        },
        {
            f: "🇵🇦",
            n: "Panama",
            c: "+507"
        }, {
            f: "🇵🇬",
            n: "Papua New Guinea",
            c: "+675"
        }, {
            f: "🇵🇾",
            n: "Paraguay",
            c: "+595"
        },
        {
            f: "🇵🇪",
            n: "Peru",
            c: "+51"
        }, {
            f: "🇵🇭",
            n: "Philippines",
            c: "+63"
        }, {
            f: "🇵🇱",
            n: "Poland",
            c: "+48"
        },
        {
            f: "🇵🇹",
            n: "Portugal",
            c: "+351"
        }, {
            f: "🇶🇦",
            n: "Qatar",
            c: "+974"
        }, {
            f: "🇷🇴",
            n: "Romania",
            c: "+40"
        },
        {
            f: "🇷🇺",
            n: "Russia",
            c: "+7"
        }, {
            f: "🇷🇼",
            n: "Rwanda",
            c: "+250"
        }, {
            f: "🇸🇦",
            n: "Saudi Arabia",
            c: "+966"
        },
        {
            f: "🇸🇳",
            n: "Senegal",
            c: "+221"
        }, {
            f: "🇷🇸",
            n: "Serbia",
            c: "+381"
        }, {
            f: "🇸🇱",
            n: "Sierra Leone",
            c: "+232"
        },
        {
            f: "🇸🇬",
            n: "Singapore",
            c: "+65"
        }, {
            f: "🇸🇰",
            n: "Slovakia",
            c: "+421"
        }, {
            f: "🇸🇮",
            n: "Slovenia",
            c: "+386"
        },
        {
            f: "🇸🇴",
            n: "Somalia",
            c: "+252"
        }, {
            f: "🇿🇦",
            n: "South Africa",
            c: "+27"
        }, {
            f: "🇸🇸",
            n: "South Sudan",
            c: "+211"
        },
        {
            f: "🇪🇸",
            n: "Spain",
            c: "+34"
        }, {
            f: "🇱🇰",
            n: "Sri Lanka",
            c: "+94"
        }, {
            f: "🇸🇩",
            n: "Sudan",
            c: "+249"
        },
        {
            f: "🇸🇷",
            n: "Suriname",
            c: "+597"
        }, {
            f: "🇸🇪",
            n: "Sweden",
            c: "+46"
        }, {
            f: "🇨🇭",
            n: "Switzerland",
            c: "+41"
        },
        {
            f: "🇸🇾",
            n: "Syria",
            c: "+963"
        }, {
            f: "🇹🇼",
            n: "Taiwan",
            c: "+886"
        }, {
            f: "🇹🇯",
            n: "Tajikistan",
            c: "+992"
        },
        {
            f: "🇹🇿",
            n: "Tanzania",
            c: "+255"
        }, {
            f: "🇹🇭",
            n: "Thailand",
            c: "+66"
        }, {
            f: "🇹🇱",
            n: "Timor-Leste",
            c: "+670"
        },
        {
            f: "🇹🇬",
            n: "Togo",
            c: "+228"
        }, {
            f: "🇹🇹",
            n: "Trinidad & Tobago",
            c: "+1-868"
        }, {
            f: "🇹🇳",
            n: "Tunisia",
            c: "+216"
        },
        {
            f: "🇹🇷",
            n: "Turkey",
            c: "+90"
        }, {
            f: "🇹🇲",
            n: "Turkmenistan",
            c: "+993"
        }, {
            f: "🇺🇬",
            n: "Uganda",
            c: "+256"
        },
        {
            f: "🇺🇦",
            n: "Ukraine",
            c: "+380"
        }, {
            f: "🇦🇪",
            n: "UAE",
            c: "+971"
        }, {
            f: "🇬🇧",
            n: "United Kingdom",
            c: "+44"
        },
        {
            f: "🇺🇸",
            n: "USA",
            c: "+1"
        }, {
            f: "🇺🇾",
            n: "Uruguay",
            c: "+598"
        }, {
            f: "🇺🇿",
            n: "Uzbekistan",
            c: "+998"
        },
        {
            f: "🇻🇪",
            n: "Venezuela",
            c: "+58"
        }, {
            f: "🇻🇳",
            n: "Vietnam",
            c: "+84"
        }, {
            f: "🇾🇪",
            n: "Yemen",
            c: "+967"
        },
        {
            f: "🇿🇲",
            n: "Zambia",
            c: "+260"
        }, {
            f: "🇿🇼",
            n: "Zimbabwe",
            c: "+263"
        }
    ];
    const INDIA_IDX = COUNTRIES.findIndex(c => c.n === "India");

    function buildCountrySel() {
        const sel = document.createElement('select');
        sel.className = 'country-sel';
        COUNTRIES.forEach((c, i) => {
            const o = document.createElement('option');
            o.value = i;
            o.textContent = c.f + ' ' + c.c;
            o.title = c.n;
            sel.appendChild(o);
        });
        sel.value = INDIA_IDX >= 0 ? INDIA_IDX : 0;
        return sel;
    }

    function addPhoneRow(listId, removable = null) {
        const list = document.getElementById(listId);
        const canRemove = removable !== null ? removable : list.children.length > 0;
        const row = document.createElement('div');
        row.className = 'multi-row';
        const wrap = document.createElement('div');
        wrap.className = 'phone-wrap form-inp';
        wrap.style.cssText = 'padding:0;display:flex;align-items:center;';
        wrap.appendChild(buildCountrySel());
        const inp = document.createElement('input');
        inp.type = 'tel';
        inp.className = 'phone-num-inp';
        inp.placeholder = 'XXXXX XXXXX';
        wrap.appendChild(inp);
        row.appendChild(wrap);
        if (canRemove) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'row-remove-btn';
            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
            btn.onclick = () => row.remove();
            row.appendChild(btn);
        }
        list.appendChild(row);
    }

    function addEmailRow(listId, removable = null) {
        const list = document.getElementById(listId);
        const canRemove = removable !== null ? removable : list.children.length > 0;
        const row = document.createElement('div');
        row.className = 'multi-row';
        const inp = document.createElement('input');
        inp.type = 'email';
        inp.className = 'form-inp multi-email-inp';
        inp.placeholder = 'email@company.com';
        row.appendChild(inp);
        if (canRemove) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'row-remove-btn';
            btn.innerHTML = '<i class="bi bi-x-lg"></i>';
            btn.onclick = () => row.remove();
            row.appendChild(btn);
        }
        list.appendChild(row);
    }

    function initModalRows(modalId) {
        const prefixes = {
            addLeadModal: 'add',
            editLeadModal: 'edit'
        };
        const p = prefixes[modalId];
        if (!p) return;
        const el = document.getElementById(p + '-email-list');
        const pl = document.getElementById(p + '-phone-list');
        if (el && el.children.length === 0) addEmailRow(p + '-email-list');
        if (pl && pl.children.length === 0) addPhoneRow(p + '-phone-list');
    }

    document.addEventListener('DOMContentLoaded', () => {
        ['addLeadModal', 'editLeadModal'].forEach(id => initModalRows(id));
    });

    const _origOpenModal = typeof openModal === 'function' ? openModal : null;

    function openModal(id) {
        if (_origOpenModal) _origOpenModal(id);
        initModalRows(id);
    }
    window.confirmDelete = function(url) {
        document.getElementById('deleteForm').action = url;
        openModal('deleteModal');
    };

    // DATE RANGE LISTENER
    document.addEventListener('dateRangeApplied', function(e) {
        const start = e.detail.start;
        const end = e.detail.end;
        if (start && end) {
            function formatDate(date) {
                let d = new Date(date),
                    month = '' + (d.getMonth() + 1),
                    day = '' + d.getDate(),
                    year = d.getFullYear();
                if (month.length < 2) month = '0' + month;
                if (day.length < 2) day = '0' + day;
                return [year, month, day].join('-');
            }
            
            const startInp = document.getElementById('drpStartInput');
            const endInp = document.getElementById('drpEndInput');
            if(startInp && endInp) {
                startInp.value = formatDate(start);
                endInp.value = formatDate(end);
                startInp.form.submit();
            }
        }
    });
</script>

@endsection