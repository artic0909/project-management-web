@extends('admin.layout.app')

@section('title', 'Order Inquiries')

@section('content')

<style>
    /* ─── INQUIRY INDEX RESPONSIVE STYLES ─── */
    .inquiry-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    /* Stat KPI Cards Grid */
    .inquiry-stat-grid {
        display: grid;
        grid-template-columns: repeat(5, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    @media (max-width: 1200px) {
        .inquiry-stat-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
    }

    /* 2 columns on mobile/tablet screens */
    @media (max-width: 768px) {
        .inquiry-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
            margin-bottom: 18px !important;
        }
    }

    @media (max-width: 480px) {
        .inquiry-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }
    }

    .inquiry-stat-card {
        padding: 16px 18px;
        cursor: pointer;
        border-radius: 12px;
        background: var(--bg2);
        border: 1px solid var(--b3);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .inquiry-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .inquiry-stat-card.active {
        background: var(--bg3);
        border-color: var(--accent);
        box-shadow: var(--shadow-sm);
    }

    .stat-head {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 12px;
    }

    .stat-icon-box {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .stat-badge {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-val {
        font-size: 26px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -0.5px;
        line-height: 1;
        font-family: var(--mono, monospace);
    }

    .stat-lbl {
        font-size: 12px;
        color: var(--t3);
        font-weight: 600;
        margin-top: 6px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Small Screen Adjustments for Stat Cards */
    @media (max-width: 768px) {
        .inquiry-stat-card {
            padding: 12px 14px;
            border-radius: 10px;
        }

        .stat-head {
            margin-bottom: 8px;
        }

        .stat-icon-box {
            width: 30px;
            height: 30px;
            border-radius: 8px;
        }

        .stat-icon-box i {
            font-size: 14px !important;
        }

        .stat-badge {
            font-size: 9px;
            padding: 2px 6px;
        }

        .stat-val {
            font-size: 20px;
        }

        .stat-lbl {
            font-size: 11px;
            margin-top: 4px;
        }
    }

    @media (max-width: 480px) {
        .inquiry-stat-card {
            padding: 10px 12px;
        }

        .stat-icon-box {
            width: 26px;
            height: 26px;
            border-radius: 6px;
        }

        .stat-icon-box i {
            font-size: 12.5px !important;
        }

        .stat-badge {
            font-size: 8px;
            padding: 1.5px 5px;
        }

        .stat-val {
            font-size: 18px;
        }

        .stat-lbl {
            font-size: 10.5px;
            margin-top: 3px;
        }
    }

    /* Filter Bar Responsive */
    .inquiry-filter-form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 768px) {
        .inquiry-filter-form {
            width: 100%;
            flex-direction: column;
            align-items: stretch;
        }
        .inquiry-filter-form .global-search,
        .inquiry-filter-form .drp-trigger,
        .inquiry-filter-form .filter-select {
            width: 100% !important;
        }
    }

    /* Table Container & Table */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .data-table {
        width: 100%;
        min-width: 900px;
        border-collapse: separate;
        border-spacing: 0;
    }

    .data-table th {
        padding: 12px 14px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--t2);
        background: var(--bg3);
        border-bottom: 1px solid var(--b3);
        white-space: nowrap;
    }

    .data-table td {
        padding: 12px 14px;
        vertical-align: middle;
        border-bottom: 1px solid var(--b3);
        background: transparent;
        font-size: 13px;
    }

    .data-table tbody tr:hover td {
        background: var(--bg3);
    }

    .data-table tbody tr:last-child td {
        border-bottom: none;
    }

    .lead-avatar-sm {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 800;
        color: #fff;
        flex-shrink: 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.12);
    }

    .lead-cell {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .ln {
        font-weight: 700;
        color: var(--t1);
        font-size: 13.5px;
        line-height: 1.3;
    }

    .ls {
        font-size: 12px;
        color: var(--t3);
        margin-top: 2px;
    }

    .budget-badge {
        display: inline-flex;
        align-items: center;
        font-family: var(--mono, monospace);
        font-weight: 700;
        font-size: 13px;
        color: var(--t1);
        background: var(--bg3);
        padding: 4px 8px;
        border-radius: 6px;
        border: 1px solid var(--b3);
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 0.3px;
    }

    /* Action Buttons */
    .row-actions {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .ra-btn {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: var(--bg3);
        border: 1px solid var(--b3);
        color: var(--t2);
        text-decoration: none;
        cursor: pointer;
        transition: var(--transition);
        font-size: 13px;
    }

    .ra-btn:hover {
        background: var(--bg4);
        color: var(--t1);
        border-color: var(--b2);
    }

    .ra-btn.phone:hover {
        background: rgba(16, 185, 129, 0.15) !important;
        color: #10b981 !important;
        border-color: rgba(16, 185, 129, 0.3) !important;
    }

    .ra-btn.email:hover {
        background: rgba(14, 165, 233, 0.15) !important;
        color: #0ea5e9 !important;
        border-color: rgba(14, 165, 233, 0.3) !important;
    }

    .ra-btn.danger:hover {
        background: rgba(239, 68, 68, 0.15) !important;
        color: #ef4444 !important;
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    /* Footer */
    .table-footer {
        padding: 14px 20px;
        border-top: 1px solid var(--b3);
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        background: var(--bg2);
    }

    @media (max-width: 576px) {
        .table-footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page">
        <!-- PAGE HEADER -->
        <div class="inquiry-page-header">
            <div>
                <h1 class="page-title">Order Inquiries</h1>
                <p class="page-desc">Manage all potential project requests from the landing page</p>
            </div>

            <div class="header-actions">
                <a href="{{ route($routePrefix . '.inquiry.export', request()->all()) }}" class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Inquiries
                </a>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS (2-per-row on mobile) -->
        <div id="statGridWrap" class="inquiry-stat-grid">
            @php
                $statItems = [
                    ['lbl' => 'Total Inquiries', 'val' => $stats['total'], 'ico' => 'bi-inboxes-fill', 'clr' => '#6366f1', 'key' => ''],
                    ['lbl' => 'Pending', 'val' => $stats['pending'], 'ico' => 'bi-hourglass-split', 'clr' => '#f59e0b', 'key' => 'pending'],
                    ['lbl' => 'Reviewed', 'val' => $stats['reviewed'], 'ico' => 'bi-eye-fill', 'clr' => '#0ea5e9', 'key' => 'reviewed'],
                    ['lbl' => 'Converted', 'val' => $stats['converted'], 'ico' => 'bi-check-circle-fill', 'clr' => '#10b981', 'key' => 'converted'],
                    ['lbl' => 'Rejected', 'val' => $stats['rejected'], 'ico' => 'bi-x-circle-fill', 'clr' => '#ef4444', 'key' => 'rejected'],
                ];
            @endphp

            @foreach($statItems as $st)
            <div class="inquiry-stat-card {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? 'active' : '' }}" 
                 style="border-bottom: 3px solid {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? $st['clr'] : 'transparent' }};" 
                 onclick="applyStatusFilter('{{ $st['key'] }}')">
                <div class="stat-head">
                    <div class="stat-icon-box" style="background:{{ $st['clr'] }}18;">
                        <i class="bi {{ $st['ico'] }}" style="font-size:16px;color:{{ $st['clr'] }};"></i>
                    </div>
                    <span class="stat-badge" style="background:{{ $st['clr'] }}15;color:{{ $st['clr'] }};">Inquiries</span>
                </div>
                <div class="stat-val">{{ $st['val'] }}</div>
                <div class="stat-lbl">{{ $st['lbl'] }}</div>
            </div>
            @endforeach
        </div>

        <!-- MAIN TABLE CARD -->
        <div class="dash-grid">
            <div class="dash-card span-12" style="overflow:visible;">
                <div class="card-head" style="gap: 14px; flex-wrap: wrap;">
                    <div>
                        <div class="card-title">Inquiry Records</div>
                        <div class="card-sub" id="drpActiveSub">Showing {{ $inquiries->count() }} of {{ $inquiries->total() }} records</div>
                    </div>
                    
                    <div>
                        <form action="{{ route($routePrefix . '.inquiry.index') }}" method="GET" id="filterForm" class="inquiry-filter-form" onsubmit="event.preventDefault(); updateFilters()">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search company, client, email..." autocomplete="off">
                            </div>

                            <!-- DATE RANGE PICKER TRIGGER -->
                            <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                                <i class="bi bi-calendar3"></i>
                                <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'Date Filter' }}</span>
                                <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                            </button>

                            <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                            <select name="status" id="statusFilter" class="filter-select" onchange="updateFilters()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="reviewed" {{ request('status') == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                <option value="converted" {{ request('status') == 'converted' ? 'selected' : '' }}>Converted</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                            </select>
                        </form>
                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>
                    </div>
                </div>

                <div id="inquiriesTableWrap">
                    <div class="table-responsive-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">SL.</th>
                                    <th>Inquiry ID</th>
                                    <th>Date</th>
                                    <th>Company & Domain</th>
                                    <th>Contact Person</th>
                                    <th>Budget</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($inquiries as $in)
                                <tr>
                                    <td style="color:var(--t4);font-size:12px;font-weight:700;font-family:var(--mono, monospace);">
                                        {{ $loop->iteration + ($inquiries->currentPage() - 1) * $inquiries->perPage() }}
                                    </td>
                                    <td>
                                        <a href="{{ route($routePrefix . '.inquiry.show', $in->id) }}" style="font-size:12.5px; font-weight:700; color:var(--accent); text-decoration:none; font-family:var(--mono, monospace);">
                                            #INQ-{{ str_pad($in->id, 4, '0', STR_PAD_LEFT) }}
                                        </a>
                                    </td>
                                    <td>
                                        <div class="ln" style="font-size:12.5px;">{{ $in->created_at->format('d M Y') }}</div>
                                        <div class="ls" style="font-size:11px;">{{ $in->created_at->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <div class="lead-cell">
                                            <div class="lead-avatar-sm" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">
                                                {{ strtoupper(substr($in->company_name ?? 'C', 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="ln">{{ $in->company_name }}</div>
                                                <div class="ls">
                                                    @if($in->domain_name)
                                                        <a href="https://{{ $in->domain_name }}" target="_blank" rel="noopener noreferrer" style="color:var(--accent); text-decoration:none;">
                                                            {{ $in->domain_name }} <i class="bi bi-box-arrow-up-right" style="font-size:10px;"></i>
                                                        </a>
                                                    @else
                                                        <span style="color:var(--t4);">No Domain</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="ln">{{ $in->client_name }}</div>
                                        <div class="ls">{{ $in->emails[0] ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <span class="budget-badge">₹{{ number_format($in->order_value ?? 0, 0) }}</span>
                                    </td>
                                    <td>
                                        <div class="ln" style="font-size:13px;">{{ $in->city ?? 'N/A' }}</div>
                                        <div class="ls">{{ $in->state ?? '' }}</div>
                                    </td>
                                    <td>
                                        @php
                                            $statusClr = ['pending' => '#f59e0b', 'reviewed' => '#0ea5e9', 'converted' => '#10b981', 'rejected' => '#ef4444'];
                                            $clr = $statusClr[$in->status] ?? '#6366f1';
                                        @endphp
                                        <span class="status-pill" style="background:{{ $clr }}18; color:{{ $clr }}; border:1px solid {{ $clr }}30;">
                                            <span style="width:6px; height:6px; border-radius:50%; background:{{ $clr }};"></span>
                                            {{ ucfirst($in->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row-actions" style="justify-content: flex-end;">
                                            @php
                                                $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                                                $phoneList = (array)$in->phones;
                                                $emailList = (array)$in->emails;
                                                $fullPhones = [];
                                                foreach($phoneList as $p) {
                                                    $codeStr = $codes[$p['code_idx'] ?? ''] ?? '';
                                                    $fullPhones[] = $codeStr . ' ' . ($p['number'] ?? '');
                                                }
                                            @endphp
                                            
                                            <a href="javascript:void(0)" class="ra-btn phone" 
                                               onclick="handleContactClick('tel', {{ json_encode($fullPhones) }}, 'Phone Numbers')" title="Call Client">
                                                <i class="bi bi-telephone-fill"></i>
                                            </a>
                                            <a href="javascript:void(0)" class="ra-btn email" 
                                               onclick="handleContactClick('mailto', {{ json_encode($emailList) }}, 'Email Addresses')" title="Email Client">
                                                <i class="bi bi-envelope-fill"></i>
                                            </a>

                                            <a href="{{ route($routePrefix . '.inquiry.show', $in->id) }}" class="ra-btn" title="View Details">
                                                <i class="bi bi-eye-fill"></i>
                                            </a>
                                            <a href="{{ route($routePrefix . '.inquiry.edit', $in->id) }}" class="ra-btn" title="Edit Inquiry">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <button type="button" class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route($routePrefix . '.inquiry.destroy', $in->id) }}')">
                                                <i class="bi bi-trash-fill"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" style="text-align:center; padding: 48px 20px; color: var(--t3);">
                                        <div style="font-size:32px; color:var(--t4); margin-bottom:8px;"><i class="bi bi-inbox"></i></div>
                                        <div style="font-weight:600; font-size:15px; color:var(--t2);">No Inquiries Found</div>
                                        <div style="font-size:12.5px; color:var(--t3); margin-top:4px;">Try modifying your search keywords or date filters</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="table-footer">
                        <span class="tf-info">Showing {{ $inquiries->count() }} of {{ $inquiries->total() }} Inquiries</span>
                        <div class="tf-pagination">
                            {{ $inquiries->links('admin.includes.pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTACT LIST MODAL -->
    <div class="modal-backdrop" id="contactModal">
        <div class="modal-box" style="width: min(420px, calc(100vw - 32px));" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span id="contactModalTitle">Contact List</span>
                <button type="button" class="modal-close" onclick="closeModal('contactModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" id="contactModalBody" style="padding:16px; display:flex; flex-direction:column; gap:10px;">
                <!-- Dynamically filled -->
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" style="width: min(440px, calc(100vw - 32px));" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid rgba(239, 68, 68, 0.2);">
                <span style="color:#ef4444; font-weight:700;"><i class="bi bi-exclamation-triangle-fill"></i> Delete Inquiry</span>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:28px 20px;">
                <div style="width:60px;height:60px;background:rgba(239, 68, 68, 0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:13.5px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this Inquiry?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid var(--b3); justify-content:flex-end; gap:10px;">
                <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13.5px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete Inquiry
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function confirmDelete(url) {
        const form = document.getElementById('deleteForm');
        form.action = url;
        openModal('deleteModal');
    }

    function handleContactClick(type, list, title) {
        if (!list || list.length === 0) return;
        
        if (list.length === 1) {
            const rawVal = list[0].replace(/\s+/g, '');
            window.location.href = type + ':' + rawVal;
            return;
        }

        // Show modal for multiple
        const body = document.getElementById('contactModalBody');
        const modalTitle = document.getElementById('contactModalTitle');
        modalTitle.innerText = title;
        body.innerHTML = '';

        list.forEach(val => {
            const rawVal = val.replace(/\s+/g, '');
            const a = document.createElement('a');
            a.href = type + ':' + rawVal;
            a.className = 'btn-ghost';
            a.style.width = '100%';
            a.style.justifyContent = 'flex-start';
            a.style.padding = '10px 14px';
            a.style.borderRadius = '8px';
            a.innerHTML = `<i class="bi ${type === 'tel' ? 'bi-telephone-fill' : 'bi-envelope-fill'}" style="color:var(--accent);"></i> <span style="font-weight:600;">${val}</span>`;
            body.appendChild(a);
        });

        openModal('contactModal');
    }

    // Date Picker Integration
    function toggleDatePicker() {
        const panel = document.getElementById('dateRangePanel');
        const trigger = document.getElementById('dateRangeTrigger');
        if (!panel || !trigger) return;
        const isOpen = panel.style.display === 'flex';
        panel.style.display = isOpen ? 'none' : 'flex';
        trigger.classList.toggle('open', !isOpen);
    }

    function applyDatePicker() {
        const start = document.getElementById('drpStartInput').value;
        const end = document.getElementById('drpEndInput').value;
        
        if (start && end) {
            const rangeInput = document.getElementById('drpRangeInput');
            if (rangeInput) rangeInput.value = '';
        }
        
        updateFilters();
    }

    // AJAX filter logic
    window.updateFilters = function() {
        const form = document.getElementById('filterForm');
        if (!form) return;
        const formData = new FormData(form);
        const params = new URLSearchParams(formData);
        const url = new URL(window.location.pathname, window.location.origin);
        url.search = params.toString();
        fetchAndReplace(url);
    };

    window.applyStatusFilter = function(status) {
        document.getElementById('statusFilter').value = status;
        updateFilters();
    };

    async function fetchAndReplace(url) {
        const wrap = document.getElementById('inquiriesTableWrap');
        if (wrap) wrap.style.opacity = '0.5';

        try {
            const response = await fetch(url.toString(), {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            
            const newContent = doc.getElementById('inquiriesTableWrap');
            if (newContent && wrap) {
                wrap.innerHTML = newContent.innerHTML;
            }
            
            const newStats = doc.getElementById('statGridWrap');
            const oldStats = document.getElementById('statGridWrap');
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

    let debounceTimer;
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'searchQuery') {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateFilters, 500);
        }
    });

    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.tf-pagination a');
        if (paginationLink) {
            e.preventDefault();
            fetchAndReplace(new URL(paginationLink.href));
        }
    });

    // Listen for preset clicks
    document.querySelectorAll('.drp-preset').forEach(preset => {
        preset.addEventListener('click', function() {
            const p = this.dataset.preset;
            if (p === 'last7') {
                const rangeInput = document.getElementById('drpRangeInput');
                if (rangeInput) rangeInput.value = '7_days';
                document.getElementById('drpStartInput').value = '';
                document.getElementById('drpEndInput').value = '';
                updateFilters();
            }
        });
    });

    document.addEventListener('click', function(e) {
        const panel = document.getElementById('dateRangePanel');
        const trigger = document.getElementById('dateRangeTrigger');
        if (panel && trigger && !panel.contains(e.target) && !trigger.contains(e.target)) {
            panel.style.display = 'none';
            trigger.classList.remove('open');
        }
    });
</script>

@endsection
