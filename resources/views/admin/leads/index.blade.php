@extends('admin.layout.app')

@section('title', 'All ad Leads')

@section('content')

<style>
    .modal-header .btn-close { filter: none; }
    [data-theme="dark"] .modal-header .btn-close { filter: invert(1); }

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

    @media (max-width: 768px) {
        .row-actions {
            flex-wrap: wrap;
            justify-content: flex-end;
        }
    }

    .ra-btn.phone:hover {
        background: rgba(16, 185, 129, 0.1) !important;
        color: #10b981 !important;
        border-color: #10b981 !important;
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
                <button type="button" class="btn-primary-solid sm" onclick="openImportModal()">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>
                <button class="btn-primary-solid sm" onclick="exportLeads()">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                <a class="btn-primary-solid sm" href="{{ route($routePrefix . '.leads.create') }}">
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



                <div class="stat-box" style="--sb-color:#0ea5e9; border: 2px solid #0ea5e9;">
                    <div class="sb-icon"><i class="bi bi-telephone-fill"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#0ea5e9;">Total Calling</div>
                        <div class="sb-val">{{ $totalCallingFollowupsFiltered }}</div>
                        <div class="sb-lbl">Assigned Calling</div>
                    </div>
                </div>

                <div class="stat-box" style="--sb-color:#f43f5e; border: 2px solid #f43f5e;">
                    <div class="sb-icon"><i class="bi bi-chat-dots-fill"></i></div>
                    <div class="sb-content">
                        <div class="sb-cat" style="--cat-color:#f43f5e;">Total Message</div>
                        <div class="sb-val">{{ $totalMessageFollowupsFiltered }}</div>
                        <div class="sb-lbl">Assigned Message</div>
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

            {{-- Row 2+: Status (Dynamic) --}}
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

            {{-- Additional Dynamics --}}
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
                        <div class="card-title">Lead Pipeline</div>
                        <div class="card-sub" id="drpActiveSub">
                            @if(request('start_date') && request('end_date'))
                                {{ \Carbon\Carbon::parse(request('start_date'))->format('M d') }} – {{ \Carbon\Carbon::parse(request('end_date'))->format('M d, Y') }}
                            @elseif(request('q'))
                                Search: "{{ request('q') }}"
                            @else
                                Overall Summary
                            @endif
                            · {{ $totalLeads }} total 
                            · {{ $priorityCounts['Hot 🔥'] ?? 0 }} hot leads
                        </div>
                    </div>
                    <form action="{{ route($routePrefix . '.leads.index') }}" method="GET" class="card-actions mb-2">
                        <div class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search..." autocomplete="off">
                            <button type="submit" class="btn-primary-solid sm" style="display: none;">Search</button>
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

                        <select name="source_id" class="filter-select" onchange="updateFilters()">
                            <option value="">Lead Source</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ request('source_id') == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                            @endforeach
                        </select>

                        <select name="service_id" class="filter-select" onchange="updateFilters()">
                            <option value="">All Services</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" {{ request('service_id') == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                            @endforeach
                        </select>

                        <select name="priority" class="filter-select" onchange="updateFilters()">
                            <option value="">Priority</option>
                            <option value="Hot 🔥" {{ request('priority') == 'Hot 🔥' ? 'selected' : '' }}>Hot 🔥</option>
                            <option value="Warm" {{ request('priority') == 'Warm' ? 'selected' : '' }}>Warm</option>
                            <option value="Cold" {{ request('priority') == 'Cold' ? 'selected' : '' }}>Cold</option>
                        </select>
                        <select name="status_id" class="filter-select" onchange="updateFilters()">
                            <option value="">All Statuses</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status->id }}" {{ request('status_id') == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                            @endforeach
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

                    <!-- {{-- Date Range Picker (replaces simple select) --}} -->
                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>
                </div>

                <div id="leadsTableWrap">
                    <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Lead</th>
                                <th>Campaign / Source</th>
                                <th>Contact Person</th>
                                <th>Phone</th>
                                <th>Services</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Sales Person</th>
                                <th>Followup</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                            @endphp
                            @forelse($leads as $index => $lead)
                            <tr>
                                <td>{{ $leads->firstItem() + $index }}</td>
                                <td><div class="ls" style="font-size:12px; font-weight:600;">{{ $lead->created_at->format('d M Y') }}</div></td>
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
                                    <div style="margin-top:4px; display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach($lead->sources as $src)
                                            <span style="font-size:10px; background:var(--bg3); border:1px solid var(--b1); padding:2px 6px; border-radius:4px; color:var(--t3);">{{ $src->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td><strong style="color:var(--t2)">{{ $lead->contact_person }}</strong></td>
                                <td>
                                    @foreach($lead->phones as $p)
                                        <strong style="color:var(--t2)">
                                            {{ ($codes[$p['code_idx']] ?? '') . $p['number'] }}
                                        </strong><br>
                                    @endforeach
                                </td>
                                <td>
                                    <div style="display:flex; flex-wrap:wrap; gap:4px;">
                                        @foreach($lead->services as $srv)
                                            <strong style="color:var(--t2); font-size:12px; background:rgba(99, 102, 241, 0.05); padding:2px 6px; border-radius:4px;">{{ $srv->name }}</strong>
                                        @endforeach
                                        @if($lead->services->isEmpty())
                                            <span style="color:var(--t4)">N/A</span>
                                        @endif
                                    </div>
                                </td>
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

                                        <a href="{{ route($routePrefix . '.leads.show', $lead->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route($routePrefix . '.leads.followup', $lead->id) }}" class="ra-btn" title="Followup"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a class="ra-btn" title="Edit" href="{{ route($routePrefix . '.leads.edit', $lead->id) }}"><i class="bi bi-pencil-fill"></i></a>
                                      
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route($routePrefix . '.leads.destroy', $lead->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                       
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

</main>


<script>
    (function() {
        // AJAX filtering logic remains
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
            } catch (error) {
                console.error('AJAX error:', error);
                if (wrap) wrap.style.opacity = '1';
            }
        }

        window.exportLeads = function() {
            const form = document.querySelector('.card-actions');
            let params = '';
            if (form) {
                const formData = new FormData(form);
                params = '?' + new URLSearchParams(formData).toString();
            }
            window.location.href = "{{ route($routePrefix . '.leads.export') }}" + params;
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

        // Wait for DOM to handle any page-specific initializations if needed
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
    <!-- ── Import Leads Modal ── -->
    <div class="modal fade" id="importLeadsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <form action="{{ route($routePrefix . '.leads.import') }}" method="POST" enctype="multipart/form-data" class="modal-content" style="background: var(--bg2); border-color: var(--b2); border-radius: 12px;">
                @csrf
                <div class="modal-header" style="border-bottom-color: var(--b1);">
                    <h5 class="modal-title" style="font-weight: 700; color: var(--t1);">Import Leads from CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
                </div>
                <div class="modal-body">
                    <div style="background: rgba(99,102,241,0.05); border: 1px dashed var(--accent); border-radius: 8px; padding: 20px; text-align: center;">
                        <i class="bi bi-cloud-upload" style="font-size: 32px; color: var(--accent); display: block; margin-bottom: 10px;"></i>
                        <p style="font-size: 14px; font-weight: 600; color: var(--t2); margin-bottom: 5px;">Select your CSV file</p>
                        <p style="font-size: 12px; color: var(--t4); margin-bottom: 15px;">Column headers should match the Export format</p>
                        <input type="file" name="csv_file" class="form-control" accept=".csv" required style="font-size: 13px;">
                    </div>
                </div>
                <div class="modal-footer" style="border-top-color: var(--b1);">
                    <button type="button" class="btn btn-secondary sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn-primary-solid sm">Start Import</button>
                </div>
            </form>
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

            // Ensure options is an array (handles JSON objects from PHP associative arrays)
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

        function openImportModal() {
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('importLeadsModal'));
            modal.show();
        }
    </script>
@endsection