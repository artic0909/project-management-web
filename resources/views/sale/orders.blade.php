@extends('sale.layout.app')

@section('title', 'All Orders')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Orders</h1>
            </div>
        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Recent Orders</div>
                        <div class="card-sub">₹48.6L this month</div>
                    </div>
                    <div class="card-actions">
                        <div class="order-type-tabs">
                            <button class="ot-tab active" data-type="all">All</button>
                            <button class="ot-tab" data-type="order">Orders</button>
                            <button class="ot-tab" data-type="marketing">Marketing</button>
                        </div>
                        <select class="filter-select">
                            <option>All Status</option>
                            <option>Paid</option>
                            <option>Pending</option>
                            <option>Overdue</option>
                        </select>
                        <button class="btn-primary-solid sm" onclick="openModal('addOrderModal')">
                            <i class="bi bi-plus-lg"></i> Add Order
                        </button>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Type</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Service</th>
                                <th>Budget</th>
                                <th>Full Address</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr data-order-type="order">
                                <td><span class="mono">#ORD-2847</span></td>
                                <td><span class="type-badge order-type">Order</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">TC</div>
                                        <div>
                                            <div class="ln">TechCorp Pvt Ltd</div>
                                            <div class="ls">cto@techcorp.io</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Rahul Sharma</div>
                                    <div class="ls">9565528200</div>
                                </td>
                                <td><span class="src-tag website">Website</span></td>
                                <td><span class="src-tag">₹8.5L</span></td>
                                <td>
                                    <div class="ls">Mumbai, MH 400001</div>
                                    <div class="ls">Andheri East</div>
                                </td>
                                <td><span class="status-pill paid">Paid</span></td>
                                <td>
                                    <div class="ln">Neha Kapoor</div>
                                    <div class="ls">neha@company.com</div>
                                </td>
                                <td>
                                    <div class="ln">Priya Dev</div>
                                    <div class="ls">priya@company.com</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-telephone-fill"></i></button>
                                        <a href="{{route('sale.payments.create')}}" target="_blank" class="ra-btn"><i class="bi bi-wallet-fill"></i></a>
                                        <button class="ra-btn" onclick="openModal('editOrderModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            <tr data-order-type="marketing">
                                <td><span class="mono">#MKT-1024</span></td>
                                <td><span class="type-badge marketing-type">Marketing</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">DF</div>
                                        <div>
                                            <div class="ln">DataFirst Corp</div>
                                            <div class="ls">cto@datafirst.io</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Amit Verma</div>
                                    <div class="ls">9876543210</div>
                                </td>
                                <td><span class="src-tag">SEO</span></td>
                                <td><span class="src-tag">₹4.2L</span></td>
                                <td>
                                    <div class="ls">Delhi, DL 110001</div>
                                    <div class="ls">Connaught Place</div>
                                </td>
                                <td><span class="status-pill pending">Pending</span></td>
                                <td>
                                    <div class="ln">Ravi Singh</div>
                                    <div class="ls">ravi@company.com</div>
                                </td>
                                <td>
                                    <div class="ln">Sales Team</div>
                                    <div class="ls">sales@company.com</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('orderDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-telephone-fill"></i></button>
                                        <a href="{{route('sale.payments.create')}}" target="_blank" class="ra-btn"><i class="bi bi-wallet-fill"></i></a>
                                        <button class="ra-btn" onclick="openModal('editOrderModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing 2 of 24 Orders</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <span class="pg-ellipsis">…</span>
                        <button class="pg-btn">5</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                    <div class="tf-per-page"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         ADD ORDER MODAL — single modal, all fields
    ════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="addOrderModal">
        <div class="modal-box modal-box-lg" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Create New Order</span>
                <button class="modal-close" onclick="closeModal('addOrderModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">

                {{-- ── Common Fields ── --}}
                <div class="form-grid">
                    <div class="form-row">
                        <label class="form-lbl">Company Name *</label>
                        <input type="text" class="form-inp" placeholder="Company name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Client Name *</label>
                        <input type="text" class="form-inp" placeholder="Full name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Email</label>
                        <input type="email" class="form-inp" placeholder="email@company.com">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Phone</label>
                        <input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Domain Name</label>
                        <input type="text" class="form-inp" placeholder="Domain name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Service / Product</label>
                        <input type="text" class="form-inp" placeholder="What are we delivering?">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Order Value *</label>
                        <input type="text" class="form-inp" placeholder="₹ Amount">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Terms</label>
                        <select class="form-inp">
                            <option>Full Advance</option>
                            <option>50-50</option>
                            <option>Milestone</option>
                            <option>Net 30</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Delivery Date</label>
                        <input type="date" class="form-inp">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">City</label>
                        <input type="text" class="form-inp" placeholder="City">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Region / State</label>
                        <input type="text" class="form-inp" placeholder="State or Province">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Zip Code</label>
                        <input type="text" class="form-inp" placeholder="Zip / PIN">
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Full Address</label>
                        <textarea class="form-inp" rows="2" placeholder="Street address…"></textarea>
                    </div>
                </div>

                {{-- ── Marketing Order Only Fields ── --}}
                <div class="mkt-section">
                    <div class="mkt-section-label">
                        <i class="bi bi-megaphone-fill"></i>
                        Marketing Order Details
                        <span class="mkt-section-note">Fill only for marketing orders</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-row">
                            <label class="form-lbl">Payment Status</label>
                            <select class="form-inp">
                                <option>Pending</option>
                                <option>Paid</option>
                                <option>Overdue</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Starting Date</label>
                            <input type="date" class="form-inp">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Plan Name</label>
                            <input type="text" class="form-inp" placeholder="Plan name">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Username</label>
                            <input type="text" class="form-inp" placeholder="Account username">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Password</label>
                            <input type="text" class="form-inp" placeholder="Account password">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('addOrderModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('addOrderModal');showToast('success','Order created!','bi-bag-check-fill')">
                    <i class="bi bi-plus-lg"></i> Create Order
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         EDIT ORDER MODAL — single modal, all fields
    ════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="editOrderModal">
        <div class="modal-box modal-box-lg" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Update Order</span>
                <button class="modal-close" onclick="closeModal('editOrderModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">

                {{-- ── Common Fields ── --}}
                <div class="form-grid">
                    <div class="form-row">
                        <label class="form-lbl">Company Name *</label>
                        <input type="text" class="form-inp" placeholder="Company name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Client Name *</label>
                        <input type="text" class="form-inp" placeholder="Full name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Email</label>
                        <input type="email" class="form-inp" placeholder="email@company.com">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Phone</label>
                        <input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Domain Name</label>
                        <input type="text" class="form-inp" placeholder="Domain name">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Service / Product</label>
                        <input type="text" class="form-inp" placeholder="What are we delivering?">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Order Value *</label>
                        <input type="text" class="form-inp" placeholder="₹ Amount">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Payment Terms</label>
                        <select class="form-inp">
                            <option>Full Advance</option>
                            <option>50-50</option>
                            <option>Milestone</option>
                            <option>Net 30</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Delivery Date</label>
                        <input type="date" class="form-inp">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">City</label>
                        <input type="text" class="form-inp" placeholder="City">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Region / State</label>
                        <input type="text" class="form-inp" placeholder="State or Province">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl">Zip Code</label>
                        <input type="text" class="form-inp" placeholder="Zip / PIN">
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Full Address</label>
                        <textarea class="form-inp" rows="2" placeholder="Street address…"></textarea>
                    </div>
                </div>

                {{-- ── Marketing Order Only Fields ── --}}
                <div class="mkt-section">
                    <div class="mkt-section-label">
                        <i class="bi bi-megaphone-fill"></i>
                        Marketing Order Details
                        <span class="mkt-section-note">Fill only for marketing orders</span>
                    </div>
                    <div class="form-grid">
                        <div class="form-row">
                            <label class="form-lbl">Payment Status</label>
                            <select class="form-inp">
                                <option>Pending</option>
                                <option>Paid</option>
                                <option>Overdue</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Starting Date</label>
                            <input type="date" class="form-inp">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Plan Name</label>
                            <input type="text" class="form-inp" placeholder="Plan name">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Username</label>
                            <input type="text" class="form-inp" placeholder="Account username">
                        </div>
                        <div class="form-row">
                            <label class="form-lbl">Password</label>
                            <input type="text" class="form-inp" placeholder="Account password">
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editOrderModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editOrderModal');showToast('success','Order updated!','bi-bag-check-fill')">
                    <i class="bi bi-floppy-fill"></i> Update Order
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         DELETE MODAL
    ════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Order</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:#111827;">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:#6b7280;line-height:1.6;">Are you sure you want to delete this Order?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="closeModal('deleteModal');showToast('success','Order Deleted!','bi-trash3-fill')">
                    <i class="bi bi-trash3-fill"></i> Delete Order
                </button>
            </div>
        </div>
    </div>


    {{-- ═══════════════════════════════════════════════════
         ORDER DETAIL MODAL
    ════════════════════════════════════════════════════ --}}
    <div class="modal-backdrop" id="orderDetailModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Order #ORD-2847</span>
                <button class="modal-close" onclick="closeModal('orderDetailModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="detail-kpis" style="margin-bottom:20px">
                    <div class="dk-item">
                        <div class="dk-val">₹8.5L</div>
                        <div class="dk-lbl">Order Value</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#10b981">Paid</div>
                        <div class="dk-lbl">Status</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Nov 18</div>
                        <div class="dk-lbl">Date</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">Dec 15</div>
                        <div class="dk-lbl">Delivery</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company Name</label><input class="form-inp" value="TechCorp Pvt Ltd" readonly></div>
                    <div class="form-row"><label class="form-lbl">Client Name</label><input class="form-inp" value="Rahul Sharma" readonly></div>
                    <div class="form-row"><label class="form-lbl">Domain</label><input class="form-inp" value="techcorp.io" readonly></div>
                    <div class="form-row"><label class="form-lbl">Payment Terms</label><input class="form-inp" value="Full Advance" readonly></div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Assign To (Developer)</label>
                        <select class="form-inp">
                            <option>Rahul Kumar</option>
                            <option>Priya Sharma</option>
                            <option>Neha Kapoor</option>
                        </select>
                    </div>
                </div>

                <div class="mkt-section">
                    <div class="mkt-section-label">
                        <i class="bi bi-megaphone-fill"></i>
                        Marketing Order Details
                    </div>
                    <div class="form-grid">
                        <div class="form-row"><label class="form-lbl">Plan Name</label><input class="form-inp" value="Growth Plan" readonly></div>
                        <div class="form-row"><label class="form-lbl">Username</label><input class="form-inp" value="datafirst_user" readonly></div>
                        <div class="form-row"><label class="form-lbl">Password</label><input class="form-inp" value="••••••••" readonly></div>
                        <div class="form-row">
                            <label class="form-lbl">Payment Status</label>
                            <select class="form-inp">
                                <option>Pending</option>
                                <option>Paid</option>
                                <option>Overdue</option>
                            </select>
                        </div>
                        <div class="form-row" style="grid-column:1/-1">
                            <label class="form-lbl">Assign To (Sales)</label>
                            <select class="form-inp">
                                <option>Rahul Kumar</option>
                                <option>Priya Sharma</option>
                                <option>Neha Kapoor</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('orderDetailModal')">Close</button>
                <button class="btn-primary-solid" onclick="closeModal('orderDetailModal');showToast('success','Order updated!','bi-bag-check-fill')">Update</button>
            </div>
        </div>
    </div>

</main>

<style>
    /* ── Order type tabs ── */
    .order-type-tabs {
        display: flex;
        gap: 2px;
        background: #f3f4f6;
        border-radius: 8px;
        padding: 3px;
    }

    .ot-tab {
        padding: 5px 14px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        background: transparent;
        border-radius: 6px;
        cursor: pointer;
        color: #6b7280;
        transition: all 0.15s;
    }

    .ot-tab.active {
        background: #fff;
        color: #111827;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    /* ── Type badges in table ── */
    .type-badge {
        display: inline-flex;
        align-items: center;
        padding: 3px 9px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 600;
        letter-spacing: 0.03em;
        text-transform: uppercase;
    }

    .order-type {
        background: #dbeafe;
        color: #1d4ed8;
    }


    /* ── Wider modal ── */
    .modal-box-lg {
        max-width: 760px !important;
        width: 90vw !important;
    }

    /* ── Marketing section block ── */
    .mkt-section {
        margin-top: 20px;
        border: 1px solid #e9d5ff;
        border-radius: 10px;
        overflow: hidden;
    }

    .mkt-section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f5f0ff;
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 700;
        color: #7c3aed;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid #e9d5ff;
    }

    .mkt-section-note {
        margin-left: auto;
        font-size: 11px;
        font-weight: 400;
        color: #a78bfa;
        text-transform: none;
        letter-spacing: 0;
    }

    .mkt-section .form-grid {
        padding: 14px;
        background: #faf5ff;
    }
</style>

<script>
    /* ── Tab filter ── */
    document.querySelectorAll('.ot-tab').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('.ot-tab').forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            const type = this.dataset.type;
            document.querySelectorAll('tbody tr[data-order-type]').forEach(row => {
                row.style.display = (type === 'all' || row.dataset.orderType === type) ? '' : 'none';
            });
        });
    });
</script>

@endsection