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
            <div class="notif-body"><strong>Project Orion v2</strong> deadline in 2 days<div class="notif-time">5 min ago</div></div>
          </div>
          <div class="notif-item unread">
            <div class="notif-icon green"><i class="bi bi-currency-rupee"></i></div>
            <div class="notif-body"><strong>₹2.4L order</strong> received from TechCorp<div class="notif-time">22 min ago</div></div>
          </div>
          <div class="notif-item unread">
            <div class="notif-icon orange"><i class="bi bi-person-fill"></i></div>
            <div class="notif-body"><strong>Priya Sharma</strong> marked attendance late<div class="notif-time">1 hr ago</div></div>
          </div>
          <div class="notif-item">
            <div class="notif-icon purple"><i class="bi bi-code-slash"></i></div>
            <div class="notif-body"><strong>Sprint 12</strong> review completed<div class="notif-time">3 hrs ago</div></div>
          </div>
          <div class="notif-item">
            <div class="notif-icon red"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="notif-body"><strong>API Gateway</strong> response time elevated<div class="notif-time">Yesterday</div></div>
          </div>
        </div>
        <div class="notif-footer"><a href="#">View all notifications →</a></div>
      </div>

      <div class="tb-btn" data-tooltip="Messages">
        <i class="bi bi-chat-dots-fill"></i>
      </div>
      <div class="tb-divider"></div>
      <div class="tb-user" onclick="toggleUserMenu()">
        <div class="user-ava sm" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</div>
        <span class="tb-user-name">{{ Auth::user()->name ?? 'User' }}</span>
        <i class="bi bi-chevron-down" style="font-size:10px;color:var(--t3)"></i>
      </div>
      <div class="user-menu" id="userMenu">
        <a class="um-item" href="#"><i class="bi bi-person-fill"></i> My Profile</a>
        <a class="um-item" href="#" onclick="navigate(event,'settings')"><i class="bi bi-gear-fill"></i> Settings</a>
        <a class="um-item" href="#"><i class="bi bi-shield-check"></i> Security</a>
        <hr class="um-divider">
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
        </form>
        <a class="um-item danger" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="bi bi-box-arrow-right"></i> Sign Out
        </a>
      </div>
    </div>
  </header>