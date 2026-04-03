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
        position: absolute;
        top: calc(100% + 8px);
        right: 0;
        z-index: 1000;
        display: none;
        background: var(--bg2);
        border: 1px solid var(--b2);
        border-radius: var(--r-lg);
        box-shadow: 0 20px 60px rgba(0, 0, 0, .28), 0 4px 16px rgba(0, 0, 0, .14);
        overflow: hidden;
        animation: drpIn .18s cubic-bezier(.34, 1.56, .64, 1);
        min-width: 760px;
        max-height: calc(100vh - 100px);
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
        width: 190px;
        flex-shrink: 0;
        border-right: 1px solid var(--b1);
        padding: 14px 10px;
        overflow-y: auto;
        max-height: 500px;
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
        gap: 12px;
        justify-content: flex-end;
        align-items: center;
        margin-top: auto;
        padding-top: 16px;
        border-top: 1px solid var(--b1);
    }
</style>

<!-- ══ DATE RANGE PICKER PANEL (fixed positioned) ══ -->
<div id="dateRangePanel" class="drp-panel" style="display:none;">

    <!-- Left: Presets -->
    <div class="drp-presets">
        <div class="drp-preset-group-label">Quick select</div>
        <label class="drp-preset" data-preset="today"><span class="drp-radio"></span> Today</label>
        <label class="drp-preset" data-preset="yesterday"><span class="drp-radio"></span> Yesterday</label>
        <label class="drp-preset" data-preset="today_yesterday"><span class="drp-radio"></span> Today & Yesterday</label>
        <div class="drp-preset-group-label" style="margin-top:10px;">Ranges</div>
        <label class="drp-preset active" data-preset="last7"><span class="drp-radio"></span> 7 Days</label>
        <label class="drp-preset" data-preset="last1month"><span class="drp-radio"></span> 1 Month</label>
        <label class="drp-preset" data-preset="last6month"><span class="drp-radio"></span> 6 Month</label>
        <label class="drp-preset" data-preset="last1year"><span class="drp-radio"></span> 1 Year</label>
        <label class="drp-preset" data-preset="custom"><span class="drp-radio"></span> Custom range</label>
    </div>

    <!-- Right: Calendars -->
    <div class="drp-calendars">
        <div class="drp-cal-row">
            <!-- Month 1 -->
            <div class="drp-cal">
                <div class="drp-cal-nav">
                    <button type="button" class="drp-nav-btn" onclick="shiftMonths(-1)"><i class="bi bi-chevron-left"></i></button>
                    <div class="drp-cal-title">
                        <select class="drp-month-sel" id="month1Sel" onchange="onMonthChange(0)"></select>
                        <select class="drp-year-sel" id="year1Sel" onchange="onYearChange(0)"></select>
                    </div>
                </div>
                <table class="drp-cal-table" id="cal1"></table>
            </div>
            <!-- Month 2 -->
            <div class="drp-cal">
                <div class="drp-cal-nav">
                    <div class="drp-cal-title">
                        <select class="drp-month-sel" id="month2Sel" onchange="onMonthChange(1)"></select>
                        <select class="drp-year-sel" id="year2Sel" onchange="onYearChange(1)"></select>
                    </div>
                    <button type="button" class="drp-nav-btn" onclick="shiftMonths(1)"><i class="bi bi-chevron-right"></i></button>
                </div>
                <table class="drp-cal-table" id="cal2"></table>
            </div>
        </div>

        <!-- Compare -->
        <div class="drp-compare-row">
            <label class="drp-compare-toggle">
                <input type="checkbox" id="compareToggle" onchange="toggleCompare()">
                <span class="drp-compare-chk"></span>
                <span style="font-size:13px;font-weight:500;color:var(--t2);">Compare</span>
            </label>
            <div class="drp-compare-inputs" id="compareInputs" style="display:none;">
                <select class="drp-compare-sel" id="comparePreset">
                    <option value="preceding">Preceding period</option>
                    <option value="prev_year">Previous year</option>
                    <option value="custom_cmp">Custom</option>
                </select>
                <div class="drp-date-inputs">
                    <input type="text" class="drp-date-inp" id="cmpStart" readonly placeholder="Start">
                    <span class="drp-dash">—</span>
                    <input type="text" class="drp-date-inp" id="cmpEnd" readonly placeholder="End">
                </div>
            </div>
        </div>

        <!-- Range display -->
        <div class="drp-range-display">
            <div class="drp-range-fields">
                <div class="drp-range-field">
                    <span class="drp-range-lbl">From</span>
                    <span class="drp-range-val" id="rangeStartDisplay">—</span>
                </div>
                <i class="bi bi-arrow-right" style="color:var(--t4);font-size:12px;"></i>
                <div class="drp-range-field">
                    <span class="drp-range-lbl">To</span>
                    <span class="drp-range-val" id="rangeEndDisplay">—</span>
                </div>
            </div>
            <div style="font-size:11px;color:var(--t4);margin-top:6px;">Dates are shown in local time</div>
        </div>

        <!-- Footer -->
        <div class="drp-footer">
            <button type="button" class="btn-ghost" onclick="cancelDatePicker()">Cancel</button>
            <button type="button" class="btn-primary-solid" onclick="applyDatePicker()">
                <i class="bi bi-check2"></i> Update
            </button>
        </div>
    </div>
</div>


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

        function fmtBackend(d) {
            if (!d) return '';
            let year = d.getFullYear();
            let month = ('0' + (d.getMonth() + 1)).slice(-2);
            let day = ('0' + d.getDate()).slice(-2);
            return `${year}-${month}-${day}`;
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
            last1month: () => {
                const a = new Date(today);
                a.setMonth(a.getMonth() - 1);
                return [a, clone(today)];
            },
            last6month: () => {
                const a = new Date(today);
                a.setMonth(a.getMonth() - 6);
                return [a, clone(today)];
            },
            last1year: () => {
                const a = new Date(today);
                a.setFullYear(a.getFullYear() - 1);
                return [a, clone(today)];
            },
            custom: () => [null, null],
        };

        const presetLabels = {
            today: 'Today',
            yesterday: 'Yesterday',
            today_yesterday: 'Today & Yesterday',
            last7: '7 Days',
            last1month: '1 Month',
            last6month: '6 Month',
            last1year: '1 Year',
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
            if (panel.style.display === 'flex') {
                closeDatePicker();
                return;
            }

            panel.style.display = 'flex';
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
            
            const lbl = document.getElementById('drpLabel');
            if (lbl) lbl.textContent = display;

            // Update hidden inputs if they exist (for the search form)
            const startInp = document.getElementById('drpStartInput');
            const endInp = document.getElementById('drpEndInput');
            if (startInp && rangeStart) startInp.value = fmtBackend(rangeStart);
            if (endInp && rangeEnd) endInp.value = fmtBackend(rangeEnd);

            // Update card subtitle
            const sub = document.getElementById('drpActiveSub');
            if (sub) {
                // Keep the existing stats but update the date text
                let currentText = sub.textContent;
                let parts = currentText.split(' · ');
                if (parts.length > 1) {
                    sub.textContent = display + ' · ' + parts.slice(1).join(' · ');
                } else {
                    sub.textContent = display;
                }
            }

            closeDatePicker();
            
            // Trigger AJAX filter update if the function exists
            if (typeof window.updateFilters === 'function') {
                window.updateFilters();
            }

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
</script>