@extends('admin.layout.app')

@section('title', 'Status — Leads, Orders, Payments, Projects')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-status">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Status Manager</h1>
                <p class="page-desc">Manage statuses for Leads, Orders, Payments & Projects</p>
            </div>

            <button class="btn-primary-solid sm" onclick="openModal('addStatusModal')">
                <i class="bi bi-plus-lg"></i> Add Status
            </button>
        </div>

        <!-- KPI CARDS -->
        <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:14px;margin-bottom:24px;">

            <!-- Lead Status -->
            <div class="dash-card" style="padding:18px 20px;cursor:pointer;transition:var(--transition);"
                onmouseover="this.style.borderColor='var(--accent)'"
                onmouseout="this.style.borderColor='var(--b1)'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba(99,102,241,.14);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-person-lines-fill" style="font-size:19px;color:#6366f1;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(99,102,241,.12);color:#818cf8;">Lead</span>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">11</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Lead Statuses</div>
                <div style="margin-top:12px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:68%;background:#6366f1;border-radius:3px;"></div>
                </div>
            </div>

            <!-- Order Status -->
            <div class="dash-card" style="padding:18px 20px;cursor:pointer;transition:var(--transition);"
                onmouseover="this.style.borderColor='#10b981'"
                onmouseout="this.style.borderColor='var(--b1)'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba(16,185,129,.14);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-bag-check-fill" style="font-size:19px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(16,185,129,.12);color:#10b981;">Order</span>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">6</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Order Statuses</div>
                <div style="margin-top:12px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:40%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            <!-- Payment Status -->
            <div class="dash-card" style="padding:18px 20px;cursor:pointer;transition:var(--transition);"
                onmouseover="this.style.borderColor='#f59e0b'"
                onmouseout="this.style.borderColor='var(--b1)'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba(245,158,11,.14);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-credit-card-2-front-fill" style="font-size:19px;color:#f59e0b;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(245,158,11,.12);color:#f59e0b;">Payment</span>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">5</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Payment Statuses</div>
                <div style="margin-top:12px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:33%;background:#f59e0b;border-radius:3px;"></div>
                </div>
            </div>

            <!-- Project Status -->
            <div class="dash-card" style="padding:18px 20px;cursor:pointer;transition:var(--transition);"
                onmouseover="this.style.borderColor='#8b5cf6'"
                onmouseout="this.style.borderColor='var(--b1)'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:42px;height:42px;border-radius:11px;background:rgba(139,92,246,.14);display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-kanban-fill" style="font-size:19px;color:#8b5cf6;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 9px;border-radius:20px;background:rgba(139,92,246,.12);color:#8b5cf6;">Project</span>
                </div>
                <div style="font-size:28px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">7</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Project Statuses</div>
                <div style="margin-top:12px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:46%;background:#8b5cf6;border-radius:3px;"></div>
                </div>
            </div>

        </div>

        <!-- STATUS TABLE CARD -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head" style="padding:16px 18px 0;">
                    <div>
                        <div class="card-title">All Statuses</div>
                        <div class="card-sub" id="tableSubCount">29 total statuses</div>
                    </div>
                    <div class="card-actions mb-2">
                        <!-- Filter Dropdown -->
                        <select class="filter-select" id="typeFilter" onchange="filterTable()">
                            <option value="all">All Types</option>
                            <option value="lead">Lead</option>
                            <option value="order">Order</option>
                            <option value="payment">Payment</option>
                            <option value="project">Project</option>
                        </select>
                        <!-- Search -->
                        <div class="global-search" style="max-width:220px;">
                            <i class="bi bi-search"></i>
                            <input type="text" id="statusSearch" placeholder="Search status…" oninput="filterTable()">
                        </div>

                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="statusTable">
                        <thead>
                            <tr>
                                <th style="width:52px;">SL</th>
                                <th>Status Name</th>
                                <th>Type</th>
                                <th style="width:110px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="statusTbody">
                            <!-- Lead Statuses -->
                            <tr data-type="lead">
                                <td>1</td>
                                <td><span style="font-weight:600;color:var(--t1);">Not Responding</span></td>
                                <td><span class="type-badge lead">Lead</span></td>
                                <td>
                                    <div class="row-actions"><button class="ra-btn" onclick="openEditModal('Not Responding','lead','#6b7280')" title="Edit"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger" onclick="openModal('deleteStatusModal')" title="Delete"><i class="bi bi-trash-fill"></i></button></div>
                                </td>
                            </tr>


                            <!-- Order Statuses -->
                            <tr data-type="order">
                                <td>2</td>
                                <td><span style="font-weight:600;color:var(--t1);">Pending</span></td>
                                <td><span class="type-badge order">Order</span></td>
                                <td>
                                    <div class="row-actions"><button class="ra-btn" onclick="openEditModal('Pending','order','#f59e0b')" title="Edit"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger" onclick="openModal('deleteStatusModal')" title="Delete"><i class="bi bi-trash-fill"></i></button></div>
                                </td>
                            </tr>


                            <!-- Payment Statuses -->
                            <tr data-type="payment">
                                <td>3</td>
                                <td><span style="font-weight:600;color:var(--t1);">Paid</span></td>
                                <td><span class="type-badge payment">Payment</span></td>
                                <td>
                                    <div class="row-actions"><button class="ra-btn" onclick="openEditModal('Paid','payment','#10b981')" title="Edit"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger" onclick="openModal('deleteStatusModal')" title="Delete"><i class="bi bi-trash-fill"></i></button></div>
                                </td>
                            </tr>


                            <!-- Project Statuses -->
                            <tr data-type="project">
                                <td>4</td>
                                <td><span style="font-weight:600;color:var(--t1);">Planning</span></td>
                                <td><span class="type-badge project">Project</span></td>
                                <td>
                                    <div class="row-actions"><button class="ra-btn" onclick="openEditModal('Planning','project','#f59e0b')" title="Edit"><i class="bi bi-pencil-fill"></i></button><button class="ra-btn danger" onclick="openModal('deleteStatusModal')" title="Delete"><i class="bi bi-trash-fill"></i></button></div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Empty state -->
                <div id="emptyState" style="display:none;padding:48px 24px;text-align:center;">
                    <div style="width:56px;height:56px;background:var(--bg4);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                        <i class="bi bi-search" style="font-size:22px;color:var(--t3);"></i>
                    </div>
                    <div style="font-size:15px;font-weight:700;color:var(--t2);margin-bottom:5px;">No statuses found</div>
                    <div style="font-size:13px;color:var(--t3);">Try a different filter or search term</div>
                </div>

                <!-- Table Footer -->
                <div class="table-footer">
                    <span class="tf-info" id="visibleCount">Showing 29 of 29 statuses</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>

    </div><!-- end page -->


    <!-- ══════════════════════════════════
         ADD STATUS MODAL
    ══════════════════════════════════ -->
    <div class="modal-backdrop" id="addStatusModal" onclick="closeModal('addStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Add New Status</span>
                <button class="modal-close" onclick="closeModal('addStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="form-row">
                    <label class="form-lbl">Status Name *</label>
                    <input type="text" class="form-inp" id="addStatusName" placeholder="e.g. Under Review">
                </div>
                <div class="form-row">
                    <label class="form-lbl">Type *</label>
                    <select class="form-inp" id="addStatusType">
                        <option value="">— Select Type —</option>
                        <option value="lead">Lead</option>
                        <option value="order">Order</option>
                        <option value="payment">Payment</option>
                        <option value="project">Project</option>
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addStatusModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addStatusModal');showToast('success','Status added!','bi-check-circle-fill')">
                    <i class="bi bi-plus-lg"></i> Add Status
                </button>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════
         EDIT STATUS MODAL
    ══════════════════════════════════ -->
    <div class="modal-backdrop" id="editStatusModal" onclick="closeModal('editStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Edit Status</span>
                <button class="modal-close" onclick="closeModal('editStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="form-row">
                    <label class="form-lbl">Status Name *</label>
                    <input type="text" class="form-inp" id="editStatusName" placeholder="Status name">
                </div>
                <div class="form-row">
                    <label class="form-lbl">Type *</label>
                    <select class="form-inp" id="editStatusType">
                        <option value="lead">Lead</option>
                        <option value="order">Order</option>
                        <option value="payment">Payment</option>
                        <option value="project">Project</option>
                    </select>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editStatusModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editStatusModal');showToast('success','Status updated!','bi-check-circle-fill')">
                    <i class="bi bi-pencil-fill"></i> Update Status
                </button>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════
         DELETE STATUS MODAL
    ══════════════════════════════════ -->
    <div class="modal-backdrop" id="deleteStatusModal" onclick="closeModal('deleteStatusModal')">
        <div class="modal-box sm-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Status</span>
                <button class="modal-close" onclick="closeModal('deleteStatusModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">
                    This status will be permanently deleted.<br>
                    This action <strong style="color:#dc2626;">cannot be undone.</strong>
                </p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteStatusModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="closeModal('deleteStatusModal');showToast('success','Status deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete
                </button>
            </div>
        </div>
    </div>


    <!-- ══════════════════════════════════
         PAGE-SPECIFIC STYLES
    ══════════════════════════════════ -->
    <style>
        /* Type badges */
        .type-badge {
            display: inline-flex;
            align-items: center;
            font-size: 11px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
            text-transform: capitalize;
            letter-spacing: .02em;
        }

        .type-badge.lead {
            background: rgba(99, 102, 241, .12);
            color: #818cf8;
        }

        .type-badge.order {
            background: rgba(16, 185, 129, .12);
            color: #10b981;
        }

        .type-badge.payment {
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .type-badge.project {
            background: rgba(139, 92, 246, .12);
            color: #8b5cf6;
        }

        /* Status preview pill in table */
        .status-preview {
            display: inline-flex;
            align-items: center;
            font-size: 12px;
            font-weight: 600;
            padding: 4px 11px;
            border-radius: 20px;
        }

        /* Hide row utility */
        tr.hidden-row {
            display: none;
        }
    </style>


    <!-- ══════════════════════════════════
         PAGE SCRIPTS
    ══════════════════════════════════ -->
    <script>
        /* ── Filter + Search ── */
        function filterTable() {
            const type = document.getElementById('typeFilter').value.toLowerCase();
            const search = document.getElementById('statusSearch').value.toLowerCase();
            const rows = document.querySelectorAll('#statusTbody tr');
            let visible = 0;

            rows.forEach(row => {
                const rowType = row.dataset.type || '';
                const rowText = row.textContent.toLowerCase();
                const typeOk = (type === 'all' || rowType === type);
                const textOk = rowText.includes(search);

                if (typeOk && textOk) {
                    row.classList.remove('hidden-row');
                    visible++;
                } else {
                    row.classList.add('hidden-row');
                }
            });

            document.getElementById('visibleCount').textContent =
                `Showing ${visible} of ${rows.length} statuses`;
            document.getElementById('emptyState').style.display =
                visible === 0 ? 'block' : 'none';
        }

        /* ── Open Edit Modal pre-filled ── */
        function openEditModal(name, type, color) {
            document.getElementById('editStatusName').value = name;
            document.getElementById('editStatusType').value = type;
            document.getElementById('editStatusColor').value = color;
            document.getElementById('editStatusColorHex').value = color;
            updateEditPreview();
            openModal('editStatusModal');
        }

        /* ── Color preview helpers ── */
        function hexToRgb(hex) {
            const r = parseInt(hex.slice(1, 3), 16);
            const g = parseInt(hex.slice(3, 5), 16);
            const b = parseInt(hex.slice(5, 7), 16);
            return `${r},${g},${b}`;
        }

        function updateAddPreview() {
            const color = document.getElementById('addStatusColor').value;
            document.getElementById('addStatusColorHex').value = color;
            const badge = document.getElementById('addPreviewBadge');
            const name = document.getElementById('addStatusName').value || 'Preview';
            badge.textContent = name;
            badge.style.background = `rgba(${hexToRgb(color)},.13)`;
            badge.style.color = color;
        }

        function updateEditPreview() {
            const color = document.getElementById('editStatusColor').value;
            document.getElementById('editStatusColorHex').value = color;
            const badge = document.getElementById('editPreviewBadge');
            const name = document.getElementById('editStatusName').value || 'Preview';
            badge.textContent = name;
            badge.style.background = `rgba(${hexToRgb(color)},.13)`;
            badge.style.color = color;
        }

        /* Sync hex text → color picker */
        function syncColorPicker(prefix) {
            const hexInp = document.getElementById(prefix + 'StatusColorHex');
            const colorInp = document.getElementById(prefix + 'StatusColor');
            const val = hexInp.value.trim();
            if (/^#[0-9a-fA-F]{6}$/.test(val)) {
                colorInp.value = val;
                prefix === 'add' ? updateAddPreview() : updateEditPreview();
            }
        }

        /* Also update preview badge text while typing name */
        document.getElementById('addStatusName')?.addEventListener('input', updateAddPreview);
        document.getElementById('editStatusName')?.addEventListener('input', updateEditPreview);
    </script>

</main>

@endsection