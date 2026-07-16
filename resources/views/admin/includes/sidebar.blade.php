<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <div class="brand">
     
        <img src="{{ asset('logo.png') }}" alt="Logo" class="brand-logo" width="125">
        <!-- <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" />
        </svg> -->
      
      <!-- <div class="brand-text">
        <span class="brand-name">StandsWeb</span>
        <span class="brand-sub">ERP Platform</span>
      </div> -->
    </div>
    <button class="sidebar-collapse-btn" onclick="toggleSidebar()" id="sidebarToggle">
      <i class="bi bi-layout-sidebar-reverse"></i>
    </button>
  </div>

  @php
    $guard = auth()->guard('admin')->check() ? 'admin' : (auth()->guard('sale')->check() ? 'sale' : 'web');
    $routePrefix = $guard === 'admin' ? 'admin.' : ($guard === 'sale' ? 'sale.' : '');

    $isLeadsActive = request()->routeIs($routePrefix . 'leads*');
    $isLostedLeadsActive = request()->routeIs($routePrefix . 'losted-leads') || request()->routeIs($routePrefix . 'losted-leads.show');

    if (request()->routeIs($routePrefix . 'leads.followup') && isset($model) && $model->is_losted) {
        $isLeadsActive = false;
        $isLostedLeadsActive = true;
    }
  @endphp

  <nav class="sidebar-nav" id="sidebarNav">
    <div class="nav-panel" id="nav-{{ $guard }}">

      <div class="nav-section-label">Overview</div>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'dashboard') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'dashboard') }}">
        <i class="bi bi-grid-1x2-fill"></i><span>Dashboard</span>
        <span class="nav-badge pulse">Live</span>
      </a>

      @if($guard === 'admin')
        <div class="nav-section-label">Utilities</div>
        <a class="nav-item {{ request()->routeIs('admin.sources*') ? 'active' : '' }}"
          href="{{ route('admin.sources.index') }}">
          <i class="bi bi-broadcast"></i><span>Sources</span>
          <span class="nav-count">{{ $sourceCount }}</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}"
          href="{{ route('admin.services.index') }}">
          <i class="bi bi-briefcase-fill"></i><span>Services</span>
          <span class="nav-count">{{ $serviceCount }}</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}"
          href="{{ route('admin.plans.index') }}">
          <i class="bi bi-layers-half"></i><span>Plans</span>
          <span class="nav-count">{{ $planCount }}</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.campaign*') ? 'active' : '' }}"
          href="{{ route('admin.campaign.index') }}">
          <i class="bi bi-megaphone-fill"></i><span>Campaign</span>
          <span class="nav-count">{{ $campaignCount }}</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.status') ? 'active' : '' }}" href="{{ route('admin.status') }}">
          <i class="bi bi-flag-fill"></i><span>All Status</span>
          <span class="nav-count">{{ $statusCount }}</span>
        </a>
      @endif

      <div class="nav-section-label">Business</div>
      <a class="nav-item {{ $isLeadsActive ? 'active' : '' }}"
        href="{{ route($routePrefix . 'leads.index') }}">
        <i class="bi bi-person-lines-fill"></i><span>{{ $guard === 'sale' ? 'My Leads' : 'Leads' }}</span>
        <span class="nav-count">{{ $leadCount }}</span>
      </a>
      <a class="nav-item {{ request()->routeIs($routePrefix . 'orders.index') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'orders.index') }}">
        <i class="bi bi-bag-check-fill"></i><span>{{ $guard === 'sale' ? 'My Orders' : 'Orders' }}</span>
        <span class="nav-count">{{ $orderCount }}</span>
      </a>

      <a class="nav-item {{ request()->routeIs($routePrefix . 'orders.renewals') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'orders.renewals') }}">
        <i class="bi bi-arrow-repeat"></i><span>Renewals</span>
        @if(isset($upcomingRenewals) && $upcomingRenewals->count() > 0)
          <span class="nav-badge">{{ $upcomingRenewals->count() }}</span>
        @endif
      </a>


      <a class="nav-item {{ request()->routeIs($routePrefix . 'payments*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'payments.index') }}">
        <i class="bi bi-wallet2"></i><span>Payments</span>
      </a>

      @if($guard === 'admin')
      <a class="nav-item {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}"
        href="{{ route('admin.invoices.index') }}">
        <i class="bi bi-receipt"></i><span>Invoices</span>
        <span class="nav-count">{{ $invoiceCount }}</span>
      </a>
      @elseif($guard === 'sale')
      <a class="nav-item {{ request()->routeIs('sale.invoices*') ? 'active' : '' }}"
        href="{{ route('sale.invoices.index') }}">
        <i class="bi bi-receipt"></i><span>Invoices</span>
      </a>
      @endif


      <a class="nav-item {{ request()->routeIs($routePrefix . 'projects*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'projects.index') }}">
        <i class="bi bi-kanban-fill"></i><span>{{ $guard === 'sale' ? 'My Projects' : 'Projects' }}</span>
        <span class="nav-count">{{ $projectCount }}</span>
      </a>

      <a class="nav-item {{ $isLostedLeadsActive ? 'active' : '' }}"
        href="{{ route($routePrefix . 'losted-leads') }}">
        <i class="bi bi-ban"></i><span>Losted Leads</span>
        <span class="nav-count">{{ $lostLeadCount }}</span>
      </a>

      <a class="nav-item {{ request()->routeIs($routePrefix . 'meetings*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'meetings.index') }}">
        <i class="bi bi-camera-video-fill"></i><span>Meetings</span>
        <span class="nav-count">{{ $meetingCount }}</span>
      </a>

      <div class="nav-section-label">Team Members</div>
      @if($guard === 'admin')
        <a class="nav-item {{ request()->routeIs('admin.sales-person*') ? 'active' : '' }}"
          href="{{ route('admin.sales-person') }}">
          <i class="bi bi-people-fill"></i><span>Sales Person</span>
          <span class="nav-count">{{ $salesPersonCount }}</span>
        </a>
      @endif
      @if($guard === 'admin')
      <a class="nav-item {{ request()->routeIs($routePrefix . 'developer*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'developer') }}">
        <i class="bi bi-person-workspace"></i><span>Developers</span>
        <span class="nav-count">{{ $developerCount }}</span>
      </a>
      @endif
      @if ($guard === 'admin')
        <div class="nav-section-label">Attendance</div>
        <a class="nav-item {{ request()->routeIs('admin.attendance.sale-index') ? 'active' : '' }}"
          href="{{ route('admin.attendance.sale-index') }}">
          <i class="bi bi-person-badge-fill"></i><span>Sale Attendance</span>
        </a>
        <a class="nav-item {{ request()->routeIs('admin.attendance.dev-index') ? 'active' : '' }}"
          href="{{ route('admin.attendance.dev-index') }}">
          <i class="bi bi-calendar-check-fill"></i><span>Dev Attendance</span>
        </a>
      @else
        <a class="nav-item {{ request()->routeIs($routePrefix . 'attendance*') ? 'active' : '' }}"
          href="{{ route($routePrefix . 'attendance.index') }}">
          <i class="bi bi-clock-history"></i><span>My Attendances</span>
          <div class="nav-dot green"></div>
        </a>
      @endif
      

      <div class="nav-section-label">Others</div>
      @if($guard === 'admin')
      <a class="nav-item {{ request()->routeIs('admin.inquiry*') ? 'active' : '' }}"
        href="{{ route('admin.inquiry.index') }}">
        <i class="bi bi-chat-left-text-fill"></i><span>Order Inquiries</span>
        <span class="nav-count">{{ $inquiryCount }}</span>
      </a>

      <!-- Notes -->
       <a class="nav-item {{ request()->routeIs('admin.notes*') ? 'active' : '' }}" href="{{ route('admin.notes.index') }}">
          <i class="bi bi-sticky-fill"></i><span>Notes</span>
        </a>

        <a class="nav-item {{ request()->routeIs('admin.supports*') ? 'active' : '' }}" href="{{ route('admin.supports.index') }}">
          <i class="bi bi-headset"></i><span>Support</span>
          <span class="nav-count">{{ $supportCount }}</span>
        </a>
      @endif

      <a class="nav-item {{ request()->routeIs($routePrefix . 'account-settings*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'account-settings') }}">
        <i class="bi bi-gear-fill"></i><span>Settings</span>
      </a>

    </div>
  </nav>

  <div class="sidebar-footer">
    <div class="theme-row">
      <span class="theme-label"><i class="bi bi-moon-stars-fill"></i> Dark Mode</span>
      <label class="toggle-switch">
        <input type="checkbox" id="themeSwitch" onchange="toggleTheme()" {{ session('theme', 'dark') === 'dark' ? 'checked' : '' }}>
        <span class="toggle-track"><span class="toggle-thumb"></span></span>
      </label>
    </div>
    <div class="user-profile">
      <div class="user-ava" style="{{ auth()->guard($guard)->user()->profile_image ? 'background:transparent;' : 'background:linear-gradient(135deg,#6366f1,#8b5cf6);' }}">
        @if(auth()->guard($guard)->user()->profile_image)
            <img src="{{ asset('storage/' . auth()->guard($guard)->user()->profile_image) }}" alt="" style="width:100%;height:100%;object-fit:cover;border-radius:inherit;">
        @else
            {{ strtoupper(substr(auth()->guard($guard)->user()->name ?? 'U', 0, 2)) }}
        @endif
      </div>
      <div class="user-info">
        <div class="user-name">{{ auth()->guard($guard)->user()->name ?? ($guard === 'admin' ? 'Admin' : 'User') }}
        </div>
        <div class="user-role">{{ auth()->guard($guard)->user()->email ?? 'Member' }}</div>
      </div>
      <div class="user-status-dot"></div>
    </div>
  </div>
</aside>