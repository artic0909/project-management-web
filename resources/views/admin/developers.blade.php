@extends('admin.layout.app')

@section('title', 'Add Developers')

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
                <span class="breadcrumb-page" id="activePageLabel">Developers</span>
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

        <div class="page" id="page-dashboard">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <h1 class="page-title">Your All Developers</h1>
                </div>
            </div>


            <!-- MAIN GRID -->
            <div class="dash-grid">


                <!-- Developer Table -->
                <div class="dash-card span-12">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Developers</div>
                            <div class="card-sub">147 total</div>
                        </div>
                        <div class="card-actions">
                            <button class="btn-primary-solid sm" onclick="openModal('addModal')"><i class="bi bi-plus-lg"></i> Add</button>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created By</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="lead-cell">
                                            <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">TC</div>
                                            <div>
                                                <div class="ln">TechCorp Solutions</div>
                                                <div class="ls">techcorp@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="src-tag website">techcorp@example.com</span></td>
                                    <td><strong style="color:#10b981">Admin</strong></td>
                                    <td><span class="lead-stage hot">Working</span></td>
                                    <td>
                                        <!-- Modal Btns -->
                                        <div class="row-actions">
                                            <button class="ra-btn"><i class="bi bi-power"></i></button>
                                            <button class="ra-btn" onclick="openModal('editModal')"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="ra-btn"><i class="bi bi-envelope-fill"></i></button>
                                            <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="table-footer">
                        <span class="tf-info">Showing 5 of 24 Developers</span>
                        <div class="tf-pagination">
                            <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                            <button class="pg-btn active">1</button>
                            <button class="pg-btn">2</button>
                            <button class="pg-btn">3</button>
                            <span class="pg-ellipsis">…</span>
                            <button class="pg-btn">5</button>
                            <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                        </div>
                        <div class="tf-per-page">
                            <!-- <span>Rows:</span>
                            <select>
                                <option>5</option>
                                <option>10</option>
                                <option>25</option>
                            </select> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Modal -->
        <div class="modal-backdrop" id="addModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Add Developer</span><button class="modal-close" onclick="closeModal('addModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Developer *</label><input type="text" class="form-inp" placeholder="Developer name"></div>
                        <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                        <div class="form-row"><label class="form-lbl">Set Password *</label><input type="text" class="form-inp" value="12345"></div>
                        <div class="form-row"><label class="form-lbl">Confirm Password *</label><input type="text" class="form-inp" value="12345"></div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('addModal')">Cancel</button>
                    <button class="btn-primary-solid" onclick="closeModal('addModal');showToast('success','Developer Added!','bi-person-check-fill')">
                        <i class="bi bi-plus-lg"></i> Add Developer
                    </button>
                </div>
            </div>
        </div>


        <!-- Edit Modal -->
        <div class="modal-backdrop" id="editModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Update Developer</span><button class="modal-close" onclick="closeModal('editModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Developer *</label><input type="text" class="form-inp" placeholder="Developer name"></div>
                        <div class="form-row"><label class="form-lbl">Email *</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                        <div class="form-row"><label class="form-lbl">Set Password *</label><input type="text" class="form-inp" value="12345"></div>
                        <div class="form-row"><label class="form-lbl">Confirm Password *</label><input type="text" class="form-inp" value="12345"></div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('editModal')">Cancel</button>
                    <button class="btn-primary-solid" onclick="closeModal('editModal');showToast('success','Developer Updated!','bi-person-check-fill')">
                        <i class="bi bi-plus-lg"></i> Update Developer
                    </button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal-backdrop" id="deleteModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                    <span style="color:#dc2626;">Delete Developer</span>
                    <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                    <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                    </div>
                    <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                    <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Developer?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
                </div>
                <div class="modal-ft" style="border-top:1px solid #fecaca;">
                    <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                    <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteModal');showToast('success','Developer Deleted!','bi-trash3-fill')">
                        <i class="bi bi-trash3-fill"></i> Delete Developer
                    </button>
                </div>
            </div>
        </div>

    </main>
</div>

@endsection