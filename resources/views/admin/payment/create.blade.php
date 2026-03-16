@extends('admin.layout.app')

@section('title', 'Add Payment — #ORD-2848')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Add Payment</h1>
                <p class="page-desc">Order <span class="mono">#ORD-2848</span> — NovaTech Solutions</p>
            </div>
            <div class="header-actions">
                <a href="#" class="btn-ghost"><i class="bi bi-arrow-left"></i> Back to Payments</a>
            </div>
        </div>

        <div class="dash-grid">

            <!-- ═══ LEFT COL — Order Info + Payment Form ═══ -->
            <div class="span-8">

                <!-- Order Summary Card -->
                <div class="dash-card" style="margin-bottom:16px">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Order Details</div>
                            <div class="card-sub">Full information for this order</div>
                        </div>
                        <span class="status-pill pending">Pending</span>
                    </div>
                    <div class="card-body">

                        <!-- KPI Strip -->
                        <div class="detail-kpis" style="margin-bottom:20px">
                            <div class="dk-item">
                                <div class="dk-val">₹12,00,000</div>
                                <div class="dk-lbl">Order Cost</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:#10b981">₹6,00,000</div>
                                <div class="dk-lbl">Total Paid</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:#ef4444">₹6,00,000</div>
                                <div class="dk-lbl">Total Due</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val">50%</div>
                                <div class="dk-lbl">Collected</div>
                            </div>
                        </div>

                        <!-- Progress Bar -->
                        <div style="margin-bottom:20px">
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--t3);margin-bottom:6px">
                                <span>Payment Progress</span>
                                <span style="font-weight:700;color:var(--t1)">50% collected</span>
                            </div>
                            <div class="prog-bar-wrap" style="height:8px">
                                <div class="prog-fill" style="width:50%;background:#6366f1"></div>
                            </div>
                        </div>

                        <!-- Info Grid -->
                        <div class="form-grid">
                            <div class="form-row">
                                <label class="form-lbl">Company</label>
                                <div style="display:flex;align-items:center;gap:9px;padding:9px 12px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm)">
                                    <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">NT</div>
                                    <div>
                                        <div class="ln" style="font-size:13px">NovaTech Solutions</div>
                                        <div class="ls">info@novatech.in</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Contact Person</label>
                                <div style="padding:9px 12px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm)">
                                    <div class="ln" style="font-size:13px">Anita Verma</div>
                                    <div class="ls">9812345678</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Order Date</label>
                                <input class="form-inp" value="15 Jan 2025" readonly>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Due Date</label>
                                <input class="form-inp" value="15 Mar 2025" readonly style="color:#f59e0b">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Service / Product</label>
                                <input class="form-inp" value="Mobile App Development" readonly>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Payment Terms</label>
                                <input class="form-inp" value="50-50" readonly>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Assigned To</label>
                                <input class="form-inp" value="Arjun Singh" readonly>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Created By</label>
                                <input class="form-inp" value="Priya Mehta" readonly>
                            </div>
                            <div class="form-row" style="grid-column:1/-1">
                                <label class="form-lbl">Address</label>
                                <input class="form-inp" value="302, Skyline Tower, Bandra West, Mumbai — 400050, Maharashtra" readonly>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Add Payment Form -->
                <div class="dash-card">
                    <div class="card-head">
                        <div>
                            <div class="card-title"><i class="bi bi-plus-circle" style="color:var(--accent);margin-right:6px"></i>Add New Payment</div>
                            <div class="card-sub">Record a payment received for this order</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="form-row">
                                <label class="form-lbl">Payment Date <span style="color:#ef4444">*</span></label>
                                <input type="date" class="form-inp" id="paymentDate" value="2025-03-16">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Amount Received (₹) <span style="color:#ef4444">*</span></label>
                                <input type="number" class="form-inp" id="paymentAmount" placeholder="e.g. 200000">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Payment Mode</label>
                                <select class="form-inp" id="paymentMode">
                                    <option value="">Select mode</option>
                                    <option>Bank Transfer</option>
                                    <option>UPI</option>
                                    <option>Cheque</option>
                                    <option>Cash</option>
                                    <option>Credit Card</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Reference / Transaction ID</label>
                                <input type="text" class="form-inp" id="paymentRef" placeholder="UTR / Cheque No.">
                            </div>
                            <div class="form-row" style="grid-column:1/-1">
                                <label class="form-lbl">Note</label>
                                <input type="text" class="form-inp" id="paymentNote" placeholder="e.g. Second installment received">
                            </div>
                        </div>
                        <div style="display:flex;gap:8px;justify-content:flex-end;margin-top:4px">
                            <button class="btn-ghost" type="button" onclick="clearForm()">
                                <i class="bi bi-x-lg"></i> Clear
                            </button>
                            <button class="btn-primary-solid" type="button" onclick="addPaymentEntry()">
                                <i class="bi bi-plus-lg"></i> Add Payment
                            </button>
                        </div>
                    </div>
                </div>

            </div>

            <!-- ═══ RIGHT COL — Payment History ═══ -->
            <div class="span-4">
                <div class="dash-card" style="position:sticky;top:80px">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Payment History</div>
                            <div class="card-sub">All payments for this order</div>
                        </div>
                        <span class="nav-count" id="paymentCount">1</span>
                    </div>
                    <div class="card-body" style="padding-top:8px">

                        <!-- Payment Entries List -->
                        <div id="paymentList" style="display:flex;flex-direction:column;gap:10px">

                            <!-- Static entry 1 -->
                            <div class="payment-entry" style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:12px;position:relative">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
                                    <div>
                                        <div style="font-size:13px;font-weight:700;color:var(--t1)">₹6,00,000</div>
                                        <div style="font-size:11px;color:var(--t3);margin-top:2px">15 Jan 2025</div>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:6px">
                                        <span class="status-pill paid" style="font-size:10px;padding:2px 7px">Paid</span>
                                        <button class="ra-btn danger" style="width:24px;height:24px;font-size:11px" title="Remove"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:6px;font-size:11.5px;color:var(--t3)">
                                    <span><i class="bi bi-credit-card" style="margin-right:3px"></i>Bank Transfer</span>
                                    <span style="color:var(--t4)">·</span>
                                    <span class="mono" style="font-size:11px">UTR8823401</span>
                                </div>
                                <div style="font-size:11.5px;color:var(--t3);margin-top:4px">
                                    <i class="bi bi-chat-left-text" style="margin-right:3px"></i>First installment received
                                </div>
                            </div>

                        </div>

                        <!-- Summary Footer -->
                        <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--b1)">
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:7px">
                                <span style="color:var(--t3)">Order Cost</span>
                                <span class="money-cell">₹12,00,000</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:7px">
                                <span style="color:var(--t3)">Total Paid</span>
                                <span class="money-cell" style="color:#10b981" id="totalPaidDisplay">₹6,00,000</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:700;padding-top:8px;border-top:1px dashed var(--b2)">
                                <span style="color:var(--t2)">Balance Due</span>
                                <span class="money-cell" style="color:#ef4444" id="totalDueDisplay">₹6,00,000</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<script>
    // Set today's date on load
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('paymentDate').value = today;
    });

    const orderCost = 1200000;
    let totalPaid = 600000;

    function fmtINR(n) {
        return '₹' + n.toLocaleString('en-IN');
    }

    function updateSummary() {
        document.getElementById('totalPaidDisplay').textContent = fmtINR(totalPaid);
        document.getElementById('totalDueDisplay').textContent = fmtINR(Math.max(0, orderCost - totalPaid));
        document.getElementById('paymentCount').textContent = document.querySelectorAll('.payment-entry').length;
    }

    function addPaymentEntry() {
        const date = document.getElementById('paymentDate').value;
        const amount = parseFloat(document.getElementById('paymentAmount').value);
        const mode = document.getElementById('paymentMode').value;
        const ref = document.getElementById('paymentRef').value;
        const note = document.getElementById('paymentNote').value;

        if (!date || !amount || amount <= 0) {
            alert('Please enter a valid date and amount.');
            return;
        }

        totalPaid += amount;

        const fmtDate = new Date(date).toLocaleDateString('en-IN', {
            day: '2-digit',
            month: 'short',
            year: 'numeric'
        });
        const entry = document.createElement('div');
        entry.className = 'payment-entry';
        entry.style.cssText = 'background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:12px;position:relative;animation:pageIn .2s ease';
        entry.innerHTML = `
        <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
            <div>
                <div style="font-size:13px;font-weight:700;color:var(--t1)">${fmtINR(amount)}</div>
                <div style="font-size:11px;color:var(--t3);margin-top:2px">${fmtDate}</div>
            </div>
            <div style="display:flex;align-items:center;gap:6px">
                <span class="status-pill paid" style="font-size:10px;padding:2px 7px">Paid</span>
                <button class="ra-btn danger" style="width:24px;height:24px;font-size:11px" title="Remove" onclick="removeEntry(this, ${amount})"><i class="bi bi-trash-fill"></i></button>
            </div>
        </div>
        ${mode ? `<div style="display:flex;flex-wrap:wrap;gap:6px;font-size:11.5px;color:var(--t3)"><span><i class="bi bi-credit-card" style="margin-right:3px"></i>${mode}</span>${ref ? `<span style="color:var(--t4)">·</span><span class="mono" style="font-size:11px">${ref}</span>` : ''}</div>` : ''}
        ${note ? `<div style="font-size:11.5px;color:var(--t3);margin-top:4px"><i class="bi bi-chat-left-text" style="margin-right:3px"></i>${note}</div>` : ''}
    `;

        document.getElementById('paymentList').appendChild(entry);
        updateSummary();
        clearForm();
    }

    function removeEntry(btn, amount) {
        btn.closest('.payment-entry').remove();
        totalPaid -= amount;
        updateSummary();
    }

    function clearForm() {
        document.getElementById('paymentAmount').value = '';
        document.getElementById('paymentMode').value = '';
        document.getElementById('paymentRef').value = '';
        document.getElementById('paymentNote').value = '';
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('paymentDate').value = today;
    }
</script>

@endsection