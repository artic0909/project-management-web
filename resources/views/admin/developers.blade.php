@extends('admin.layout.app')

@section('title', 'Add Developers')

@section('content')

<style>
    .multi-row {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 6px;
    }

    .multi-row:last-child {
        margin-bottom: 0;
    }

    .phone-wrap {
        display: flex;
        flex: 1;
        min-width: 0;
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
        overflow: hidden;
    }

    .country-sel {
        border: none;
        border-right: 1px solid var(--b1);
        background: var(--bg3);
        color: var(--t2);
        padding: 6px 4px 6px 8px;
        font-size: 13px;
        cursor: pointer;
        outline: none;
        font-family: inherit;
        width: 100px;
        flex-shrink: 0;
    }

    .phone-num-inp {
        border: none;
        padding: 6px 10px;
        font-size: 14px;
        font-family: inherit;
        flex: 1;
        min-width: 0;
        outline: none;
        background: transparent;
        color: var(--t1);
    }

    .multi-email-inp {
        flex: 1;
        min-width: 0;
    }

    .row-remove-btn {
        background: none;
        border: 1px solid var(--b2);
        border-radius: var(--r-sm);
        width: 28px;
        height: 28px;
        cursor: pointer;
        color: var(--t3);
        font-size: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        padding: 0;
        transition: var(--transition);
    }

    .row-remove-btn:hover {
        color: #ef4444;
        border-color: #ef4444;
        background: rgba(239, 68, 68, .08);
    }

    /* ── Summary stat boxes ── */
    .stat-scroll-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 20px;
        scrollbar-width: none;
    }

    .stat-scroll-row::-webkit-scrollbar {
        display: none;
    }

    .stat-box {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 11px 16px;
        min-width: 148px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

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
        transition: transform .25s ease;
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

    .sb-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--sb-color) 14%, transparent);
        color: var(--sb-color);
    }

    .sb-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.4px;
        line-height: 1;
    }

    .sb-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 500;
        margin-top: 2px;
        white-space: nowrap;
    }

    .stat-section-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--t4);
        padding: 0 6px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: var(--b2);
        flex-shrink: 0;
        margin: 0 4px;
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
                <h1 class="page-title">Your All Developers</h1>
            </div>
            <div class="d-flex gap-2">
                @if($routePrefix == 'admin')
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                @endif
                <button class="btn-primary-solid sm" onclick="openModal('addModal')"><i class="bi bi-plus-lg"></i> Add Developer</button>
            </div>
        </div>

        @if (session('success'))
        <div style="background: #dcfce7; color: #166534; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #bbf7d0; display: flex; align-items: center; gap: 8px;">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div style="background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #fecaca;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <!-- SUMMARY STAT BOXES -->
        <div class="stat-scroll-row">
            <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-person-workspace"></i></div>
                <div>
                    <div class="sb-val">{{ $developers->count() }}</div>
                    <div class="sb-lbl">Total Developers</div>
                </div>
            </div>
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">


            <!-- Developer Table -->
            <div class="dash-card span-12">
                <div class="card-head mb-2">
                    <div>
                        <div class="card-title">Developers</div>
                        <div class="card-sub">{{ $developers->count() }} total</div>
                    </div>
                    <div class="card-actions">
                        @if($routePrefix == 'admin')
                        <div id="bulkActions" style="display: none; align-items: center; gap: 10px; margin-right: 15px;">
                            <span id="selectedCount" style="font-size: 13px; font-weight: 600; color: var(--accent);">0 selected</span>
                            <button type="button" class="btn-primary-solid sm" style="background: #ef4444; border-color: #ef4444;" onclick="bulkDelete()">
                                <i class="bi bi-trash"></i> Delete Selected
                            </button>
                        </div>
                        @endif
                        <form class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </form>
                    </div>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                @if($routePrefix == 'admin')
                                <th style="width: 40px; padding-left: 20px;">
                                    <input type="checkbox" id="selectAll" class="custom-checkbox" onchange="toggleAll(this)">
                                </th>
                                @endif
                                <th>SL</th>
                                <th>Name</th>
                                <th>Designation</th>
                                <th>Email</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($developers as $index => $developer)
                            <tr>
                                @if($routePrefix == 'admin')
                                <td style="padding-left: 20px;">
                                    <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $developer->id }}" onchange="updateBulkActionState()">
                                </td>
                                @endif
                                <td>{{ $developers->firstItem() + $index }}</td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                                            {{ strtoupper(substr($developer->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="ln">{{ $developer->name }}</div>
                                            <div class="ls">{{ $developer->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="src-tag website">{{ $developer->designation }}</span></td>
                                <td><span class="src-tag website">{{ $developer->email }}</span></td>
                                <td>
                                    <!-- Modal Btns -->
                                    <div class="row-actions">
                                        <!-- <button class="ra-btn" title="Toggle Power"><i class="bi bi-power"></i></button> -->
                                        <button class="ra-btn" title="Edit" onclick="editDeveloper({{ json_encode($developer) }})"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn" title="Send Email"><i class="bi bi-envelope-fill"></i></button>
                                        @if($routePrefix == 'admin')
                                        <button class="ra-btn danger" title="Delete" onclick="deleteDeveloper({{ $developer->id }})"><i class="bi bi-trash-fill"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer">
                    <span class="tf-info">Showing {{ $developers->firstItem() ?? 0 }} to {{ $developers->lastItem() ?? 0 }} of {{ $developers->total() }} Developers</span>
                    <div class="tf-pagination">
                        {{ $developers->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div class="modal-backdrop" id="addModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Add Developer</span><button class="modal-close" onclick="closeModal('addModal')"><i class="bi bi-x-lg"></i></button></div>
            <form action="{{ route($routePrefix . '.developer.store') }}" method="POST">
                @csrf
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row">
                            <label class="form-lbl">Developer *</label>
                            <input type="text" name="name" class="form-inp" placeholder="Developer name" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Email *</label>
                            <input type="email" name="email" class="form-inp" placeholder="email@company.com" required>
                        </div>
                        <div class="form-row" style="grid-column: 1 / -1;">
                            <label class="form-lbl">Designation *</label>
                            <input type="text" name="designation" class="form-inp" placeholder="Developer Designation" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Set Password *</label>
                            <input type="password" name="password" class="form-inp" value="12345" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Confirm Password *</label>
                            <input type="password" name="password_confirmation" class="form-inp" value="12345" required>
                        </div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" onclick="closeModal('addModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">
                        <i class="bi bi-plus-lg"></i> Add Developer
                    </button>
                </div>
            </form>
        </div>
    </div>


    <!-- Edit Modal -->
    <div class="modal-backdrop" id="editModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd"><span>Update Developer</span><button class="modal-close" onclick="closeModal('editModal')"><i class="bi bi-x-lg"></i></button></div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row">
                            <label class="form-lbl">Developer *</label>
                            <input type="text" name="name" id="edit_name" class="form-inp" placeholder="Developer name" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Email *</label>
                            <input type="email" name="email" id="edit_email" class="form-inp" placeholder="email@company.com" required>
                        </div>
                        <div class="form-row" style="grid-column: 1 / -1;">
                            <label class="form-lbl">Designation *</label>
                            <input type="text" name="designation" id="edit_designation" class="form-inp" placeholder="Developer Designation" required>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Set Password (leave blank to keep current)</label>
                            <input type="password" name="password" class="form-inp" autocomplete="new-password">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-inp" autocomplete="new-password">
                        </div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button type="button" class="btn-ghost" onclick="closeModal('editModal')">Cancel</button>
                    <button type="submit" class="btn-primary-solid">
                        <i class="bi bi-plus-lg"></i> Update Developer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Developer</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <form id="deleteForm" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                    <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                    </div>
                    <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                    <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Developer?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
                </div>
                <div class="modal-ft" style="border-top:1px solid #fecaca;">
                    <button type="button" class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                    <button type="submit" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                        <i class="bi bi-trash3-fill"></i> Delete Developer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function editDeveloper(developer) {
            document.getElementById('editForm').action = `{{ url($routePrefix . '/add-developer') }}/${developer.id}`;
            document.getElementById('edit_name').value = developer.name;
            document.getElementById('edit_email').value = developer.email;
            document.getElementById('edit_designation').value = developer.designation;
            // Clear password fields on open
            document.querySelector('#editForm [name="password"]').value = '';
            document.querySelector('#editForm [name="password_confirmation"]').value = '';
            openModal('editModal');
        }

        function deleteDeveloper(id) {
            document.getElementById('deleteForm').action = `{{ url($routePrefix . '/add-developer') }}/${id}`;
            openModal('deleteModal');
        }

        function toggleAll(source) {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateBulkActionState();
        }

        function updateBulkActionState() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            const bulkActions = document.getElementById('bulkActions');
            const selectedCount = document.getElementById('selectedCount');
            const selectAll = document.getElementById('selectAll');
            const allCheckboxes = document.querySelectorAll('.row-checkbox');

            if (checkboxes.length > 0) {
                bulkActions.style.display = 'flex';
                selectedCount.innerText = checkboxes.length + ' selected';
            } else {
                bulkActions.style.display = 'none';
            }

            if (checkboxes.length === allCheckboxes.length && allCheckboxes.length > 0) {
                if (selectAll) selectAll.checked = true;
            } else {
                if (selectAll) selectAll.checked = false;
            }
        }

        function bulkDelete() {
            const checkboxes = document.querySelectorAll('.row-checkbox:checked');
            if (checkboxes.length === 0) return;

            if (confirm(`Are you sure you want to delete ${checkboxes.length} Developers?`)) {
                const ids = Array.from(checkboxes).map(cb => cb.value);
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `{{ route($routePrefix . '.developer.bulk-destroy') }}`;

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                ids.forEach(id => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = id;
                    form.appendChild(input);
                });

                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

</main>

@endsection