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
                    <button type="button" class="btn-primary-solid sm" id="bulkDeleteBtn" style="display: none; background: #dc2626; border-color: #dc2626; color: white;" onclick="bulkDeleteSelected()">
                        <i class="bi bi-trash-fill"></i> Bulk Delete
                    </button>
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
                <div style="display: flex; align-items: center; gap: 10px; margin-left: auto; flex-wrap: wrap;">
                    <form action="{{ route($routePrefix . '.attendance.index') }}" method="GET" class="card-actions mb-0" id="filterForm" style="display:flex; align-items:center; gap:8px;">
                        
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
            <div class="card-body">
                <div class="table-wrap">
                    <table class="table">
                        <thead>
                            <tr>
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAll" onclick="toggleAll(this)" style="cursor: pointer;">
                                </th>
                                <th>SL</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Check-in</th>
                                <th>Check-out</th>
                                <th>Late Hours</th>
                                <th>Total Hours</th>
                                <th>Check-in Photo</th>
                                <th>Check-out Photo</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($attendances as $index => $row)
                                <tr>
                                    <td style="text-align: center;">
                                        <input type="checkbox" class="record-checkbox" name="ids[]" value="{{ $row->id }}" onclick="updateBulkDeleteButton()" style="cursor: pointer;">
                                    </td>
                                    <td>{{ $attendances->firstItem() + $index }}</td>
                                    <td style="font-weight:600;">{{ $row->date?->format('d M, Y') ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge {{ ($row->status ?? 'Present') == 'Present' ? 'bg-success-subtle text-success' : (($row->status ?? 'Present') == 'Late' ? 'bg-warning-subtle text-warning' : 'bg-danger-subtle text-danger') }}" style="font-weight:700;">
                                            {{ strtoupper($row->status ?? 'Present') }}
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
                                    <td>
                                        <div style="display:flex; gap:6px;">
                                            <button type="button" class="btn btn-sm btn-outline-danger" style="padding: 2px 6px;" onclick="confirmSingleDelete('{{ route($routePrefix . '.attendance.destroy', $row->id) }}')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
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
                    {{ $attendances->links() }}
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
        .v-link.out { color: var(--red); }
        .v-link:hover { transform: translateY(-2px); }

        /* Ensure table data is left aligned */
        .table th, .table td { text-align: left !important; }
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

    <!-- BULK DELETE MODAL -->
    <div class="modal-backdrop" id="bulkDeleteModal">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Bulk Delete</span>
                <button class="modal-close" onclick="closeModal('bulkDeleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Delete All Selected?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete the <strong id="bulkCount">0</strong> selected records?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('bulkDeleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="executeBulkDelete()">
                    <i class="bi bi-trash3-fill"></i> Confirm Bulk Deletion
                </button>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Attendance</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Delete Record?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this record?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <form id="deleteForm" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="document.getElementById('deleteForm').submit()">
                    <i class="bi bi-trash3-fill"></i> Confirm Deletion
                </button>
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

        function toggleDatePicker() {
            const drp = document.getElementById('dateRangePicker');
            if(drp) drp.style.display = drp.style.display === 'block' ? 'none' : 'block';
        }

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

        function toggleAll(source) {
            const checkboxes = document.getElementsByClassName('record-checkbox');
            for (let i = 0; i < checkboxes.length; i++) {
                checkboxes[i].checked = source.checked;
            }
            updateBulkDeleteButton();
        }

        function updateBulkDeleteButton() {
            const checkboxes = document.getElementsByClassName('record-checkbox');
            let anyChecked = false;
            let count = 0;
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    anyChecked = true;
                    count++;
                }
            }
            document.getElementById('bulkDeleteBtn').style.display = anyChecked ? 'inline-block' : 'none';
            document.getElementById('bulkCount').innerText = count;
        }

        function bulkDeleteSelected() {
            const m = document.getElementById('bulkDeleteModal');
            m.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function executeBulkDelete() {
            const checkboxes = document.getElementsByClassName('record-checkbox');
            const selectedIds = [];
            for (let i = 0; i < checkboxes.length; i++) {
                if (checkboxes[i].checked) {
                    selectedIds.push(checkboxes[i].value);
                }
            }

            if (selectedIds.length > 0) {
                fetch("{{ route($routePrefix . '.attendance.bulk-destroy') }}", {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids: selectedIds })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Failed to delete records.');
                    }
                });
            }
        }

        function confirmSingleDelete(url) {
            document.getElementById('deleteForm').action = url;
            const m = document.getElementById('deleteModal');
            m.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function closeModal(id) {
            const m = document.getElementById(id);
            if(m) {
                m.classList.remove('open');
                document.body.style.overflow = 'auto';
            }
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
