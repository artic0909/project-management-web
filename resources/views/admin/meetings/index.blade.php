@extends('admin.layout.app')

@section('title', 'Meetings Management')

@section('content')

    <main class="page-area" id="pageArea">
        <div class="page" id="page-meetings">

            <!-- ── PAGE HEADER ── -->
            <div class="page-header" style="margin-bottom:24px;">
                <div>
                    <h1 class="page-title">Meeting Schedule</h1>
                    <p class="page-desc">Track and manage upcoming discussions with clients and teams.</p>
                </div>
                <div>
                    <a href="{{ route($routePrefix . '.meetings.create') }}" class="btn-primary-solid">
                        <i class="bi bi-plus-lg"></i> Schedule Meeting
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success"
                    style="padding:14px 18px;background:rgba(16,185,129,.1);color:#10b981;border-radius:var(--r);border:1px solid rgba(16,185,129,.2);display:flex;align-items:center;gap:10px;margin-bottom:20px;">
                    <i class="bi bi-check-circle-fill"></i>
                    <span style="font-size:14px;font-weight:600;">{{ session('success') }}</span>
                </div>
            @endif
            <!-- ── STATUS ANALYTICS ── -->
            <div class="dash-grid" style="margin-bottom:24px;">
                <div class="span-3">
                    <div class="dash-card stat-card pending">
                        <div class="stat-icon"><i class="bi bi-hourglass-split"></i></div>
                        <div class="stat-meta">
                            <span class="stat-lbl">Pending</span>
                            <h2 class="stat-val text-pending">{{ $counts['pending'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="span-3">
                    <div class="dash-card stat-card rescheduled">
                        <div class="stat-icon"><i class="bi bi-arrow-repeat"></i></div>
                        <div class="stat-meta">
                            <span class="stat-lbl">Rescheduled</span>
                            <h2 class="stat-val text-rescheduled">{{ $counts['rescheduled'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="span-3">
                    <div class="dash-card stat-card completed">
                        <div class="stat-icon"><i class="bi bi-check2-all"></i></div>
                        <div class="stat-meta">
                            <span class="stat-lbl">Completed</span>
                            <h2 class="stat-val text-success">{{ $counts['completed'] }}</h2>
                        </div>
                    </div>
                </div>
                <div class="span-3">
                    <div class="dash-card stat-card canceled">
                        <div class="stat-icon"><i class="bi bi-x-circle"></i></div>
                        <div class="stat-meta">
                            <span class="stat-lbl">Cancelled</span>
                            <h2 class="stat-val text-danger">{{ $counts['canceled'] }}</h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ── FILTER BAR ── -->
            <div class="dash-card" style="margin-bottom:24px;">
                <form action="{{ route($routePrefix . '.meetings.index') }}" method="GET" id="filterForm">
                    <div class="card-body"
                        style="padding:16px 20px; display:flex; flex-wrap:wrap; gap:12px; align-items:center;">
                        <div style="flex:1; min-width:200px;">
                            <div class="search-inp-wrap">
                                <i class="bi bi-search"></i>
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="Search title or description..." class="filter-inp"
                                    onkeyup="debounceSearch()">
                            </div>
                        </div>
                        <div style="width:180px;">
                            <input type="date" name="date" value="{{ request('date') }}" class="filter-inp"
                                onchange="this.form.submit()">
                        </div>
                        <div style="width:180px;">
                            <select name="sale_id" class="filter-inp" onchange="this.form.submit()">
                                <option value="">— All Sales —</option>
                                @foreach($sales as $sale)
                                    <option value="{{ $sale->id }}" {{ request('sale_id') == $sale->id ? 'selected' : '' }}>
                                        {{ $sale->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div style="width:180px;">
                            <select name="dev_id" class="filter-inp" onchange="this.form.submit()">
                                <option value="">— All Developers —</option>
                                @foreach($developers as $dev)
                                    <option value="{{ $dev->id }}" {{ request('dev_id') == $dev->id ? 'selected' : '' }}>
                                        {{ $dev->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @if(request()->anyFilled(['search', 'date', 'sale_id', 'dev_id']))
                            <a href="{{ route($routePrefix . '.meetings.index') }}" class="btn-primary-ghost"
                                style="padding:8px 12px; border-radius:8px; font-size:13px;"><i class="bi bi-x-lg"></i>
                                Clear</a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- ── MEETINGS TABLE ── -->
            <div class="dash-card">
                <div class="card-head"
                    style="padding:16px 20px; display:flex; justify-content:space-between; align-items:center;">
                    <div class="card-title">Timeline History ({{ $meetings->total() }})</div>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="orion-table">
                            <thead>
                                <tr>
                                    <th style="width:60px;">SL.</th>
                                    <th style="width:120px;">Date & Time</th>
                                    <th style="width:180px;">Target (Order/Lead)</th>
                                    <th style="width:200px;">Meeting Subject</th>
                                    <th>Description</th>
                                    <th style="width:100px;">Status</th>
                                    <th style="width:180px;">Developers</th>
                                    <th style="width:180px;">Sales Team</th>
                                    <th style="width:150px;">Created By</th>
                                    <th style="width:120px; text-align:right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($meetings as $meeting)
                                    <tr>
                                        <td style="font-weight:700; color:var(--t3);">
                                            {{ $meetings->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div style="font-weight:800; color:var(--t1);">
                                                {{ $meeting->meeting_date->format('d M, Y') }}</div>
                                            <div style="font-size:11px; font-weight:700; color:var(--t3); margin-top:2px;">
                                                <i class="bi bi-clock"></i>
                                                {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <span class="m-type-tag {{ strtolower($meeting->meeting_type) }}"
                                                style="display:inline-block; margin-bottom:4px;">{{ strtoupper($meeting->meeting_type) }}</span>
                                            @if($meeting->meeting_type == 'lead' && $meeting->lead)
                                                <a href="{{ route($routePrefix . '.leads.show', $meeting->lead_id) }}"
                                                    class="m-link active" style="font-size:12px;"><i
                                                        class="bi bi-person-circle"></i> {{ $meeting->lead->company }}</a>
                                            @elseif($meeting->meeting_type == 'order' && $meeting->order)
                                                <a href="{{ route($routePrefix . '.orders.index') }}" class="m-link active"
                                                    style="font-size:12px;"><i class="bi bi-bag-check"></i> Order
                                                    #{{ $meeting->order->id }}</a>
                                            @elseif($meeting->meeting_type == 'project' && $meeting->project)
                                                <a href="{{ route($routePrefix . '.projects.show', $meeting->project_id) }}"
                                                    class="m-link active" style="font-size:12px;"><i class="bi bi-kanban"></i>
                                                    {{ $meeting->project->name }}</a>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="font-weight:800; color:var(--t1); line-height:1.4;">
                                                {{ $meeting->meeting_title }}</div>
                                        </td>
                                        <td>
                                            <div class="m-desc-cell"
                                                title="{{ $meeting->meeting_description ?? 'No description' }}">
                                                {{ \Illuminate\Support\Str::limit($meeting->meeting_description ?? 'No description', 100) }}
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="m-status-pill {{ strtolower($meeting->status) }}">{{ $meeting->status }}</span>
                                        </td>
                                        <td>
                                            <div class="participant-stack">
                                                @php
                                                    $d_ids = is_string($meeting->assigndev_ids)
                                                        ? json_decode($meeting->assigndev_ids, true)
                                                        : ($meeting->assigndev_ids ?? []);
                                                @endphp

                                                @forelse($developers->whereIn('id', $d_ids ?? []) as $d)
                                                    <div class="p-item">
                                                        <span class="p-name">{{ $d->name ?? '' }}</span>
                                                        <span class="p-email">{{ $d->email ?? '' }}</span>
                                                    </div>
                                                @empty
                                                    <span>No Developer</span>
                                                @endforelse
                                            </div>
                                        </td>

                                        <td>
                                            <div class="participant-stack">
                                                @php
                                                    $s_ids = is_string($meeting->assignsale_ids)
                                                        ? json_decode($meeting->assignsale_ids, true)
                                                        : ($meeting->assignsale_ids ?? []);
                                                @endphp

                                                @forelse($sales->whereIn('id', $s_ids ?? []) as $s)
                                                    <div class="p-item">
                                                        <span class="p-name">{{ $s->name ?? '' }}</span>
                                                        <span class="p-email">{{ $s->email ?? '' }}</span>
                                                    </div>
                                                @empty
                                                    <span>No Sales Person</span>
                                                @endforelse
                                            </div>
                                        </td>
                                        <td>
                                            <div class="p-item">
                                                <span class="p-name">{{ $meeting->createdBy->name ?? 'System' }}</span>
                                                <span class="p-email">{{ $meeting->createdBy->email ?? '-' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="m-actions" style="justify-content:flex-end;">
                                                @if($meeting->meeting_link)
                                                    <a href="{{ $meeting->meeting_link }}" target="_blank" class="act-btn primary"
                                                        title="Join Link"><i class="bi bi-camera-video"></i></a>
                                                @endif
                                                <a href="{{ route($routePrefix . '.meetings.show', $meeting->id) }}"
                                                    class="act-btn" title="View Details"><i class="bi bi-eye"></i></a>
                                                <a href="{{ route($routePrefix . '.meetings.edit', $meeting->id) }}"
                                                    class="act-btn" title="Edit Meeting"><i class="bi bi-pencil"></i></a>
                                                @if($routePrefix == 'admin')
                                                    <button type="button" class="act-btn danger" title="Delete" onclick="confirmDelete('{{ route($routePrefix . '.meetings.destroy', $meeting->id) }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9">
                                            <div style="padding:60px 40px; text-align:center; color:var(--t4);">
                                                <i class="bi bi-calendar-x"
                                                    style="font-size:42px; display:block; margin-bottom:15px; opacity:0.5;"></i>
                                                <h3 style="font-size:16px; font-weight:700; color:var(--t2);">No matching
                                                    meetings found</h3>
                                                <p style="font-size:13px;">Try adjusting your filters or search query.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div style="padding:20px;">
                        {{ $meetings->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>

        </div>
    </main>

    <!-- {{-- DELETE MODAL --}} -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Meeting</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this meeting schedule?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="document.getElementById('deleteMeetingForm').submit()">
                    <i class="bi bi-trash3-fill"></i> Confirm Deletion
                </button>
            </div>
        </div>
    </div>

    <form id="deleteMeetingForm" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <style>
        /* ── ORION TABLE SYSTEM ── */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .orion-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 1000px;
        }

        .orion-table th {
            padding: 14px 20px;
            background: var(--bg4);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            color: var(--t4);
            letter-spacing: 0.5px;
            border-bottom: 1px solid var(--b1);
            position: sticky;
            top: 0;
            z-index: 10;
            text-align: left;
        }

        .orion-table td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid var(--b1);
            background: transparent;
            transition: background 0.2s;
        }

        .orion-table tr:hover td {
            background: var(--bg3);
        }

        .orion-table tr:last-child td {
            border-bottom: none;
        }

        /* Cells & Typography */
        .m-desc-cell {
            font-size: 13px;
            color: var(--t3);
            line-height: 1.5;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 300px;
        }

        .participant-stack {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .p-item {
            display: flex;
            flex-direction: column;
            line-height: 1.2;
        }

        .p-item .p-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--t1);
        }

        .p-item .p-email {
            font-size: 11px;
            font-weight: 600;
            color: var(--t4);
        }

        /* Metadata Tags */
        .m-type-tag {
            font-size: 9px;
            font-weight: 800;
            padding: 2px 8px;
            border-radius: 20px;
            letter-spacing: 0.5px;
        }

        .m-type-tag.lead {
            background: rgba(99, 102, 241, 0.12);
            color: #6366f1;
        }

        .m-type-tag.order {
            background: rgba(245, 158, 11, 0.12);
            color: #f59e0b;
        }

        .m-type-tag.project {
            background: rgba(16, 185, 129, 0.12);
            color: #10b981;
        }

        .m-status-pill {
            font-size: 10px;
            font-weight: 700;
            padding: 4px 12px;
            border-radius: 8px;
            text-transform: capitalize;
            display: inline-block;
        }

        .m-status-pill.pending {
            background: #fef3c7;
            color: #92400e;
        }

        .m-status-pill.rescheduled {
            background: #ffedd5;
            color: #ea580c;
        }

        .m-status-pill.completed {
            background: #d1fae5;
            color: #065f46;
        }

        .m-status-pill.canceled {
            background: #fee2e2;
            color: #991b1b;
        }

        .m-link {
            font-size: 12px;
            font-weight: 700;
            color: var(--t4);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            transition: 0.2s;
        }

        .m-link i {
            font-size: 14px;
        }

        .m-link.active {
            color: var(--accent);
        }

        .stat-card {
            padding: 18px 20px;
            display: flex;
            align-items: center;
            gap: 15px;
            overflow: hidden;
            position: relative;
        }

        .stat-card .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .stat-card.pending .stat-icon {
            background: #fffbeb;
            color: #d97706;
        }

        .stat-card.rescheduled .stat-icon {
            background: #fff7ed;
            color: #ea580c;
        }

        .stat-card.completed .stat-icon {
            background: #f0fdf4;
            color: #16a34a;
        }

        .stat-card.canceled .stat-icon {
            background: #fef2f2;
            color: #dc2626;
        }

        .stat-meta {
            display: flex;
            flex-direction: column;
        }

        .stat-lbl {
            font-size: 11px;
            font-weight: 700;
            color: var(--t4);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-val {
            font-size: 22px;
            font-weight: 800;
            color: var(--t1);
            margin-top: 2px;
        }

        .text-rescheduled {
            color: #ea580c;
        }

        .text-pending {
            color: #d97706;
        }

        .filter-inp {
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
            width: 100%;
            outline: none;
            transition: 0.2s;
        }

        .filter-inp:focus {
            border-color: var(--accent);
            background: var(--bg2);
        }

        .search-inp-wrap {
            position: relative;
            width: 100%;
        }

        .search-inp-wrap i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--t4);
            font-size: 14px;
            pointer-events: none;
        }

        .search-inp-wrap .filter-inp {
            padding-left: 40px;
        }

        .m-actions {
            display: flex;
            gap: 6px;
        }

        .act-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            background: var(--bg4);
            color: var(--t3);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 14px;
            text-decoration: none;
        }

        .act-btn:hover {
            background: var(--bg3);
            color: var(--accent);
        }

        .act-btn.primary {
            background: var(--accent-bg);
            color: var(--accent);
        }

        .act-btn.danger:hover {
            background: #fee2e2;
            color: #dc2626;
        }

        @media (max-width: 1200px) {

            .orion-table th:nth-child(4),
            .orion-table td:nth-child(4) {
                display: none;
            }

            /* Hide description on tablets */
        }
    </style>

    <script>
        let searchTimeout;
        function debounceSearch() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                document.getElementById('filterForm').submit();
            }, 600);
        }

        function confirmDelete(url) {
            const form = document.getElementById('deleteMeetingForm');
            form.action = url;
            openModal('deleteModal');
        }

        function openModal(id) {
            document.getElementById(id).style.display = 'flex';
            setTimeout(() => {
                document.getElementById(id).classList.add('active');
            }, 10);
        }

        function closeModal(id) {
            document.getElementById(id).classList.remove('active');
            setTimeout(() => {
                document.getElementById(id).style.display = 'none';
            }, 300);
        }
    </script>

@endsection