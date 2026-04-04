@extends('admin.layout.app')

@section('title', 'Attendance Management')

@section('content')
    <main class="page-area">
        <!-- Attendance Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Attendance Tracking</h1>
                <p class="page-desc">Comprehensive log of presence, late-time, and work duration.</p>
            </div>

            <div class="page-actions">
                @if ($routePrefix !== 'admin')
                    @php
                        $isCurrentlyCheckedIn = $todayAttendance && $todayAttendance->is_checked_in;
                        $btnLabel = $isCurrentlyCheckedIn ? 'Check-out' : 'Check-in';
                        $btnColor = $isCurrentlyCheckedIn ? 'var(--red)' : '#10b981';
                        $btnIcon = $isCurrentlyCheckedIn ? 'bi-box-arrow-right' : 'bi-box-arrow-in-right';
                    @endphp
                    
                    @if(!$todayAttendance || ($todayAttendance && $todayAttendance->is_checked_in))
                    <button class="btn btn-primary btn-lg px-4 py-2" onclick="processAttendance()" id="giveAttendanceBtn"
                        style="background: {{ $btnColor }}; border:none; font-weight: 700; transition: 0.3s; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
                        <i class="bi {{ $btnIcon }} me-2"></i> {{ $btnLabel }} Verification
                    </button>
                    @else
                    <div class="badge bg-success-subtle text-success p-2 px-3 border border-success-subtle">
                         <i class="bi bi-check-circle-fill me-1"></i> Today's Shift Completed
                    </div>
                    @endif
                @endif

                @if ($routePrefix === 'admin')
                    <button class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#settingsModal">
                        <i class="bi bi-gear-fill me-2"></i> Shift Settings
                    </button>
                @endif
            </div>
        </div>

        <!-- Attendance Stats -->
        <div class="kpi-grid">
             <div class="kpi-card" style="--kpi-accent:#6366f1">
                <div class="kpi-top">
                    <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-calendar-check"></i></div>
                </div>
                <div class="kpi-value">{{ $attendances->total() }}</div>
                <div class="kpi-label">Total Logs</div>
            </div>
        </div>

        <!-- Attendance Table -->
        <div class="dash-card">
            <div class="card-head">
                <div class="card-title">Presence & Verification Log</div>
                <form action="{{ route($routePrefix . '.attendance.index') }}" method="GET" class="filter-form">
                    @if($routePrefix === 'admin')
                    <select name="user_type" class="filter-select" onchange="this.form.submit()">
                        <option value="">All Staff</option>
                        <option value="Developer" {{ request('user_type') == 'Developer' ? 'selected' : '' }}>Developers</option>
                        <option value="Sale" {{ request('user_type') == 'Sale' ? 'selected' : '' }}>Sales Team</option>
                    </select>
                    @endif
                    <input type="date" name="date" class="filter-select" value="{{ request('date') }}" onchange="this.form.submit()">
                </form>
            </div>
            <div class="card-body">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Staff Name</th>
                                <th>Role</th>
                                <th>Date</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Late Duration</th>
                                <th>Total Hours</th>
                                <th>Verification</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $index => $row)
                                <tr>
                                    <td>{{ $attendances->firstItem() + $index }}</td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-ava sm">
                                                {{ strtoupper(substr($row->user->name ?? 'U', 0, 1)) }}
                                            </div>
                                            <div class="user-det">
                                                <div class="u-name-sm">{{ $row->user->name ?? 'Unknown' }}</div>
                                                <div class="u-email-sm text-muted" style="font-size: 11px;">{{ $row->user->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="role-badge">{{ $row->user_type }}</span></td>
                                    <td>{{ $row->date->format('d M, Y') }}</td>
                                    <td>
                                        <div class="time-stamp">
                                            <i class="bi bi-clock-fill text-success"></i>
                                            {{ $row->check_in_time ? Carbon\Carbon::parse($row->check_in_time)->format('h:i A') : '--:--' }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="time-stamp">
                                            <i class="bi bi-clock-history text-muted"></i>
                                            {{ $row->check_out_time ? Carbon\Carbon::parse($row->check_out_time)->format('h:i A') : '--:--' }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($row->late_minutes > 0)
                                            <span class="text-danger fw-bold">
                                                {{ floor($row->late_minutes / 60) > 0 ? floor($row->late_minutes / 60) . 'h ' : '' }}{{ $row->late_minutes % 60 }}m Late
                                            </span>
                                        @elseif($row->late_minutes < 0)
                                            <span class="text-success fw-bold">
                                                {{ floor(abs($row->late_minutes) / 60) > 0 ? floor(abs($row->late_minutes) / 60) . 'h ' : '' }}{{ abs($row->late_minutes) % 60 }}m Early
                                            </span>
                                        @else
                                            <span class="text-success small">On-time</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->total_minutes > 0)
                                            <span class="fw-bold text-primary">
                                                {{ floor($row->total_minutes / 60) }}h {{ $row->total_minutes % 60 }}m
                                            </span>
                                        @else
                                            <span class="text-muted small">--</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="verification-actions">
                                            @if($row->check_in_screenshot)
                                                <a href="{{ asset('storage/' . $row->check_in_screenshot) }}" target="_blank" class="v-link in" title="Check-in Proof">
                                                    <i class="bi bi-image"></i>
                                                </a>
                                            @endif
                                            @if($row->check_out_screenshot)
                                                <a href="{{ asset('storage/' . $row->check_out_screenshot) }}" target="_blank" class="v-link out" title="Check-out Proof">
                                                    <i class="bi bi-image-fill"></i>
                                                </a>
                                            @endif
                                            
                                            <!-- Strong Info Indicators -->
                                            <span class="info-dot" title="IP: {{ $row->ip_address ?? 'Unknown' }} | Device: {{ $row->user_agent ?? 'HIDDEN' }}">
                                                <i class="bi bi-info-circle-fill"></i>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5">No attendance records for the selected period.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    <!-- SETTINGS MODAL (Admin Only) -->
    @if ($routePrefix === 'admin')
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="background:#fff; border-radius: 16px; overflow: hidden;">
                <div class="modal-header border-0 bg-light px-4 py-3">
                    <h5 class="modal-title fw-bold" style="color:var(--t1)">Attendance Shift Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.attendance.store-settings') }}" method="POST">
                    @csrf
                    <div class="modal-body px-4 py-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Dev Check-in</label>
                                <input type="time" name="dev_checkin_time" class="form-control" value="{{ $settings->dev_checkin_time }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Dev Check-out</label>
                                <input type="time" name="dev_checkout_time" class="form-control" value="{{ $settings->dev_checkout_time }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Sales Check-in</label>
                                <input type="time" name="sale_checkin_time" class="form-control" value="{{ $settings->sale_checkin_time }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted small fw-bold">Sales Check-out</label>
                                <input type="time" name="sale_checkout_time" class="form-control" value="{{ $settings->sale_checkout_time }}" style="border-radius: 8px;">
                            </div>
                            <div class="col-12">
                                <label class="form-label text-muted small fw-bold">Grace Period (Minutes)</label>
                                <input type="number" name="grace_period_minutes" class="form-control" value="{{ $settings->grace_period_minutes }}" style="border-radius: 8px;">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer border-0 px-4 py-3">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal" style="border-radius: 8px;">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4" style="border-radius: 8px;">Update Shift Schedule</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
    </main>

    <!-- HTML2CANVAS FOR CAPTURE -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <!-- STYLES -->
    <style>
        .hidden { display: none !important; }
        .role-badge { font-size: 11px; padding: 2px 8px; background: rgba(0,0,0,0.05); border-radius: 4px; font-weight: 600; }
        .time-stamp { font-size: 13px; display: flex; align-items: center; gap: 6px; }
        .verification-actions { display: flex; align-items: center; gap: 6px; }
        .v-link { font-size: 18px; line-height: 1; transition: 0.2s; }
        .v-link.in { color: var(--accent); }
        .v-link.out { color: var(--red); }
        .v-link:hover { transform: translateY(-2px); }
        .info-dot { color: #94a3b8; cursor: pointer; font-size: 14px; }
        .info-dot:hover { color: var(--accent); }

        .capture-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(4px);
            z-index: 10000;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
        }
        .capture-card {
            background: rgba(255,255,255,0.05);
            padding: 40px;
            border-radius: 24px;
            border: 1px solid rgba(255,255,255,0.1);
            max-width: 400px;
        }
    </style>

    <!-- CAPTURE LOGIC -->
    <div id="captureOverlay" class="capture-overlay hidden">
        <div class="capture-card">
            <div class="spinner-border text-light mb-4" style="width: 3rem; height: 3rem;" role="status"></div>
            <h3 class="mb-2">Securing Presence...</h3>
            <p class="text-white-50">Capturing biometric snapshot and logging strong information.</p>
        </div>
    </div>

    <script>
        function processAttendance() {
            // Show overlay
            document.getElementById('captureOverlay').classList.remove('hidden');

            // Use html2canvas for instant, no-prompt screenshot of the dashboard
            html2canvas(document.body, {
                scale: 0.5, // Scale down to save storage
                useCORS: true,
                logging: false,
                backgroundColor: '#f8fafc'
            }).then(canvas => {
                const base64 = canvas.toDataURL('image/png');
                submitAttendance(base64);
            }).catch(err => {
                console.error("Capture Failed:", err);
                document.getElementById('captureOverlay').classList.add('hidden');
                showToast('danger', 'Biometric verification failed. Please try again.', 'bi-exclamation-octagon');
            });
        }

        function submitAttendance(base64) {
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
                    showToast('success', data.message, 'bi-check-circle-fill');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    showToast('danger', data.message, 'bi-exclamation-triangle-fill');
                    document.getElementById('captureOverlay').classList.add('hidden');
                }
            })
            .catch(err => {
                showToast('danger', "Verification server unreachable.", 'bi-wifi-off');
                document.getElementById('captureOverlay').classList.add('hidden');
            });
        }
    </script>
@endsection
