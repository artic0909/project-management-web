@extends('admin.layout.app')

@section('title', 'Add Leads')

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
                <h1 class="page-title">Your All Leads</h1>
            </div>
        </div>

        <!-- SUMMARY STAT BOXES -->
        <div class="stat-scroll-row">
            <span class="stat-section-lbl">Overview</span>
            <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-people-fill"></i></div>
                <div>
                    <div class="sb-val">147</div>
                    <div class="sb-lbl">Total Leads</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#10b981;">
                <div class="sb-icon"><i class="bi bi-person-check-fill"></i></div>
                <div>
                    <div class="sb-val">38</div>
                    <div class="sb-lbl">Converted</div>
                </div>
            </div>

            <div class="stat-divider"></div>
            <span class="stat-section-lbl">Priority</span>
            <div class="stat-box" style="--sb-color:#ef4444;">
                <div class="sb-icon"><i class="bi bi-fire"></i></div>
                <div>
                    <div class="sb-val" style="color:#ef4444;">38</div>
                    <div class="sb-lbl">Hot 🔥</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#f59e0b;">
                <div class="sb-icon"><i class="bi bi-thermometer-half"></i></div>
                <div>
                    <div class="sb-val" style="color:#f59e0b;">54</div>
                    <div class="sb-lbl">Warm</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#06b6d4;">
                <div class="sb-icon"><i class="bi bi-snow"></i></div>
                <div>
                    <div class="sb-val" style="color:#06b6d4;">41</div>
                    <div class="sb-lbl">Cold</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#6b7280;">
                <div class="sb-icon"><i class="bi bi-x-circle"></i></div>
                <div>
                    <div class="sb-val" style="color:#6b7280;">14</div>
                    <div class="sb-lbl">Lost</div>
                </div>
            </div>

            <div class="stat-divider"></div>
            <span class="stat-section-lbl">Status</span>
            <div class="stat-box" style="--sb-color:#10b981;">
                <div class="sb-icon"><i class="bi bi-chat-dots-fill"></i></div>
                <div>
                    <div class="sb-val">29</div>
                    <div class="sb-lbl">Respond</div>
                </div>
            </div>
            <!-- <div class="stat-box" style="--sb-color:#6366f1;">
                <div class="sb-icon"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="sb-val">18</div>
                    <div class="sb-lbl">Interested</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#f59e0b;">
                <div class="sb-icon"><i class="bi bi-wallet2"></i></div>
                <div>
                    <div class="sb-val">11</div>
                    <div class="sb-lbl">Budget Issue</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#8b5cf6;">
                <div class="sb-icon"><i class="bi bi-calendar-check-fill"></i></div>
                <div>
                    <div class="sb-val">9</div>
                    <div class="sb-lbl">Booked</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#ef4444;">
                <div class="sb-icon"><i class="bi bi-hand-thumbs-down-fill"></i></div>
                <div>
                    <div class="sb-val">16</div>
                    <div class="sb-lbl">Not Interested</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#6b7280;">
                <div class="sb-icon"><i class="bi bi-telephone-x-fill"></i></div>
                <div>
                    <div class="sb-val">22</div>
                    <div class="sb-lbl">Not Responding</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#9ca3af;">
                <div class="sb-icon"><i class="bi bi-slash-circle"></i></div>
                <div>
                    <div class="sb-val">8</div>
                    <div class="sb-lbl">Not Required</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#f97316;">
                <div class="sb-icon"><i class="bi bi-geo-alt-fill"></i></div>
                <div>
                    <div class="sb-val">5</div>
                    <div class="sb-lbl">Location Issue</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#06b6d4;">
                <div class="sb-icon"><i class="bi bi-translate"></i></div>
                <div>
                    <div class="sb-val">4</div>
                    <div class="sb-lbl">Language Barrier</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#64748b;">
                <div class="sb-icon"><i class="bi bi-question-circle-fill"></i></div>
                <div>
                    <div class="sb-val">7</div>
                    <div class="sb-lbl">Not Inquired</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#14b8a6;">
                <div class="sb-icon"><i class="bi bi-briefcase-fill"></i></div>
                <div>
                    <div class="sb-val">3</div>
                    <div class="sb-lbl">Job</div>
                </div>
            </div> -->
        </div>


        <!-- MAIN GRID -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Lead Pipeline</div>
                        <div class="card-sub" id="drpActiveSub">Last 7 Days · 147 total · 38 hot leads</div>
                    </div>
                    <div class="card-actions mb-2">

                        <form class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </form>

                        <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                        <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                            <i class="bi bi-calendar3"></i>
                            <span id="drpLabel">Last 7 Days</span>
                            <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                        </button>

                        <!-- {{-- Date Range Picker (replaces simple select) --}} -->
                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>

                        <select class="filter-select">
                            <option selected>Lead Source</option>
                            <option>All Sources</option>
                            <option>Website</option>
                            <option>Referral</option>
                            <option>LinkedIn</option>
                        </select>

                        <select class="filter-select">
                            <option selected>All Services</option>
                            <option>Website Design</option>
                            <option>Marketing</option>
                        </select>

                        <select class="filter-select">
                            <option selected>Priority</option>
                            <option>Hot 🔥</option>
                            <option>Cold</option>
                            <option>Warm</option>
                            <option>Lost</option>
                        </select>

                        <button class="btn-primary-solid sm" onclick="openModal('addLeadModal')">
                            <i class="bi bi-plus-lg"></i> Add Lead
                        </button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Lead</th>
                                <th>Source</th>
                                <th>Contact Person</th>
                                <th>Service Need</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>1</td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div>
                                        <div>
                                            <div class="ln">DataFirst Corp</div>
                                            <div class="ls">cto@datafirst.io</div>
                                        </div>
                                    </div>
                                </td>
                                <td><span class="src-tag website">Website</span></td>
                                <td><strong style="color:#10b981">Abhishek</strong></td>
                                <td><strong style="color:#10b981">Website Design</strong></td>
                                <td><span class="lead-stage hot">Hot 🔥</span></td>
                                <td><strong style="color:#10b981">Respond</strong></td>
                                <td>
                                    <div class="ln">Ravi Singh</div>
                                    <div class="ls">ravi@company.com</div>
                                </td>
                                <td>
                                    <div class="ln">Rahul Kumar</div>
                                    <div class="ls">rahul@company.com</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" title="View" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn" title="Call"><i class="bi bi-telephone-fill"></i></button>
                                        <button class="ra-btn" title="Email"><i class="bi bi-envelope-fill"></i></button>
                                        <a href="{{route('admin.leads.followup')}}" class="ra-btn" title="Followup" target="_blank"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <button class="ra-btn" title="Edit" onclick="openModal('editLeadModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" title="Delete" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing 5 of 147 Leads</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <span class="pg-ellipsis">…</span>
                        <button class="pg-btn">5</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>
    </div>


    <!-- ADD MODAL -->
    <div class="modal-backdrop" id="addLeadModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Add New Lead</span>
                <button class="modal-close" onclick="closeModal('addLeadModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Email</label>
                        <div id="add-email-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('add-email-list')"><i class="bi bi-plus-lg"></i> Add Email</button>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Phone</label>
                        <div id="add-phone-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('add-phone-list')"><i class="bi bi-plus-lg"></i> Add Phone</button>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Service Need</label>
                        <select class="form-inp">
                            <option>Web</option>
                            <option>Design</option>
                            <option>Mark</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Address</label>
                        <textarea class="form-inp" rows="3" placeholder="Full address…"></textarea>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Source</label>
                        <select class="form-inp">
                            <option>Web</option>
                            <option>Linkedin</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Priority</label>
                        <select class="form-inp">
                            <option>Cold</option>
                            <option>Warm</option>
                            <option>Hot 🔥</option>
                            <option>Lost</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Issue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addLeadModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addLeadModal');showToast('success','Lead added!','bi-person-check-fill')">
                    <i class="bi bi-plus-lg"></i> Add Lead
                </button>
            </div>
        </div>
    </div>

    <!-- EDIT MODAL -->
    <div class="modal-backdrop" id="editLeadModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Update Lead</span>
                <button class="modal-close" onclick="closeModal('editLeadModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Email</label>
                        <div id="edit-email-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('edit-email-list')"><i class="bi bi-plus-lg"></i> Add Email</button>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Phone</label>
                        <div id="edit-phone-list"></div>
                        <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('edit-phone-list')"><i class="bi bi-plus-lg"></i> Add Phone</button>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Service Need</label>
                        <select class="form-inp">
                            <option>Web</option>
                            <option>Design</option>
                            <option>Mark</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Address</label>
                        <textarea class="form-inp" rows="3" placeholder="Full address…"></textarea>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Source</label>
                        <select class="form-inp">
                            <option>Web</option>
                            <option>Linkedin</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Priority</label>
                        <select class="form-inp">
                            <option>Cold</option>
                            <option>Warm</option>
                            <option>Hot 🔥</option>
                            <option>Lost</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Issue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editLeadModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editLeadModal');showToast('success','Lead updated!','bi-person-check-fill')">
                    <i class="bi bi-pencil-fill"></i> Update Lead
                </button>
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
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="closeModal('deleteModal');showToast('success','Lead Deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete Lead
                </button>
            </div>
        </div>
    </div>

    <!-- LEAD DETAIL MODAL -->
    <div class="modal-backdrop" id="leadDetailModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Lead Detail — TechCorp Solutions</span>
                <button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="detail-kpis" style="margin-bottom:20px">
                    <div class="dk-item">
                        <div class="dk-val">12-02-2026</div>
                        <div class="dk-lbl">Created Date</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#ef4444">Hot 🔥</div>
                        <div class="dk-lbl">Priority</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Website</div>
                        <div class="dk-lbl">Service Need</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Linkedin</div>
                        <div class="dk-lbl">Lead Source</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Contact</label><input class="form-inp" value="Vikram Bhatia" readonly></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="vikram@techcorp.com" readonly></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="+91 98765 43210" readonly></div>
                    <div class="form-row">
                        <label class="form-lbl">Change Priority</label>
                        <select class="form-inp">
                            <option>Hot</option>
                            <option>Warm</option>
                            <option>Cold</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Change Lead Status</label>
                        <select class="form-inp">
                            <option>Not Responding</option>
                            <option>Not Interested</option>
                            <option>Not Required</option>
                            <option>Location Issue</option>
                            <option>Job</option>
                            <option>Not Inquired</option>
                            <option>Respond</option>
                            <option>Interested</option>
                            <option>Language Barrier</option>
                            <option>Booked</option>
                            <option>Budget Issue</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Convert Lead To</label>
                        <select class="form-inp">
                            <option>Order</option>
                            <option>Closed</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Add Remark/Note</label>
                        <textarea class="form-inp" rows="2" placeholder="Add note…"></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('leadDetailModal')">Close</button>
                <button class="btn-primary-solid" onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">Update Lead</button>
            </div>
        </div>
    </div>

</main>


<script>
    /* ═══════════════════════════════════════════
   DATE RANGE PICKER LOGIC
═══════════════════════════════════════════ */
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

            // Update card subtitle
            const sub = document.getElementById('drpActiveSub');
            if (sub) sub.textContent = display + ' · 147 total · 38 hot leads';

            closeDatePicker();
            document.dispatchEvent(new CustomEvent('dateRangeApplied', {
                detail: {
                    preset: activePreset,
                    start: rangeStart,
                    end: rangeEnd
                }
            }));
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
</script>

@endsection