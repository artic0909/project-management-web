@extends('admin.layout.app')

@section('title', 'Admin Dashboard')

@section('content')

<div class="main-wrap" id="mainWrap">

    <!-- TOPBAR -->
    <header class="topbar" id="topbar">
        <div class="topbar-left">
            <button class="mob-menu-btn" onclick="openSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="page-breadcrumb">
                <span class="breadcrumb-panel" id="activePanelLabel">Admin Panel</span>
                <i class="bi bi-chevron-right bc-sep"></i>
                <span class="breadcrumb-page" id="activePageLabel">Dashboard</span>
            </div>
        </div>

        <div class="topbar-center">
            <div class="global-search">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Search projects, leads, orders, team…" id="globalSearch">
                <kbd>⌘K</kbd>
            </div>
        </div>

        <div class="topbar-right">
            <div class="tb-btn" onclick="showToast('info','Syncing data...','bi-arrow-clockwise')" data-tooltip="Sync">
                <i class="bi bi-arrow-clockwise"></i>
            </div>

            <div class="tb-btn notif-btn" data-tooltip="Notifications" onclick="toggleNotifPanel()">
                <i class="bi bi-bell-fill"></i>
                <span class="notif-badge">7</span>
            </div>

            <!-- Notification dropdown -->
            <div class="notif-panel" id="notifPanel">
                <div class="notif-header">
                    <span>Notifications</span>
                    <button class="btn-xs" onclick="markAllRead()">Mark all read</button>
                </div>
                <div class="notif-list">
                    <div class="notif-item unread">
                        <div class="notif-icon blue"><i class="bi bi-kanban-fill"></i></div>
                        <div class="notif-body"><strong>Project Orion v2</strong> deadline in 2 days<div class="notif-time">5 min ago</div>
                        </div>
                    </div>
                    <div class="notif-item unread">
                        <div class="notif-icon green"><i class="bi bi-currency-rupee"></i></div>
                        <div class="notif-body"><strong>₹2.4L order</strong> received from TechCorp<div class="notif-time">22 min ago</div>
                        </div>
                    </div>
                    <div class="notif-item unread">
                        <div class="notif-icon orange"><i class="bi bi-person-fill"></i></div>
                        <div class="notif-body"><strong>Priya Sharma</strong> marked attendance late<div class="notif-time">1 hr ago</div>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="notif-icon purple"><i class="bi bi-code-slash"></i></div>
                        <div class="notif-body"><strong>Sprint 12</strong> review completed<div class="notif-time">3 hrs ago</div>
                        </div>
                    </div>
                    <div class="notif-item">
                        <div class="notif-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <div class="notif-body"><strong>API Gateway</strong> response time elevated<div class="notif-time">Yesterday</div>
                        </div>
                    </div>
                </div>
                <div class="notif-footer"><a href="#">View all notifications →</a></div>
            </div>

            <div class="tb-btn" data-tooltip="Messages">
                <i class="bi bi-chat-dots-fill"></i>
            </div>
            <div class="tb-divider"></div>
            <div class="tb-user" onclick="toggleUserMenu()">
                <div class="user-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">RK</div>
                <span class="tb-user-name">Rahul Kumar</span>
                <i class="bi bi-chevron-down" style="font-size:10px;color:var(--t3)"></i>
            </div>
            <div class="user-menu" id="userMenu">
                <a class="um-item" href="#"><i class="bi bi-person-fill"></i> My Profile</a>
                <a class="um-item" href="#" onclick="navigate(event,'settings')"><i class="bi bi-gear-fill"></i> Settings</a>
                <a class="um-item" href="#"><i class="bi bi-shield-check"></i> Security</a>
                <hr class="um-divider">
                <a class="um-item danger" href="#"><i class="bi bi-box-arrow-right"></i> Sign Out</a>
            </div>
        </div>
    </header>

    <!-- ═══ PAGE CONTENT AREA ═══ -->
    <main class="page-area" id="pageArea">


        <!-- ADMIN DASHBOARD PAGE -->
        <div class="page" id="page-dashboard">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Admin Dashboard</h1>
                    <p class="page-desc">Live overview · <span id="liveDate"></span></p>
                </div>
                <div class="header-actions">
                    <button class="btn-ghost"><i class="bi bi-sliders2"></i> Filters</button>
                    <div class="date-range-picker">
                        <i class="bi bi-calendar3"></i>
                        <select onchange="showToast('info','Date range updated','bi-calendar3')">
                            <option>This Month</option>
                            <option>Last 7 Days</option>
                            <option>Last Quarter</option>
                            <option>This Year</option>
                        </select>
                    </div>
                    <button class="btn-ghost"><i class="bi bi-download"></i> Export</button>
                    <button class="btn-primary-solid" onclick="openModal('quickAddModal')">
                        <i class="bi bi-plus-lg"></i> Quick Add
                    </button>
                </div>
            </div>

            <!-- KPI STRIP -->
            <div class="kpi-grid">
                <div class="kpi-card" style="--kpi-accent:#6366f1">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(99,102,241,.15);color:#6366f1"><i class="bi bi-currency-rupee"></i></div>
                        <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 18.4%</span>
                    </div>
                    <div class="kpi-value">₹48.6L</div>
                    <div class="kpi-label">Total Revenue</div>
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
                <div class="kpi-card" style="--kpi-accent:#10b981">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(16,185,129,.15);color:#10b981"><i class="bi bi-bag-check-fill"></i></div>
                        <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 12.1%</span>
                    </div>
                    <div class="kpi-value">238</div>
                    <div class="kpi-label">Orders Closed</div>
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
                <div class="kpi-card" style="--kpi-accent:#f59e0b">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(245,158,11,.15);color:#f59e0b"><i class="bi bi-person-lines-fill"></i></div>
                        <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 9.8%</span>
                    </div>
                    <div class="kpi-value">1,247</div>
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
                <div class="kpi-card" style="--kpi-accent:#8b5cf6">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(139,92,246,.15);color:#8b5cf6"><i class="bi bi-kanban-fill"></i></div>
                        <span class="kpi-trend down"><i class="bi bi-arrow-down-right"></i> 2.3%</span>
                    </div>
                    <div class="kpi-value">24</div>
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
                <div class="kpi-card" style="--kpi-accent:#ef4444">
                    <div class="kpi-top">
                        <div class="kpi-icon" style="background:rgba(239,68,68,.15);color:#ef4444"><i class="bi bi-people-fill"></i></div>
                        <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 4.2%</span>
                    </div>
                    <div class="kpi-value">52</div>
                    <div class="kpi-label">Team Members</div>
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
                        <div class="kpi-icon" style="background:rgba(6,182,212,.15);color:#06b6d4"><i class="bi bi-clock-fill"></i></div>
                        <span class="kpi-trend up"><i class="bi bi-arrow-up-right"></i> 6.7%</span>
                    </div>
                    <div class="kpi-value">94.2%</div>
                    <div class="kpi-label">Attendance Rate</div>
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
            </div>

            <!-- MAIN GRID -->
            <div class="dash-grid">

                <!-- Revenue Chart -->
                <div class="dash-card span-8">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Revenue vs Target</div>
                            <div class="card-sub">Monthly comparison · FY 2024–25</div>
                        </div>
                        <div class="card-actions">
                            <div class="seg-control">
                                <button class="seg-btn active">Monthly</button>
                                <button class="seg-btn">Quarterly</button>
                                <button class="seg-btn">Yearly</button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="chart-legend">
                            <span class="legend-item"><span class="l-dot" style="background:#6366f1"></span>Revenue</span>
                            <span class="legend-item"><span class="l-dot" style="background:#10b981"></span>Target</span>
                            <span class="legend-item"><span class="l-dot" style="background:#f59e0b"></span>Expenses</span>
                        </div>
                        <div class="bar-chart-wrap">
                            <div class="y-axis">
                                <span>50L</span><span>40L</span><span>30L</span><span>20L</span><span>10L</span><span>0</span>
                            </div>
                            <div class="bar-chart">
                                <div class="bar-group" data-month="Apr">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:62%" data-val="₹31L"></div>
                                        <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                                        <div class="bar exp" style="height:38%" data-val="₹19L"></div>
                                    </div>
                                    <div class="bar-label">Apr</div>
                                </div>
                                <div class="bar-group" data-month="May">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:75%" data-val="₹37.5L"></div>
                                        <div class="bar tgt" style="height:70%" data-val="₹35L"></div>
                                        <div class="bar exp" style="height:42%" data-val="₹21L"></div>
                                    </div>
                                    <div class="bar-label">May</div>
                                </div>
                                <div class="bar-group" data-month="Jun">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:58%" data-val="₹29L"></div>
                                        <div class="bar tgt" style="height:72%" data-val="₹36L"></div>
                                        <div class="bar exp" style="height:35%" data-val="₹17.5L"></div>
                                    </div>
                                    <div class="bar-label">Jun</div>
                                </div>
                                <div class="bar-group" data-month="Jul">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:80%" data-val="₹40L"></div>
                                        <div class="bar tgt" style="height:74%" data-val="₹37L"></div>
                                        <div class="bar exp" style="height:45%" data-val="₹22.5L"></div>
                                    </div>
                                    <div class="bar-label">Jul</div>
                                </div>
                                <div class="bar-group" data-month="Aug">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:88%" data-val="₹44L"></div>
                                        <div class="bar tgt" style="height:76%" data-val="₹38L"></div>
                                        <div class="bar exp" style="height:50%" data-val="₹25L"></div>
                                    </div>
                                    <div class="bar-label">Aug</div>
                                </div>
                                <div class="bar-group" data-month="Sep">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:72%" data-val="₹36L"></div>
                                        <div class="bar tgt" style="height:78%" data-val="₹39L"></div>
                                        <div class="bar exp" style="height:40%" data-val="₹20L"></div>
                                    </div>
                                    <div class="bar-label">Sep</div>
                                </div>
                                <div class="bar-group" data-month="Oct">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:95%" data-val="₹47.5L"></div>
                                        <div class="bar tgt" style="height:80%" data-val="₹40L"></div>
                                        <div class="bar exp" style="height:52%" data-val="₹26L"></div>
                                    </div>
                                    <div class="bar-label">Oct</div>
                                </div>
                                <div class="bar-group active" data-month="Nov">
                                    <div class="bar-stack">
                                        <div class="bar rev" style="height:97%;background:linear-gradient(180deg,#818cf8,#6366f1)" data-val="₹48.6L"></div>
                                        <div class="bar tgt" style="height:82%" data-val="₹41L"></div>
                                        <div class="bar exp" style="height:48%" data-val="₹24L"></div>
                                    </div>
                                    <div class="bar-label">Nov ●</div>
                                </div>
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
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#6366f1" stroke-width="18" stroke-dasharray="94.2 220.8" stroke-dashoffset="0" stroke-linecap="round" />
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#10b981" stroke-width="18" stroke-dasharray="66 248.9" stroke-dashoffset="-94.2" stroke-linecap="round" />
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#f59e0b" stroke-width="18" stroke-dasharray="44 271" stroke-dashoffset="-160.2" stroke-linecap="round" />
                                <circle cx="60" cy="60" r="50" fill="none" stroke="#ef4444" stroke-width="18" stroke-dasharray="30 285" stroke-dashoffset="-204.2" stroke-linecap="round" />
                                <text x="60" y="56" text-anchor="middle" fill="var(--t1)" font-size="14" font-weight="700" font-family="Plus Jakarta Sans">24</text>
                                <text x="60" y="68" text-anchor="middle" fill="var(--t3)" font-size="6" font-family="Plus Jakarta Sans">Projects</text>
                            </svg>
                        </div>
                        <div class="donut-legend">
                            <div class="dl-item"><span class="dl-dot" style="background:#6366f1"></span><span class="dl-label">In Progress</span><span class="dl-val">10</span></div>
                            <div class="dl-item"><span class="dl-dot" style="background:#10b981"></span><span class="dl-label">Completed</span><span class="dl-val">7</span></div>
                            <div class="dl-item"><span class="dl-dot" style="background:#f59e0b"></span><span class="dl-label">Review</span><span class="dl-val">5</span></div>
                            <div class="dl-item"><span class="dl-dot" style="background:#ef4444"></span><span class="dl-label">Delayed</span><span class="dl-val">2</span></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </main>
</div>

@endsection