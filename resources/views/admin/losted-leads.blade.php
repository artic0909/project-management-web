@extends('admin.layout.app')

@section('title', 'Losted Leads')

@section('content')

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .modal-header .btn-close { filter: none; }
    [data-theme="dark"] .modal-header .btn-close { filter: invert(1); }

    /* ── 6-column uniform grid ── */
    .stat-grid-wrap {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        gap: 10px;
        margin-bottom: 20px;
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

    /* ── Contact Icons UI ── */
    .contact-actions {
        display: flex;
        gap: 6px;
        margin-top: 4px;
    }
    .contact-btn {
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        cursor: pointer;
        transition: var(--transition);
        border: 1px solid var(--b1);
        background: var(--bg2);
        color: var(--t3);
        text-decoration: none;
    }
    .contact-btn:hover {
        background: var(--accent-bg);
        color: var(--accent);
        border-color: var(--accent);
        transform: translateY(-1px);
    }
    .contact-btn.phone:hover {
        background: rgba(16, 185, 129, 0.1);
        color: #10b981;
        border-color: #10b981;
    }

    .row-actions {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        flex-wrap: nowrap !important;
        white-space: nowrap !important;
    }

    .ra-btn.phone:hover {
        background: rgba(16, 185, 129, 0.1) !important;
        color: #10b981 !important;
        border-color: #10b981 !important;
    }

    /* ── Select2 Customization ── */
    .bulk-assign-wrap .select2-container--default .select2-selection--single {
        background-color: var(--bg2) !important;
        border: 1px solid var(--b1) !important;
        border-radius: var(--r-sm, 8px) !important;
        height: 32px !important;
        display: inline-flex;
        align-items: center;
        font-size: 13px;
        min-width: 180px;
    }
    .bulk-assign-wrap .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--t2) !important;
        padding-left: 8px !important;
        padding-right: 24px !important;
        line-height: 30px !important;
    }
    .bulk-assign-wrap .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 30px !important;
        right: 4px !important;
    }
    .bulk-assign-wrap .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: var(--t3) !important;
    }
    .select2-dropdown {
        background-color: var(--bg2) !important;
        border: 1px solid var(--b1) !important;
        color: var(--t1) !important;
        font-size: 13px;
    }
    .select2-search__field {
        background-color: var(--bg3) !important;
        border: 1px solid var(--b1) !important;
        color: var(--t1) !important;
        border-radius: 4px !important;
        padding: 4px 8px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent) !important;
        color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--b2) !important;
        color: var(--t1) !important;
    }
    .select2-container--default .select2-results__option {
        color: var(--t2) !important;
    }
</style>


