@extends('admin.layout.app')

@section('title', ($routePrefix == 'admin' ? 'Admin' : ($routePrefix == 'developer' ? 'Developer' : 'Sales')) . ' Dashboard')

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

                <div class="kpi-card" style="--kpi-accent:#3b82f6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(59,130,246,.15);color:#3b82f6"><i
                                class="bi bi-folder-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($totalProjects) }}</div>
                    <div class="kpi-label">Total Projects</div>
                    <div class="kpi-spark">
                        <div class="spark-bar" style="height:50%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar" style="height:70%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar" style="height:60%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar" style="height:85%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar" style="height:65%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar" style="height:90%;--kpi-accent:#3b82f6"></div>
                        <div class="spark-bar active" style="height:80%;--kpi-accent:#3b82f6"></div>
                    </div>
                </div>

                @if($routePrefix == 'admin')
                <div class="kpi-card" style="--kpi-accent:#8b5cf6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i
                                class="bi bi-kanban-fill"></i></div>

                    </div>
                    <div class="kpi-value">{{ number_format($activeProjects) }}</div>
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
                    <div class="kpi-value">{{ number_format($completedProjects) }}</div>
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
                @endif

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
            <!-- ═══════════════════════════════════════════════════════════
                 ADVANCED ANALYTICS & VISUALIZATION GRID
            ═══════════════════════════════════════════════════════════ -->
            <div class="dash-viz-grid">

                <!-- ─── ROW 1: LEAD INVERTED FUNNEL & FOLLOWUP DONUT ─── -->
                <div class="dash-viz-row">
                    
                    <!-- 1. LEAD CONVERSION FUNNEL (INVERTED PYRAMID) -->
                    <div class="dash-card-premium viz-funnel-card">
                        <div class="card-head-premium">
                            <div class="ch-left">
                                <div class="ch-icon" style="background: rgba(59, 130, 246, 0.12); color: #3b82f6;">
                                    <i class="bi bi-funnel-fill"></i>
                                </div>
                                <div>
                                    <div class="card-title-premium">Lead Conversion Funnel</div>
                                    <div class="card-sub-premium">Inverted stage pipeline · Filtered Period</div>
                                </div>
                            </div>
                            <div class="ch-badges">
                                <span class="badge-pill bg-blue-subtle">
                                    <i class="bi bi-graph-up-arrow"></i> {{ $leadFunnel['conversion_rate'] }}% Conversion
                                </span>
                            </div>
                        </div>

                        <div class="card-body-premium">
                            <div class="funnel-container">
                                <!-- Funnel Graphic -->
                                <div class="funnel-graphic-wrap">
                                    @php
                                        $fTotal = max($leadFunnel['total'], 1);
                                        $pContacted = $leadFunnel['total'] > 0 ? round(($leadFunnel['contacted'] / $leadFunnel['total']) * 100, 1) : 0;
                                        $pDiscussion = $leadFunnel['total'] > 0 ? round(($leadFunnel['discussion'] / $leadFunnel['total']) * 100, 1) : 0;
                                        $pConverted = $leadFunnel['total'] > 0 ? round(($leadFunnel['converted'] / $leadFunnel['total']) * 100, 1) : 0;
                                    @endphp

                                    <!-- Tier 1: Total / New Leads -->
                                    <div class="funnel-slice tier-1" onclick="window.location.href='{{ route($routePrefix . '.leads.index') }}'">
                                        <div class="funnel-shape">
                                            <span class="fs-text">New Leads / Inquiries</span>
                                        </div>
                                    </div>

                                    <!-- Tier 2: Contacted / In Touch -->
                                    <div class="funnel-slice tier-2" onclick="window.location.href='{{ route($routePrefix . '.leads.index', ['type' => 'followup_total']) }}'">
                                        <div class="funnel-shape">
                                            <span class="fs-text">Contacted & Qualified</span>
                                        </div>
                                    </div>

                                    <!-- Tier 3: In Discussion / Proposals -->
                                    <div class="funnel-slice tier-3" onclick="window.location.href='{{ route($routePrefix . '.leads.index', ['type' => 'followup_future']) }}'">
                                        <div class="funnel-shape">
                                            <span class="fs-text">Proposals & Discussion</span>
                                        </div>
                                    </div>

                                    <!-- Tier 4: Won / Converted Orders -->
                                    <div class="funnel-slice tier-4" onclick="window.location.href='{{ route($routePrefix . '.orders.index') }}'">
                                        <div class="funnel-shape">
                                            <span class="fs-text"><i class="bi bi-trophy-fill"></i> Won / Converted</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Funnel Data Metrics & Connectors -->
                                <div class="funnel-metrics-list">
                                    <!-- Metric 1 -->
                                    <div class="fm-item tier-1-metric" onclick="window.location.href='{{ route($routePrefix . '.leads.index') }}'">
                                        <div class="fm-connector"></div>
                                        <div class="fm-content">
                                            <div class="fm-label-row">
                                                <span class="fm-badge-dot" style="background:#3b82f6;"></span>
                                                <span class="fm-name">Total Leads</span>
                                                <span class="fm-pct">100%</span>
                                            </div>
                                            <div class="fm-val">{{ number_format($leadFunnel['total']) }}</div>
                                        </div>
                                    </div>

                                    <!-- Metric 2 -->
                                    <div class="fm-item tier-2-metric" onclick="window.location.href='{{ route($routePrefix . '.leads.index', ['type' => 'followup_total']) }}'">
                                        <div class="fm-connector"></div>
                                        <div class="fm-content">
                                            <div class="fm-label-row">
                                                <span class="fm-badge-dot" style="background:#06b6d4;"></span>
                                                <span class="fm-name">Contacted</span>
                                                <span class="fm-pct">{{ $pContacted }}%</span>
                                            </div>
                                            <div class="fm-val">{{ number_format($leadFunnel['contacted']) }}</div>
                                        </div>
                                    </div>

                                    <!-- Metric 3 -->
                                    <div class="fm-item tier-3-metric" onclick="window.location.href='{{ route($routePrefix . '.leads.index', ['type' => 'followup_future']) }}'">
                                        <div class="fm-connector"></div>
                                        <div class="fm-content">
                                            <div class="fm-label-row">
                                                <span class="fm-badge-dot" style="background:#10b981;"></span>
                                                <span class="fm-name">Proposal / Active</span>
                                                <span class="fm-pct">{{ $pDiscussion }}%</span>
                                            </div>
                                            <div class="fm-val">{{ number_format($leadFunnel['discussion']) }}</div>
                                        </div>
                                    </div>

                                    <!-- Metric 4 -->
                                    <div class="fm-item tier-4-metric" onclick="window.location.href='{{ route($routePrefix . '.orders.index') }}'">
                                        <div class="fm-connector"></div>
                                        <div class="fm-content">
                                            <div class="fm-label-row">
                                                <span class="fm-badge-dot" style="background:#f59e0b;"></span>
                                                <span class="fm-name">Won Deals</span>
                                                <span class="fm-pct">{{ $pConverted }}%</span>
                                            </div>
                                            <div class="fm-val text-amber">{{ number_format($leadFunnel['converted']) }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Funnel Summary Chips -->
                            <div class="funnel-footer-chips">
                                <div class="ff-chip">
                                    <span class="ff-icon" style="color: #6366f1;"><i class="bi bi-star-fill"></i></span>
                                    <div class="ff-meta">
                                        <span class="ff-title">Fresh Unassigned</span>
                                        <span class="ff-val">{{ number_format($leadFunnel['new']) }}</span>
                                    </div>
                                </div>
                                <div class="ff-chip">
                                    <span class="ff-icon" style="color: #10b981;"><i class="bi bi-check-circle-fill"></i></span>
                                    <div class="ff-meta">
                                        <span class="ff-title">Win Rate</span>
                                        <span class="ff-val">{{ $leadFunnel['conversion_rate'] }}%</span>
                                    </div>
                                </div>
                                <div class="ff-chip">
                                    <span class="ff-icon" style="color: #ef4444;"><i class="bi bi-x-circle-fill"></i></span>
                                    <div class="ff-meta">
                                        <span class="ff-title">Lost / Drop-off</span>
                                        <span class="ff-val">{{ number_format($leadFunnel['lost']) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 2. FOLLOWUPS BREAKDOWN (PIE / DONUT CHART) -->
                    <div class="dash-card-premium viz-followup-card">
                        <div class="card-head-premium">
                            <div class="ch-left">
                                <div class="ch-icon" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                                    <i class="bi bi-pie-chart-fill"></i>
                                </div>
                                <div>
                                    <div class="card-title-premium">Followup Analytics</div>
                                    <div class="card-sub-premium">Distribution by schedule & channel</div>
                                </div>
                            </div>
                            <div class="ch-badges">
                                <span class="badge-pill bg-amber-subtle">
                                    {{ $followupStats['today'] }} Today's Action
                                </span>
                            </div>
                        </div>

                        <div class="card-body-premium">
                            <div class="followup-chart-wrapper">
                                <div id="followupDonutChart" class="donut-chart-container"></div>
                            </div>

                            <!-- Channel Badges Breakdown -->
                            <div class="followup-channels-grid">
                                <a href="{{ route($routePrefix . '.leads.index', ['type' => 'followup_today']) }}" class="fc-pill today-pill">
                                    <div class="fcp-top">
                                        <span class="fcp-dot" style="background:#ef4444;"></span>
                                        <span class="fcp-title">Today's</span>
                                    </div>
                                    <div class="fcp-count">{{ number_format($followupStats['today']) }}</div>
                                </a>

                                <a href="{{ route($routePrefix . '.leads.index', ['type' => 'followup_pending']) }}" class="fc-pill pending-pill">
                                    <div class="fcp-top">
                                        <span class="fcp-dot" style="background:#f59e0b;"></span>
                                        <span class="fcp-title">Pending</span>
                                    </div>
                                    <div class="fcp-count">{{ number_format($followupStats['pending']) }}</div>
                                </a>

                                <a href="{{ route($routePrefix . '.leads.index', ['type' => 'followup_future']) }}" class="fc-pill future-pill">
                                    <div class="fcp-top">
                                        <span class="fcp-dot" style="background:#6366f1;"></span>
                                        <span class="fcp-title">Future</span>
                                    </div>
                                    <div class="fcp-count">{{ number_format($followupStats['future']) }}</div>
                                </a>
                            </div>

                            <!-- Communication Mode Breakdown -->
                            <div class="channel-modes-row">
                                <div class="cm-item">
                                    <i class="bi bi-telephone-fill" style="color:#06b6d4;"></i>
                                    <span>Calling: <strong>{{ number_format($followupStats['calling']) }}</strong></span>
                                </div>
                                <div class="cm-item">
                                    <i class="bi bi-chat-dots-fill" style="color:#10b981;"></i>
                                    <span>WhatsApp/Msg: <strong>{{ number_format($followupStats['message']) }}</strong></span>
                                </div>
                                <div class="cm-item">
                                    <i class="bi bi-intersect" style="color:#8b5cf6;"></i>
                                    <span>Both: <strong>{{ number_format($followupStats['both']) }}</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- ─── ROW 2: REVENUE SMOOTH AREA SPLINE GRAPH ─── -->
                <div class="dash-viz-row">
                    <div class="dash-card-premium viz-revenue-card">
                        <div class="card-head-premium">
                            <div class="ch-left">
                                <div class="ch-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                                    <i class="bi bi-graph-up"></i>
                                </div>
                                <div>
                                    <div class="card-title-premium">Revenue & Financial Growth Trend</div>
                                    <div class="card-sub-premium">Smooth dynamic curve · Last 8 Months performance</div>
                                </div>
                            </div>
                            
                            <div class="rev-chart-controls">
                                <div class="rev-btn-group">
                                    <button class="rev-toggle-btn active" id="btnShowReceived" onclick="toggleRevenueSeries('received')">
                                        <span class="btn-dot" style="background:#10b981;"></span> Earnings (Received)
                                    </button>
                                    <button class="rev-toggle-btn" id="btnShowBooked" onclick="toggleRevenueSeries('booked')">
                                        <span class="btn-dot" style="background:#6366f1;"></span> Order Value (Booked)
                                    </button>
                                    <button class="rev-toggle-btn" id="btnShowBoth" onclick="toggleRevenueSeries('both')">
                                        <i class="bi bi-layers-fill"></i> Both Comparison
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="card-body-premium">
                            <!-- Revenue Highlights Ribbon -->
                            <div class="rev-kpi-ribbon">
                                <div class="rkr-item">
                                    <span class="rkr-label">Total Period Received</span>
                                    <span class="rkr-value text-emerald">{{ formatDashCurrency($totalReceivedAmount) }}</span>
                                </div>
                                <div class="rkr-divider"></div>
                                <div class="rkr-item">
                                    <span class="rkr-label">Total Booked Value</span>
                                    <span class="rkr-value text-indigo">{{ formatDashCurrency($totalOrderValue) }}</span>
                                </div>
                                <div class="rkr-divider"></div>
                                <div class="rkr-item">
                                    <span class="rkr-label">Pending Recovery</span>
                                    <span class="rkr-value text-rose">{{ formatDashCurrency($totalPending) }}</span>
                                </div>
                                <div class="rkr-divider"></div>
                                <div class="rkr-item">
                                    <span class="rkr-label">Collection Rate</span>
                                    <span class="rkr-value text-cyan">
                                        {{ $totalOrderValue > 0 ? round(($totalReceivedAmount / $totalOrderValue) * 100, 1) : 0 }}%
                                    </span>
                                </div>
                            </div>

                            <!-- Apex Smooth Spline Curve Chart -->
                            <div id="revenueSplineChart" class="revenue-chart-canvas"></div>
                        </div>
                    </div>
                </div>

                <!-- ─── ROW 3: PROJECT HORIZONTAL BARS & ORDER VERTICAL BARS ─── -->
                <div class="dash-viz-row">

                    <!-- 3. PROJECT STATUS PIPELINE (HORIZONTAL BAR CHART) -->
                    <div class="dash-card-premium viz-project-card">
                        <div class="card-head-premium">
                            <div class="ch-left">
                                <div class="ch-icon" style="background: rgba(99, 102, 241, 0.12); color: #6366f1;">
                                    <i class="bi bi-kanban"></i>
                                </div>
                                <div>
                                    <div class="card-title-premium">Project Pipeline Status</div>
                                    <div class="card-sub-premium">Horizontal breakdown · {{ $totalProjects }} Total Projects</div>
                                </div>
                            </div>
                            <div class="ch-badges">
                                <a href="{{ route($routePrefix . '.projects.index') }}" class="btn-link-subtle">
                                    View All <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body-premium">
                            <div id="projectHorizontalBarChart" class="project-chart-canvas"></div>

                            <!-- Mini Project Stats Summary -->
                            <div class="project-legend-badges">
                                @foreach ($projectPipeline as $pipeline)
                                    <div class="pl-badge-item">
                                        <span class="pl-dot" style="background: {{ $pipeline['color'] }};"></span>
                                        <span class="pl-name">{{ $pipeline['name'] }}</span>
                                        <span class="pl-count">{{ $pipeline['count'] }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- 4. ORDER VOLUME & PERFORMANCE (VERTICAL BAR CHART) -->
                    <div class="dash-card-premium viz-order-card">
                        <div class="card-head-premium">
                            <div class="ch-left">
                                <div class="ch-icon" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                                    <i class="bi bi-bar-chart-line-fill"></i>
                                </div>
                                <div>
                                    <div class="card-title-premium">Order Performance</div>
                                    <div class="card-sub-premium">Vertical monthly order comparison</div>
                                </div>
                            </div>
                            <div class="ch-badges">
                                <a href="{{ route($routePrefix . '.orders.index') }}" class="btn-link-subtle">
                                    All Orders <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>

                        <div class="card-body-premium">
                            <div id="orderVerticalBarChart" class="order-chart-canvas"></div>

                            <!-- Mini Order KPIs -->
                            <div class="order-mini-summary">
                                <div class="oms-item">
                                    <i class="bi bi-globe2 text-blue"></i>
                                    <div>
                                        <div class="oms-val">{{ array_sum($monthlyWebOrderCounts) }}</div>
                                        <div class="oms-lbl">Web / Dev Orders</div>
                                    </div>
                                </div>
                                <div class="oms-item">
                                    <i class="bi bi-megaphone-fill text-amber"></i>
                                    <div>
                                        <div class="oms-val">{{ array_sum($monthlyMktOrderCounts) }}</div>
                                        <div class="oms-lbl">Marketing Orders</div>
                                    </div>
                                </div>
                                <div class="oms-item">
                                    <i class="bi bi-check2-all text-emerald"></i>
                                    <div>
                                        <div class="oms-val">{{ number_format($totalOrders) }}</div>
                                        <div class="oms-lbl">Period Total</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            @endif
        </div>

    </main>

    @if($routePrefix != 'developer')
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @endif

    <style>
        /* ═══════════════════════════════════════════════════════════
           DASHBOARD PREMIUM VISUALIZATION SYSTEM
        ═══════════════════════════════════════════════════════════ */
        .dash-viz-grid {
            display: flex;
            flex-direction: column;
            gap: 24px;
            margin-top: 24px;
        }

        .dash-viz-row {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 24px;
        }

        .viz-funnel-card { grid-column: span 7; }
        .viz-followup-card { grid-column: span 5; }
        .viz-revenue-card { grid-column: span 12; }
        .viz-project-card { grid-column: span 6; }
        .viz-order-card { grid-column: span 6; }

        @media (max-width: 1100px) {
            .viz-funnel-card { grid-column: span 12; }
            .viz-followup-card { grid-column: span 12; }
            .viz-project-card { grid-column: span 12; }
            .viz-order-card { grid-column: span 12; }
        }

        /* Card Container */
        .dash-card-premium {
            background: var(--bg2);
            border: 1px solid var(--b2);
            border-radius: var(--r-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .dash-card-premium:hover {
            box-shadow: var(--shadow-md);
            border-color: var(--b3);
        }

        .card-head-premium {
            padding: 18px 22px;
            border-bottom: 1px solid var(--b2);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            flex-wrap: wrap;
            background: rgba(255, 255, 255, 0.015);
        }

        .ch-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .ch-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
            flex-shrink: 0;
        }

        .card-title-premium {
            font-size: 15.5px;
            font-weight: 700;
            color: var(--t1);
            letter-spacing: -0.2px;
        }

        .card-sub-premium {
            font-size: 12px;
            color: var(--t3);
            margin-top: 2px;
            font-weight: 500;
        }

        .card-body-premium {
            padding: 22px;
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .badge-pill {
            font-size: 12px;
            font-weight: 700;
            padding: 6px 13px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        .bg-blue-subtle { background: rgba(59, 130, 246, 0.12); color: #3b82f6; border: 1px solid rgba(59, 130, 246, 0.25); }
        .bg-amber-subtle { background: rgba(245, 158, 11, 0.12); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.25); }
        .btn-link-subtle {
            font-size: 12.5px;
            font-weight: 700;
            color: var(--accent2);
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: 0.2s;
        }
        .btn-link-subtle:hover { color: var(--accent); transform: translateX(2px); }

        /* ─── 1. INVERTED PYRAMID / FUNNEL STYLING ─── */
        .funnel-container {
            display: flex;
            align-items: center;
            gap: 30px;
            padding: 10px 0 20px;
            flex-wrap: wrap;
        }

        .funnel-graphic-wrap {
            flex: 1;
            min-width: 240px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }

        .funnel-slice {
            cursor: pointer;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            filter: drop-shadow(0 4px 10px rgba(0, 0, 0, 0.15));
        }

        .funnel-slice:hover {
            transform: translateY(-2px) scale(1.02);
            filter: drop-shadow(0 8px 18px rgba(0, 0, 0, 0.25));
        }

        .funnel-shape {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            color: #ffffff;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.3px;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
            transition: all 0.25s ease;
        }

        /* Funnel Tiers with trapezoid clip paths matching inverted pyramid */
        .funnel-slice.tier-1 { width: 100%; max-width: 320px; }
        .funnel-slice.tier-1 .funnel-shape {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            clip-path: polygon(0% 0%, 100% 0%, 90% 100%, 10% 100%);
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }

        .funnel-slice.tier-2 { width: 84%; max-width: 270px; }
        .funnel-slice.tier-2 .funnel-shape {
            background: linear-gradient(135deg, #06b6d4, #0891b2);
            clip-path: polygon(0% 0%, 100% 0%, 88% 100%, 12% 100%);
        }

        .funnel-slice.tier-3 { width: 68%; max-width: 220px; }
        .funnel-slice.tier-3 .funnel-shape {
            background: linear-gradient(135deg, #84cc16, #10b981);
            clip-path: polygon(0% 0%, 100% 0%, 84% 100%, 16% 100%);
        }

        .funnel-slice.tier-4 { width: 50%; max-width: 160px; }
        .funnel-slice.tier-4 .funnel-shape {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            clip-path: polygon(0% 0%, 100% 0%, 80% 100%, 20% 100%);
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }

        .funnel-metrics-list {
            flex: 1;
            min-width: 220px;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .fm-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 9px 14px;
            border-radius: 10px;
            background: var(--bg3);
            border: 1px solid var(--b2);
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .fm-item:hover {
            background: var(--b1);
            border-color: var(--accent);
            transform: translateX(4px);
        }

        .fm-connector {
            width: 18px;
            height: 2px;
            background: var(--b3);
            position: relative;
        }
        .fm-connector::after {
            content: '';
            position: absolute;
            right: 0;
            top: -3px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--b3);
        }

        .fm-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .fm-label-row {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .fm-badge-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }

        .fm-name {
            font-size: 13px;
            font-weight: 600;
            color: var(--t1);
        }

        .fm-pct {
            font-size: 11px;
            font-weight: 700;
            color: var(--t3);
            background: var(--b2);
            padding: 2px 6px;
            border-radius: 6px;
        }

        .fm-val {
            font-size: 16px;
            font-weight: 800;
            color: var(--t1);
            font-family: var(--mono);
        }

        .funnel-footer-chips {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 14px;
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid var(--b2);
        }

        .ff-chip {
            background: var(--bg3);
            border: 1px solid var(--b2);
            border-radius: 12px;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .ff-icon {
            font-size: 20px;
        }

        .ff-meta {
            display: flex;
            flex-direction: column;
        }

        .ff-title {
            font-size: 11.5px;
            color: var(--t3);
            font-weight: 600;
        }

        .ff-val {
            font-size: 15px;
            font-weight: 800;
            color: var(--t1);
            font-family: var(--mono);
        }

        /* ─── 2. FOLLOWUP DONUT STYLING ─── */
        .followup-chart-wrapper {
            position: relative;
            min-height: 220px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .followup-channels-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 12px;
        }

        .fc-pill {
            text-decoration: none;
            background: var(--bg3);
            border: 1px solid var(--b2);
            border-radius: 12px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: all 0.2s;
        }

        .fc-pill:hover {
            transform: translateY(-2px);
            border-color: var(--accent);
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .fcp-top {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .fcp-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .fcp-title {
            font-size: 11.5px;
            font-weight: 700;
            color: var(--t2);
        }

        .fcp-count {
            font-size: 17px;
            font-weight: 800;
            color: var(--t1);
            font-family: var(--mono);
        }

        .channel-modes-row {
            display: flex;
            align-items: center;
            justify-content: space-around;
            background: var(--b1);
            padding: 10px 14px;
            border-radius: 10px;
            margin-top: 14px;
            border: 1px dashed var(--b3);
            flex-wrap: wrap;
            gap: 10px;
        }

        .cm-item {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--t2);
        }

        .cm-item strong {
            color: var(--t1);
            font-family: var(--mono);
        }

        /* ─── 3. REVENUE SMOOTH AREA GRAPH STYLING ─── */
        .rev-chart-controls {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .rev-btn-group {
            display: flex;
            background: var(--b2);
            padding: 3px;
            border-radius: 10px;
            border: 1px solid var(--b3);
            gap: 4px;
        }

        .rev-toggle-btn {
            background: transparent;
            border: none;
            color: var(--t2);
            font-size: 12px;
            font-weight: 700;
            padding: 6px 12px;
            border-radius: 7px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: all 0.2s;
        }

        .rev-toggle-btn.active {
            background: var(--b1);
            color: var(--t1);
            box-shadow: 0 2px 6px rgba(0,0,0,0.15);
        }

        .rev-toggle-btn .btn-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
        }

        .rev-kpi-ribbon {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: var(--bg3);
            border: 1px solid var(--b2);
            border-radius: 14px;
            padding: 14px 22px;
            margin-bottom: 18px;
            flex-wrap: wrap;
            gap: 16px;
        }

        .rkr-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .rkr-label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--t3);
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .rkr-value {
            font-size: 18px;
            font-weight: 800;
            font-family: var(--mono);
        }

        .rkr-divider {
            width: 1px;
            height: 32px;
            background: var(--b2);
        }

        .revenue-chart-canvas {
            min-height: 320px;
        }

        /* ─── 4. PROJECT HORIZONTAL BARS & ORDER VERTICAL BARS ─── */
        .project-chart-canvas,
        .order-chart-canvas {
            min-height: 280px;
        }

        .project-legend-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--b2);
        }

        .pl-badge-item {
            background: var(--bg3);
            border: 1px solid var(--b2);
            border-radius: 8px;
            padding: 5px 10px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: var(--t2);
            font-weight: 600;
        }

        .pl-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
        }

        .pl-count {
            color: var(--t1);
            font-weight: 800;
            font-family: var(--mono);
        }

        .order-mini-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin-top: 14px;
            padding-top: 14px;
            border-top: 1px solid var(--b2);
        }

        .oms-item {
            background: var(--bg3);
            border: 1px solid var(--b2);
            border-radius: 10px;
            padding: 10px 12px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .oms-item i {
            font-size: 20px;
        }

        .oms-val {
            font-size: 16px;
            font-weight: 800;
            color: var(--t1);
            font-family: var(--mono);
        }

        .oms-lbl {
            font-size: 11px;
            font-weight: 600;
            color: var(--t3);
        }

        /* Utility colors */
        .text-emerald { color: #10b981 !important; }
        .text-indigo { color: #6366f1 !important; }
        .text-amber { color: #f59e0b !important; }
        .text-rose { color: #ef4444 !important; }
        .text-cyan { color: #06b6d4 !important; }
        .text-blue { color: #3b82f6 !important; }

        @media (max-width: 768px) {
            .funnel-container {
                flex-direction: column;
                gap: 16px;
            }
            .funnel-graphic-wrap {
                width: 100%;
            }
            .funnel-footer-chips,
            .order-mini-summary,
            .followup-channels-grid {
                grid-template-columns: 1fr;
            }
            .rkr-divider {
                display: none;
            }
            .rev-kpi-ribbon {
                display: grid;
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>

    @if($routePrefix != 'developer')
    <!-- ═══════════════════════════════════════════════════════════
         APEXCHARTS SCRIPT INITIALIZATION
    ═══════════════════════════════════════════════════════════ -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.getAttribute('data-theme') !== 'light';
            const textThemeColor = isDark ? '#b0b8d1' : '#475569';
            const gridBorderColor = isDark ? 'rgba(255, 255, 255, 0.06)' : 'rgba(0, 0, 0, 0.06)';

            // Data from Controller
            const months = {!! json_encode($months) !!};
            const monthlyReceived = {!! json_encode($monthlyReceivedAmounts) !!};
            const monthlyOrderValues = {!! json_encode($monthlyOrderValues) !!};

            const followupData = [
                {{ $followupStats['today'] }},
                {{ $followupStats['pending'] }},
                {{ $followupStats['future'] }}
            ];

            const projectStatuses = {!! json_encode($projectPipeline->pluck('name')) !!};
            const projectCounts = {!! json_encode($projectPipeline->pluck('count')) !!};
            const projectColors = {!! json_encode($projectPipeline->pluck('color')) !!};

            const monthlyWebOrders = {!! json_encode($monthlyWebOrderCounts) !!};
            const monthlyMktOrders = {!! json_encode($monthlyMktOrderCounts) !!};

            // Currency Formatter
            function formatCurrency(val) {
                if (val >= 10000000) return '₹' + (val / 10000000).toFixed(1) + 'Cr';
                if (val >= 100000) return '₹' + (val / 100000).toFixed(1) + 'L';
                if (val >= 1000) return '₹' + (val / 1000).toFixed(1) + 'K';
                return '₹' + Number(val).toLocaleString('en-IN');
            }

            // ─────────────────────────────────────────────────────────────
            // 1. REVENUE SMOOTH AREA SPLINE CHART
            // ─────────────────────────────────────────────────────────────
            const revenueOptions = {
                series: [{
                    name: 'Earnings (Received)',
                    data: monthlyReceived
                }],
                chart: {
                    type: 'area',
                    height: 330,
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#10b981', '#6366f1'],
                dataLabels: { enabled: false },
                stroke: {
                    curve: 'smooth',
                    width: 3.5
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.45,
                        opacityTo: 0.03,
                        stops: [0, 90, 100]
                    }
                },
                markers: {
                    size: 5,
                    strokeColors: '#ffffff',
                    strokeWidth: 2,
                    hover: { size: 7 }
                },
                xaxis: {
                    categories: months,
                    labels: {
                        style: { colors: textThemeColor, fontSize: '12px', fontWeight: 600 }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: textThemeColor, fontSize: '11px', fontWeight: 600 },
                        formatter: function(val) { return formatCurrency(val); }
                    }
                },
                grid: {
                    borderColor: gridBorderColor,
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function(val) {
                            return '₹' + Number(val).toLocaleString('en-IN');
                        }
                    }
                }
            };

            const revenueChart = new ApexCharts(document.querySelector("#revenueSplineChart"), revenueOptions);
            revenueChart.render();

            // Toggle Revenue Series function
            window.toggleRevenueSeries = function(mode) {
                document.querySelectorAll('.rev-toggle-btn').forEach(btn => btn.classList.remove('active'));
                if (mode === 'received') {
                    document.getElementById('btnShowReceived').classList.add('active');
                    revenueChart.updateOptions({
                        colors: ['#10b981'],
                        series: [{
                            name: 'Earnings (Received)',
                            data: monthlyReceived
                        }]
                    });
                } else if (mode === 'booked') {
                    document.getElementById('btnShowBooked').classList.add('active');
                    revenueChart.updateOptions({
                        colors: ['#6366f1'],
                        series: [{
                            name: 'Order Value (Booked)',
                            data: monthlyOrderValues
                        }]
                    });
                } else if (mode === 'both') {
                    document.getElementById('btnShowBoth').classList.add('active');
                    revenueChart.updateOptions({
                        colors: ['#10b981', '#6366f1'],
                        series: [
                            { name: 'Earnings (Received)', data: monthlyReceived },
                            { name: 'Order Value (Booked)', data: monthlyOrderValues }
                        ]
                    });
                }
            };

            // ─────────────────────────────────────────────────────────────
            // 2. FOLLOWUPS DONUT CHART
            // ─────────────────────────────────────────────────────────────
            const totalFollowupsSum = followupData.reduce((a, b) => a + b, 0);
            const followupOptions = {
                series: followupData,
                labels: ["Today's Followup", "Pending Followup", "Future Scheduled"],
                chart: {
                    type: 'donut',
                    height: 240,
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                colors: ['#ef4444', '#f59e0b', '#6366f1'],
                stroke: { width: 0 },
                dataLabels: { enabled: false },
                plotOptions: {
                    pie: {
                        donut: {
                            size: '72%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '13px',
                                    fontWeight: 600,
                                    color: textThemeColor
                                },
                                value: {
                                    show: true,
                                    fontSize: '22px',
                                    fontWeight: 800,
                                    color: isDark ? '#ffffff' : '#0f172a',
                                    formatter: function(val) { return Number(val).toLocaleString(); }
                                },
                                total: {
                                    show: true,
                                    label: 'Followups',
                                    color: textThemeColor,
                                    fontSize: '12px',
                                    fontWeight: 700,
                                    formatter: function() { return totalFollowupsSum.toLocaleString(); }
                                }
                            }
                        }
                    }
                },
                legend: { show: false },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                }
            };

            const followupChart = new ApexCharts(document.querySelector("#followupDonutChart"), followupOptions);
            followupChart.render();

            // ─────────────────────────────────────────────────────────────
            // 3. PROJECT PIPELINE HORIZONTAL BAR CHART
            // ─────────────────────────────────────────────────────────────
            const projectOptions = {
                series: [{
                    name: 'Projects',
                    data: projectCounts
                }],
                chart: {
                    type: 'bar',
                    height: 270,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        borderRadius: 6,
                        horizontal: true,
                        distributed: true,
                        barHeight: '60%',
                        dataLabels: {
                            position: 'right'
                        }
                    }
                },
                colors: projectColors,
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    offsetX: 6,
                    style: {
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: [isDark ? '#ffffff' : '#0f172a']
                    },
                    formatter: function(val) { return val + ' Projects'; }
                },
                xaxis: {
                    categories: projectStatuses,
                    labels: {
                        style: { colors: textThemeColor, fontSize: '11px', fontWeight: 600 }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: textThemeColor, fontSize: '12px', fontWeight: 600 }
                    }
                },
                grid: {
                    borderColor: gridBorderColor,
                    strokeDashArray: 4,
                    xaxis: { lines: { show: true } }
                },
                legend: { show: false },
                tooltip: {
                    theme: isDark ? 'dark' : 'light'
                }
            };

            const projectChart = new ApexCharts(document.querySelector("#projectHorizontalBarChart"), projectOptions);
            projectChart.render();

            // ─────────────────────────────────────────────────────────────
            // 4. ORDER VERTICAL BAR / COLUMN CHART
            // ─────────────────────────────────────────────────────────────
            const orderOptions = {
                series: [
                    {
                        name: 'Web / Dev Orders',
                        data: monthlyWebOrders
                    },
                    {
                        name: 'Marketing Orders',
                        data: monthlyMktOrders
                    }
                ],
                chart: {
                    type: 'bar',
                    height: 270,
                    toolbar: { show: false },
                    fontFamily: 'Plus Jakarta Sans, sans-serif'
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '50%',
                        borderRadius: 5
                    }
                },
                colors: ['#3b82f6', '#f59e0b'],
                dataLabels: { enabled: false },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: months,
                    labels: {
                        style: { colors: textThemeColor, fontSize: '11.5px', fontWeight: 600 }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    labels: {
                        style: { colors: textThemeColor, fontSize: '11px', fontWeight: 600 },
                        formatter: function(val) { return Math.round(val); }
                    }
                },
                grid: {
                    borderColor: gridBorderColor,
                    strokeDashArray: 4,
                    yaxis: { lines: { show: true } }
                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    labels: { colors: textThemeColor }
                },
                tooltip: {
                    theme: isDark ? 'dark' : 'light',
                    y: {
                        formatter: function(val) { return val + ' Orders'; }
                    }
                }
            };

            const orderChart = new ApexCharts(document.querySelector("#orderVerticalBarChart"), orderOptions);
            orderChart.render();
        });
    </script>
    @endif
@endsection