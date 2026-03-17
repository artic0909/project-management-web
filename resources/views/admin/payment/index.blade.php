@extends('admin.layout.app')

@section('title', 'All Payments')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Payments</h1>
            </div>
        </div>

        {{-- ═══════════════════════════════════════════════════
             PAYMENT SUMMARY BOXES
        ════════════════════════════════════════════════════ --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;max-width:600px;">

            {{-- 1. Paid Amount --}}
            <div class="dash-card" style="padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:11px;background:rgba(16,185,129,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check-circle-fill" style="font-size:20px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Collected</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#10b981;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹48,60,000</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Paid Amount</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:72%;background:#10b981;border-radius:4px;"></div>
                </div>
            </div>

            {{-- 2. Unpaid Amount --}}
            <div class="dash-card" style="padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:11px;background:rgba(239,68,68,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-hourglass-split" style="font-size:20px;color:#ef4444;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(239,68,68,.1);color:#ef4444;">Outstanding</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹18,40,000</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Unpaid Amount</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:28%;background:#ef4444;border-radius:4px;"></div>
                </div>
            </div>

        </div>
        {{-- END SUMMARY BOXES --}}


        <!-- MAIN GRID -->
        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Recent Payments</div>
                        <div class="card-sub">₹48.6L this month</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form class="global-search">
                            <i class="bi bi-search"></i>
                            <input type="text" placeholder="Search...">
                            <button type="submit" class="btn-primary-solid sm">Search</button>
                        </form>

                        <select class="filter-select">
                            <option selected>Last 7 Days</option>
                            <option>Today</option>
                            <option>Yesterday</option>
                            <option>1 Week</option>
                            <option>Month</option>
                            <option>Year</option>
                        </select>

                        <select class="filter-select">
                            <option>All Status</option>
                            <option>Paid</option>
                            <option>Pending</option>
                            <option>Overdue</option>
                        </select>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL</th>
                                <th>Order ID</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Order Date</th>
                                <th>Due Date</th>
                                <th>Order Cost</th>
                                <th>Paid Amount</th>
                                <th>Total Due</th>
                                <th>Status</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <!-- Row 1 -->
                            <tr>
                                <td>1</td>
                                <td><span class="mono">#ORD-2847</span></td>
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
                                    <div class="ln">Raj Sharma</div>
                                    <div class="ls">9565528200</div>
                                </td>
                                <td>
                                    <div class="ls">10 Jan 2025</div>
                                </td>
                                <td><span class="date-cell ok">10 Feb 2025</span></td>
                                <td><span class="money-cell">₹8,50,000</span></td>
                                <td><span class="money-cell" style="color:#10b981">₹8,50,000</span></td>
                                <td><span class="money-cell" style="color:#10b981">₹0</span></td>
                                <td><span class="status-pill paid">Paid</span></td>
                                <td>
                                    <div class="ln">Priya Mehta</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('PaymentDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-telephone-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-envelope-fill"></i></button>
                                        <button class="ra-btn" onclick="openModal('editPaymentModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Row 2 -->
                            <tr>
                                <td>2</td>
                                <td><span class="mono">#ORD-2848</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">NT</div>
                                        <div>
                                            <div class="ln">NovaTech Solutions</div>
                                            <div class="ls">info@novatech.in</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">Anita Verma</div>
                                    <div class="ls">9812345678</div>
                                </td>
                                <td>
                                    <div class="ls">15 Jan 2025</div>
                                </td>
                                <td><span class="date-cell warn">15 Mar 2025</span></td>
                                <td><span class="money-cell">₹12,00,000</span></td>
                                <td><span class="money-cell" style="color:#10b981">₹6,00,000</span></td>
                                <td><span class="money-cell" style="color:#ef4444">₹6,00,000</span></td>
                                <td><span class="status-pill pending">Pending</span></td>
                                <td>
                                    <div class="ln">Arjun Singh</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <button class="ra-btn" onclick="openModal('PaymentDetailModal')"><i class="bi bi-eye-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-telephone-fill"></i></button>
                                        <button class="ra-btn"><i class="bi bi-envelope-fill"></i></button>
                                        <button class="ra-btn" onclick="openModal('editPaymentModal')"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="ra-btn danger" onclick="openModal('deleteModal')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>

                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="table-footer">
                    <span class="tf-info">Showing 5 of 24 Payments</span>
                    <div class="tf-pagination">
                        <button class="pg-btn"><i class="bi bi-chevron-left"></i></button>
                        <button class="pg-btn active">1</button>
                        <button class="pg-btn">2</button>
                        <button class="pg-btn">3</button>
                        <span class="pg-ellipsis">…</span>
                        <button class="pg-btn">5</button>
                        <button class="pg-btn"><i class="bi bi-chevron-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Edit Payment Modal -->
    <div class="modal-backdrop" id="editPaymentModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Update Payment</span>
                <button class="modal-close" onclick="closeModal('editPaymentModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company Name*</label><input type="text" class="form-inp" placeholder="Company name"></div>
                    <div class="form-row"><label class="form-lbl">Client Name*</label><input type="text" class="form-inp" placeholder="Full name"></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input type="email" class="form-inp" placeholder="email@company.com"></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input type="tel" class="form-inp" placeholder="+91 XXXXX XXXXX"></div>
                    <div class="form-row"><label class="form-lbl">Order Cost*</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
                    <div class="form-row"><label class="form-lbl">Paid Amount*</label><input type="text" class="form-inp" placeholder="₹ Amount"></div>
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
                        <label class="form-lbl">Status</label>
                        <select class="form-inp">
                            <option>Pending</option>
                            <option>Partial</option>
                            <option>Paid</option>
                            <option>Overdue</option>
                        </select>
                    </div>
                    <div class="form-row"><label class="form-lbl">Order Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row"><label class="form-lbl">Due Date</label><input type="date" class="form-inp"></div>
                    <div class="form-row" style="grid-column:1/-1"><label class="form-lbl">Address</label><textarea class="form-inp" rows="3" placeholder="Full address…"></textarea></div>
                </div>
            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('editPaymentModal')">Cancel</button>
                <button class="btn-primary-solid" onclick="closeModal('editPaymentModal')">
                    <i class="bi bi-check-lg"></i> Update Payment
                </button>
            </div>
        </div>
    </div>


    <!-- Delete Modal -->
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Payment</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this Payment?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="closeModal('deleteModal')">
                    <i class="bi bi-trash3-fill"></i> Delete Payment
                </button>
            </div>
        </div>
    </div>


    <!-- Payment Detail Modal -->
    <div class="modal-backdrop" id="PaymentDetailModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <span>Payment #ORD-2847</span>
                <button class="modal-close" onclick="closeModal('PaymentDetailModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd">
                <div class="detail-kpis" style="margin-bottom:20px">
                    <div class="dk-item">
                        <div class="dk-val">₹8.5L</div>
                        <div class="dk-lbl">Order Cost</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#10b981">₹8.5L</div>
                        <div class="dk-lbl">Paid Amount</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#10b981">₹0</div>
                        <div class="dk-lbl">Total Due</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#10b981">Paid</div>
                        <div class="dk-lbl">Status</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-row"><label class="form-lbl">Company Name</label><input class="form-inp" value="DataFirst Corp" readonly></div>
                    <div class="form-row"><label class="form-lbl">Client Name</label><input class="form-inp" value="Raj Sharma" readonly></div>
                    <div class="form-row"><label class="form-lbl">Email</label><input class="form-inp" value="cto@datafirst.io" readonly></div>
                    <div class="form-row"><label class="form-lbl">Phone</label><input class="form-inp" value="9565528200" readonly></div>
                    <div class="form-row"><label class="form-lbl">Order Date</label><input class="form-inp" value="10 Jan 2025" readonly></div>
                    <div class="form-row"><label class="form-lbl">Due Date</label><input class="form-inp" value="10 Feb 2025" readonly></div>
                    <div class="form-row"><label class="form-lbl">Payment Terms</label><input class="form-inp" value="Full Advance" readonly></div>
                    <div class="form-row"><label class="form-lbl">Service</label><input class="form-inp" value="Web Development" readonly></div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Assign To</label>
                        <select class="form-inp">
                            <option>Priya Mehta</option>
                            <option>Arjun Singh</option>
                            <option>Kiran Rao</option>
                            <option>Dev Nair</option>
                        </select>
                    </div>
                    <div class="form-row" style="grid-column:1/-1">
                        <label class="form-lbl">Notes</label>
                        <textarea class="form-inp" cols="30" rows="3" readonly>Second installment received</textarea>
                    </div>
                </div>

                <!-- Payment Screenshot / Proof -->
                <div style="margin-top:20px;border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:8px;padding:10px 14px;background:var(--bg3);border-bottom:1px solid var(--b1);">
                        <div style="width:28px;height:28px;border-radius:7px;background:rgba(99,102,241,.12);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-image-fill" style="font-size:13px;color:var(--accent);"></i>
                        </div>
                        <span style="font-size:12px;font-weight:700;color:var(--t2);letter-spacing:.03em;">Payment Screenshot / Proof</span>
                        <a href="https://aniportalimages.s3.amazonaws.com/media/details/ANI-20220923040255.jpeg"
                            target="_blank"
                            style="margin-left:auto;display:flex;align-items:center;gap:5px;font-size:11px;font-weight:600;color:var(--accent);text-decoration:none;padding:3px 9px;border-radius:6px;border:1px solid var(--accent);background:var(--accent-bg);transition:var(--transition);"
                            onmouseover="this.style.opacity='.8'" onmouseout="this.style.opacity='1'">
                            <i class="bi bi-box-arrow-up-right"></i> Open Full
                        </a>
                    </div>
                    <div style="background:var(--bg4);padding:12px;display:flex;justify-content:center;align-items:center;">
                        <img src="https://aniportalimages.s3.amazonaws.com/media/details/ANI-20220923040255.jpeg"
                            alt="Payment Screenshot"
                            style="width:100%;max-height:280px;object-fit:contain;border-radius:var(--r-sm);display:block;">
                    </div>
                </div>

            </div>
            <div class="modal-ft">
                <button class="btn-ghost" onclick="closeModal('PaymentDetailModal')">Close</button>
            </div>
        </div>
    </div>

</main>
@endsection