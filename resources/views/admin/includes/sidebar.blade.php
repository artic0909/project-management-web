  <!-- ═══════════════════════════════════════════
     SIDEBAR
════════════════════════════════════════════ -->
  <aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <div class="brand">
        <div class="brand-icon">
          <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
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

    <nav class="sidebar-nav" id="sidebarNav">
      <!-- ADMIN NAV -->
      <div class="nav-panel" id="nav-admin">
        <div class="nav-section-label">Overview</div>
        <a class="nav-item active" href="{{ route('admin.dashboard') }}">
          <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
          <span class="nav-badge pulse">Live</span>
        </a>

        <div class="nav-section-label">Utilities</div>
        <a class="nav-item" href="{{ route('admin.sources') }}">
          <i class="bi bi-link-45deg"></i><span>Sources</span>
          <span class="nav-count">5</span>
        </a>
        <a class="nav-item" href="{{ route('admin.services') }}">
          <i class="bi bi-diagram-3-fill"></i><span>Services</span>
          <span class="nav-count">4</span>
        </a>

        <div class="nav-section-label">Business</div>
        <a class="nav-item" href="{{ route('admin.leads') }}">
          <i class="bi bi-person-lines-fill"></i><span>Leads</span>
          <span class="nav-count">147</span>
        </a>

        <a class="nav-item" href="{{ route('admin.orders') }}">
          <i class="bi bi-bag-check-fill"></i><span>Orders</span>
          <span class="nav-count">38</span>
        </a>

        <a class="nav-item" href="{{ route('admin.marketing-orders') }}">
          <i class="bi bi-graph-up-arrow"></i><span>Marketing Orders</span>
          <span class="nav-count">104</span>
        </a>

        <a class="nav-item" href="#">
          <i class="bi bi-kanban-fill"></i><span>Projects</span>
          <span class="nav-count">24</span>
        </a>

        <div class="nav-section-label">People</div>
        <a class="nav-item" href="{{ route('admin.sales-person') }}">
          <i class="bi bi-people-fill"></i><span>Sales Person</span>
          <span class="nav-count">52</span>
        </a>

        <a class="nav-item" href="#">
          <i class="bi bi-clock-history"></i><span>Sales Attendance</span>
          <div class="nav-dot green"></div>
        </a>

        <a class="nav-item" href="{{ route('admin.developer') }}">
          <i class="bi bi-person-workspace"></i><span>Developers</span>
          <span class="nav-count">52</span>
        </a>
        <a class="nav-item" href="#">
          <i class="bi bi-clock-history"></i><span>Developer Attendance</span>
          <div class="nav-dot green"></div>
        </a>

        <div class="nav-section-label">System</div>
        <a class="nav-item" href="{{ route('admin.account-settings') }}">
          <i class="bi bi-gear-fill"></i><span>Settings</span>
        </a>
      </div>
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
        <div class="user-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">RK</div>
        <div class="user-info">
          <div class="user-name">Admin Name</div>
          <div class="user-role">Super Admin</div>
        </div>
        <div class="user-status-dot"></div>
      </div>
    </div>
  </aside>