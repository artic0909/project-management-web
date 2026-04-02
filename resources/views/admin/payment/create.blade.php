@extends('admin.layout.app')

@section('title', 'Add Payment — #ORD-' . (1000 + $order->id))

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Manage Payments</h1>
                <p class="page-desc">Order <span class="mono">#ORD-{{ 1000 + $order->id }}</span> — {{ $order->company_name }}</p>
            </div>
            <div class="header-actions">
                <a href="{{ route($routePrefix . '.payments.index') }}" class="btn-ghost"><i class="bi bi-arrow-left"></i> Back to Payments</a>
            </div>
        </div>

        <div class="dash-grid">

            <!-- ═══ LEFT COL — Order Info + Payment Form ═══ -->
            <div class="span-8">

                {{-- Order Summary Card --}}
                <div class="dash-card" style="margin-bottom:16px">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Order Details</div>
                            <div class="card-sub">Core information for this order</div>
                        </div>
                        <span class="status-pill {{ $order->status->name == 'Cancelled' ? 'danger' : 'pending' }}">{{ $order->status->name ?? 'Pending' }}</span>
                    </div>
                    <div class="card-body">
                        @php 
                           $paid_total = $order->payments->sum('amount');
                           $due_total = max(0, $order->order_value - $paid_total);
                           $collected_percentage = $order->order_value > 0 ? ($paid_total / $order->order_value) * 100 : 0;
                        @endphp
                        {{-- KPI Strip --}}
                        <div class="detail-kpis" style="margin-bottom:20px">
                            <div class="dk-item">
                                <div class="dk-val">₹{{ number_format($order->order_value, 0) }}</div>
                                <div class="dk-lbl">Order Value</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:#10b981">₹{{ number_format($paid_total, 0) }}</div>
                                <div class="dk-lbl">Total Paid</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:#ef4444">₹{{ number_format($due_total, 0) }}</div>
                                <div class="dk-lbl">Total Due</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val">{{ number_format($collected_percentage, 0) }}%</div>
                                <div class="dk-lbl">Collected</div>
                            </div>
                        </div>

                        {{-- Progress Bar --}}
                        <div style="margin-bottom:20px">
                            <div style="display:flex;justify-content:space-between;font-size:12px;color:var(--t3);margin-bottom:6px">
                                <span>Payment Progress</span>
                                <span style="font-weight:700;color:var(--t1)">{{ number_format($collected_percentage, 0) }}% collected</span>
                            </div>
                            <div class="prog-bar-wrap" style="height:8px; background: var(--b1); border-radius: 4px; overflow: hidden;">
                                <div class="prog-fill" style="height: 100%; width:{{ $collected_percentage }}%; background:#6366f1"></div>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div class="form-row">
                                <label class="form-lbl">Company</label>
                                <div style="display:flex;align-items:center;gap:9px;padding:9px 12px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm)">
                                    <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ strtoupper(substr($order->company_name, 0, 2)) }}</div>
                                    <div>
                                        <div class="ln" style="font-size:13px">{{ $order->company_name }}</div>
                                        <div class="ls">{{ $order->emails[0] ?? '' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Contact Person</label>
                                <div style="padding:9px 12px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm)">
                                    <div class="ln" style="font-size:13px">{{ $order->client_name }}</div>
                                    <div class="ls">{{ $order->phones[0]['number'] ?? '' }}</div>
                                </div>
                            </div>
                            <div class="form-row"><label class="form-lbl">Order Date</label><input class="form-inp" value="{{ $order->created_at->format('d M Y') }}" readonly></div>
                            <div class="form-row"><label class="form-lbl">Service</label><input class="form-inp" value="{{ $order->service->name ?? 'N/A' }}" readonly></div>
                        </div>
                    </div>
                </div>

                {{-- Add Payment Form --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div>
                            <div class="card-title"><i class="bi bi-plus-circle" style="color:var(--accent);margin-right:6px"></i>Add New Payment Entry</div>
                            <div class="card-sub">Record an installment or final payment</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route($routePrefix . '.payments.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="order_id" value="{{ $order->id }}">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Payment Date <span style="color:#ef4444">*</span></label>
                                    <input type="date" name="transaction_date" class="form-inp" value="{{ date('Y-m-d') }}" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Amount Received (₹) <span style="color:#ef4444">*</span></label>
                                    <input type="number" name="amount" class="form-inp" placeholder="e.g. 50000" step="0.01" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Mode</label>
                                    <select name="payment_method" class="form-inp">
                                        <option value="Bank Transfer">Bank Transfer</option>
                                        <option value="UPI">UPI / GPay / PhonePe</option>
                                        <option value="Cheque">Cheque</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Reference / UTR ID</label>
                                    <input type="text" name="transaction_id" class="form-inp" placeholder="Transaction ref code">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Payment Proof / Screenshot</label>
                                    <input type="file" name="screenshot" class="form-inp" accept="image/*,application/pdf">
                                    <p style="font-size:11px; color:var(--t3); margin-top:4px;">Upload PNG, JPG, or PDF (Max 5MB)</p>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Internal Notes</label>
                                    <textarea name="notes" class="form-inp" rows="2" placeholder="Mention installment details or bank info..."></textarea>
                                </div>
                            </div>
                            <div style="display:flex;justify-content:flex-end;margin-top:16px;">
                                <button type="submit" class="btn-primary-solid">
                                    <i class="bi bi-plus-lg"></i> Record Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

            <!-- ═══ RIGHT COL — Payment History ═══ -->
            <div class="span-4">
                <div class="dash-card" style="position:sticky;top:80px">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Payment History</div>
                            <div class="card-sub">Audit trail for this order</div>
                        </div>
                        <span class="nav-count">{{ $order->payments->count() }}</span>
                    </div>
                    <div class="card-body" style="padding-top:8px">

                        <div style="display:flex;flex-direction:column;gap:12px">
                            @forelse($order->payments->sortByDesc('transaction_date') as $pay)
                            <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:14px;position:relative; transition: var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--b1)'">
                                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:6px">
                                    <div>
                                        <div style="font-size:14px;font-weight:800;color:var(--t1)">₹{{ number_format($pay->amount, 0) }}</div>
                                        <div style="font-size:11px;color:var(--t3);margin-top:2px">{{ $pay->transaction_date->format('d M Y') }}</div>
                                    </div>
                                    @if($routePrefix == 'admin')
                                    <form action="{{ route($routePrefix . '.payments.destroy', $pay->id) }}" method="POST" onsubmit="return confirm('Delete this payment entry?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="ra-btn danger sm" title="Delete"><i class="bi bi-trash-fill"></i></button>
                                    </form>
                                    @endif
                                </div>
                                <div style="display:flex;flex-wrap:wrap;gap:6px;font-size:11.5px;color:var(--t2)">
                                    <span><i class="bi bi-credit-card" style="margin-right:2px"></i> {{ $pay->payment_method ?? 'N/A' }}</span>
                                    @if($pay->transaction_id)
                                    <span style="color:var(--t4)">·</span>
                                    <span class="mono" style="font-size:10px">{{ $pay->transaction_id }}</span>
                                    @endif
                                </div>
                                @if($pay->screenshot)
                                <div style="margin-top:10px; padding-top:10px; border-top:1px solid var(--b1);">
                                    <a href="{{ asset('storage/' . $pay->screenshot) }}" target="_blank" style="display:flex; align-items:center; gap:6px; font-size:11.5px; color:var(--accent); text-decoration:none; font-weight:600;">
                                        <i class="bi bi-file-earmark-image"></i> View Proof
                                    </a>
                                </div>
                                @endif
                                @if($pay->notes)
                                <div style="font-size:11px; color:var(--t3); margin-top:8px; line-height:1.4; font-style:italic;">
                                    "{{ $pay->notes }}"
                                </div>
                                @endif
                            </div>
                            @empty
                            <div style="text-align:center; padding:30px 10px; color:var(--t4); font-size:13px; font-style:italic;">
                                No payments recorded yet.
                            </div>
                            @endforelse
                        </div>

                        {{-- Final Balances --}}
                        <div style="margin-top:14px;padding-top:12px;border-top:1px solid var(--b1)">
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:7px">
                                <span style="color:var(--t3)">Total Value</span>
                                <span class="money-cell">₹{{ number_format($order->order_value, 0) }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:12.5px;margin-bottom:7px">
                                <span style="color:var(--t3)">Total Received</span>
                                <span class="money-cell" style="color:#10b981">₹{{ number_format($paid_total, 0) }}</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;font-size:14px;font-weight:800;padding-top:10px;border-top:2px solid var(--b1); margin-top:5px;">
                                <span style="color:var(--t1)">Balance Due</span>
                                <span class="money-cell" style="color:#ef4444">₹{{ number_format($due_total, 0) }}</span>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</main>

<style>
    .detail-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; }
    .dk-item { text-align: center; background: var(--bg3); padding: 10px; border-radius: 8px; border: 1px solid var(--b1); }
    .dk-val { font-size: 16px; font-weight: 800; color: var(--t1); margin-bottom: 2px; }
    .dk-lbl { font-size: 10px; font-weight: 700; color: var(--t4); text-transform: uppercase; letter-spacing: 0.05em; }
    
    .money-cell { font-family: var(--mono); font-weight: 700; }
    
    .ra-btn.sm { width: 28px; height: 28px; font-size: 12px; }
</style>

@endsection