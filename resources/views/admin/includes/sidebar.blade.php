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
    $isLeadsActive = request()->routeIs($routePrefix . 'leads*') && !request()->routeIs($routePrefix . 'leads.followup');
    $isLostedLeadsActive = request()->routeIs($routePrefix . 'losted-leads') || request()->routeIs($routePrefix . 'losted-leads.show');

    if (request()->routeIs($routePrefix . 'leads.followup') && isset($model) && $model->is_losted) {
        $isLostedLeadsActive = true;
    }

    if ($isLostedLeadsActive) {
        $isLeadsActive = true;
    }

    $isFollowupsActive = request()->routeIs($routePrefix . 'leads.index') && in_array(request('type'), ['followup_today', 'followup_pending', 'followup_future']);
    if ($isFollowupsActive) {
        $isLeadsActive = false;
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
        @php
          $isUtilitiesActive = request()->routeIs('admin.sources*') || request()->routeIs('admin.services*') || request()->routeIs('admin.plans*') || request()->routeIs('admin.campaign*') || request()->routeIs('admin.status*');
        @endphp
        <div class="nav-dropdown {{ $isUtilitiesActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isUtilitiesActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-flag-fill"></i>
            <span>Utilities</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isUtilitiesActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isUtilitiesActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.sources*') ? 'active' : '' }}"
              href="{{ route('admin.sources.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-broadcast" style="font-size: 13px;"></i><span>Sources</span>
              <span class="nav-count">{{ $sourceCount }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.services*') ? 'active' : '' }}"
              href="{{ route('admin.services.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-briefcase-fill" style="font-size: 13px;"></i><span>Services</span>
              <span class="nav-count">{{ $serviceCount }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.plans*') ? 'active' : '' }}"
              href="{{ route('admin.plans.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-layers-half" style="font-size: 13px;"></i><span>Plans</span>
              <span class="nav-count">{{ $planCount }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.campaign*') ? 'active' : '' }}"
              href="{{ route('admin.campaign.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-megaphone-fill" style="font-size: 13px;"></i><span>Campaign</span>
              <span class="nav-count">{{ $campaignCount }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.status*') ? 'active' : '' }}"
              href="{{ route('admin.status') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-flag-fill" style="font-size: 13px;"></i><span>All Status</span>
              <span class="nav-count">{{ $statusCount }}</span>
            </a>
          </div>
        </div>
      @endif

      <div class="nav-section-label">Business</div>
      @if($guard === 'sale')
        <div class="nav-dropdown {{ $isLeadsActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isLeadsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-person-lines-fill"></i>
            <span>Leads</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isLeadsActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isLeadsActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ ($isLeadsActive && request('type') === 'new') ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'new']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-plus-circle" style="font-size: 13px;"></i><span>New Leads</span>
              <span class="nav-count">{{ $newLeadCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isLeadsActive && (request('type') === 'my' || !request('type'))) ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'my']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-person" style="font-size: 13px;"></i><span>My Leads</span>
              <span class="nav-count">{{ $myLeadCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isLeadsActive && request('type') === 'total') ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'total']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-collection" style="font-size: 13px;"></i><span>Total Leads</span>
              <span class="nav-count">{{ $totalLeadCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ $isLostedLeadsActive ? 'active' : '' }}"
              href="{{ route('sale.losted-leads') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-ban" style="font-size: 13px;"></i><span>Losted Leads</span>
              <span class="nav-count">{{ $lostLeadCount ?? 0 }}</span>
            </a>
          </div>
        </div>

        <div class="nav-dropdown {{ $isFollowupsActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isFollowupsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-calendar2-check"></i>
            <span>Lead Followups</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isFollowupsActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isFollowupsActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_today') ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'followup_today']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-calendar-event" style="font-size: 13px;"></i><span>Today Followup</span>
              <span class="nav-count">{{ $todayFollowupCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_pending') ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'followup_pending']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-clock-history" style="font-size: 13px;"></i><span>Pending Followup</span>
              <span class="nav-count">{{ $pendingFollowupCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_future') ? 'active' : '' }}"
              href="{{ route('sale.leads.index', ['type' => 'followup_future']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-calendar-plus" style="font-size: 13px;"></i><span>Future Followup</span>
              <span class="nav-count">{{ $futureFollowupCount ?? 0 }}</span>
            </a>
          </div>
        </div>
      @elseif($guard === 'admin')
        <div class="nav-dropdown {{ $isLeadsActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isLeadsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-person-lines-fill"></i>
            <span>Leads</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isLeadsActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isLeadsActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ ($isLeadsActive && request('type') === 'new') ? 'active' : '' }}"
              href="{{ route('admin.leads.index', ['type' => 'new']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-plus-circle" style="font-size: 13px;"></i><span>New Leads</span>
              <span class="nav-count">{{ $newLeadCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isLeadsActive && (request('type') === 'total' || (!request('type') && !$isLostedLeadsActive))) ? 'active' : '' }}"
              href="{{ route('admin.leads.index', ['type' => 'total']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-collection" style="font-size: 13px;"></i><span>Total Leads</span>
              <span class="nav-count">{{ $totalLeadCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ $isLostedLeadsActive ? 'active' : '' }}"
              href="{{ route('admin.losted-leads') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-ban" style="font-size: 13px;"></i><span>Losted Leads</span>
              <span class="nav-count">{{ $lostLeadCount ?? 0 }}</span>
            </a>
          </div>
        </div>

        <div class="nav-dropdown {{ $isFollowupsActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isFollowupsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-calendar2-check"></i>
            <span>Lead Followups</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isFollowupsActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isFollowupsActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_today') ? 'active' : '' }}"
              href="{{ route('admin.leads.index', ['type' => 'followup_today']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-calendar-event" style="font-size: 13px;"></i><span>Today Followup</span>
              <span class="nav-count">{{ $todayFollowupCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_pending') ? 'active' : '' }}"
              href="{{ route('admin.leads.index', ['type' => 'followup_pending']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-clock-history" style="font-size: 13px;"></i><span>Pending Followup</span>
              <span class="nav-count">{{ $pendingFollowupCount ?? 0 }}</span>
            </a>
            <a class="nav-item nav-sub-item {{ ($isFollowupsActive && request('type') === 'followup_future') ? 'active' : '' }}"
              href="{{ route('admin.leads.index', ['type' => 'followup_future']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-calendar-plus" style="font-size: 13px;"></i><span>Future Followup</span>
              <span class="nav-count">{{ $futureFollowupCount ?? 0 }}</span>
            </a>
          </div>
        </div>
      @else
        <a class="nav-item {{ $isLeadsActive ? 'active' : '' }}"
          href="{{ route($routePrefix . 'leads.index') }}">
          <i class="bi bi-person-lines-fill"></i><span>Leads</span>
          <span class="nav-count">{{ $leadCount }}</span>
        </a>
      @endif
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

      @php
        $isProjectsActive = request()->routeIs($routePrefix . 'projects*');
        $isCompleteProjects = request('status') === 'complete';
        $isActiveProjects = !$isCompleteProjects;
      @endphp
      <div class="nav-dropdown {{ $isProjectsActive ? 'open' : '' }}">
        <a class="nav-item nav-dropdown-toggle {{ $isProjectsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
          <i class="bi bi-kanban-fill"></i>
          <span>{{ $guard === 'sale' ? 'My Projects' : 'Projects' }}</span>
          <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isProjectsActive ? 'transform: rotate(180deg);' : '' }}"></i>
        </a>
        <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isProjectsActive ? 'display: block;' : 'display: none;' }}">

          <a class="nav-item nav-sub-item {{ ($isProjectsActive && request('status') === 'active') ? 'active' : '' }}"
            href="{{ route($routePrefix . 'projects.index', ['status' => 'active']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-kanban" style="font-size: 13px;"></i><span>Active Projects</span>
            <span class="nav-count">{{ $activeProjectCount ?? 0 }}</span>
          </a>
          <a class="nav-item nav-sub-item {{ ($isProjectsActive && $isCompleteProjects) ? 'active' : '' }}"
            href="{{ route($routePrefix . 'projects.index', ['status' => 'complete']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-check2-circle" style="font-size: 13px;"></i><span>Complete Projects</span>
            <span class="nav-count">{{ $completeProjectCount ?? 0 }}</span>
          </a>

                    <a class="nav-item nav-sub-item {{ ($isProjectsActive && !request('status')) ? 'active' : '' }}"
            href="{{ route($routePrefix . 'projects.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-collection" style="font-size: 13px;"></i><span>All Projects</span>
            <span class="nav-count">{{ $projectCount ?? 0 }}</span>
          </a>
        </div>
      </div>


      @php
        $isPaymentsActive = request()->routeIs($routePrefix . 'payments*') || request()->routeIs($routePrefix . 'invoices*');
      @endphp
      <div class="nav-dropdown {{ $isPaymentsActive ? 'open' : '' }}">
        <a class="nav-item nav-dropdown-toggle {{ $isPaymentsActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
          <i class="bi bi-wallet2"></i>
          <span>Payments & Invoices</span>
          <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isPaymentsActive ? 'transform: rotate(180deg);' : '' }}"></i>
        </a>
        <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isPaymentsActive ? 'display: block;' : 'display: none;' }}">
          
          <a class="nav-item nav-sub-item {{ request()->routeIs($routePrefix . 'payments*') ? 'active' : '' }}"
            href="{{ route($routePrefix . 'payments.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-wallet" style="font-size: 13px;"></i><span>Payments</span>
          </a>

          @if($guard === 'admin')
          <a class="nav-item nav-sub-item {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}"
            href="{{ route('admin.invoices.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-receipt" style="font-size: 13px;"></i><span>Invoices</span>
            <span class="nav-count">{{ $invoiceCount }}</span>
          </a>
          @elseif($guard === 'sale')
          <a class="nav-item nav-sub-item {{ request()->routeIs('sale.invoices*') ? 'active' : '' }}"
            href="{{ route('sale.invoices.index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-receipt" style="font-size: 13px;"></i><span>Invoices</span>
          </a>
          @endif
        </div>
      </div>




      <a class="nav-item {{ request()->routeIs($routePrefix . 'meetings*') ? 'active' : '' }}"
        href="{{ route($routePrefix . 'meetings.index') }}">
        <i class="bi bi-camera-video-fill"></i><span>Meetings</span>
        <span class="nav-count">{{ $meetingCount }}</span>
      </a>

      @if($guard === 'admin')
      <div class="nav-section-label">Team Members</div>
      @php
        $isTeamMembersActive = request()->routeIs('admin.sales-person*') || request()->routeIs('admin.developer*');
      @endphp
      <div class="nav-dropdown {{ $isTeamMembersActive ? 'open' : '' }}">
        <a class="nav-item nav-dropdown-toggle {{ $isTeamMembersActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
          <i class="bi bi-people-fill"></i>
          <span>Team Members</span>
          <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isTeamMembersActive ? 'transform: rotate(180deg);' : '' }}"></i>
        </a>
        <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isTeamMembersActive ? 'display: block;' : 'display: none;' }}">
          <a class="nav-item nav-sub-item {{ request()->routeIs('admin.sales-person*') ? 'active' : '' }}"
            href="{{ route('admin.sales-person') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-people" style="font-size: 13px;"></i><span>Sales Person</span>
            <span class="nav-count">{{ $salesPersonCount }}</span>
          </a>
          <a class="nav-item nav-sub-item {{ request()->routeIs('admin.developer*') ? 'active' : '' }}"
            href="{{ route('admin.developer') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
            <i class="bi bi-person-workspace" style="font-size: 13px;"></i><span>Developers</span>
            <span class="nav-count">{{ $developerCount }}</span>
          </a>
        </div>
      </div>
      @endif
      @if ($guard === 'admin')
        <div class="nav-section-label">Attendance</div>
        @php
          $isAttendanceActive = request()->routeIs('admin.attendance.sale-index') || request()->routeIs('admin.attendance.dev-index');
        @endphp
        <div class="nav-dropdown {{ $isAttendanceActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isAttendanceActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-person-badge-fill"></i>
            <span>Attendance</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isAttendanceActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isAttendanceActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.attendance.sale-index') ? 'active' : '' }}"
              href="{{ route('admin.attendance.sale-index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-person-badge" style="font-size: 13px;"></i><span>Sale Attendance</span>
            </a>
            <a class="nav-item nav-sub-item {{ request()->routeIs('admin.attendance.dev-index') ? 'active' : '' }}"
              href="{{ route('admin.attendance.dev-index') }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-calendar-check" style="font-size: 13px;"></i><span>Dev Attendance</span>
            </a>
          </div>
        </div>
      @else
        <a class="nav-item {{ request()->routeIs($routePrefix . 'attendance*') ? 'active' : '' }}"
          href="{{ route($routePrefix . 'attendance.index') }}">
          <i class="bi bi-clock-history"></i><span>My Attendances</span>
          <div class="nav-dot green"></div>
        </a>
      @endif
      

      <div class="nav-section-label">Others</div>
      @if($guard === 'admin' || $guard === 'sale')
        @php
          $isInquiryActive = request()->routeIs($routePrefix . 'inquiry*');
        @endphp
        <div class="nav-dropdown {{ $isInquiryActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isInquiryActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-chat-left-text-fill"></i>
            <span>Order Inquiries</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isInquiryActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isInquiryActive ? 'display: block;' : 'display: none;' }}">
            <a class="nav-item nav-sub-item {{ request('filter') == 'new' ? 'active' : '' }}"
               href="{{ route($routePrefix . 'inquiry.index', ['filter' => 'new']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-envelope-plus" style="font-size: 13px;"></i><span>New Inquiries</span>
              <span class="nav-count">{{ $newInquiryCount ?? 0 }}</span>
            </a>
            
            @if($guard === 'sale')
            <a class="nav-item nav-sub-item {{ request('filter') == 'my' ? 'active' : '' }}"
               href="{{ route($routePrefix . 'inquiry.index', ['filter' => 'my']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-person-badge" style="font-size: 13px;"></i><span>My Inquiries</span>
              <span class="nav-count">{{ $myInquiryCount ?? 0 }}</span>
            </a>
            @endif

            @if($guard === 'admin')
            <a class="nav-item nav-sub-item {{ request('filter') == 'total' ? 'active' : '' }}"
               href="{{ route($routePrefix . 'inquiry.index', ['filter' => 'total']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-check2-all" style="font-size: 13px;"></i><span>Total Inquiries</span>
              <span class="nav-count">{{ $totalInquiryCount ?? 0 }}</span>
            </a>
            @endif
          </div>
        </div>
      @endif

      <!-- Notes -->
      @if($guard === 'admin' || $guard === 'sale')
        <a class="nav-item {{ request()->routeIs($routePrefix . 'notes*') ? 'active' : '' }}" href="{{ route($routePrefix . 'notes.index') }}">
          <i class="bi bi-sticky-fill"></i><span>Notes</span>
          <span class="nav-count">{{ $noteCount ?? 0 }}</span>
        </a>
      @endif

      @if($guard === 'admin')
        @php
          $isSupportActive = request()->routeIs('admin.supports*');
          $isClosedSupport = request('status') === 'closed';
        @endphp
        <div class="nav-dropdown {{ $isSupportActive ? 'open' : '' }}">
          <a class="nav-item nav-dropdown-toggle {{ $isSupportActive ? 'active' : '' }}" href="javascript:void(0)" onclick="toggleNavDropdown(this)">
            <i class="bi bi-headset"></i>
            <span>Support</span>
            <i class="bi bi-chevron-down nav-dropdown-chevron" style="margin-left: auto; font-size: 11px; transition: transform 0.2s ease; {{ $isSupportActive ? 'transform: rotate(180deg);' : '' }}"></i>
          </a>
          <div class="nav-dropdown-menu" style="padding-left: 14px; {{ $isSupportActive ? 'display: block;' : 'display: none;' }}">
            
            <a class="nav-item nav-sub-item {{ ($isSupportActive && request('status') !== 'closed') ? 'active' : '' }}"
              href="{{ route('admin.supports.index', ['status' => 'active']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-activity" style="font-size: 13px;"></i><span>Active</span>
              <span class="nav-count">{{ $supportActiveCount ?? 0 }}</span>
            </a>

            <a class="nav-item nav-sub-item {{ ($isSupportActive && $isClosedSupport) ? 'active' : '' }}"
              href="{{ route('admin.supports.index', ['status' => 'closed']) }}" style="font-size: 12.5px; padding: 6px 10px; margin-top: 2px;">
              <i class="bi bi-check2-circle" style="font-size: 13px;"></i><span>Closed</span>
              <span class="nav-count">{{ $supportClosedCount ?? 0 }}</span>
            </a>

          </div>
        </div>
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