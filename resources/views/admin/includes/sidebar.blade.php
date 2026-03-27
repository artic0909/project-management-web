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

  @php
    $guard = auth()->guard('admin')->check() ? 'admin' : (auth()->guard('sale')->check() ? 'sale' : 'web');
    $routePrefix = $guard === 'admin' ? 'admin.' : ($guard === 'sale' ? 'sale.' : '');
  @endphp

  <nav class="sidebar-nav" id="sidebarNav">
    <div class="nav-panel" id="nav-{{ $guard }}">

      <div class="nav-section-label">Overview</div>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }}" href="{{ route($routePrefix . 'dashboard') }}">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        <span class="nav-badge pulse">Live</span>
      </a>

      @if($guard === 'admin')
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
      @endif

      <div class="nav-section-label">Business</div>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'leads*') ? 'active' : '' }}" href="{{ route($routePrefix . 'leads.index') }}">
        <i class="bi bi-person-lines-fill"></i><span>Leads</span>
        <span class="nav-count">{{ $leadCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'orders*') ? 'active' : '' }}" href="{{ route($routePrefix . 'orders.index') }}">
        <i class="bi bi-bag-check-fill"></i><span>Orders</span>
        <span class="nav-count">{{ $orderCount }}</span>
      </a>  

      <a class="nav-item {{ request()->routeIs($routePrefix . 'payments*') ? 'active' : '' }}" href="{{ route($routePrefix . 'payments.index') }}">
        <i class="bi bi-wallet2"></i><span>Payments</span>
      </a>


      <a class="nav-item {{ request()->routeIs($routePrefix . 'projects*') ? 'active' : '' }}" href="{{ route($routePrefix . 'projects.index') }}">
        <i class="bi bi-kanban-fill"></i><span>Projects</span>
        <span class="nav-count">{{ $projectCount }}</span>
      </a>

      <a class="nav-item {{ request()->routeIs($routePrefix . 'losted-leads') ? 'active' : '' }}" href="{{ route($routePrefix . 'losted-leads') }}">
        <i class="bi bi-ban"></i><span>Losted Leads</span>
        <span class="nav-count">{{ $lostLeadCount }}</span>
      </a>

      <div class="nav-section-label">People</div>
      @if($guard === 'admin')
      <a class="nav-item {{ request()->routeIs('admin.sales-person*') ? 'active' : '' }}" href="{{ route('admin.sales-person') }}">
        <i class="bi bi-people-fill"></i><span>Sales Person</span>
        <span class="nav-count">{{ $salesPersonCount }}</span>
      </a>
      @endif
      <a class="nav-item" href="#">
        <i class="bi bi-clock-history"></i><span>Sales Attendance</span>
        <div class="nav-dot green"></div>
      </a>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'developer*') ? 'active' : '' }}" href="{{ route($routePrefix . 'developer') }}">
        <i class="bi bi-person-workspace"></i><span>Developers</span>
        <span class="nav-count">{{ $developerCount }}</span>
      </a>
      <a class="nav-item" href="#">
        <i class="bi bi-clock-history"></i><span>Developer Attendance</span>
        <div class="nav-dot green"></div>
      </a>

      <div class="nav-section-label">Others</div>
      <a class="nav-item " href="#">
        <i class="bi bi-headset"></i><span>Support</span>
      </a>

      <a class="nav-item " href="#">
        <i class="bi bi-browser-chrome"></i><span>Inquiries</span>
      </a>

      <a class="nav-item {{ request()->routeIs($routePrefix . 'account-settings*') ? 'active' : '' }}" href="{{ route($routePrefix . 'account-settings') }}">
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
        {{ strtoupper(substr(auth()->guard($guard)->user()->name ?? 'U', 0, 2)) }}
      </div>
      <div class="user-info">
        <div class="user-name">{{ auth()->guard($guard)->user()->name ?? ($guard === 'admin' ? 'Admin' : 'User') }}</div>
        <div class="user-role">{{ auth()->guard($guard)->user()->email ?? 'Member' }}</div>
      </div>
      <div class="user-status-dot"></div>
    </div>
  </div>
</aside>