@extends('admin.layout.app')

@section('title', 'Add Leads')

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
                <span class="breadcrumb-page" id="activePageLabel">Leads</span>
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
                    <h1 class="page-title">Your All Leads</h1>
                </div>
            </div>


            <!-- MAIN GRID -->
            <div class="dash-grid">


                <div class="dash-card span-12">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Lead Pipeline</div>
                            <div class="card-sub">147 total · 38 hot leads</div>
                        </div>
                        <div class="card-actions">
                            <select class="filter-select">
                                <option>All Sources</option>
                                <option>Website</option>
                                <option>Referral</option>
                                <option>LinkedIn</option>
                            </select>
                            <button class="btn-primary-solid sm" onclick="openModal('addLeadModal')"><i class="bi bi-plus-lg"></i> Add Lead</button>
                        </div>
                    </div>
                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Lead</th>
                                    <th>Source</th>
                                    <th>Contact Person</th>
                                    <th>Service Need</th>
                                    <th>Prioprity</th>
                                    <th>Status</th>
                                    <th>Created By</th>
                                    <th>Assign To</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="lead-cell">
                                            <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div>
                                            <div>
                                                <div class="ln">DataFirst Corp</div>
                                                <div class="ls">cto@datafirst.io</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="src-tag website">Website</span></td>
                                    <td><strong style="color:#10b981">Abhishek</strong></td>
                                    <td><strong style="color:#10b981">Website Design</strong></td>
                                    <td>
                                        <span class="lead-stage hot">Hot 🔥</span>
                                        <span class="lead-stage cold">Cold</span>
                                        <span class="lead-stage warm">Warm</span>
                                    </td>
                                    <td><strong style="color:#10b981">Respond</strong></td>
                                    <td>
                                        <div>
                                            <div class="ln">DataFirst Corp</div>
                                            <div class="ls">cto@datafirst.io</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="ln">DataFirst Corp</div>
                                            <div class="ls">cto@datafirst.io</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            <button class="ra-btn" onclick="openModal('leadDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                            <button class="ra-btn"><i class="bi bi-telephone-fill"></i></button>
                                            <button class="ra-btn" onclick="openModal('followupModal')"><i class="bi bi-arrow-counterclockwise"></i></button>
                                            <button class="ra-btn" onclick="openModal('editLeadModal')"><i class="bi bi-pencil-fill"></i></button>
                                            <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="table-footer">
                        <span class="tf-info">Showing 5 of 24 Leads</span>
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
        <div class="modal-backdrop" id="addLeadModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Add New Lead</span><button class="modal-close" onclick="closeModal('addLeadModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                        <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                        <div class="form-row"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>
                        <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                        <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>


                        <div class="form-row"><label class="form-lbl">Service Need</label><select class="form-inp">
                                <option>Web</option>
                                <option>Design</option>
                                <option>Mark</option>
                            </select>
                        </div>

                        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Lead Qualification</label><input class="form-inp" placeholder="Lead qualification"></div>

                        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Address</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>

                        <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp">
                                <option>Web</option>
                                <option>Linkedin</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="form-lbl">Lead Priority</label>
                            <select class="form-inp">
                                <option>Cold</option>
                                <option>Warm</option>
                                <option>Hot</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="form-lbl">Lead Status</label>
                            <select class="form-inp">
                                <option>Not Responding</option>
                                <option>Not Interested</option>
                                <option>Not Required</option>
                                <option>Location Isuue</option>
                                <option>Job</option>
                                <option>Not Inquired</option>
                                <option>Respond</option>
                                <option>Interested</option>
                                <option>Language Barrier</option>
                                <option>Booked</option>
                                <option>Budget Issue</option>
                            </select>
                        </div>

                        <div class="form-row"><label class="form-lbl">Assign To</label><select class="form-inp">
                                <option>Rahul Kumar</option>
                                <option>Priya Sharma</option>
                                <option>Neha Kapoor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('addLeadModal')">Cancel</button>
                    <button class="btn-primary-solid" onclick="closeModal('addLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Add Lead</button>
                </div>
            </div>
        </div>

        <!-- Edit Modal -->
        <div class="modal-backdrop" id="editLeadModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Update Lead</span><button class="modal-close" onclick="closeModal('editLeadModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Company *</label><input type="text" class="form-inp" placeholder="Company name"></div>
                        <div class="form-row"><label class="form-lbl">Contact Person *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                        <div class="form-row"><label class="form-lbl">Business Type *</label><input type="text" class="form-inp" placeholder="Business type"></div>
                        <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                        <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>


                        <div class="form-row"><label class="form-lbl">Service Need</label><select class="form-inp">
                                <option>Web</option>
                                <option>Design</option>
                                <option>Mark</option>
                            </select>
                        </div>

                        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Lead Qualification</label><input class="form-inp" placeholder="Lead qualification"></div>

                        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Address</label><textarea class="form-inp" rows="3" placeholder="Project scope and objectives…"></textarea></div>

                        <div class="form-row"><label class="form-lbl">Lead Source</label><select class="form-inp">
                                <option>Web</option>
                                <option>Linkedin</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="form-lbl">Lead Priority</label>
                            <select class="form-inp">
                                <option>Cold</option>
                                <option>Warm</option>
                                <option>Hot</option>
                            </select>
                        </div>

                        <div class="form-row">
                            <label class="form-lbl">Lead Status</label>
                            <select class="form-inp">
                                <option>Not Responding</option>
                                <option>Not Interested</option>
                                <option>Not Required</option>
                                <option>Location Isuue</option>
                                <option>Job</option>
                                <option>Not Inquired</option>
                                <option>Respond</option>
                                <option>Interested</option>
                                <option>Language Barrier</option>
                                <option>Booked</option>
                                <option>Budget Issue</option>
                            </select>
                        </div>

                        <div class="form-row"><label class="form-lbl">Assign To</label><select class="form-inp">
                                <option>Rahul Kumar</option>
                                <option>Priya Sharma</option>
                                <option>Neha Kapoor</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('editLeadModal')">Cancel</button>
                    <button class="btn-primary-solid" onclick="closeModal('editLeadModal');showToast('success','Lead added!','bi-person-check-fill')"><i class="bi bi-plus-lg"></i> Update Lead</button>
                </div>
            </div>
        </div>

        <!-- Delete Modal -->
        <div class="modal-backdrop" id="deleteModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                    <span style="color:#dc2626;">Delete Lead</span>
                    <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
                </div>
                <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                    <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                    </div>
                    <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                    <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Lead?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
                </div>
                <div class="modal-ft" style="border-top:1px solid #fecaca;">
                    <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                    <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteModal');showToast('success','Service Deleted!','bi-trash3-fill')">
                        <i class="bi bi-trash3-fill"></i> Delete Lead
                    </button>
                </div>
            </div>
        </div>

        <!-- Lead Detail -->
        <div class="modal-backdrop" id="leadDetailModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Lead Detail — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('leadDetailModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">
                    <div class="detail-kpis" style="margin-bottom:20px">
                        <div class="dk-item">
                            <div class="dk-val">12-02-2026</div>
                            <div class="dk-lbl">Created Date</div>
                        </div>
                        <div class="dk-item">
                            <div class="dk-val" style="color:#ef4444">Hot 🔥</div>
                            <div class="dk-lbl">Priority</div>
                        </div>
                        <div class="dk-item">
                            <div class="dk-val">Website</div>
                            <div class="dk-lbl">Service Need</div>
                        </div>
                        <div class="dk-item">
                            <div class="dk-val">Linkedin</div>
                            <div class="dk-lbl">Lead Source</div>
                        </div>
                    </div>
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Contact</label><input class="form-inp" value="Vikram Bhatia" readonly></div>
                        <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="vikram@techcorp.com" readonly></div>
                        <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="+91 98765 43210" readonly></div>
                        <div class="form-row">
                            <label class="form-lbl">Change Priority</label>
                            <select class="form-inp">
                                <option>Hot</option>
                                <option>Warm</option>
                                <option>Cold</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Change Lead Status</label>
                            <select class="form-inp">
                                <option>Not Responding</option>
                                <option>Not Interested</option>
                                <option>Not Required</option>
                                <option>Location Isuue</option>
                                <option>Job</option>
                                <option>Not Inquired</option>
                                <option>Respond</option>
                                <option>Interested</option>
                                <option>Language Barrier</option>
                                <option>Booked</option>
                                <option>Budget Issue</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Assign To</label>
                            <select class="form-inp">
                                <option>Rahul Kumar</option>
                                <option>Priya Sharma</option>
                                <option>Neha Kapoor</option>
                            </select>
                        </div>
                        <div class="form-row" style="grid-column:1/-1">
                            <label class="form-lbl">Convert Lead To</label>
                            <select class="form-inp">
                                <option>Order</option>
                                <option>Closed</option>
                            </select>
                        </div>
                        <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Add Remark/Note</label><textarea class="form-inp" rows="2" placeholder="Add note…"></textarea></div>
                    </div>
                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('leadDetailModal')">Close</button>
                    <button class="btn-primary-solid" onclick="closeModal('leadDetailModal');showToast('success','Lead updated!','bi-person-check-fill')">Update Lead</button>
                </div>
            </div>
        </div>

        <!-- Followup Modal -->
        <div class="modal-backdrop" id="followupModal">
            <div class="modal-box" onclick="event.stopPropagation()">
                <div class="modal-hd"><span>Add Followup — TechCorp Solutions</span><button class="modal-close" onclick="closeModal('followupModal')"><i class="bi bi-x-lg"></i></button></div>
                <div class="modal-bd">

                    <div class="form-row">
                        <label class="form-lbl">Followup Date *</label>
                        <input type="date" class="form-inp">
                    </div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Followup Note</label><textarea class="form-inp" rows="2" placeholder="Add follow-up note…"></textarea></div>

                    <!-- Followup History -->
                    <div style="margin-top:20px;margin-bottom:12px;">
                        <span style="font-size:14px;font-weight:600;color:var(--t1);">Followup History</span>
                    </div>

                    <div style="height: 300px; overflow-y: auto;">
                        <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                            <!-- Card Body -->
                            <div style="padding:16px;">

                                <!-- Date pill -->
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                        <i class="bi bi-clock" style="font-size:12px;color:var(--accent2);"></i>
                                        <span style="font-size:13px;font-weight:500;color:var(--t1);">12 Feb 2026</span>
                                    </div>
                                    <span style="font-size:12px;color:var(--t3);">Follow-up date</span>
                                </div>

                                <!-- Note -->
                                <div style="border-left:2px solid var(--accent);padding-left:12px;border-radius:0;">
                                    <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Note</p>
                                    <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                            <!-- Card Body -->
                            <div style="padding:16px;">

                                <!-- Date pill -->
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                        <i class="bi bi-clock" style="font-size:12px;color:var(--accent2);"></i>
                                        <span style="font-size:13px;font-weight:500;color:var(--t1);">12 Feb 2026</span>
                                    </div>
                                    <span style="font-size:12px;color:var(--t3);">Follow-up date</span>
                                </div>

                                <!-- Note -->
                                <div style="border-left:2px solid var(--accent);padding-left:12px;border-radius:0;">
                                    <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Note</p>
                                    <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                    </p>
                                </div>

                            </div>
                        </div>

                        <div style="background:var(--bg2);border:1px solid var(--b1);border-radius:var(--r);overflow:hidden; margin-bottom: 5px;">

                            <!-- Card Body -->
                            <div style="padding:16px;">

                                <!-- Date pill -->
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:14px;">
                                    <div style="display:flex;align-items:center;gap:6px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:6px 12px;">
                                        <i class="bi bi-clock" style="font-size:12px;color:var(--accent2);"></i>
                                        <span style="font-size:13px;font-weight:500;color:var(--t1);">12 Feb 2026</span>
                                    </div>
                                    <span style="font-size:12px;color:var(--t3);">Follow-up date</span>
                                </div>

                                <!-- Note -->
                                <div style="border-left:2px solid var(--accent);padding-left:12px;border-radius:0;">
                                    <p style="font-size:12px;font-weight:600;color:var(--t3);margin:0 0 4px;text-transform:uppercase;letter-spacing:0.05em;">Note</p>
                                    <p style="font-size:14px;color:var(--t2);margin:0;line-height:1.6;">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Quisquam, quae.
                                    </p>
                                </div>

                            </div>
                        </div>
                    </div>

                </div>
                <div class="modal-ft">
                    <button class="btn-ghost" onclick="closeModal('followupModal')">Cancel</button>
                    <button class="btn-primary-solid" onclick="closeModal('followupModal');showToast('success','Followup Added!','bi-person-check-fill')">
                        <i class="bi bi-plus-lg"></i> Update
                    </button>
                </div>
            </div>
        </div>

    </main>
</div>

@endsection