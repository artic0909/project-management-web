<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="brand">
      <div class="brand-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </div>
      <div class="brand-text">
        <span class="brand-name">Orion</span>
        <span class="brand-sub">ERP Platform</span>
      </div>
    </div>
    <button class="sidebar-collapse-btn" onclick="toggleSidebar()" id="sidebarToggle">
      <i class="bi bi-layout-sidebar-reverse"></i>
    </button>
  </div>

  <!-- Panel Switcher Removed -->

  <nav class="sidebar-nav" id="sidebarNav">
    <!-- ADMIN NAV -->
    @if(Auth::guard('admin')->check())
    <div class="nav-panel" id="nav-admin">
      <div class="nav-section-label">Overview</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'dashboard')">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        <span class="nav-badge pulse">Live</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'analytics')">
        <i class="bi bi-activity"></i><span>Analytics</span>
      </a>

      <div class="nav-section-label">Business</div>
      <a class="nav-item" href="#" onclick="navigate(event,'projects')">
        <i class="bi bi-kanban-fill"></i><span>Projects</span>
        <span class="nav-count">24</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'leads')">
        <i class="bi bi-person-lines-fill"></i><span>Leads</span>
        <span class="nav-count">147</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'sales')">
        <i class="bi bi-bag-check-fill"></i><span>Sales & Orders</span>
        <span class="nav-count">38</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'finance')">
        <i class="bi bi-currency-rupee"></i><span>Finance</span>
      </a>

      <div class="nav-section-label">People</div>
      <a class="nav-item" href="#" onclick="navigate(event,'team')">
        <i class="bi bi-people-fill"></i><span>Team</span>
        <span class="nav-count">52</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>Attendance</span>
        <div class="nav-dot green"></div>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'payroll')">
        <i class="bi bi-wallet2"></i><span>Payroll</span>
      </a>

      <div class="nav-section-label">System</div>
      <a class="nav-item" href="#" onclick="navigate(event,'reports')">
        <i class="bi bi-file-earmark-bar-graph-fill"></i><span>Reports</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'settings')">
        <i class="bi bi-gear-fill"></i><span>Settings</span>
      </a>
    </div>
    <!-- SALES NAV -->
    @elseif(Auth::guard('sale')->check())
    <div class="nav-panel" id="nav-sales">
      <div class="nav-section-label">Sales Desk</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'sales-dash')">
        <i class="bi bi-speedometer2"></i><span>My Dashboard</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'leads')">
        <i class="bi bi-person-lines-fill"></i><span>My Leads</span>
        <span class="nav-count">28</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'add-lead')">
        <i class="bi bi-person-plus-fill"></i><span>Add Lead</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'sales')">
        <i class="bi bi-bag-check-fill"></i><span>Orders</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'add-order')">
        <i class="bi bi-plus-circle-fill"></i><span>New Order</span>
      </a>
      <div class="nav-section-label">Personal</div>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>My Attendance</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'targets')">
        <i class="bi bi-bullseye"></i><span>My Targets</span>
      </a>
    </div>
    <!-- DEV NAV -->
    @elseif(Auth::guard('developer')->check())
    <div class="nav-panel" id="nav-dev">
      <div class="nav-section-label">Developer Desk</div>
      <a class="nav-item active" href="#" onclick="navigate(event,'dev-dash')">
        <i class="bi bi-terminal-fill"></i><span>My Dashboard</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'my-projects')">
        <i class="bi bi-kanban-fill"></i><span>My Projects</span>
        <span class="nav-count">6</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'tasks')">
        <i class="bi bi-check2-square"></i><span>Tasks</span>
        <span class="nav-count">14</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'timeline')">
        <i class="bi bi-calendar3"></i><span>Timeline</span>
      </a>
      <div class="nav-section-label">Personal</div>
      <a class="nav-item" href="#" onclick="navigate(event,'attendance')">
        <i class="bi bi-clock-history"></i><span>My Attendance</span>
      </a>
      <a class="nav-item" href="#" onclick="navigate(event,'git-log')">
        <i class="bi bi-git"></i><span>Commit Log</span>
      </a>
    </div>
    @endif
  </nav>

  <div class="sidebar-footer">
    <div class="theme-row">
      <span class="theme-label"><i class="bi bi-moon-stars-fill"></i> Dark Mode</span>
      <label class="toggle-switch">
        <input type="checkbox" id="themeSwitch" onchange="toggleTheme()" checked>
        <span class="toggle-track"><span class="toggle-thumb"></span></span>
      </label>
    </div>
    <div class="user-profile">
      <div class="user-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</div>
      <div class="user-info">
        <div class="user-name">{{ Auth::user()->name ?? 'User' }}</div>
        <div class="user-role">
            @if(Auth::guard('admin')->check()) Super Admin
            @elseif(Auth::guard('sale')->check()) Sales Executive
            @elseif(Auth::guard('developer')->check()) Developer
            @else User
            @endif
        </div>
      </div>
      <div class="user-status-dot"></div>
    </div>
  </div>
</aside>