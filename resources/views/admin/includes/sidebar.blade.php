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
    <div class="nav-panel" id="nav-admin">

      <div class="nav-section-label">Overview</div>
      <a class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        <span class="nav-badge pulse">Live</span>
      </a>

      <div class="nav-section-label">Utilities</div>
      <a class="nav-item {{ request()->routeIs('admin.sources*') ? 'active' : '' }}" href="{{ route('admin.sources.index') }}">
        <i class="bi bi-link-45deg"></i><span>Sources</span>
        <span class="nav-count">{{ $sourceCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}" href="{{ route('admin.services.index') }}">
        <i class="bi bi-diagram-3-fill"></i><span>Services</span>
        <span class="nav-count">{{ $serviceCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs('admin.campaign*') ? 'active' : '' }}" href="{{ route('admin.campaign.index') }}">
        <i class="bi bi-diagram-3-fill"></i><span>Campaign</span>
        <span class="nav-count">{{ $campaignCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs('admin.status') ? 'active' : '' }}" href="{{ route('admin.status') }}">
        <i class="bi bi-flag-fill"></i><span>All Status</span>
        <span class="nav-count">{{ $statusCount }}</span>
      </a>

      <div class="nav-section-label">Business</div>
      <a class="nav-item {{ request()->routeIs('admin.leads*') ? 'active' : '' }}" href="{{ route('admin.leads.index') }}">
        <i class="bi bi-person-lines-fill"></i><span>Leads</span>
        <span class="nav-count">{{ $leadCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
        <i class="bi bi-bag-check-fill"></i><span>Orders</span>
        <span class="nav-count">{{ $orderCount }}</span>
      </a>  

      <a class="nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
        <i class="bi bi-wallet2"></i><span>Payments</span>
      </a>


      <a class="nav-item {{ request()->routeIs('admin.projects*') ? 'active' : '' }}" href="{{ route('admin.projects.index') }}">
        <i class="bi bi-kanban-fill"></i><span>Projects</span>
        <span class="nav-count">24</span>
      </a>

      <a class="nav-item {{ request()->routeIs('admin.losted-leads') ? 'active' : '' }}" href="{{ route('admin.losted-leads') }}">
        <i class="bi bi-ban"></i><span>Losted Leads</span>
        <span class="nav-count">20</span>
      </a>

      <div class="nav-section-label">People</div>
      <a class="nav-item {{ request()->routeIs('admin.sales-person*') ? 'active' : '' }}" href="{{ route('admin.sales-person') }}">
        <i class="bi bi-people-fill"></i><span>Sales Person</span>
        <span class="nav-count">{{ $salesPersonCount }}</span>
      </a>
      <a class="nav-item" href="#">
        <i class="bi bi-clock-history"></i><span>Sales Attendance</span>
        <div class="nav-dot green"></div>
      </a>
      <a class="nav-item {{ request()->routeIs('admin.developer*') ? 'active' : '' }}" href="{{ route('admin.developer') }}">
        <i class="bi bi-person-workspace"></i><span>Developers</span>
        <span class="nav-count">{{ $developerCount }}</span>
      </a>
      <a class="nav-item" href="#">
        <i class="bi bi-clock-history"></i><span>Developer Attendance</span>
        <div class="nav-dot green"></div>
      </a>

      <div class="nav-section-label">System</div>
      <a class="nav-item {{ request()->routeIs('admin.account-settings*') ? 'active' : '' }}" href="{{ route('admin.account-settings') }}">
        <i class="bi bi-gear-fill"></i><span>Settings</span>
      </a>

    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="theme-row">
      <span class="theme-label"><i class="bi bi-moon-stars-fill"></i> Dark Mode</span>
      <label class="toggle-switch">
        <input type="checkbox" id="themeSwitch" onchange="toggleTheme()"
          {{ session('theme', 'dark') === 'dark' ? 'checked' : '' }}>
        <span class="toggle-track"><span class="toggle-thumb"></span></span>
      </label>
    </div>
    <div class="user-profile">
      <div class="user-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
        {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 2)) }}
      </div>
      <div class="user-info">
        <div class="user-name">{{ auth()->user()->name ?? 'Admin' }}</div>
        <div class="user-role">{{ auth()->user()->role ?? 'Super Admin' }}</div>
      </div>
      <div class="user-status-dot"></div>
    </div>
  </div>
</aside>