<!-- ═══ PAGE CONTENT AREA ═══ -->
<main class="page-area" id="pageArea">

    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Losted Leads</h1>
            </div>
            <div class="d-flex gap-2">
                @if($routePrefix == 'admin')
                <button type="button" class="btn-primary-solid sm" id="bulkDeleteBtn" style="display: none; background: #dc2626; border-color: #dc2626; color: white;" onclick="bulkDeleteSelected()">
                    <i class="bi bi-trash-fill"></i> Bulk Delete
                </button>
                <div id="bulkAssignContainer" style="display: none; align-items: center; gap: 8px;">
                    <select id="bulkAssignSalesperson" class="filter-select" style="margin: 0; padding: 6px 12px; height: 32px; font-size: 13px; min-width: 180px;">
                        <option value="">Assign Salesperson...</option>
                        @foreach($sales as $sale)
                            <option value="{{ $sale->id }}">{{ $sale->name }}</option>
                        @endforeach
                    </select>
                    <button type="button" class="btn-primary-solid sm" onclick="bulkAssignSelected()">
                        <i class="bi bi-person-plus-fill"></i> Assign
                    </button>
                </div>
                <button class="btn-primary-solid sm" onclick="exportLostedLeads()">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                @endif
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

            <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-people-fill"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#6366f1;">Overview</div>
                    <div class="sb-val">{{ $totalLostLeads }}</div>
                    <div class="sb-lbl">Total Losted Leads</div>
                </div>
            </div>

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
                @endif
            @endif

            <div class="stat-box" style="--sb-color:#0ea5e9; border: 2px solid #0ea5e9;">
                <div class="sb-icon"><i class="bi bi-telephone-fill"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#0ea5e9;">Total Calling</div>
                    <div class="sb-val">{{ $totalCallingFollowupsFiltered ?? 0 }}</div>
                    <div class="sb-lbl">Total Calling</div>
                </div>
            </div>

            <div class="stat-box" style="--sb-color:#f43f5e; border: 2px solid #f43f5e;">
                <div class="sb-icon"><i class="bi bi-chat-dots-fill"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#f43f5e;">Total Message</div>
                    <div class="sb-val">{{ $totalMessageFollowupsFiltered ?? 0 }}</div>
                    <div class="sb-lbl">Total Message</div>
                </div>
            </div>

            @if(($priorityCounts['Hot 🔥'] ?? 0) > 0)
            <div class="stat-box" style="--sb-color:#ef4444;">
                <div class="sb-icon"><i class="bi bi-fire"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#ef4444;">Priority</div>
                    <div class="sb-val" style="color:#ef4444;">{{ $priorityCounts['Hot 🔥'] ?? 0 }}</div>
                    <div class="sb-lbl">Hot 🔥</div>
                </div>
            </div>
            @endif

            @if(($priorityCounts['Warm'] ?? 0) > 0)
            <div class="stat-box" style="--sb-color:#f59e0b;">
                <div class="sb-icon"><i class="bi bi-thermometer-half"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#f59e0b;">Priority</div>
                    <div class="sb-val" style="color:#f59e0b;">{{ $priorityCounts['Warm'] ?? 0 }}</div>
                    <div class="sb-lbl">Warm</div>
                </div>
            </div>
            @endif

            @if(($priorityCounts['Cold'] ?? 0) > 0)
            <div class="stat-box" style="--sb-color:#06b6d4;">
                <div class="sb-icon"><i class="bi bi-snow"></i></div>
                <div class="sb-content">
                    <div class="sb-cat" style="--cat-color:#06b6d4;">Priority</div>
                    <div class="sb-val" style="color:#06b6d4;">{{ $priorityCounts['Cold'] ?? 0 }}</div>
                    <div class="sb-lbl">Cold</div>
                </div>
            </div>
            @endif

            {{-- Status (Dynamic) --}}
            @if(isset($statuses))
            @foreach($statuses as $st)
                @if($st->leads_count > 0)
                <div class="stat-box" style="--sb-color:#6366f1;">
                    <div class="sb-icon"><i class="bi bi-hash"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#6366f1;">Status</div>
                        <div class="sb-val">{{ $st->leads_count }}</div>
                        <div class="sb-lbl">{{ $st->name }}</div>
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            {{-- Additional Dynamics --}}
            @if($routePrefix == 'sale')
            @foreach($sources as $src)
                @if($src->leads_count > 0)
                <div class="stat-box" style="--sb-color:#8b5cf6;">
                    <div class="sb-icon"><i class="bi bi-box-arrow-in-right"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#8b5cf6;">Source</div>
                        <div class="sb-val">{{ $src->leads_count }}</div>
                        <div class="sb-lbl">{{ $src->name }}</div>
                    </div>
                </div>
                @endif
            @endforeach

            @foreach($services as $srv)
                @if($srv->leads_count > 0)
                <div class="stat-box" style="--sb-color:#ec4899;">
                    <div class="sb-icon"><i class="bi bi-briefcase"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#ec4899;">Service</div>
                        <div class="sb-val">{{ $srv->leads_count }}</div>
                        <div class="sb-lbl">{{ $srv->name }}</div>
                    </div>
                </div>
                @endif
            @endforeach
            @endif

            @foreach($campaigns as $cmp)
                @if($cmp->leads_count > 0)
                <div class="stat-box" style="--sb-color:#f59e0b;">
                    <div class="sb-icon"><i class="bi bi-megaphone"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#f59e0b;">Campaign</div>
                        <div class="sb-val">{{ $cmp->leads_count }}</div>
                        <div class="sb-lbl">{{ $cmp->name }}</div>
                    </div>
                </div>
                @endif
            @endforeach

        </div>

        <!-- MAIN GRID -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Losted Leads</div>
                        <div class="card-sub" id="drpActiveSub">
                            @if(request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('M d') }} – {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                            @elseif(request('q'))
                                Search: "{{ request('q') }}"
                            @else
                                Overall Summary
                            @endif
                            · {{ $totalLostLeads }} total 
                            · {{ $priorityCounts['Hot 🔥'] ?? 0 }} hot leads
                        </div>
                    </div>
                    <form action="{{ route($routePrefix . '.losted-leads') }}" method="GET" class="card-actions mb-2">
                        <div class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search..." autocomplete="off">
                            <button type="submit" class="btn-primary-solid sm" style="display: none;">Search</button>
                        </div>

                        <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                        <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                            <i class="bi bi-calendar3"></i>
                            <span style="opacity: 0.7; margin-right: 2px;">Created:</span>
                            <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'Default' }}</span>
                            <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                        </button>

                        <!-- Hidden inputs for date range from the custom picker -->
                        <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                        @if($routePrefix == 'sale')
                        <select name="source_id" class="filter-select" onchange="updateFilters()">
                            <option value="">Lead Source</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ request('source_id') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        
                        <select name="campaign_id" class="filter-select" onchange="updateFilters()">
                            <option value="">All Campaigns</option>
                            @foreach($campaigns as $campaign)
                                <option value="{{ $campaign->id }}" {{ request('campaign_id') == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                            @endforeach
                        </select>

                        @if($routePrefix == 'sale')
                        <select name="service_id" class="filter-select" onchange="updateFilters()">
                            <option value="">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>
                        @endif

                        <select name="priority" class="filter-select" onchange="updateFilters()">
                            <option value="">Priority</option>
                            <option value="Hot 🔥" {{ request('priority') == 'Hot 🔥' ? 'selected' : '' }}>Hot 🔥</option>
                            <option value="Warm" {{ request('priority') == 'Warm' ? 'selected' : '' }}>Warm</option>
                            <option value="Cold" {{ request('priority') == 'Cold' ? 'selected' : '' }}>Cold</option>
                        </select>
                        @if(isset($statuses))
                        <select name="status_id" class="filter-select" onchange="updateFilters()">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
                        </select>
                        @endif
                        <select name="per_page" class="filter-select" onchange="updateFilters()">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Rows</option>
                            <option value="20" {{ (request('per_page') == 20 || !request('per_page')) ? 'selected' : '' }}>20 Rows</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Rows</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Rows</option>
                            <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All Rows</option>
                        </select>
                        @if($routePrefix == 'admin')
                        <select name="assigned_to" class="filter-select" onchange="updateFilters()">
                            <option value="">Sales Person</option>
                            @foreach($sales as $sale)
                                <option value="{{ $sale->id }}" {{ request('assigned_to') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                            @endforeach
                        </select>
                        @endif
                    </form>

                    <div style="position:relative;">
                        @include('admin.includes.date-range-picker')
                    </div>
                </div>

                <div id="leadsTableWrap">
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                @if($routePrefix == 'admin')
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAllLeads" onclick="toggleAllLeads(this)" style="cursor: pointer;">
                                </th>
                                @endif
                                <th>SL</th>
                                <th>Date</th>
                                @if(!($routePrefix == 'sale'))
                                <th>Lead</th>
                                @endif
                                @if($routePrefix == 'sale')
                                <th>Campaign / Source</th>
                                <th>Contact Person</th>
                                @else
                                <th>Campaign</th>
                                @endif
                                <th>Phone</th>
                                <th>Priority</th>
                                <th>Status</th>
                                @if($routePrefix == 'sale')
                                <th>Created By</th>
                                @endif
                                @if(!($routePrefix == 'sale'))
                                <th>Sales Person</th>
                                @endif
                                <th>Followup</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                            @endphp
                            @forelse($leads as $index => $lead)
                            <tr id="lead-{{ $lead->id }}" @if(session('highlight_lead_id') == $lead->id) style="background-color: rgba(16, 185, 129, 0.15);" @endif>
                                @if($routePrefix == 'admin')
                                <td style="text-align: center;">
                                    <input type="checkbox" class="lead-checkbox" name="lead_ids[]" value="{{ $lead->id }}" onclick="updateBulkDeleteButton()" style="cursor: pointer;">
                                </td>
                                @endif
                                <td>{{ $leads->firstItem() + $index }}</td>
                                <td><div class="ls" style="font-size:12px; font-weight:600;">{{ $lead->created_at->format('d M Y') }}</div></td>
                                @if($routePrefix == 'admin')
                                <td>
                                    <div class="lead-cell">
                                        @php
                                            $initials = strtoupper(substr($lead->company, 0, 1) . substr($lead->contact_person, 0, 1));
                                            $emails = is_array($lead->emails) ? ($lead->emails[0] ?? 'N/A') : (json_decode($lead->emails)[0] ?? 'N/A');
                                        @endphp
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ $initials }}</div>
                                        <div>
                                            <div class="ln">
                                                {{ $lead->company }}
                                            </div>
                                            <div class="ls">{{ $emails }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="src-tag">{{ $lead->campaign->name ?? 'N/A' }}</span>
                                </td>
                                @else
                                <td>
                                    <span class="src-tag">{{ $lead->campaign->name ?? 'N/A' }}</span>
                                    <div style="margin-top:4px; display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach($lead->sources as $src)
                                            <span style="font-size:10px; background:var(--bg3); border:1px solid var(--b1); padding:2px 6px; border-radius:4px; color:var(--t3);">{{ $src->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td><strong style="color:var(--t2)">{{ $lead->contact_person }}</strong></td>
                                @endif
                                <td>
                                    @foreach($lead->phones as $p)
                                        <strong style="color:var(--t2)">
                                            {{ ($codes[$p['code_idx']] ?? '') . $p['number'] }}
                                        </strong><br>
                                    @endforeach
                                </td>
                                <td>
                                    @php
                                        $pCls = strtolower(str_replace([' ', '🔥'], '', $lead->priority));
                                    @endphp
                                    <span class="lead-stage {{ $pCls }}">{{ $lead->priority }}</span>
                                </td>
                                <td><strong style="color:var(--accent)">{{ $lead->status->name ?? 'N/A' }}</strong></td>
                                @if($routePrefix == 'sale')
                                <td>
                                    @if($lead->createdBy)
                                        <div class="ln">{{ $lead->createdBy->name }}</div>
                                    @else
                                        <div class="ln">System</div>
                                    @endif
                                </td>
                                @endif
                                @if(!($routePrefix == 'sale'))
                                <td>
                                    @foreach($lead->assignments as $assign)
                                        <div class="ln">
                                            {{ $assign->sale->name ?? 'N/A' }}
                                        </div>
                                    @endforeach
                                    @if($lead->assignments->isEmpty())
                                        <span style="color:var(--t4)">Unassigned</span>
                                    @endif
                                </td>
                                @endif
                                <td>
                                    <button type="button" class="badge" onclick="openFollowupTimelineModal({{ $lead->id }}, '{{ addslashes($lead->company) }}')" style="background:rgba(99, 102, 241, 0.1); color:var(--accent); padding:4px 10px; border-radius:6px; font-weight:700; font-family:var(--font-mono); font-size:12px; cursor:pointer; border:none; outline:none; transition:var(--transition);" onmouseover="this.style.background='rgba(99,102,241,0.2)'" onmouseout="this.style.background='rgba(99,102,241,0.1)'">
                                        {{ $lead->followups_count }}
                                    </button>
                                    
                                    <template id="followup-timeline-{{ $lead->id }}">
                                        @php
                                            $leadFollowups = $lead->followups()->with('creator')->orderBy('followup_date', 'desc')->get();
                                        @endphp

                                        <form action="{{ route($routePrefix . '.leads.followup.store', $lead->id) }}" method="POST" style="margin-bottom: 24px;">
                                            @csrf
                                            <input type="hidden" name="return_url" value="{{ request()->fullUrl() }}">
                                            
                                            <div class="dash-card" style="background:var(--bg2); border:1px solid var(--b1); border-radius:12px; overflow:hidden;">
                                                <div class="card-body" style="padding:14px 18px 20px;">
                                                    <div class="form-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:16px;">
                                                        <div class="form-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Change Status <span style="color:#ef4444">*</span></label>
                                                            <select name="status_id" class="form-inp" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                                <option value="" {{ empty($lead->status_id) ? 'selected' : '' }} disabled hidden>Select Status</option>
                                                                @foreach($statuses as $status)
                                                                    <option value="{{ $status->id }}" {{ $lead->status_id == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="form-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Set Priority <span style="color:#ef4444">*</span></label>
                                                            <select name="priority" class="form-inp" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                                <option value="" {{ empty($lead->priority) ? 'selected' : '' }} disabled hidden>Select Priority</option>
                                                                <option value="Hot 🔥" {{ $lead->priority == 'Hot 🔥' ? 'selected' : '' }}>Hot 🔥</option>
                                                                <option value="Warm" {{ $lead->priority == 'Warm' ? 'selected' : '' }}>Warm</option>
                                                                <option value="Cold" {{ $lead->priority == 'Cold' ? 'selected' : '' }}>Cold</option>
                                                            </select>
                                                        </div>
                                                        <div class="form-row followup-date-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Followup Date <span style="color:#ef4444">*</span></label>
                                                            <input type="datetime-local" name="followup_date" class="form-inp" value="{{ date('Y-m-d\TH:i') }}" max="{{ date('Y-m-d\TH:i') }}" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card-body" style="padding:14px 18px 20px;">
                                                    <div class="form-grid" style="display:grid; grid-template-columns:repeat(3, 1fr); gap:16px; margin-bottom:16px;">
                                                        <div class="form-row schedule-next-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Schedule Next <span style="color:#ef4444">*</span></label>
                                                            <select name="schedule_type" class="form-inp" required onchange="const c = this.nextElementSibling; if(this.value==='Custom') c.style.display='block'; else c.style.display='none';" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                                <option value="Today">Today</option>
                                                                <option value="Tomorrow">Tomorrow</option>
                                                                <option value="After 2 Days">After 2 days</option>
                                                                <option value="After 3 Days">After 3 days</option>
                                                                <option value="After 5 Days">After 5 days</option>
                                                                <option value="After 7 Days">After 7 days</option>
                                                                <option value="No Schedule">Closed Schedule</option>
                                                                <option value="Custom">Custom Date</option>
                                                            </select>
                                                            <div style="display:none; margin-top:8px;">
                                                                <input type="date" name="custom_schedule_date" class="form-inp" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                            </div>
                                                        </div>
                                                        <div class="form-row schedule-time-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Set Schedule Time <span style="font-size:10px;color:var(--t4);">(Optional)</span></label>
                                                            <input type="time" name="schedule_time" class="form-inp" style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;">
                                                        </div>
                                                        <div class="form-row">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Interaction Vector <span style="color:#ef4444">*</span></label>
                                                            <select name="followup_type" class="form-inp" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none;" onchange="
                                                                const val = this.value; 
                                                                const form = this.closest('form');
                                                                const cArea = form.querySelector('.calling-note-row');
                                                                const mArea = form.querySelector('.message-note-row');
                                                                const fDateRow = form.querySelector('.followup-date-row');
                                                                const sNextRow = form.querySelector('.schedule-next-row');
                                                                const sTimeRow = form.querySelector('.schedule-time-row');
                                                                const cInp = cArea.querySelector('textarea');
                                                                const mInp = mArea.querySelector('textarea');
                                                                
                                                                cArea.style.display = (val === 'Calling' || val === 'Both') ? 'block' : 'none';
                                                                mArea.style.display = (val === 'Message' || val === 'Both') ? 'block' : 'none';
                                                                cArea.style.gridColumn = (val === 'Calling') ? '1 / -1' : 'auto';
                                                                mArea.style.gridColumn = (val === 'Message') ? '1 / -1' : 'auto';
                                                                cInp.required = (val === 'Calling' || val === 'Both');
                                                                mInp.required = (val === 'Message' || val === 'Both');

                                                                if (val === 'None') {
                                                                    if (fDateRow) { fDateRow.style.display = 'none'; fDateRow.querySelector('input').required = false; }
                                                                    if (sNextRow) { sNextRow.style.display = 'none'; sNextRow.querySelector('select').required = false; }
                                                                    if (sTimeRow) { sTimeRow.style.display = 'none'; }
                                                                } else {
                                                                    if (fDateRow) { fDateRow.style.display = 'block'; fDateRow.querySelector('input').required = true; }
                                                                    if (sNextRow) { sNextRow.style.display = 'block'; sNextRow.querySelector('select').required = true; }
                                                                    if (sTimeRow) { sTimeRow.style.display = 'block'; }
                                                                }
                                                            ">
                                                                <option value="None">None</option>
                                                                <option value="Calling">Calling</option>
                                                                <option value="Message">Message</option>
                                                                <option value="Both" selected>Both</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-grid" style="display:grid; grid-template-columns:repeat(2, 1fr); gap:16px; margin-bottom:16px;">
                                                        <div class="form-row calling-note-row" style="display:block;">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Calling Note <span style="color:#ef4444">*</span></label>
                                                            <textarea name="calling_note" class="form-inp" rows="2" placeholder="What was discussed during the call?" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none; resize:vertical;"></textarea>
                                                        </div>
                                                        <div class="form-row message-note-row" style="margin-bottom:0; display:block;">
                                                            <label class="form-lbl" style="display:block; font-size:12px; font-weight:600; color:var(--t2); margin-bottom:6px;">Message Note <span style="color:#ef4444">*</span></label>
                                                            <textarea name="message_note" class="form-inp" rows="2" placeholder="Summary of messages, emails, or drafts sent…" required style="width:100%; padding:8px 12px; border-radius:6px; border:1px solid var(--b1); background:var(--bg3); color:var(--t1); font-size:13px; outline:none; resize:vertical;"></textarea>
                                                        </div>
                                                    </div>
                                                    <div style="display:flex;justify-content:flex-end;margin-top:16px;">
                                                        <button type="submit" class="btn-primary-solid" style="background:var(--accent); color:#fff; border:none; border-radius:6px; padding:10px 18px; font-size:13px; font-weight:600; cursor:pointer; display:flex; align-items:center; gap:8px;">
                                                            <i class="bi bi-plus-lg"></i> Record Followup & Update
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>

                                        <div style="position:relative;padding-left:26px;">
                                            <div style="position:absolute;left:10px;top:4px;bottom:0;width:2px;background:var(--b2);border-radius:2px;"></div>
                                            @forelse($leadFollowups as $followup)
                                                @php 
                                                    $typeColor = $followup->followup_type == 'Calling' ? '#10b981' : ($followup->followup_type == 'Message' ? '#f59e0b' : '#6366f1');
                                                    $typeBg = $followup->followup_type == 'Calling' ? 'rgba(16,185,129,.1)' : ($followup->followup_type == 'Message' ? 'rgba(245,158,11,.1)' : 'rgba(99,102,241,.1)');
                                                @endphp
                                                <div style="position:relative;margin-bottom:20px;">
                                                    <div style="position:absolute;left:-23px;top:14px;width:14px;height:14px;border-radius:50%;background:var(--bg1);border:3px solid {{ $typeColor }};"></div>
                                                    
                                                    <div class="history-item-box" style="background:var(--bg3);border:1px solid var(--b1);border-radius:14px;overflow:hidden;">
                                                        <div style="padding:10px 14px;background:var(--bg2);border-bottom:1px solid var(--b1);display:flex;align-items:center;justify-content:space-between;">
                                                            <div style="display:flex;align-items:center;gap:8px;">
                                                                <div style="font-size:12px;font-weight:700;color:{{ $typeColor }};background:{{ $typeBg }};padding:3px 10px;border-radius:6px;border:1px solid {{ str_replace('0.1','0.2',$typeBg) }};">
                                                                    {{ $followup->followup_date->format('d M Y, h:i A') }}
                                                                </div>
                                                                <span style="font-size:11px;color:var(--t3);font-weight:600;">
                                                                    @if($followup->followup_type == 'Calling') <i class="bi bi-telephone-outbound"></i> Call 
                                                                    @elseif($followup->followup_type == 'Message') <i class="bi bi-chat-dots"></i> Message
                                                                    @else <i class="bi bi-intersect"></i> Unified
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            <div style="display:flex;align-items:center;gap:12px;">
                                                                <div style="font-size:10px;color:var(--t4);">
                                                                    Logged by: 
                                                                    @if($followup->creator)
                                                                        {{ $followup->creator->name }} - {{ $followup->creator->email }}
                                                                    @else
                                                                        System
                                                                    @endif
                                                                </div>
                                                                @php
                                                                    $fData = [
                                                                        'id' => $followup->id,
                                                                        'date' => $followup->followup_date->format('Y-m-d\TH:i'),
                                                                        'type' => $followup->followup_type,
                                                                        'cNote' => $followup->calling_note,
                                                                        'mNote' => $followup->message_note,
                                                                        'sDate' => $followup->next_schedule_date ? $followup->next_schedule_date->format('Y-m-d') : '',
                                                                        'sTime' => $followup->next_schedule_date ? $followup->next_schedule_date->format('H:i') : ''
                                                                    ];
                                                                @endphp
                                                                <button type="button" class="btn-ghost sm" style="padding:4px; font-size:12px; color:var(--t3);" data-followup="{{ json_encode($fData) }}" onclick="openEditFollowupModal(this)">
                                                                    <i class="bi bi-pencil"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                        
                                                        <div style="padding:12px 14px;display:flex;flex-direction:column;gap:10px;">
                                                            @if($followup->calling_note)
                                                                <div style="border-left:3px solid #10b981;padding-left:10px;">
                                                                    <p style="font-size:10.5px;font-weight:800;color:var(--t4);margin:0 0 2px;text-transform:uppercase;">Call Intelligence</p>
                                                                    <p style="font-size:13.5px;color:var(--t2);margin:0;line-height:1.6;font-weight:500;">{{ $followup->calling_note }}</p>
                                                                </div>
                                                            @endif
                                                            @if($followup->message_note)
                                                                <div style="border-left:3px solid #f59e0b;padding-left:10px;">
                                                                    <p style="font-size:10.5px;font-weight:800;color:var(--t4);margin:0 0 2px;text-transform:uppercase;">Messengers Records</p>
                                                                    <p style="font-size:13.5px;color:var(--t2);margin:0;line-height:1.6;font-weight:500;">{{ $followup->message_note }}</p>
                                                                </div>
                                                            @endif
                                                            @if($followup->next_schedule_date)
                                                                <div style="border-left:3px solid #6366f1;padding-left:10px;">
                                                                    <p style="font-size:10.5px;font-weight:800;color:var(--t4);margin:0 0 2px;text-transform:uppercase;">Next Schedule</p>
                                                                    <p style="font-size:13.5px;color:var(--accent);margin:0;line-height:1.6;font-weight:700;"><i class="bi bi-clock-history"></i>
                                                                        @if($followup->next_schedule_date->format('H:i') === '00:00')
                                                                            {{ $followup->next_schedule_date->format('d M Y') }}
                                                                        @else
                                                                            {{ $followup->next_schedule_date->format('d M Y, h:i A') }}
                                                                        @endif
                                                                    </p>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <div style="padding:20px;text-align:center;color:var(--t4);font-style:italic;">No interactions recorded yet.</div>
                                            @endforelse
                                            <div style="position:relative;margin-top:10px;">
                                                <div style="position:absolute;left:-23px;top:4px;width:14px;height:14px;border-radius:50%;background:var(--accent);display:flex;align-items:center;justify-content:center;">
                                                    <i class="bi bi-star-fill" style="font-size:7px;color:#fff;"></i>
                                                </div>
                                                <div style="padding-left:2px;">
                                                    <span style="font-size:12px;color:var(--t3);font-weight:600;">Lead journey started on <strong style="color:var(--t1);">{{ $lead->created_at->format('d M Y') }}</strong></span>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @php
                                            $phoneList = is_array($lead->phones) ? $lead->phones : [];
                                            $emailList = is_array($lead->emails) ? $lead->emails : [];
                                            $fullPhones = [];
                                            foreach($phoneList as $p) {
                                                $fullPhones[] = ($codes[$p['code_idx']] ?? '') . $p['number'];
                                            }
                                        @endphp
                                        <a href="javascript:void(0)" class="ra-btn phone" 
                                           onclick="handleContactClick(event, 'tel', {{ json_encode($fullPhones) }})" title="Call Lead">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ra-btn email" 
                                           onclick="handleContactClick(event, 'mailto', {{ json_encode($emailList) }})" title="Email Lead">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route($routePrefix . '.meetings.create', ['lead_id' => $lead->id]) }}" class="ra-btn" title="Meeting"><i class="bi bi-camera-video-fill"></i></a>

                                        <a href="{{ route($routePrefix . '.losted-leads.show', $lead->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route($routePrefix . '.leads.followup', ['id' => $lead->id, 'return_url' => request()->fullUrl()]) }}" class="ra-btn" title="Followup"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a class="ra-btn" title="Edit" href="{{ route($routePrefix . '.leads.edit', $lead->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                      
                                        @if($routePrefix == 'admin')
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route($routePrefix . '.leads.destroy', $lead->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                        @endif
                                       
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="12" style="text-align:center;padding:40px;color:var(--t4);">No lost leads found.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing {{ $leads->count() }} of {{ $leads->total() }} Lost Leads</span>
                    <div class="tf-pagination">
                        {{ $leads->links('admin.includes.pagination') }}
                    </div>
                    <div class="tf-per-page"></div>
                </div>
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

    <!-- BULK DELETE MODAL -->
    <div class="modal-backdrop" id="bulkDeleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Bulk Delete Leads</span>
                <button class="modal-close" onclick="closeModal('bulkDeleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete the <strong id="bulkDeleteCount">0</strong> selected leads?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('bulkDeleteModal')">Cancel</button>
                <button type="button" id="executeBulkDeleteBtn" onclick="executeBulkDelete()" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-trash3-fill"></i> Delete Leads
                </button>
            </div>
        </div>
    </div>

    <!-- EDIT FOLLOWUP MODAL -->
    <div class="modal-backdrop" id="editFollowupModal" style="z-index: 10000;">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid var(--b1);">
                <span style="color:var(--t1);">Edit Followup</span>
                <button class="modal-close" onclick="closeModal('editFollowupModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="editFollowupForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-bd" style="padding:20px 24px;">
                    <div class="form-grid" style="display:grid; grid-template-columns: 1fr; gap:16px;">
                        <div class="form-row edit-followup-date-row">
                            <label class="form-lbl">Followup Date <span style="color:#ef4444">*</span></label>
                            <input type="datetime-local" name="followup_date" id="edit_f_date" class="form-inp" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Interaction Vector <span style="color:#ef4444">*</span></label>
                            <select name="followup_type" id="edit_f_type" class="form-inp" required onchange="toggleEditFollowupType()">
                                <option value="None">None</option>
                                <option value="Calling">Calling</option>
                                <option value="Message">Message</option>
                                <option value="Both">Both</option>
                            </select>
                        </div>
                        <div class="form-row" id="edit_calling_area">
                            <label class="form-lbl">Calling Note</label>
                            <textarea name="calling_note" id="edit_c_note" class="form-inp" rows="2"></textarea>
                        </div>
                        <div class="form-row" id="edit_message_area">
                            <label class="form-lbl">Message Note</label>
                            <textarea name="message_note" id="edit_m_note" class="form-inp" rows="2"></textarea>
                        </div>
                        <div class="form-row edit-schedule-next-row">
                            <label class="form-lbl">Schedule Next</label>
                            <select name="schedule_type" id="edit_s_type" class="form-inp" onchange="const c = this.nextElementSibling; if(this.value==='Custom') c.style.display='block'; else c.style.display='none';">
                                <option value="">Keep Existing</option>
                                <option value="Today">Today</option>
                                <option value="Tomorrow">Tomorrow</option>
                                <option value="After 2 Days">After 2 days</option>
                                <option value="After 3 Days">After 3 days</option>
                                <option value="After 5 Days">After 5 days</option>
                                <option value="After 7 Days">After 7 days</option>
                                <option value="No Schedule">Closed Schedule</option>
                                <option value="Custom">Custom Date</option>
                            </select>
                            <div style="display:none; margin-top:8px;">
                                <input type="date" name="custom_schedule_date" id="edit_custom_date" class="form-inp">
                            </div>
                        </div>
                        <div class="form-row edit-schedule-time-row">
                            <label class="form-lbl">Set Schedule Time <span style="font-size:10px;color:var(--t4);">(Optional)</span></label>
                            <input type="time" name="schedule_time" id="edit_s_time" class="form-inp">
                        </div>
                    </div>
                </div>
                <div class="modal-ft" style="border-top:1px solid var(--b1); display:flex; justify-content:flex-end; gap:10px; padding:16px 24px;">
                    <button type="button" class="btn-ghost" onclick="closeModal('editFollowupModal')">Cancel</button>
                    <button type="submit" style="background:var(--accent);color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;">
                        <i class="bi bi-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

</main>


<!-- TIMELINE MODAL -->
<div class="modal-backdrop" id="timelineModal" onclick="closeModal('timelineModal')">
    <div class="modal-box" onclick="event.stopPropagation()" style="max-width:760px; max-height:85vh; display:flex; flex-direction:column; padding:0;">
        <div class="modal-hd" style="padding:20px 24px; border-bottom:1px solid var(--b1);">
            <div style="display:flex; flex-direction:column;">
                <span id="timelineModalTitle" style="font-size:18px; font-weight:800; color:var(--t1);">Engagement Timeline</span>
                <span style="font-size:12px; color:var(--t3); font-weight:500; margin-top:2px;">Complete log of all touches for this lead</span>
            </div>
            <button class="modal-close" onclick="closeModal('timelineModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd" style="overflow-y:auto; padding:24px 32px;" id="timelineModalBody">
            <!-- Dynamic Content -->
        </div>
    </div>
</div>

<script>
    function openFollowupTimelineModal(leadId, companyName) {
        document.getElementById('timelineModalTitle').innerText = 'Engagement Timeline: ' + companyName;
        document.getElementById('timelineModalBody').innerHTML = document.getElementById('followup-timeline-' + leadId).innerHTML;
        openModal('timelineModal');
    }

    function openEditFollowupModal(btn) {
        const data = JSON.parse(btn.getAttribute('data-followup'));
        const form = document.getElementById('editFollowupForm');
        const prefix = window.location.pathname.startsWith('/admin') ? 'admin' : 'sale';
        form.action = `/${prefix}/followup/${data.id}`;
        
        document.getElementById('edit_f_date').value = data.date;
        document.getElementById('edit_f_type').value = data.type;
        document.getElementById('edit_c_note').value = data.cNote || '';
        document.getElementById('edit_m_note').value = data.mNote || '';
        document.getElementById('edit_custom_date').value = data.sDate || '';
        document.getElementById('edit_s_time').value = data.sTime || '';
        
        const sTypeSelect = document.getElementById('edit_s_type');
        if (data.sDate) {
            sTypeSelect.value = 'Custom';
            sTypeSelect.nextElementSibling.style.display = 'block';
        } else {
            sTypeSelect.value = '';
            sTypeSelect.nextElementSibling.style.display = 'none';
        }

        toggleEditFollowupType();
        openModal('editFollowupModal');
    }

    function toggleEditFollowupType() {
        const val = document.getElementById('edit_f_type').value;
        const cArea = document.getElementById('edit_calling_area');
        const mArea = document.getElementById('edit_message_area');
        const cInp = document.getElementById('edit_c_note');
        const mInp = document.getElementById('edit_m_note');
        
        cArea.style.display = (val === 'Calling' || val === 'Both') ? 'block' : 'none';
        mArea.style.display = (val === 'Message' || val === 'Both') ? 'block' : 'none';
        cInp.required = (val === 'Calling' || val === 'Both');
        mInp.required = (val === 'Message' || val === 'Both');

        const fDateRow = document.querySelector('.edit-followup-date-row');
        const sNextRow = document.querySelector('.edit-schedule-next-row');
        const sTimeRow = document.querySelector('.edit-schedule-time-row');

        if (val === 'None') {
            if (fDateRow) { fDateRow.style.display = 'none'; fDateRow.querySelector('input').required = false; }
            if (sNextRow) { sNextRow.style.display = 'none'; sNextRow.querySelector('select').required = false; }
            if (sTimeRow) { sTimeRow.style.display = 'none'; }
        } else {
            if (fDateRow) { fDateRow.style.display = 'block'; fDateRow.querySelector('input').required = true; }
            if (sNextRow) { sNextRow.style.display = 'block'; sNextRow.querySelector('select').required = true; }
            if (sTimeRow) { sTimeRow.style.display = 'block'; }
        }
    }

    (function() {
        window.updateFilters = function() {
            const form = document.querySelector('.card-actions');
            if (!form) return;
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = new URL(window.location.pathname, window.location.origin);
            url.search = params.toString();
            fetchAndReplace(url);
        };

        async function fetchAndReplace(url) {
            const wrap = document.getElementById('leadsTableWrap');
            if (wrap) wrap.style.opacity = '0.5';

            try {
                const response = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                const newContent = doc.getElementById('leadsTableWrap');
                if (newContent && wrap) {
                    wrap.innerHTML = newContent.innerHTML;
                }
                
                const newStats = doc.querySelector('.stat-grid-wrap');
                const oldStats = document.querySelector('.stat-grid-wrap');
                if (newStats && oldStats) {
                    oldStats.innerHTML = newStats.innerHTML;
                }

                const newSub = doc.getElementById('drpActiveSub');
                const oldSub = document.getElementById('drpActiveSub');
                if (newSub && oldSub) {
                    oldSub.innerHTML = newSub.innerHTML;
                }

                if (wrap) wrap.style.opacity = '1';
                window.history.pushState({}, '', url);
                if (typeof updateBulkDeleteButton === 'function') {
                    updateBulkDeleteButton();
                }
            } catch (error) {
                console.error('AJAX error:', error);
                if (wrap) wrap.style.opacity = '1';
            }
        }

        window.exportLostedLeads = function() {
            const form = document.querySelector('.card-actions');
            let params = '';
            if (form) {
                const formData = new FormData(form);
                params = '?' + new URLSearchParams(formData).toString();
            }
            window.location.href = "{{ route($routePrefix . '.losted-leads.export') }}" + params;
        };

        let debounceTimer;
        document.addEventListener('input', function(e) {
            if (e.target && e.target.id === 'searchQuery') {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(updateFilters, 500);
            }
        });

        // Intercept pagination clicks
        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.tf-pagination a');
            if (paginationLink) {
                e.preventDefault();
                fetchAndReplace(new URL(paginationLink.href));
            }
        });
    })();

    function toggleAllLeads(source) {
        const checkboxes = document.querySelectorAll('.lead-checkbox');
        checkboxes.forEach(cb => cb.checked = source.checked);
        updateBulkDeleteButton();
    }

    function updateBulkDeleteButton() {
        const checkedCount = document.querySelectorAll('.lead-checkbox:checked').length;
        const bulkBtn = document.getElementById('bulkDeleteBtn');
        const bulkAssignContainer = document.getElementById('bulkAssignContainer');
        if (checkedCount > 0) {
            if (bulkBtn) bulkBtn.style.display = 'inline-flex';
            if (bulkAssignContainer) bulkAssignContainer.style.display = 'inline-flex';
        } else {
            if (bulkBtn) bulkBtn.style.display = 'none';
            if (bulkAssignContainer) bulkAssignContainer.style.display = 'none';
        }
    }

    function bulkAssignSelected() {
        const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        const salespersonId = document.getElementById('bulkAssignSalesperson').value;
        if (!salespersonId) {
            alert('Please select a salesperson to assign the leads to.');
            return;
        }

        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('admin.leads.bulk-assign') }}";
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);
        
        ids.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });

        const salespersonInput = document.createElement('input');
        salespersonInput.type = 'hidden';
        salespersonInput.name = 'assigned_to';
        salespersonInput.value = salespersonId;
        form.appendChild(salespersonInput);

        document.body.appendChild(form);
        form.submit();
    }

    function bulkDeleteSelected() {
        const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        document.getElementById('bulkDeleteCount').innerText = checkedBoxes.length;
        openModal('bulkDeleteModal');
    }

    function executeBulkDelete() {
        const checkedBoxes = document.querySelectorAll('.lead-checkbox:checked');
        if (checkedBoxes.length === 0) return;

        document.getElementById('executeBulkDeleteBtn').disabled = true;
        document.getElementById('executeBulkDeleteBtn').innerText = 'Deleting...';

        const ids = Array.from(checkedBoxes).map(cb => cb.value);
        
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route($routePrefix . '.leads.bulk-destroy') }}";
        
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = "{{ csrf_token() }}";
        form.appendChild(csrfInput);
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        ids.forEach(id => {
            const idInput = document.createElement('input');
            idInput.type = 'hidden';
            idInput.name = 'ids[]';
            idInput.value = id;
            form.appendChild(idInput);
        });

        document.body.appendChild(form);
        form.submit();
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
                updateFilters();
            }
        }
    });
