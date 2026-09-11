@extends('admin.layout.app')

@section('title', 'Support Tickets')

@section('content')

<style>
    /* ─── SUPPORTS INDEX RESPONSIVE STYLES ─── */
    .supports-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    /* Stat KPI Cards Grid */
    .supports-stat-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    @media (max-width: 992px) {
        .supports-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 10px !important;
            margin-bottom: 18px !important;
        }
    }

    @media (max-width: 480px) {
        .supports-stat-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr)) !important;
            gap: 8px !important;
        }
    }

    .supports-stat-card {
        padding: 16px 18px;
        cursor: pointer;
        border-radius: 12px;
        background: var(--bg2);
        border: 1px solid var(--b3);
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .supports-stat-card:hover {
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
        border-color: var(--accent);
    }

    .supports-stat-card.active {
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
        .supports-stat-card {
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
        .supports-stat-card {
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
    .supports-filter-form {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 10px;
    }

    @media (max-width: 860px) {
        .supports-filter-form {
            width: 100% !important;
            display: flex !important;
            flex-wrap: wrap !important;
            gap: 8px !important;
            align-items: stretch !important;
        }
        .supports-filter-form .global-search {
            width: 100% !important;
            flex: 1 1 100% !important;
            min-width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        .supports-filter-form .filter-select,
        .supports-filter-form select {
            flex: 1 1 calc(50% - 4px) !important;
            width: calc(50% - 4px) !important;
            max-width: calc(50% - 4px) !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
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
        min-width: 850px;
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

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11.5px;
        font-weight: 700;
        letter-spacing: 0.3px;
        text-transform: uppercase;
    }

    .priority-indicator {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
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
        <div class="supports-page-header">
            <div>
                <h1 class="page-title">Support Tickets</h1>
                <p class="page-desc">Manage all customer support requests, priority escalations, and ticket statuses</p>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS (2-per-row on mobile) -->
        <div id="statGridWrap" class="supports-stat-grid">
            @php
                $statItems = [
                    ['lbl' => 'Total Tickets', 'val' => $total, 'ico' => 'bi-ticket-detailed-fill', 'clr' => '#6366f1', 'key' => ''],
                    ['lbl' => 'Active Tickets', 'val' => $active, 'ico' => 'bi-activity', 'clr' => '#10b981', 'key' => 'active'],
                    ['lbl' => 'Pending Action', 'val' => $pending, 'ico' => 'bi-hourglass-split', 'clr' => '#f59e0b', 'key' => 'pending'],
                    ['lbl' => 'Closed Tickets', 'val' => $closed, 'ico' => 'bi-check-circle-fill', 'clr' => '#6b7280', 'key' => 'closed'],
                ];
            @endphp

            @foreach($statItems as $st)
            <div class="supports-stat-card {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? 'active' : '' }}" 
                 style="border-bottom: 3px solid {{ (request('status') == $st['key']) || (request('status') == null && $st['key'] == '') ? $st['clr'] : 'transparent' }};" 
                 onclick="applyStatusFilter('{{ $st['key'] }}')">
                <div class="stat-head">
                    <div class="stat-icon-box" style="background:{{ $st['clr'] }}18;">
                        <i class="bi {{ $st['ico'] }}" style="font-size:16px;color:{{ $st['clr'] }};"></i>
                    </div>
                    <span class="stat-badge" style="background:{{ $st['clr'] }}15;color:{{ $st['clr'] }};">Tickets</span>
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
                        <div class="card-title">Ticket Records</div>
                        <div class="card-sub">Showing {{ $tickets->count() }} of {{ $tickets->total() }} records</div>
                    </div>
                    
                    <div>
                        <form action="{{ route(($routePrefix ?? 'admin') . '.supports.index') }}" method="GET" class="supports-filter-form">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search company, user, subject..." id="searchInput" autocomplete="off">
                            </div>

                            <select name="status" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="review" {{ request('status') == 'review' ? 'selected' : '' }}>Review</option>
                                <option value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>Replied</option>
                                <option value="closed" {{ request('status') == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>

                            <select name="priority" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Priority</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High Priority</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium Priority</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low Priority</option>
                            </select>

                            <select name="per_page" class="filter-select" onchange="this.form.submit()">
                                <option value="10" {{ (request('per_page') == 10 || !request('per_page')) ? 'selected' : '' }}>10 Rows</option>
                                <option value="20" {{ request('per_page') == 20 ? 'selected' : '' }}>20 Rows</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Rows</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Rows</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All Rows</option>
                            </select>
                        </form>
                    </div>
                </div>

                @if(($routePrefix ?? 'admin') !== 'developer')
                <form action="{{ route(($routePrefix ?? 'admin') . '.supports.bulk-destroy') }}" method="POST" id="bulkDeleteForm">
                    @csrf
                    <!-- Bulk Action Toolbar -->
                    <div id="bulkActions" style="display:none; padding:12px 18px; background:rgba(239, 68, 68, 0.1); border-bottom:1px solid rgba(239, 68, 68, 0.2); align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px;">
                        <span style="font-size:13px; font-weight:700; color:#dc2626;"><span id="selectedCount">0</span> tickets selected</span>
                        <button type="button" class="btn-primary-solid sm" style="background:#dc2626; border-color:#dc2626; height:34px; padding:0 14px;" onclick="confirmBulkDelete()">
                            <i class="bi bi-trash-fill"></i> Delete Selected
                        </button>
                    </div>
                @endif

                <div class="table-responsive-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                @if(($routePrefix ?? 'admin') !== 'developer')
                                <th style="width: 40px; padding-left: 18px;">
                                    <input type="checkbox" id="selectAll" class="custom-checkbox">
                                </th>
                                @endif
                                <th style="width: 50px;">SL.</th>
                                <th>Ticket No</th>
                                <th>Date</th>
                                <th>Company & User</th>
                                <th>Subject</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th style="text-align: right;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($tickets as $ticket)
                            <tr>
                                @if(($routePrefix ?? 'admin') !== 'developer')
                                <td style="padding-left: 18px;">
                                    <input type="checkbox" name="ids[]" value="{{ $ticket->id }}" class="row-checkbox custom-checkbox">
                                </td>
                                @endif
                                <td style="color:var(--t4);font-size:12px;font-weight:700;font-family:var(--mono, monospace);">
                                    {{ $loop->iteration + ($tickets->currentPage() - 1) * $tickets->perPage() }}
                                </td>
                                <td>
                                    <a href="{{ route(($routePrefix ?? 'admin') . '.supports.show', $ticket->id) }}" style="font-weight:700; color:var(--accent); text-decoration:none; font-family:var(--mono, monospace); font-size:13px;">
                                        {{ $ticket->ticket_no }}
                                    </a>
                                </td>
                                <td>
                                    <div class="ln" style="font-size:12.5px;">{{ $ticket->created_at->format('d M Y') }}</div>
                                    <div class="ls" style="font-size:11px;">{{ $ticket->created_at->format('h:i A') }}</div>
                                </td>
                                <td>
                                    <div class="ln">{{ $ticket->company_name ?? 'N/A' }}</div>
                                    <div class="ls">{{ $ticket->your_name ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="ln" style="max-width: 260px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $ticket->subject }}</div>
                                    <div class="ls" style="font-size:11px;">{{ $ticket->domain_name ?? 'No Domain' }}</div>
                                </td>
                                <td>
                                    @php
                                        $pClr = match($ticket->priority) {
                                            'high' => '#ef4444',
                                            'medium' => '#f59e0b',
                                            default => '#10b981'
                                        };
                                    @endphp
                                    <div class="priority-indicator" style="color:{{ $pClr }}">
                                        <div style="width:6px;height:6px;border-radius:50%;background:{{ $pClr }}"></div>
                                        {{ $ticket->priority }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $sClr = match($ticket->status) {
                                            'pending' => '#f59e0b',
                                            'active' => '#10b981',
                                            'closed' => '#6b7280',
                                            default => '#6366f1'
                                        };
                                    @endphp
                                    <span class="status-pill" style="background:{{ $sClr }}18; color:{{ $sClr }}; border:1px solid {{ $sClr }}30;">
                                        <span style="width:5px; height:5px; border-radius:50%; background:{{ $sClr }};"></span>
                                        {{ ucfirst($ticket->status) }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions" style="justify-content: flex-end;">
                                        <a href="{{ route(($routePrefix ?? 'admin') . '.supports.show', $ticket->id) }}" class="ra-btn" title="View & Reply">
                                            <i class="bi bi-eye-fill"></i>
                                        </a>
                                        @if(($routePrefix ?? 'admin') !== 'developer')
                                        <button type="button" class="ra-btn danger" onclick="confirmDelete('{{ route(($routePrefix ?? 'admin') . '.supports.destroy', $ticket->id) }}')" title="Delete">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="9" style="text-align:center; padding: 48px 20px; color: var(--t3);">
                                    <div style="font-size:32px; color:var(--t4); margin-bottom:8px;"><i class="bi bi-ticket-detailed"></i></div>
                                    <div style="font-weight:600; font-size:15px; color:var(--t2);">No Support Tickets Found</div>
                                    <div style="font-size:12.5px; color:var(--t3); margin-top:4px;">Try modifying your search criteria or filters</div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing {{ $tickets->firstItem() ?? 0 }} to {{ $tickets->lastItem() ?? 0 }} of {{ $tickets->total() }} Tickets</span>
                    <div class="tf-pagination">
                        {{ $tickets->appends(request()->query())->links('admin.includes.pagination') }}
                    </div>
                </div>

                @if(($routePrefix ?? 'admin') !== 'developer')
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" style="width: min(440px, calc(100vw - 32px));" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom: 1px solid rgba(239, 68, 68, 0.2);">
                <span style="color:#ef4444; font-weight:700;"><i class="bi bi-exclamation-triangle-fill"></i> <span id="deleteModalTitle">Delete Ticket</span></span>
                <button type="button" class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:28px 20px;">
                <div style="width:60px;height:60px;background:rgba(239, 68, 68, 0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:26px;color:#ef4444;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:700;color:var(--t1);">Are you sure?</h3>
                <p id="deleteModalDesc" style="margin:0;font-size:13.5px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this support ticket?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top: 1px solid var(--b3); justify-content:flex-end; gap:10px;">
                <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="margin:0;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="background:#ef4444;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:13.5px;font-weight:600;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Confirm Deletion
                    </button>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function confirmDelete(url) {
        document.getElementById('deleteModalTitle').innerText = 'Delete Ticket';
        document.getElementById('deleteModalDesc').innerHTML = 'Are you sure you want to delete this support ticket?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong>';
        const form = document.getElementById('deleteForm');
        form.action = url;
        form.onsubmit = null;
        openModal('deleteModal');
    }

    function confirmBulkDelete() {
        document.getElementById('deleteModalTitle').innerText = 'Bulk Delete Tickets';
        document.getElementById('deleteModalDesc').innerHTML = 'Are you sure you want to delete all selected tickets?<br>This action <strong style="color:#ef4444;">cannot be undone.</strong>';
        
        const form = document.getElementById('deleteForm');
        form.action = '{{ route("admin.supports.bulk-destroy") }}';
        form.onsubmit = function(e) {
            e.preventDefault();
            const bulkForm = document.getElementById('bulkDeleteForm');
            bulkForm.submit();
        };
        openModal('deleteModal');
    }

    function applyStatusFilter(status) {
        const url = new URL(window.location.href);
        if(status) {
            url.searchParams.set('status', status);
        } else {
            url.searchParams.delete('status');
        }
        url.searchParams.delete('page');
        window.location.href = url.toString();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        let debounceTimer;

        if(searchInput) {
            searchInput.addEventListener('input', function() {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.form.submit();
                }, 500);
            });
        }

        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.row-checkbox');
        const bulkActions = document.getElementById('bulkActions');
        const selectedCount = document.getElementById('selectedCount');

        function updateBulkActions() {
            const checked = document.querySelectorAll('.row-checkbox:checked').length;
            if(selectedCount) selectedCount.textContent = checked;
            if(bulkActions) bulkActions.style.display = checked > 0 ? 'flex' : 'none';
            if(selectAll) selectAll.checked = checked === checkboxes.length && checkboxes.length > 0;
        }

        if(selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = this.checked);
                updateBulkActions();
            });
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateBulkActions);
        });
    });

    // Modal Helpers
    function openModal(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.add('open');
            document.body.style.overflow = 'hidden';
        }
    }
    function closeModal(id) {
        const el = document.getElementById(id);
        if(el) {
            el.classList.remove('open');
            document.body.style.overflow = 'auto';
        }
    }
</script>

@endsection
