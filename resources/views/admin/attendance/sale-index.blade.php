@extends('admin.layout.app')

@section('title', 'Sales Team Attendance')

@section('content')
    <main class="page-area">
        <!-- Attendance Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Sales Team Attendance</h1>
                <p class="page-desc">Comprehensive log of all work presence for the sales department.</p>
            </div>

            <div class="page-actions">
                <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#settingsModal">
                    <i class="bi bi-gear-fill me-2"></i> Shift Settings
                </button>
            </div>
        </div>

        @php
            function formatDuration($seconds) {
                if($seconds <= 0) return '0s';
                $h = floor($seconds / 3600);
                $m = floor(($seconds % 3600) / 60);
                $s = $seconds % 60;
                return ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
            }
        @endphp

        <!-- Attendance Stats -->
        <div class="kpi-grid mb-4" style="display:grid; grid-template-columns: repeat(4, 1fr); gap:20px;">
             <div class="kpi-card" style="--kpi-accent:#6366f1">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-people-fill"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->total() }}</div>
                <div class="kpi-label">Total Logs</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#10b981">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-check-circle-fill"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->where('status', 'Present')->count() }}</div>
                <div class="kpi-label">Present</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#f59e0b">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-exclamation-triangle-fill"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->where('status', 'Late')->count() }}</div>
                <div class="kpi-label">Late</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#0ea5e9">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(14,165,233,.15);color:#0ea5e9"><i class="bi bi-stopwatch-fill"></i></div>
                </div>
                <div class="kpi-value" style="font-size:18px;">{{ formatDuration($totalWorkSeconds) }}</div>
                <div class="kpi-label">Team Total Hours</div>
            </div>
        </div>

        <div class="dash-card">
            <div class="card-head" style="align-items: center; gap: 12px; flex-wrap: wrap;">
                <div class="card-title">All Sales Representatives</div>
                <div style="display: flex; align-items: center; gap: 10px; margin-left: auto; flex-wrap: wrap;">
                    <form action="{{ route('admin.attendance.sale-index') }}" method="GET" class="card-actions mb-0" id="filterForm" style="display:flex; align-items:center; gap:8px;">
                        
                        <select name="user_id" class="filter-select" onchange="document.getElementById('filterForm').submit()" style="height:38px; min-width:160px;">
                            <option value="">All Sales Staff</option>
                            @foreach($allSales as $sale)
                                <option value="{{ $sale->id }}" {{ request('user_id') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                            @endforeach
                        </select>

                        <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                        <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()" style="height:38px;">
                            <i class="bi bi-calendar3"></i>
                            <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'All Time' }}</span>
                            <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                        </button>

                        <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                        <span style="color:var(--t4); font-size:12px; font-weight:600;">OR</span>
                        <input type="date" name="date" class="filter-select" value="{{ request('date') }}" onchange="this.form.submit()" style="height:38px; width:140px;">
                    </form>

                    <div style="position:relative;">
                        @include('admin.includes.date-range-picker')
                    </div>
                </div>
            </div>

            <script>
                document.addEventListener('dateRangeApplied', function(e) {
                    const { start, end } = e.detail;
                    function formatDate(date) {
                        if(!date) return '';
                        let d = new Date(date),
                            month = '' + (d.getMonth() + 1),
                            day = '' + d.getDate(),
                            year = d.getFullYear();
                        if (month.length < 2) month = '0' + month;
                        if (day.length < 2) day = '0' + day;
                        return [year, month, day].join('-');
                    }
                    const sInp = document.getElementById('drpStartInput');
                    const eInp = document.getElementById('drpEndInput');
                    if(sInp && eInp) {
                        sInp.value = formatDate(start);
                        eInp.value = formatDate(end);
                        document.getElementById('filterForm').submit();
                    }
                });
            </script>
            <div class="card-body">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Staff Name</th>
                                <th>Date</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Late Hours</th>
                                <th>Total Hours</th>
                                <th>Check-in Photo</th>
                                <th>Check-out Photo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $index => $row)
                                <tr>
                                    <td>{{ ($attendances ?? null) ? $attendances->firstItem() + $index : ($index + 1) }}</td>
                                    <td>
                                        <div class="user-info" style="align-items: center; gap: 8px;">
                                            <div class="user-ava sm" style="background:var(--accent); width:32px; height:32px; font-size:12px;">{{ strtoupper(substr($row->user?->name ?? 'S', 0, 1)) }}</div>
                                            <div class="user-det">
                                                <div class="u-name-sm" style="font-size:13px; font-weight:600;">{{ $row->user?->name ?? 'Unknown' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $row->date?->format('d M, Y') ?? 'N/A' }}</td>
                                    <td>{{ $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('h:i A') : '--:--' }}</td>
                                    <td>{{ $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('h:i A') : '--:--' }}</td>
                                    <td>
                                        @if(($row->late_seconds ?? 0) > 0)
                                            <span class="text-danger fw-bold">{{ formatDuration($row->late_seconds) }} Late</span>
                                        @else
                                            <span class="text-success small">On-time</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->check_out_time)
                                            @php
                                                $displaySeconds = (int)($row->total_seconds ?? 0);
                                                // Dynamic fallback for older records or test entries
                                                if($displaySeconds == 0 && $row->check_in_time && $row->date){
                                                    $cIn = \Carbon\Carbon::parse($row->date->format('Y-m-d') . ' ' . $row->check_in_time);
                                                    $cOut = \Carbon\Carbon::parse($row->date->format('Y-m-d') . ' ' . $row->check_out_time);
                                                    $displaySeconds = abs($cOut->diffInSeconds($cIn, false));
                                                }
                                            @endphp
                                            <span class="fw-bold">{{ formatDuration(abs($displaySeconds)) }}</span>
                                        @else
                                             <span class="text-muted small">Active...</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->check_in_screenshot)
                                            <a href="javascript:void(0)" onclick="viewScreenshot('{{ asset('storage/' . $row->check_in_screenshot) }}', 'Check-in Proof')" class="v-link in" title="Check-in Proof">
                                                <i class="bi bi-camera"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->check_out_screenshot)
                                            <a href="javascript:void(0)" onclick="viewScreenshot('{{ asset('storage/' . $row->check_out_screenshot) }}', 'Check-out Proof')" class="v-link out" title="Check-out Proof">
                                                <i class="bi bi-camera-fill"></i>
                                            </a>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9" class="text-center py-4">No records found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">{{ $attendances->links() }}</div>
            </div>
        </div>
    </main>

    <!-- Screenshot Modal -->
    <div class="modal fade" id="screenshotModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; background:var(--bg2);">
                <div class="modal-header border-0 px-4 pt-4 pb-0">
                    <h5 class="modal-title fw-bold" id="screenshotModalTitle">Verification Proof</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4 text-center">
                    <img id="screenshotImg" src="" alt="Screenshot" style="width:100%; height:auto; border-radius:12px; box-shadow:0 10px 30px rgba(0,0,0,0.2);">
                </div>
            </div>
        </div>
    </div>

    <!-- SETTINGS MODAL -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold" style="color:var(--t1)">Shift Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.attendance.store-settings') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Sales Check-in</label>
                                <input type="time" name="sale_checkin_time" class="form-control" value="{{ $settings->sale_checkin_time }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Sales Check-out</label>
                                <input type="time" name="sale_checkout_time" class="form-control" value="{{ $settings->sale_checkout_time }}">
                            </div>
                            <!-- Preserve dev times too -->
                            <input type="hidden" name="dev_checkin_time" value="{{ $settings->dev_checkin_time }}">
                            <input type="hidden" name="dev_checkout_time" value="{{ $settings->dev_checkout_time }}">
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Grace Period (Minutes)</label>
                                <input type="number" name="grace_period_minutes" class="form-control" value="{{ $settings->grace_period_minutes }}">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 py-3">
                        <button type="submit" class="btn-primary-solid">Update Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .verification-actions { display: flex; align-items: center; justify-content: flex-start; }
        .v-link { font-size: 20px; line-height: 1; transition: 0.2s; }
        .v-link.in { color: var(--accent); }
        .v-link.out { color: var(--red); }
        .v-link:hover { transform: translateY(-2px); }
        
        /* Ensure table data is left aligned */
        .table th, .table td { text-align: left !important; }
    </style>

    <script>
        function viewScreenshot(url, title) {
            document.getElementById('screenshotImg').src = url;
            document.getElementById('screenshotModalTitle').innerText = title;
            const modal = new bootstrap.Modal(document.getElementById('screenshotModal'));
            modal.show();
        }
    </script>
@endsection
