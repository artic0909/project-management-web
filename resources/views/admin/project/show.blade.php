@extends('admin.layout.app')

@section('title', 'Project Detail')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-project-view">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('admin.projects.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Projects
                    </a>
                    <span style="color:var(--t4);font-size:11px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">novatech.io</span>
                </div>
                <h1 class="page-title">novatech.io</h1>
                <p class="page-desc">Project <span class="mono">#PRJ-0041</span> — NovaTech Solutions</p>
            </div>
            <div class="header-actions">
                <button class="btn-ghost" onclick="openModal('editProjectModal')">
                    <i class="bi bi-pencil-fill"></i> Edit Project
                </button>
                <button class="btn-ghost danger-ghost" onclick="openModal('deleteProjectModal')">
                    <i class="bi bi-trash-fill"></i> Delete
                </button>
            </div>
        </div>

        {{-- ── TOP KPI STRIP ── --}}
        <div class="detail-kpis" style="margin-bottom:22px;grid-template-columns:repeat(6,1fr);">
            <div class="dk-item">
                <div class="dk-val">#PRJ-0041</div>
                <div class="dk-lbl">Project ID</div>
            </div>
            <div class="dk-item">
                <div class="dk-val" style="color:#6366f1;">Development</div>
                <div class="dk-lbl">Status</div>
            </div>
            <div class="dk-item">
                <div class="dk-val">WordPress</div>
                <div class="dk-lbl">CMS</div>
            </div>
            <div class="dk-item">
                <div class="dk-val">₹2,40,000</div>
                <div class="dk-lbl">Project Price</div>
            </div>
            <div class="dk-item">
                <div class="dk-val" style="color:#10b981;">₹1,20,000</div>
                <div class="dk-lbl">Advance Paid</div>
            </div>
            <div class="dk-item">
                <div class="dk-val" style="color:#ef4444;">₹1,20,000</div>
                <div class="dk-lbl">Remaining Due</div>
            </div>
        </div>

        {{-- ── PAYMENT PROGRESS BAR ── --}}
        <div class="dash-card" style="padding:16px 20px;margin-bottom:20px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;">
                <span style="font-size:13px;font-weight:600;color:var(--t2);">Payment Progress</span>
                <div style="display:flex;align-items:center;gap:16px;font-size:12px;">
                    <span style="color:#10b981;font-weight:700;">₹1,20,000 paid</span>
                    <span style="color:var(--t3);">of ₹2,40,000</span>
                    <span class="status-pill pending">Partial</span>
                </div>
            </div>
            <div class="prog-bar-wrap" style="height:10px;">
                <div class="prog-fill" style="width:50%;background:linear-gradient(90deg,#6366f1,#06b6d4);border-radius:5px;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:11px;color:var(--t3);margin-top:5px;">
                <span>0%</span><span style="color:var(--accent);font-weight:700;">50% collected</span><span>100%</span>
            </div>
        </div>

        <div class="dash-grid">

            {{-- ══ LEFT COL ══ --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                {{-- Basic Info --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-vcard-fill" style="color:var(--accent);margin-right:6px;"></i>Basic Information</div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="pv-row"><span class="pv-lbl">Project Name / Domain</span><span class="pv-val">novatech.io</span></div>
                            <div class="pv-row"><span class="pv-lbl">Client Name</span><span class="pv-val">Anita Verma</span></div>
                            <div class="pv-row"><span class="pv-lbl">Email ID</span><span class="pv-val">anita@novatech.in</span></div>
                            <div class="pv-row"><span class="pv-lbl">Phone</span><span class="pv-val">+91 98123 45678</span></div>
                            <div class="pv-row"><span class="pv-lbl">Company Name</span><span class="pv-val">NovaTech Solutions</span></div>
                            <div class="pv-row"><span class="pv-lbl">Starting Date</span><span class="pv-val">15 Jan 2026</span></div>
                            <div class="pv-row"><span class="pv-lbl">Plan Name</span><span class="pv-val">dynamick</span></div>
                            <div class="pv-row"><span class="pv-lbl">Username</span><span class="pv-val mono">novatech_admin</span></div>
                            <div class="pv-row"><span class="pv-lbl">Password</span>
                                <span class="pv-val" style="display:flex;align-items:center;gap:8px;">
                                    <span id="pwVal" style="font-family:var(--mono);">••••••••</span>
                                    <button type="button" onclick="togglePw()" style="background:none;border:none;color:var(--accent);font-size:12px;cursor:pointer;padding:0;">
                                        <i class="bi bi-eye-fill" id="pwIcon"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="pv-row"><span class="pv-lbl">No. of Mail IDs</span><span class="pv-val">5</span></div>
                            <div class="pv-row"><span class="pv-lbl">Mail Password</span><span class="pv-val mono">mail@pass123</span></div>
                            <div class="pv-row" style="grid-column:1/-1;"><span class="pv-lbl">Domain, Server Book</span><span class="pv-val">GoDaddy (domain) · Hostinger cPanel · SSL via Let's Encrypt</span></div>
                            <div class="pv-row" style="grid-column:1/-1;"><span class="pv-lbl">Full Address</span><span class="pv-val">302, Skyline Tower, Bandra West, Mumbai — 400050, Maharashtra</span></div>
                        </div>
                    </div>
                </div>

                {{-- Website Details --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-globe2" style="color:#06b6d4;margin-right:6px;"></i>Website Project Details</div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="pv-row"><span class="pv-lbl">Domain Name</span><span class="pv-val"><a href="https://novatech.io" target="_blank" style="color:var(--accent);">novatech.io</a></span></div>
                            <div class="pv-row"><span class="pv-lbl">Hosting Provider</span><span class="pv-val">Hostinger</span></div>
                            <div class="pv-row"><span class="pv-lbl">CMS / Platform</span><span class="pv-val"><span class="cms-tag wordpress">WordPress</span></span></div>
                            <div class="pv-row"><span class="pv-lbl">Number of Pages</span><span class="pv-val">12</span></div>
                            <div class="pv-row"><span class="pv-lbl">Website Payment Status</span><span class="pv-val"><span class="status-pill pending">Partial</span></span></div>
                            <div class="pv-row" style="grid-column:1/-1;">
                                <span class="pv-lbl">Required Features</span>
                                <span class="pv-val">Custom login portal, product catalogue, WhatsApp integration, multilingual (EN/HI), SEO setup</span>
                            </div>
                            <div class="pv-row" style="grid-column:1/-1;">
                                <span class="pv-lbl">Reference Websites</span>
                                <span class="pv-val">
                                    <a href="#" style="color:var(--accent);">https://ref1.com</a>,
                                    <a href="#" style="color:var(--accent);">https://ref2.com</a>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Communication --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-chat-dots-fill" style="color:#10b981;margin-right:6px;"></i>Communication & Tracking</div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="pv-row"><span class="pv-lbl">Last Update Date</span><span class="pv-val">15 Mar 2026</span></div>
                            <div class="pv-row"><span class="pv-lbl">Client Feedback</span><span class="pv-val">Happy with design. Requested minor changes to contact form.</span></div>
                            <div class="pv-row" style="grid-column:1/-1;">
                                <span class="pv-lbl">Internal Notes</span>
                                <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:10px 12px;font-size:13px;color:var(--t2);line-height:1.6;margin-top:4px;">
                                    Dev is 60% complete. Homepage, About, Services done. Contact and Portfolio pending. SEO plugin installed. Client has approved wireframes. Awaiting product images from client side.
                                </div>
                            </div>
                        </div>

                        {{-- Quick update area --}}
                        <div style="margin-top:16px;padding-top:16px;border-top:1px solid var(--b1);">
                            <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:var(--t3);margin-bottom:10px;">Quick Update</div>
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Change Status</label>
                                    <select class="form-inp">
                                        <option>New</option>
                                        <option>Design Phase</option>
                                        <option selected>Development</option>
                                        <option>Testing</option>
                                        <option>Completed</option>
                                        <option>On Hold</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Change Payment Status</label>
                                    <select class="form-inp">
                                        <option>Pending</option>
                                        <option selected>Partial</option>
                                        <option>Paid</option>
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1;">
                                    <label class="form-lbl">Add Note</label>
                                    <textarea class="form-inp" rows="2" placeholder="Add internal note…"></textarea>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;margin-top:10px;">
                                <button class="btn-primary-solid" onclick="showToast('success','Project updated!','bi-kanban-fill')">
                                    <i class="bi bi-check2-circle"></i> Save Changes
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            {{-- ══ RIGHT COL ══ --}}
            <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                {{-- Timeline --}}
                <div class="dash-card" style="position:sticky;top:80px;">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-calendar3" style="color:#f59e0b;margin-right:6px;"></i>Project Timeline</div>
                    </div>
                    <div class="card-body" style="padding-top:10px;">

                        <div style="display:flex;flex-direction:column;gap:0;padding-left:16px;border-left:2px solid var(--b2);position:relative;">

                            <div style="position:absolute;top:0;left:-5px;width:8px;height:8px;border-radius:50%;background:#10b981;"></div>

                            <div class="tl-item">
                                <div class="tl-lbl">Project Start</div>
                                <div class="tl-val">15 Jan 2026</div>
                            </div>
                            <div class="tl-item">
                                <div class="tl-lbl">Expected Delivery</div>
                                <div class="tl-val" style="color:#f59e0b;">15 Apr 2026</div>
                            </div>
                            <div class="tl-item">
                                <div class="tl-lbl">Actual Delivery</div>
                                <div class="tl-val" style="color:var(--t4);">Not delivered yet</div>
                            </div>

                        </div>

                        <div style="margin-top:16px;padding-top:14px;border-top:1px solid var(--b1);">
                            <div style="font-size:11px;color:var(--t3);margin-bottom:6px;font-weight:600;">Days Remaining</div>
                            <div style="font-size:28px;font-weight:800;color:#f59e0b;letter-spacing:-.5px;">29 days</div>
                            <div style="font-size:11.5px;color:var(--t3);margin-top:3px;">Until expected delivery</div>
                        </div>

                    </div>
                </div>

                {{-- Financial --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-currency-rupee" style="color:#8b5cf6;margin-right:6px;"></i>Financial Summary</div>
                    </div>
                    <div class="card-body">

                        <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:14px;">
                            <div style="display:flex;justify-content:space-between;font-size:13px;padding:9px 12px;background:var(--bg3);border-radius:var(--r-sm);border:1px solid var(--b1);">
                                <span style="color:var(--t3);">Project Price</span>
                                <span class="money-cell">₹2,40,000</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;padding:9px 12px;background:var(--bg3);border-radius:var(--r-sm);border:1px solid var(--b1);">
                                <span style="color:var(--t3);">Advance Paid</span>
                                <span class="money-cell" style="color:#10b981;">₹1,20,000</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:700;padding:10px 12px;background:rgba(239,68,68,.06);border-radius:var(--r-sm);border:1px solid rgba(239,68,68,.15);">
                                <span style="color:var(--t2);">Remaining Due</span>
                                <span class="money-cell" style="color:#ef4444;">₹1,20,000</span>
                            </div>
                        </div>

                        <div style="display:flex;flex-direction:column;gap:6px;">
                            <div class="pv-row"><span class="pv-lbl">Invoice Number</span><span class="pv-val mono">INV-2041</span></div>
                            <div class="pv-row"><span class="pv-lbl">Payment Status</span><span class="pv-val"><span class="status-pill pending">Partial</span></span></div>
                        </div>

                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Quick Actions</div>
                    </div>
                    <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                        <button class="btn-ghost" style="width:100%;justify-content:flex-start;gap:10px;" onclick="openModal('editProjectModal')">
                            <i class="bi bi-pencil-fill" style="color:var(--accent);"></i> Edit Project
                        </button>
                        <a href="{{ route('admin.payments.create') }}" class="btn-ghost" style="width:100%;justify-content:flex-start;gap:10px;display:flex;align-items:center;text-decoration:none;">
                            <i class="bi bi-wallet2" style="color:#10b981;"></i> Add Payment
                        </a>
                        <button class="btn-ghost" style="width:100%;justify-content:flex-start;gap:10px;">
                            <i class="bi bi-telephone-fill" style="color:#06b6d4;"></i> Call Client
                        </button>
                        <button class="btn-ghost" style="width:100%;justify-content:flex-start;gap:10px;">
                            <i class="bi bi-envelope-fill" style="color:#8b5cf6;"></i> Send Email
                        </button>
                        <button class="btn-ghost danger-ghost" style="width:100%;justify-content:flex-start;gap:10px;" onclick="openModal('deleteProjectModal')">
                            <i class="bi bi-trash-fill"></i> Delete Project
                        </button>
                    </div>
                </div>

            </div>

        </div>
    </div>


    {{-- ── EDIT MODAL (same as index) ── --}}
    <div class="modal-backdrop" id="editProjectModal">
        <div class="modal-box modal-box-lg" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Edit Project — novatech.io</span>
                <button class="modal-close" onclick="closeModal('editProjectModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">

                <div class="proj-section-lbl"><i class="bi bi-info-circle-fill"></i> Basic Information</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Name / Domain *</label><input type="text" class="form-inp" value="novatech.io"></div>
                    <div class="form-row"><label class="form-lbl">Client Name *</label><input type="text" class="form-inp" value="Anita Verma"></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" value="anita@novatech.in"></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" value="+91 98123 45678"></div>
                    <div class="form-row"><label class="form-lbl">Company Name</label><input type="text" class="form-inp" value="NovaTech Solutions"></div>
                    <div class="form-row"><label class="form-lbl">Starting Date</label><input type="date" class="form-inp" value="2026-01-15"></div>
                    <div class="form-row"><label class="form-lbl">Plan Name</label><input type="text" class="form-inp" value="dynamick"></div>
                    <div class="form-row"><label class="form-lbl">Payment Status</label><select class="form-inp">
                            <option>Pending</option>
                            <option selected>Partial</option>
                            <option>Paid</option>
                        </select></div>
                    <div class="form-row"><label class="form-lbl">Username</label><input type="text" class="form-inp" value="novatech_admin"></div>
                    <div class="form-row"><label class="form-lbl">Password</label><input type="text" class="form-inp" value="pass@1234"></div>
                    <div class="form-row"><label class="form-lbl">No. of Mail IDs</label><input type="number" class="form-inp" value="5"></div>
                    <div class="form-row"><label class="form-lbl">Mail Password</label><input type="text" class="form-inp" value="mail@pass123"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Domain, Server Book</label><input type="text" class="form-inp" value="GoDaddy (domain) · Hostinger cPanel"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Full Address</label><textarea class="form-inp" rows="2">302, Skyline Tower, Bandra West, Mumbai — 400050</textarea></div>
                </div>

                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-globe2"></i> Website Project Details</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Domain Name</label><input type="text" class="form-inp" value="novatech.io"></div>
                    <div class="form-row"><label class="form-lbl">Hosting Provider</label><input type="text" class="form-inp" value="Hostinger"></div>
                    <div class="form-row"><label class="form-lbl">CMS / Platform</label><select class="form-inp">
                            <option selected>WordPress</option>
                            <option>Shopify</option>
                            <option>Custom</option>
                        </select></div>
                    <div class="form-row"><label class="form-lbl">Number of Pages</label><input type="number" class="form-inp" value="12"></div>
                    <div class="form-row"><label class="form-lbl">Website Payment Status</label><select class="form-inp">
                            <option>Pending</option>
                            <option selected>Partial</option>
                            <option>Paid</option>
                        </select></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Required Features</label><textarea class="form-inp" rows="2">Custom login portal, product catalogue, WhatsApp integration</textarea></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Reference Websites</label><input type="text" class="form-inp" value="https://ref1.com, https://ref2.com"></div>
                </div>

                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-calendar3"></i> Project Timeline</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Start Date</label><input type="date" class="form-inp" value="2026-01-15"></div>
                    <div class="form-row"><label class="form-lbl">Expected Delivery</label><input type="date" class="form-inp" value="2026-04-15"></div>
                    <div class="form-row"><label class="form-lbl">Actual Delivery Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Project Status</label><select class="form-inp">
                            <option>New</option>
                            <option>Design Phase</option>
                            <option selected>Development</option>
                            <option>Testing</option>
                            <option>Completed</option>
                            <option>On Hold</option>
                        </select></div>
                </div>

                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-currency-rupee"></i> Financial Fields</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Project Price *</label><input type="text" class="form-inp" value="240000"></div>
                    <div class="form-row"><label class="form-lbl">Advance Payment</label><input type="text" class="form-inp" value="120000"></div>
                    <div class="form-row"><label class="form-lbl">Remaining Amount</label><input type="text" class="form-inp" value="120000" readonly></div>
                    <div class="form-row"><label class="form-lbl">Payment Status</label><select class="form-inp">
                            <option>Pending</option>
                            <option selected>Partial</option>
                            <option>Paid</option>
                        </select></div>
                    <div class="form-row"><label class="form-lbl">Invoice Number</label><input type="text" class="form-inp" value="INV-2041"></div>
                </div>

                <div class="proj-section-lbl" style="margin-top:18px;"><i class="bi bi-chat-dots-fill"></i> Communication & Tracking</div>
                <div class="form-grid" style="margin-bottom:0;">
                    <div class="form-row"><label class="form-lbl">Last Update Date</label><input type="date" class="form-inp" value="2026-03-15"></div>
                    <div class="form-row"><label class="form-lbl">Client Feedback</label><input type="text" class="form-inp" value="Happy with design. Minor contact form changes."></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Internal Notes</label><textarea class="form-inp" rows="3">Dev is 60% complete. Homepage, About, Services done. Contact and Portfolio pending.</textarea></div>
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
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">This will permanently delete <strong style="color:var(--t1);">novatech.io</strong>.<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
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
    /* Detail view rows */
    .pv-row {
        display: flex;
        flex-direction: column;
        gap: 3px;
        padding: 10px 12px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
    }

    .pv-lbl {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--t3);
    }

    .pv-val {
        font-size: 13px;
        font-weight: 500;
        color: var(--t1);
        line-height: 1.5;
    }

    /* Timeline items */
    .tl-item {
        padding: 8px 0 8px 14px;
        border-bottom: 1px dashed var(--b1);
    }

    .tl-item:last-child {
        border-bottom: none;
    }

    .tl-lbl {
        font-size: 10.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--t3);
    }

    .tl-val {
        font-size: 13px;
        font-weight: 600;
        color: var(--t1);
        margin-top: 2px;
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

    /* Section labels */
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

    /* KPI grid override for 6 cols */
    .detail-kpis {
        grid-template-columns: repeat(6, 1fr) !important;
    }

    @media (max-width:768px) {
        .detail-kpis {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
</style>

<script>
    let pwVisible = false;

    function togglePw() {
        pwVisible = !pwVisible;
        document.getElementById('pwVal').textContent = pwVisible ? 'pass@1234' : '••••••••';
        document.getElementById('pwIcon').className = pwVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
    }
</script>

@endsection