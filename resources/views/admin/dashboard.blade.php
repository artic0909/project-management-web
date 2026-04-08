@extends('admin.layout.app')

@section('title', 'Admin Dashboard')

@section('content')


    <!-- ═══ PAGE CONTENT AREA ═══ -->
    <main class="page-area" id="pageArea">
        @php
            function formatDashCurrency($amount)
            {
                if ($amount >= 10000000) {
                    return '₹' . number_format($amount / 10000000, 1) . 'Cr';
                } elseif ($amount >= 100000) {
                    return '₹' . number_format($amount / 100000, 1) . 'L';
                } elseif ($amount >= 1000) {
                    return '₹' . number_format($amount / 1000, 1) . 'K';
                }
                return '₹' . number_format($amount, 0);
            }

            function formatDurationDash($seconds) {
                if($seconds <= 0) return '0s';
                $h = floor($seconds / 3600);
                $m = floor(($seconds % 3600) / 60);
                $s = $seconds % 60;
                return ($h > 0 ? $h . 'h ' : '') . ($m > 0 || $h > 0 ? $m . 'm ' : '') . $s . 's';
            }
        @endphp

        <!-- ADMIN DASHBOARD PAGE -->
        <div class="page" id="page-dashboard">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">{{ $routePrefix == 'admin' ? 'Admin' : ($routePrefix == 'developer' ? 'Developer' : 'Sales') }} Dashboard</h1>
                    <p class="page-desc">Live overview · <span id="liveDate"></span></p>
                </div>
                
                

                <!-- Filters -->
                <div class="page-actions d-flex align-items-center gap-3">

                <!-- Meeting Pinned like show here -->
                @if(isset($closestMeeting) && $closestMeeting)
                <div class="meeting-alert" onclick="window.location.href='{{ route($routePrefix . '.meetings.show', $closestMeeting->id) }}'">
                    <div class="ma-icon"><i class="bi bi-calendar-event-fill"></i></div>
                    <div class="ma-content">
                        <div class="ma-title">Upcoming Meeting</div>
                        <div class="ma-info">
                            <span class="ma-date"><i class="bi bi-calendar3"></i> {{ $closestMeeting->meeting_date->format('d M, Y') }}</span>
                            <span class="ma-time"><i class="bi bi-clock"></i> {{ Carbon\Carbon::parse($closestMeeting->meeting_time)->format('h:i A') }}</span>
                            <span class="ma-topic"><i class="bi bi-chat-dots"></i> {{ $closestMeeting->topic }}</span>
                        </div>
                    </div>
                    <div class="ma-chevron"><i class="bi bi-chevron-right"></i></div>
                </div>
                @endif



                    <form action="{{ route($routePrefix . '.dashboard') }}" method="GET" class="filter-form" id="filterForm">
                        <div class="filter-group">
                            <select name="month" class="filter-select" onchange="this.form.submit()">
                                @foreach (range(1, 12) as $m)
                                    <option value="{{ $m }}" {{ $selectedMonth == $m ? 'selected' : '' }}>
                                        {{ Carbon\Carbon::create(null, $m, 1)->format('F') }}
                                    </option>
                                @endforeach
                            </select>
                            <select name="year" class="filter-select" onchange="this.form.submit()">
                                @foreach ($availableYears as $y)
                                    <option value="{{ $y }}" {{ $selectedYear == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>

            <style>
                .filter-form {
                    display: flex;
                    gap: 12px;
                    align-items: center;
                }

                .filter-group {
                    display: flex;
                    gap: 8px;
                    background: var(--b2);
                    padding: 4px;
                    border-radius: 10px;
                    border: 1px solid var(--b3);
                }

                .filter-select {
                    background: transparent;
                    color: var(--t1);
                    border: none;
                    font-size: 13px;
                    font-weight: 600;
                    padding: 6px 12px;
                    border-radius: 6px;
                    cursor: pointer;
                    outline: none;
                    transition: 0.2s;
                }

                .filter-select:hover {
                    background: var(--b3);
                }

                .filter-select option {
                    background: var(--b1);
                    color: var(--t1);
                }

                /* Meeting Alert Styles */
                .meeting-alert {
                    display: flex;
                    align-items: center;
                    gap: 16px;
                    background: rgba(239, 68, 68, 0.08);
                    border: 1px solid rgba(239, 68, 68, 0.2);
                    border-radius: 12px;
                    padding: 10px 20px;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    position: relative;
                    overflow: hidden;
                    animation: pulse-red 2s infinite;
                    max-width: 500px;
                }

                @keyframes pulse-red {
                    0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
                    70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
                    100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
                }

                .meeting-alert:hover {
                    background: rgba(239, 68, 68, 0.12);
                    border-color: rgba(239, 68, 68, 0.4);
                    transform: translateY(-1px);
                }

                .ma-icon {
                    width: 36px;
                    height: 36px;
                    border-radius: 8px;
                    background: #ef4444;
                    color: white;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    flex-shrink: 0;
                }

                .ma-content {
                    flex: 1;
                    min-width: 0;
                }

                .ma-title {
                    font-size: 11px;
                    font-weight: 800;
                    color: #ef4444;
                    margin-bottom: 2px;
                    text-transform: uppercase;
                    letter-spacing: 0.8px;
                }

                .ma-info {
                    display: flex;
                    gap: 12px;
                    flex-wrap: nowrap;
                    overflow: hidden;
                }

                .ma-info span {
                    font-size: 12px;
                    color: var(--t1);
                    display: flex;
                    align-items: center;
                    gap: 5px;
                    font-weight: 600;
                    white-space: nowrap;
                }

                .ma-info i {
                    color: #ef4444;
                    font-size: 13px;
                }

                .ma-chevron {
                    color: #ef4444;
                    font-size: 16px;
                    transition: transform 0.2s;
                }

                .meeting-alert:hover .ma-chevron {
                    transform: translateX(3px);
                }

                @media (max-width: 768px) {
                    .meeting-alert {
                        margin-top: 15px;
                        max-width: 100%;
                    }
                }
            </style>

            <!-- KPI STRIP -->
            <div class="kpi-grid">
                @if($routePrefix == 'developer')
                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i
                                class="bi bi-kanban-fill"></i></div>
                    </div>
                    <div class="kpi-value">{{ $totalRunningProjects }}</div>
                    <div class="kpi-label">Total Running Projects</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-check-all"></i></div>
                    </div>
                    <div class="kpi-value">{{ $totalCompletedProjects }}</div>
                    <div class="kpi-label">Total Completed Projects</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#f59e0b">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i
                                class="bi bi-hourglass-split"></i></div>
                    </div>
                    <div class="kpi-value">{{ $pendingTasks }}</div>
                    <div class="kpi-label">Pending Tasks</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-check2-square"></i></div>
                    </div>
                    <div class="kpi-value">{{ $completedTasks }}</div>
                    <div class="kpi-label">Completed Tasks</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#8b5cf6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i
                                class="bi bi-calendar-event"></i></div>
                    </div>
                    <div class="kpi-value">{{ $pendingMeetings }}</div>
                    <div class="kpi-label">Pending Meetings</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-calendar-check"></i></div>
                    </div>
                    <div class="kpi-value">{{ $completedMeetings }}</div>
                    <div class="kpi-label">Completed Meetings</div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#06b6d4">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i
                                class="bi bi-clock-fill"></i></div>
                    </div>
                    <div class="kpi-value" style="font-size: 18px;">{{ formatDurationDash($totalWorkSeconds ?? 0) }}</div>
                    <div class="kpi-label">Total Work Hours</div>
                </div>
                @else
                {{-- ADMIN / SALE KPI STRIP --}}
                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i
                                class="bi bi-currency-rupee"></i></div>

                    </div>
                    <div class="kpi-value">{{ formatDashCurrency($totalOrderValue) }}</div>
                    <div class="kpi-label">Total Order Value</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:40%"></div>
                        <div class="spark-bar" style="height:60%"></div>
                        <div class="spark-bar" style="height:45%"></div>
                        <div class="spark-bar" style="height:75%"></div>
                        <div class="spark-bar" style="height:55%"></div>
                        <div class="spark-bar" style="height:90%"></div>
                        <div class="spark-bar active" style="height:100%"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i
                                class="bi bi-currency-rupee"></i></div>

                    </div>
                    <div class="kpi-value">{{ formatDashCurrency($totalReceivedAmount) }}</div>
                    <div class="kpi-label">Total Received Amount</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:40%"></div>
                        <div class="spark-bar" style="height:60%"></div>
                        <div class="spark-bar" style="height:45%"></div>
                        <div class="spark-bar" style="height:75%"></div>
                        <div class="spark-bar" style="height:55%"></div>
                        <div class="spark-bar" style="height:90%"></div>
                        <div class="spark-bar active" style="height:100%"></div>
                    </div>
                </div>
                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i
                                class="bi bi-currency-rupee"></i></div>

                    </div>
                    <div class="kpi-value">{{ formatDashCurrency($totalPending) }}</div>
                    <div class="kpi-label">Total Pending</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:40%"></div>
                        <div class="spark-bar" style="height:60%"></div>
                        <div class="spark-bar" style="height:45%"></div>
                        <div class="spark-bar" style="height:75%"></div>
                        <div class="spark-bar" style="height:55%"></div>
                        <div class="spark-bar" style="height:90%"></div>
                        <div class="spark-bar active" style="height:100%"></div>
                    </div>
                </div>

                @if($routePrefix != 'developer')
                <div class="kpi-card" style="--kpi-accent:#f59e0b">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i
                                class="bi bi-person-lines-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($totalLeads) }}</div>
                    <div class="kpi-label">Total Leads</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:55%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar" style="height:50%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar" style="height:85%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar" style="height:75%;--kpi-accent:#f59e0b"></div>
                        <div class="spark-bar active" style="height:95%;--kpi-accent:#f59e0b"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-bag-check-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($totalOrders) }}</div>
                    <div class="kpi-label">Total Orders</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:30%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:50%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:45%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:80%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar active" style="height:90%;--kpi-accent:#10b981"></div>
                    </div>
                </div>
                @endif

                <div class="kpi-card" style="--kpi-accent:#8b5cf6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i
                                class="bi bi-kanban-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($routePrefix == 'developer' ? $totalRunningProjects : $activeProjects) }}</div>
                    <div class="kpi-label">Active Projects</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:80%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar" style="height:90%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar" style="height:55%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar" style="height:85%;--kpi-accent:#8b5cf6"></div>
                        <div class="spark-bar active" style="height:75%;--kpi-accent:#8b5cf6"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i
                                class="bi bi-check-circle-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($routePrefix == 'developer' ? $totalCompletedProjects : $completedProjects) }}</div>
                    <div class="kpi-label">Complete Projects</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:80%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:90%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:55%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar" style="height:85%;--kpi-accent:#10b981"></div>
                        <div class="spark-bar active" style="height:75%;--kpi-accent:#10b981"></div>
                    </div>
                </div>

                @if($routePrefix == 'admin')
                <div class="kpi-card" style="--kpi-accent:#ef4444">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i
                                class="bi bi-people-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($totalSalesPerson) }}</div>
                    <div class="kpi-label">Sales Person</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:50%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:55%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar active" style="height:75%;--kpi-accent:#ef4444"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#ef4444">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i
                                class="bi bi-people-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($totalDevelopers) }}</div>
                    <div class="kpi-label">Total Developers</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:50%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:55%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#ef4444"></div>
                        <div class="spark-bar active" style="height:75%;--kpi-accent:#ef4444"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#06b6d4">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i
                                class="bi bi-clock-fill"></i></div>

                    </div>
                    <div class="kpi-value">94.2%</div>
                    <div class="kpi-label">Sales Attendance Rate</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:88%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:91%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:89%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:93%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:90%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:95%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar active" style="height:94%;--kpi-accent:#06b6d4"></div>
                    </div>
                </div>

                <div class="kpi-card" style="--kpi-accent:#06b6d4">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i
                                class="bi bi-clock-fill"></i></div>

                    </div>
                    <div class="kpi-value">94.2%</div>
                    <div class="kpi-label">Developers Attendance Rate</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:88%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:91%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:89%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:93%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:90%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar" style="height:95%;--kpi-accent:#06b6d4"></div>
                        <div class="spark-bar active" style="height:94%;--kpi-accent:#06b6d4"></div>
                    </div>
                </div>
                @endif
                @endif
            </div>

            @if($routePrefix != 'developer')
            <!-- MAIN GRID -->
            <div class="dash-grid">

                <!-- Revenue Chart -->
                <div class="dash-card span-8">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Order Value vs Received</div>
                            <div class="card-sub">Monthly comparison · Last 8 Months</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-legend">
                            <span class="legend-item"><span class="l-dot" style="background:#6366f1"></span>Order
                                Value</span>
                            <span class="legend-item"><span class="l-dot" style="background:#10b981"></span>Received</span>
                        </div>
                        <div class="bar-chart-wrap">
                            <div class="y-axis">
                                @php
                                    $maxChartVal = max(array_merge($monthlyOrderValues, $monthlyReceivedAmounts, [1]));
                                    $yAxisSteps = [
                                        $maxChartVal,
                                        $maxChartVal * 0.8,
                                        $maxChartVal * 0.6,
                                        $maxChartVal * 0.4,
                                        $maxChartVal * 0.2,
                                        0,
                                    ];
                                @endphp
                                @foreach ($yAxisSteps as $step)
                                    <span>{{ formatDashCurrency($step) }}</span>
                                @endforeach
                            </div>
                            <div class="bar-chart">
                                @foreach ($months as $index => $month)
                                    @php
                                        $orderH = ($monthlyOrderValues[$index] / $maxChartVal) * 100;
                                        $receivedH = ($monthlyReceivedAmounts[$index] / $maxChartVal) * 100;
                                        $isCurrent = $index === count($months) - 1;
                                    @endphp
                                    <div class="bar-group {{ $isCurrent ? 'active' : '' }}" data-month="{{ $month }}">
                                        <div class="bar-stack">
                                            <div class="bar rev" style="height:{{ $orderH }}%"
                                                data-val="{{ formatDashCurrency($monthlyOrderValues[$index]) }}"></div>
                                            <div class="bar tgt" style="height:{{ $receivedH }}%"
                                                data-val="{{ formatDashCurrency($monthlyReceivedAmounts[$index]) }}"></div>
                                        </div>
                                        <div class="bar-label">{{ $month }}{{ $isCurrent ? ' ●' : '' }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Project Pipeline Donut -->
                <div class="dash-card span-4">
                    <div class="card-head">
                        <div class="card-title">Project Pipeline</div>
                        <button class="icon-btn-sm"><i class="bi bi-three-dots"></i></button>
                    </div>
                    <div class="card-body">
                        <div class="donut-wrap">
                            <svg viewBox="0 0 120 120" class="donut-svg">
                                <circle cx="60" cy="60" r="50" fill="none" stroke="var(--b2)" stroke-width="18" />
                                @php
                                    $cumulativeOffset = 0;
                                    $circumference = 314.159;
                                @endphp
                                @foreach ($projectPipeline as $pipeline)
                                    @php
                                        $percentage = $totalProjects > 0 ? ($pipeline['count'] / $totalProjects) * 100 : 0;
                                        $dashArray = ($percentage / 100) * $circumference;
                                    @endphp
                                    <circle cx="60" cy="60" r="50" fill="none" stroke="{{ $pipeline['color'] }}"
                                        stroke-width="18" stroke-dasharray="{{ $dashArray }} {{ $circumference - $dashArray }}"
                                        stroke-dashoffset="-{{ $cumulativeOffset }}" stroke-linecap="round" />
                                    @php
                                        $cumulativeOffset += $dashArray;
                                    @endphp
                                @endforeach

                                <text x="60" y="56" text-anchor="middle" fill="var(--t1)" font-size="14" font-weight="700"
                                    font-family="Plus Jakarta Sans">{{ $totalProjects }}</text>
                                <text x="60" y="68" text-anchor="middle" fill="var(--t3)" font-size="6"
                                    font-family="Plus Jakarta Sans">Projects</text>
                            </svg>
                        </div>
                        <div class="donut-legend">
                            @foreach ($projectPipeline as $pipeline)
                                <div class="dl-item"><span class="dl-dot"
                                        style="background:{{ $pipeline['color'] }}"></span><span
                                        class="dl-label">{{ $pipeline['name'] }}</span><span
                                        class="dl-val">{{ $pipeline['count'] }}</span></div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            @endif
        </div>

    </main>


@endsection