</script>

<!-- ── Contact Selection Modal (Bootstrap) ── -->
<div class="modal fade" id="contactSelectionModal" tabindex="-1" aria-labelledby="contactSelectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background: var(--bg2); border-color: var(--b2); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
            <div class="modal-header" style="border-bottom-color: var(--b1);">
                <h5 class="modal-title" id="contactSelectionModalLabel" style="color: #ef4444; font-weight: 700;">Select Option</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
            </div>
            <div class="modal-body p-0">
                <div class="list-group list-group-flush" id="contactSelectionOptions">
                </div>
            </div>
            <div class="modal-footer" style="border-top-color: var(--b1);">
                <button type="button" class="btn btn-secondary sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleContactClick(e, protocol, options) {
        e.preventDefault();
        e.stopPropagation();

        if (!options || (Array.isArray(options) && options.length === 0)) {
            alert('No contact details available');
            return;
        }

        const optArr = (typeof options === 'object' && !Array.isArray(options)) ? Object.values(options) : options;

        if (optArr.length === 1) {
            window.location.href = protocol + ':' + optArr[0];
            return;
        }

        const modalEl = document.getElementById('contactSelectionModal');
        const optionsGroup = document.getElementById('contactSelectionOptions');
        const titleEl = document.getElementById('contactSelectionModalLabel');

        if (!modalEl || !optionsGroup || !titleEl) return;

        titleEl.textContent = 'Select ' + (protocol === 'tel' ? 'Phone Number' : 'Email Address');
        optionsGroup.innerHTML = '';

        optArr.forEach(opt => {
            const item = document.createElement('a');
            item.className = 'list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-bottom-0';
            item.style.cssText = 'background: transparent; color: var(--t2); border-bottom: 1px solid var(--b1) !important;';
            item.href = protocol + ':' + opt;
            item.innerHTML = `
                <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99,102,241,0.1); display: flex; align-items: center; justify-content: center;">
                    <i class="bi bi-${protocol === 'tel' ? 'telephone-fill' : 'envelope-fill'}" style="color: var(--accent);"></i>
                </div>
                <span style="font-weight: 600; font-size: 15px;">${opt}</span>
            `;
            item.onmouseover = () => { item.style.background = 'var(--bg3)'; item.style.color = 'var(--accent)'; };
            item.onmouseout = () => { item.style.background = 'transparent'; item.style.color = 'var(--t2)'; };
            item.onclick = (e) => {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            };
            optionsGroup.appendChild(item);
        });

        const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
        modal.show();
    }

    document.addEventListener('change', function(e) {
        if (e.target.name === 'followup_type') {
            const form = e.target.closest('form');
            if (!form) return;
            const type = e.target.value;
            const callingNote = form.querySelector('[name="calling_note"]');
            const messageNote = form.querySelector('[name="message_note"]');
            
            if (type === 'None') {
                if (callingNote) { callingNote.closest('.form-row').style.display = 'none'; callingNote.required = false; }
                if (messageNote) { messageNote.closest('.form-row').style.display = 'none'; messageNote.required = false; }
            } else if (type === 'Calling') {
                if (callingNote) { callingNote.closest('.form-row').style.display = 'block'; callingNote.required = true; }
                if (messageNote) { messageNote.closest('.form-row').style.display = 'none'; messageNote.required = false; }
            } else if (type === 'Message') {
                if (callingNote) { callingNote.closest('.form-row').style.display = 'none'; callingNote.required = false; }
                if (messageNote) { messageNote.closest('.form-row').style.display = 'block'; messageNote.required = true; }
            } else if (type === 'Both') {
                if (callingNote) { callingNote.closest('.form-row').style.display = 'block'; callingNote.required = true; }
                if (messageNote) { messageNote.closest('.form-row').style.display = 'block'; messageNote.required = true; }
            }
        }
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        if ($('#bulkAssignSalesperson').length) {
            $('#bulkAssignSalesperson').select2({
                placeholder: "Assign Salesperson...",
                allowClear: true,
                width: '180px'
            });
        }
    });
</script>
@endsection