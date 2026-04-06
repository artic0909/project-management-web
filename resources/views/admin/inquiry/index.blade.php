@extends('admin.layout.app')

@section('title', 'Order Inquiries')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <h1 class="page-title">Order Inquiries</h1>
                <p class="page-desc">Manage all potential project requests from the landing page.</p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('admin.inquiry.export', request()->all()) }}" class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export Inquiries
                </a>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS -->
        <div id="statGridWrap" style="display:grid;grid-template-columns:repeat(5,1fr);gap:14px;margin-bottom:24px;">
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
            <div class="dash-card {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? 'active' : '' }}" 
                 style="padding:16px 18px; cursor:pointer; border-bottom: 3px solid {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? $st['clr'] : 'transparent' }};" 
                 onclick="applyStatusFilter('{{ $st['key'] }}')">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:{{ $st['clr'] }}15;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi {{ $st['ico'] }}" style="font-size:17px;color:{{ $st['clr'] }};"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:{{ $st['clr'] }}10;color:{{ $st['clr'] }};white-space:nowrap;">Inquiries</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $st['val'] }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">{{ $st['lbl'] }}</div>
            </div>
            @endforeach
        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Recent Inquiries</div>
                        <div class="card-sub" id="drpActiveSub">Showing {{ $inquiries->count() }} of {{ $inquiries->total() }} records</div>
                    </div>
                    
                    <div class="card-actions mb-2">
                        <form action="{{ route('admin.inquiry.index') }}" method="GET" id="filterForm" class="card-actions mb-0" onsubmit="event.preventDefault(); updateFilters()">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search all fields..." autocomplete="off">
                            </div>

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
                    <div class="table-wrap">
                        <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Date</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Budget</th>
                                <th>City/State</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($inquiries as $in)
                            <tr>
                                <td style="color:var(--t4);font-size:12px;font-weight:600;">{{ $loop->iteration + ($inquiries->currentPage() - 1) * $inquiries->perPage() }}</td>
                                <td><div class="ls" style="font-size:12px; font-weight:600;">{{ $in->created_at->format('d M Y') }}</div><div class="ls" style="font-size:10px;">{{ $in->created_at->format('h:i A') }}</div></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ strtoupper(substr($in->company_name, 0, 2)) }}</div>
                                        <div>
                                            <div class="ln">{{ $in->company_name }}</div>
                                            <div class="ls">{{ $in->domain_name ?? 'No Domain' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">{{ $in->client_name }}</div>
                                    <div class="ls">{{ $in->emails[0] ?? 'N/A' }}</div>
                                </td>
                                <td><span class="src-tag">₹{{ number_format($in->order_value, 0) }}</span></td>
                                <td>
                                    <div class="ln">{{ $in->city }}</div>
                                    <div class="ls">{{ $in->state }}</div>
                                </td>
                                <td>
                                    @php
                                        $statusClr = ['pending' => '#f59e0b', 'reviewed' => '#0ea5e9', 'converted' => '#10b981', 'rejected' => '#ef4444'];
                                        $clr = $statusClr[$in->status] ?? '#6366f1';
                                    @endphp
                                    <span class="status-pill" style="background:{{ $clr }}15; color:{{ $clr }};">
                                        {{ ucfirst($in->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        @php
                                            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                                            $phoneList = (array)$in->phones;
                                            $emailList = (array)$in->emails;
                                            $fullPhones = [];
                                            foreach($phoneList as $p) {
                                                $fullPhones[] = ($codes[$p['code_idx'] ?? ''] ?? '') . ($p['number'] ?? '');
                                            }
                                        @endphp
                                        
                                        <a href="javascript:void(0)" class="ra-btn phone" 
                                           onclick="handleContactClick('tel', {{ json_encode($fullPhones) }}, 'Phone List')" title="Call Client">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ra-btn email" 
                                           onclick="handleContactClick('mailto', {{ json_encode($emailList) }}, 'Email List')" title="Email Client">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route('admin.inquiry.show', $in->id) }}" class="ra-btn" title="View Details"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route('admin.inquiry.edit', $in->id) }}" class="ra-btn" title="Edit Inquiry"><i class="bi bi-pencil-fill"></i></a>
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route('admin.inquiry.destroy', $in->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" style="text-align:center; padding: 40px; color: var(--t4);">No inquiries found matching your criteria.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer" style="padding:16px 20px; border-top:1px solid var(--b2); display:flex; justify-content:space-between; align-items:center;">
                    <span class="tf-info" style="font-size:13px; color:var(--t3); font-weight:500;">Showing {{ $inquiries->count() }} of {{ $inquiries->total() }} Inquiries</span>
                    <div class="tf-pagination">
                        {{ $inquiries->links('admin.includes.pagination') }}
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- CONTACT LIST MODAL (Multiple) -->
    <div class="modal-backdrop" id="contactModal">
        <div class="modal-box" style="width:400px;" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span id="contactModalTitle">Contact List</span>
                <button class="modal-close" onclick="closeModal('contactModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" id="contactModalBody" style="padding:16px; display:flex; flex-direction:column; gap:10px;">
                <!-- Dynamically filled -->
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Inquiry</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this Inquiry?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
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
            window.location.href = type + ':' + list[0];
            return;
        }

        // Show modal for multiple
        const body = document.getElementById('contactModalBody');
        const modalTitle = document.getElementById('contactModalTitle');
        modalTitle.innerText = title;
        body.innerHTML = '';

        list.forEach(val => {
            const a = document.createElement('a');
            a.href = type + ':' + val;
            a.className = 'btn-ghost';
            a.style.width = '100%';
            a.style.justifyContent = 'flex-start';
            a.innerHTML = `<i class="bi ${type === 'tel' ? 'bi-telephone' : 'bi-envelope'}"></i> ${val}`;
            body.appendChild(a);
        });

        openModal('contactModal');
    }

    // Date Picker Integration
    function toggleDatePicker() {
        const panel = document.getElementById('dateRangePanel');
        const trigger = document.getElementById('dateRangeTrigger');
        const isOpen = panel.style.display === 'flex';
        panel.style.display = isOpen ? 'none' : 'flex';
        trigger.classList.toggle('open', !isOpen);
    }

    // Overriding the apply function of date-range-picker to submit our form
    function applyDatePicker() {
        // Range from internal script in admin.includes.date-range-picker
        const start = document.getElementById('drpStartInput').value;
        const end = document.getElementById('drpEndInput').value;
        
        if (start && end) {
            document.getElementById('drpRangeInput').value = ''; // clear 7 days if custom used
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
                document.getElementById('drpRangeInput').value = '7_days';
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

<style>
    .ra-btn.phone:hover {
        background: rgba(16, 185, 129, 0.1) !important;
        color: #10b981 !important;
        border-color: #10b981 !important;
    }
    .ra-btn.email:hover {
        background: rgba(14, 165, 233, 0.1) !important;
        color: #0ea5e9 !important;
        border-color: #0ea5e9 !important;
    }
    .dash-card.active { border-color: var(--accent); background: var(--bg3); }
</style>

@endsection
