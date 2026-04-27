@extends('admin.layout.app')

@section('title', 'My Attendance')

@section('content')
    <main class="page-area">
        <!-- Attendance Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Attendance Tracking</h1>
                <p class="page-desc">Manage your presence and daily work logs.</p>
            </div>

            <div class="page-actions">
                @php
                    $isCurrentlyCheckedIn = ($todayAttendance ?? null) && ($todayAttendance?->is_checked_in);
                    $userRole = ucfirst($routePrefix);

                    function formatDuration($seconds) {
                        $seconds = (int)($seconds ?? 0);
                        if($seconds < 0) $seconds = 0;
                        $h = floor($seconds / 3600);
                        $m = floor(($seconds % 3600) / 60);
                        $s = $seconds % 60;
                        
                        $res = [];
                        if($h > 0) $res[] = $h . 'h';
                        if($m > 0) $res[] = $m . 'm';
                        $res[] = $s . 's'; 
                        
                        return count($res) > 0 ? implode(' ', $res) : '0s';
                    }
                @endphp
                
                <div style="display: flex; gap: 10px; align-items: center;">
                    @if(!$todayAttendance)
                        <button class="btn btn-primary-solid px-4 py-2" onclick="processAttendance('check-in')" id="checkinBtn" style="font-weight: 600;">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Give Attendance
                        </button>
                    @elseif($todayAttendance->is_checked_in)
                        <button class="btn-danger-solid px-4 py-2" onclick="processAttendance('check-out')" id="checkoutBtn" style="font-weight: 600; background:#ef4444; border:none; color:#fff; border-radius:var(--r); display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-box-arrow-right"></i> Check-out Now
                        </button>
                    @else
                        <div class="badge bg-success-subtle text-success p-2 px-3 border border-success-subtle" style="font-size:14px; border-radius:10px;">
                             <i class="bi bi-check-circle-fill me-1"></i> Today's Shift Completed
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="kpi-grid" style="grid-template-columns: repeat(5, 1fr);">
             <div class="kpi-card" style="--kpi-accent:#6366f1">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-calendar-check"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->total() }}</div>
                <div class="kpi-label">Total Logs</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#10b981">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-clock-history"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->where('status', 'Present')->count() }}</div>
                <div class="kpi-label">On-Time Days</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#f59e0b">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-exclamation-circle"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->where('status', 'Late')->count() }}</div>
                <div class="kpi-label">Late Days</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#ef4444">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i class="bi bi-x-circle"></i></div>
                </div>
                <div class="kpi-value">{{ $totalAbsentDays ?? 0 }}</div>
                <div class="kpi-label">Total Absents</div>
            </div>
            <div class="kpi-card" style="--kpi-accent:#0ea5e9">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(14,165,233,.15);color:#0ea5e9"><i class="bi bi-stopwatch-fill"></i></div>
                </div>
                <div class="kpi-value" style="font-size:18px;">{{ formatDuration($totalWorkSeconds) }}</div>
                <div class="kpi-label">Total Work Hours</div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="dash-card">
            <div class="card-head">
                <div class="card-title">My Presence History</div>
                <form action="{{ route($routePrefix . '.attendance.index') }}" method="GET" class="filter-form">
                    <input type="date" name="date" class="filter-select" value="{{ request('date') }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Status</th>
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
                                    <td>{{ $attendances->firstItem() + $index }}</td>
                                    <td style="font-weight:600;">{{ $row->date?->format('d M, Y') ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $status = $row->status ?? 'Present';
                                            $badgeStyle = '';
                                            if($status == 'Present') {
                                                $badgeStyle = 'background:rgba(16,185,129,0.15); color:#10b981;';
                                            } elseif($status == 'Late') {
                                                $badgeStyle = 'background:rgba(245,158,11,0.15); color:#f59e0b;';
                                            } else {
                                                $badgeStyle = 'background:rgba(239,68,68,0.15); color:#ef4444;';
                                            }
                                        @endphp
                                        <span class="status-pill" style="{{ $badgeStyle }} font-weight:700;">
                                            {{ strtoupper($status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="time-stamp">
                                            <i class="bi bi-clock-fill text-success"></i>
                                            {{ $row->check_in_time ? \Carbon\Carbon::parse($row->check_in_time)->format('h:i A') : '--:--' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-stamp">
                                            <i class="bi bi-clock-history text-muted"></i>
                                            {{ $row->check_out_time ? \Carbon\Carbon::parse($row->check_out_time)->format('h:i A') : '--:--' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if(($row->status ?? 'Present') == 'Absent')
                                            <span class="text-muted">--</span>
                                        @elseif(($row->late_seconds ?? 0) > 0)
                                            <span class="text-danger fw-bold">
                                                {{ formatDuration($row->late_seconds) }} Late
                                            </span>
                                        @else
                                            <span class="text-success fw-bold">On-time</span>
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
                                            <span class="fw-bold text-primary">
                                                {{ formatDuration(abs($displaySeconds)) }}
                                            </span>
                                        @else
                                            <span class="text-muted small">{{ $row->status == 'Absent' ? '--' : 'Active...' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->check_in_screenshot)
                                            <div class="verification-actions">
                                                <a href="javascript:void(0)" onclick="viewScreenshot('{{ asset('storage/' . $row->check_in_screenshot) }}', 'Check-in Proof')" class="v-link in" title="Check-in Proof">
                                                    <i class="bi bi-camera"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->check_out_screenshot)
                                            <div class="verification-actions">
                                                <a href="javascript:void(0)" onclick="viewScreenshot('{{ asset('storage/' . $row->check_out_screenshot) }}', 'Check-out Proof')" class="v-link out" title="Check-out Proof">
                                                    <i class="bi bi-camera-fill"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">No records found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $attendances->links('admin.includes.pagination') }}
                </div>
            </div>
        </div>
    </main>

    <!-- HTML2CANVAS FOR CAPTURE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- STYLES -->
    <style>
        .time-stamp { font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .verification-actions { display: flex; align-items: center; justify-content: flex-start; }
        .v-link { font-size: 20px; line-height: 1; transition: 0.2s; }
        .v-link.in { color: var(--accent); }
        .v-link.out { color: #ef4444; }
        .v-link:hover { transform: translateY(-2px); }

        /* Ensure table data is left aligned */
        .data-table th, .data-table td { text-align: left !important; }
        
        /* Dark mode overrides for table background */
        [data-theme="dark"] .data-table {
            background: transparent !important;
        }
        [data-theme="dark"] .data-table tbody tr {
            background: transparent !important;
        }
        [data-theme="dark"] .data-table tbody td {
            background: transparent !important;
            color: var(--t2);
        }
        [data-theme="dark"] .data-table thead th {
            background: var(--bg3) !important;
            color: var(--t3);
        }
    </style>

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

    <script>
        function viewScreenshot(url, title) {
            document.getElementById('screenshotImg').src = url;
            document.getElementById('screenshotModalTitle').innerText = title;
            const modal = new bootstrap.Modal(document.getElementById('screenshotModal'));
            modal.show();
        }

        function processAttendance(type) {
            const btn = type === 'check-in' ? document.getElementById('checkinBtn') : document.getElementById('checkoutBtn');
            const originalHtml = btn.innerHTML;
            
            // Subtle transition instead of blocking overlay
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Processing...';
            btn.disabled = true;

            html2canvas(document.body, {
                scale: 0.4,
                useCORS: true,
                logging: false,
                backgroundColor: '#f8fafc'
            }).then(canvas => {
                const base64 = canvas.toDataURL('image/png');
                submitAttendance(base64, btn, originalHtml);
            }).catch(err => {
                console.error("Capture Failed:", err);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }

        function submitAttendance(base64, btn, originalHtml) {
            fetch("{{ route($routePrefix . '.attendance.give') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ screenshot: base64 })
            })
            .then(async res => {
                const data = await res.json();
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message);
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            })
            .catch(err => {
                console.error(err);
                btn.innerHTML = originalHtml;
                btn.disabled = false;
            });
        }
    </script>
@endsection
