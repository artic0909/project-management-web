@extends('sale.layout.app')

@section('title', 'All Projects')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-projects">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">All Projects</h1>
                <p class="page-desc">Manage website and development projects</p>
            </div>
            <div class="header-actions">
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>
                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>
                <a href="{{ route('sale.projects.create') }}" class="btn-primary-solid">
                    <i class="bi bi-plus-lg"></i> Add Project
                </a>
            </div>
        </div>

        {{-- ── KPI CARDS ── --}}
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:22px;">

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(99,102,241,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-kanban-fill" style="font-size:17px;color:#6366f1;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(99,102,241,.1);color:#818cf8;">Total</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">64</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Total Projects</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:80%;background:#6366f1;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(6,182,212,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-play-circle-fill" style="font-size:17px;color:#06b6d4;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(6,182,212,.1);color:#06b6d4;">Active</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">28</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">In Progress</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:44%;background:#06b6d4;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-check-circle-fill" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Done</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">21</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Completed</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:33%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.13);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-pause-circle-fill" style="font-size:17px;color:#f59e0b;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(245,158,11,.1);color:#f59e0b;">Hold</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">8</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">On Hold</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:12%;background:#f59e0b;border-radius:3px;"></div>
                </div>
            </div>

        </div>

        <!-- {{-- ── STATUS FILTER SCROLL ── --}}
        <div class="stat-scroll-row" style="margin-bottom:20px;">

            <span class="stat-section-lbl">Status</span>

            <div class="stat-box active" style="--sb-color:#6366f1;" data-filter="status" data-value="all" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-grid-fill"></i></div>
                <div>
                    <div class="sb-val">64</div>
                    <div class="sb-lbl">All</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#f59e0b;" data-filter="status" data-value="new" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-star-fill"></i></div>
                <div>
                    <div class="sb-val">7</div>
                    <div class="sb-lbl">New</div>
                </div>
            </div> 
            <div class="stat-box" style="--sb-color:#06b6d4;" data-filter="status" data-value="design" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-palette-fill"></i></div>
                <div>
                    <div class="sb-val">11</div>
                    <div class="sb-lbl">Design Phase</div>
                </div>
            </div> 
            <div class="stat-box" style="--sb-color:#6366f1;" data-filter="status" data-value="development" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-code-slash"></i></div>
                <div>
                    <div class="sb-val">17</div>
                    <div class="sb-lbl">Development</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#8b5cf6;" data-filter="status" data-value="testing" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-bug-fill"></i></div>
                <div>
                    <div class="sb-val">6</div>
                    <div class="sb-lbl">Testing</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#10b981;" data-filter="status" data-value="completed" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-check-circle-fill"></i></div>
                <div>
                    <div class="sb-val">21</div>
                    <div class="sb-lbl">Completed</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#64748b;" data-filter="status" data-value="on-hold" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-pause-circle-fill"></i></div>
                <div>
                    <div class="sb-val">8</div>
                    <div class="sb-lbl">On Hold</div>
                </div>
            </div>

            <div class="stat-divider"></div>
            <span class="stat-section-lbl">Payment</span>

            <div class="stat-box" style="--sb-color:#10b981;" data-filter="payment" data-value="paid" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-check2-circle"></i></div>
                <div>
                    <div class="sb-val" style="color:#10b981;">34</div>
                    <div class="sb-lbl">Paid</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#f59e0b;" data-filter="payment" data-value="partial" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-pie-chart-fill"></i></div>
                <div>
                    <div class="sb-val" style="color:#f59e0b;">19</div>
                    <div class="sb-lbl">Partial</div>
                </div>
            </div>
            <div class="stat-box" style="--sb-color:#ef4444;" data-filter="payment" data-value="pending" onclick="filterProjects(this)">
                <div class="sb-icon"><i class="bi bi-clock-fill"></i></div>
                <div>
                    <div class="sb-val" style="color:#ef4444;">11</div>
                    <div class="sb-lbl">Pending</div>
                </div>
            </div>

        </div> -->

        {{-- ── TABLE ── --}}
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Project Pipeline</div>
                        <div class="card-sub" id="projectTableSub">64 total projects</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search projects...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </form>
                        <select class="filter-select">
                            <option>All CMS</option>
                            <option>WordPress</option>
                            <option>Shopify</option>
                            <option>Custom</option>
                        </select>
                        <select class="filter-select">
                            <option>All Status</option>
                            <option>New</option>
                            <option>Design Phase</option>
                            <option>Development</option>
                            <option>Testing</option>
                            <option>Completed</option>
                            <option>On Hold</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="projectsTable">
                        <thead>
                            <tr>
                                <th>Project ID</th>
                                <th>Project / Domain</th>
                                <th>Client</th>
                                <th>CMS</th>
                                <th>Start Date</th>
                                <th>Delivery</th>
                                <th>Project Status</th>
                                <th>Project Price</th>
                                <th>Advance</th>
                                <th>Remaining</th>
                                <th>Payment</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <tr data-status="development" data-payment="partial">
                                <td><span class="mono">#PRJ-0041</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">NT</div>
                                        <div>
                                            <div class="ln">novatech.io</div>
                                            <div class="ls">NovaTech Solutions</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Anita Verma</div>
                                    <div class="ls">9812345678</div>
                                </td>
                                <td><span class="cms-tag wordpress">WordPress</span></td>
                                <td>
                                    <div class="ls">15 Jan 2026</div>
                                </td>
                                <td><span class="date-cell warn">15 Apr 2026</span></td>
                                <td><span class="proj-status development">Development</span></td>
                                <td><span class="money-cell">₹2,40,000</span></td>
                                <td><span class="money-cell" style="color:#10b981;">₹1,20,000</span></td>
                                <td><span class="money-cell" style="color:#ef4444;">₹1,20,000</span></td>
                                <td><span class="status-pill pending">Partial</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('sale.projects.show', 1) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <button class="ra-btn" title="Edit" onclick="openModal('editProjectModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" title="Delete" onclick="openModal('deleteProjectModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <tr data-status="completed" data-payment="paid">
                                <td><span class="mono">#PRJ-0038</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div>
                                        <div>
                                            <div class="ln">datafirst.io</div>
                                            <div class="ls">DataFirst Corp</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Raj Sharma</div>
                                    <div class="ls">9565528200</div>
                                </td>
                                <td><span class="cms-tag shopify">Shopify</span></td>
                                <td>
                                    <div class="ls">10 Nov 2025</div>
                                </td>
                                <td><span class="date-cell ok">10 Feb 2026</span></td>
                                <td><span class="proj-status completed">Completed</span></td>
                                <td><span class="money-cell">₹1,80,000</span></td>
                                <td><span class="money-cell" style="color:#10b981;">₹1,80,000</span></td>
                                <td><span class="money-cell" style="color:#10b981;">₹0</span></td>
                                <td><span class="status-pill paid">Paid</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('sale.projects.show', 1) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <button class="ra-btn" title="Edit" onclick="openModal('editProjectModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" title="Delete" onclick="openModal('deleteProjectModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <tr data-status="design" data-payment="pending">
                                <td><span class="mono">#PRJ-0044</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">AV</div>
                                        <div>
                                            <div class="ln">avantika.store</div>
                                            <div class="ls">Avantika Retail</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Priya Nair</div>
                                    <div class="ls">9871234560</div>
                                </td>
                                <td><span class="cms-tag custom">Custom</span></td>
                                <td>
                                    <div class="ls">01 Mar 2026</div>
                                </td>
                                <td><span class="date-cell danger">30 Apr 2026</span></td>
                                <td><span class="proj-status design">Design Phase</span></td>
                                <td><span class="money-cell">₹3,20,000</span></td>
                                <td><span class="money-cell" style="color:#f59e0b;">₹0</span></td>
                                <td><span class="money-cell" style="color:#ef4444;">₹3,20,000</span></td>
                                <td><span class="status-pill overdue">Pending</span></td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('sale.projects.show', 1) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <button class="ra-btn" title="Edit" onclick="openModal('editProjectModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" title="Delete" onclick="openModal('deleteProjectModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info" id="projCount">Showing 3 of 64 Projects</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <span class="pg-ellipsis">…</span>
                        <button class="pg-btn">7</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- ── EDIT MODAL ── --}}
    <div class="modal-backdrop" id="editProjectModal">
        <div class="modal-box modal-box-lg" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Edit Project</span>
                <button class="modal-close" onclick="closeModal('editProjectModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">

                {{-- Basic Info --}}
                <div class="proj-section-lbl"><i class="bi bi-info-circle-fill"></i> Basic Information</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Name / Domain *</label><input type="text" class="form-inp" placeholder="e.g. novatech.io"></div>
                    <div class="form-row"><label class="form-lbl">Client Name *</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
                    <div class="form-row"><label class="form-lbl">Company Name</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Starting Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Plan Name</label><input type="text" class="form-inp" placeholder="e.g. dynamick"></div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Username</label><input type="text" class="form-inp" placeholder="Account username"></div>
                    <div class="form-row"><label class="form-lbl">Password</label><input type="text" class="form-inp" placeholder="Account password"></div>
                    <div class="form-row"><label class="form-lbl">No. of Mail IDs</label><input type="number" class="form-inp" placeholder="e.g. 5"></div>
                    <div class="form-row"><label class="form-lbl">Mail Password</label><input type="text" class="form-inp" placeholder="Mail password"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Domain, Server Book</label><input type="text" class="form-inp" placeholder="Domain & server info"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Full Address</label><textarea class="form-inp" rows="2" placeholder="Full address…"></textarea></div>
                </div>

                {{-- Website Project Details --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-globe2"></i> Website Project Details</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Domain Name</label><input type="text" class="form-inp" placeholder="example.com"></div>
                    <div class="form-row"><label class="form-lbl">Hosting Provider</label><input type="text" class="form-inp" placeholder="Hostinger, GoDaddy…"></div>
                    <div class="form-row">
                        <label class="form-lbl">CMS / Platform</label>
                        <select class="form-inp">
                            <option>WordPress</option>
                            <option>Shopify</option>
                            <option>Custom</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Number of Pages</label><input type="number" class="form-inp" placeholder="e.g. 10"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Required Features</label><textarea class="form-inp" rows="2" placeholder="Login, payment gateway, blog…"></textarea></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Reference Websites</label><input type="text" class="form-inp" placeholder="https://…"></div>
                    <div class="form-row">
                        <label class="form-lbl">Website Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                </div>

                {{-- Project Timeline --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-calendar3"></i> Project Timeline</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Start Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Expected Delivery Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Actual Delivery Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row">
                        <label class="form-lbl">Project Status</label>
                        <select class="form-inp">
                            <option>New</option>
                            <option>Design Phase</option>
                            <option>Development</option>
                            <option>Testing</option>
                            <option>Completed</option>
                            <option>On Hold</option>
                        </select>
                    </div>
                </div>

                {{-- Financial --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-currency-rupee"></i> Financial Fields</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Price *</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
                    <div class="form-row"><label class="form-lbl">Advance Payment</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
                    <div class="form-row"><label class="form-lbl">Remaining Amount</label><input type="text" class="form-inp" placeholder="₹ Amount" readonly></div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Invoice Number</label><input type="text" class="form-inp" placeholder="INV-XXXX"></div>
                </div>

                {{-- Communication --}}
                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-chat-dots-fill"></i> Communication & Tracking</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Last Update Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Client Feedback</label><input type="text" class="form-inp" placeholder="Client feedback summary"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Internal Notes</label><textarea class="form-inp" rows="3" placeholder="Internal notes visible only to team…"></textarea></div>
                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editProjectModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editProjectModal');showToast('success','Project updated!','bi-kanban-fill')">
                    <i class="bi bi-floppy-fill"></i> Update Project
                </button>
            </div>
        </div>
    </div>


    {{-- ── DELETE MODAL ── --}}
    <div class="modal-backdrop" id="deleteProjectModal">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Project</span>
                <button class="modal-close" onclick="closeModal('deleteProjectModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">This project will be permanently deleted.<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteProjectModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="closeModal('deleteProjectModal');showToast('success','Project deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete
                </button>
            </div>
        </div>
    </div>

</main>

<style>
    /* Stat scroll */
    .stat-scroll-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: none;
    }

    .stat-scroll-row::-webkit-scrollbar {
        display: none;
    }

    .stat-box {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 11px 16px;
        min-width: 140px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sb-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }

    .stat-box:hover {
        border-color: var(--sb-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
    }

    .stat-box:hover::after,
    .stat-box.active::after {
        transform: scaleX(1);
    }

    .stat-box.active {
        border-color: var(--sb-color);
        background: var(--bg3);
    }

    .sb-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--sb-color) 14%, transparent);
        color: var(--sb-color);
    }

    .sb-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.4px;
        line-height: 1;
    }

    .sb-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 500;
        margin-top: 2px;
        white-space: nowrap;
    }

    .stat-section-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--t4);
        padding: 0 6px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: var(--b2);
        flex-shrink: 0;
        margin: 0 4px;
    }

    /* CMS tags */
    .cms-tag {
        font-size: 10.5px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 5px;
    }

    .cms-tag.wordpress {
        background: rgba(33, 117, 155, .12);
        color: #21759b;
    }

    .cms-tag.shopify {
        background: rgba(150, 191, 71, .12);
        color: #96bf47;
    }

    .cms-tag.custom {
        background: rgba(245, 158, 11, .12);
        color: #f59e0b;
    }

    /* Project status pills */
    .proj-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 9px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .proj-status::before {
        content: '';
        width: 5px;
        height: 5px;
        border-radius: 50%;
    }

    .proj-status.new {
        background: rgba(245, 158, 11, .12);
        color: #f59e0b;
    }

    .proj-status.new::before {
        background: #f59e0b;
    }

    .proj-status.design {
        background: rgba(6, 182, 212, .12);
        color: #06b6d4;
    }

    .proj-status.design::before {
        background: #06b6d4;
    }

    .proj-status.development {
        background: rgba(99, 102, 241, .12);
        color: #6366f1;
    }

    .proj-status.development::before {
        background: #6366f1;
    }

    .proj-status.testing {
        background: rgba(139, 92, 246, .12);
        color: #8b5cf6;
    }

    .proj-status.testing::before {
        background: #8b5cf6;
    }

    .proj-status.completed {
        background: rgba(16, 185, 129, .12);
        color: #10b981;
    }

    .proj-status.completed::before {
        background: #10b981;
    }

    .proj-status.on-hold {
        background: rgba(100, 116, 139, .12);
        color: #64748b;
    }

    .proj-status.on-hold::before {
        background: #64748b;
    }

    /* Section labels in modal */
    .proj-section-lbl {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .08em;
        color: var(--accent);
        background: var(--accent-bg);
        padding: 8px 12px;
        border-radius: var(--r-sm);
        margin-bottom: 12px;
    }

    /* Wide modal */
    .modal-box-lg {
        max-width: 780px !important;
        width: 92vw !important;
    }
</style>

<script>
    function filterProjects(el) {
        const group = el.dataset.filter;
        document.querySelectorAll(`.stat-box[data-filter="${group}"]`).forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        const val = el.dataset.value;
        const rows = document.querySelectorAll('#projectsTable tbody tr');
        rows.forEach(row => {
            let show = true;
            if (group === 'status' && val !== 'all') show = row.dataset.status === val;
            else if (group === 'payment') show = row.dataset.payment === val;
            row.style.display = show ? '' : 'none';
        });
        const visible = [...rows].filter(r => r.style.display !== 'none').length;
        document.getElementById('projCount').textContent = `Showing ${visible} of 64 Projects`;
    }
</script>

@endsection