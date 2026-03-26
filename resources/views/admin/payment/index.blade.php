@extends('admin.layout.app')

@section('title', 'All Transactions')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Payment Transactions</h1>
                <p class="page-desc">Audit trail for all payments received across all orders</p>
            </div>
        </div>

        {{-- SUMMARY BOXES --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;max-width:600px;">
            <div class="dash-card" style="padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:11px;background:rgba(16,185,129,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check-circle-fill" style="font-size:20px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Collected</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#10b981;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹{{ number_format($totalCollected, 0) }}</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Collected Amount</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrderValue > 0 ? (min(100, ($totalCollected / $totalOrderValue) * 100)) : 0 }}%;background:#10b981;border-radius:4px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:11px;background:rgba(239,68,68,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-hourglass-split" style="font-size:20px;color:#ef4444;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(239,68,68,.1);color:#ef4444;">Outstanding</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹{{ number_format($totalOutstanding, 0) }}</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Outstanding Balance</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrderValue > 0 ? (max(0, $totalOutstanding) / $totalOrderValue) * 100 : 0 }}%;background:#ef4444;border-radius:4px;"></div>
                </div>
            </div>
        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">All Transactions</div>
                        <div class="card-sub">Total {{ $payments->count() }} transaction entries</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form action="{{ route('admin.payments.index') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search Company / Order #...">
                                <button type="submit" class="btn-primary-solid sm">Search</button>
                            </div>
                            
                            <select name="status_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">Payment Status</option>
                                @foreach($allStatuses as $st)
                                    <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Order ID</th>
                                <th>Company</th>
                                <th>Contact</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Trans ID / Ref</th>
                                <th>By</th>
                                <th>Proof</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $pay)
                            <tr>
                                <td><div class="ls">{{ $pay->transaction_date->format('d M Y') }}</div></td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $pay->order_id) }}" style="text-decoration:none">
                                        <span class="mono" style="color:var(--accent); font-weight:700;">#ORD-{{ 1000 + $pay->order_id }}</span>
                                    </a>
                                </td>
                                <td>
                                    <div class="ln">{{ $pay->order->company_name }}</div>
                                    <div class="ls" style="font-size:10px">{{ $pay->order->emails[0] ?? '' }}</div>
                                </td>
                                <td>
                                    <div class="ln">{{ $pay->order->client_name }}</div>
                                    <div class="ls">{{ $pay->order->phones[0]['number'] ?? '' }}</div>
                                </td>
                                <td><span class="src-tag google-type" style="padding:2px 7px;font-size:10px">{{ $pay->payment_method ?? 'N/A' }}</span></td>
                                <td><span class="money-cell" style="color:#10b981; font-size:14px;">₹{{ number_format($pay->amount, 0) }}</span></td>
                                <td><span class="mono" style="font-size:11px">{{ $pay->transaction_id ?? 'N/A' }}</span></td>
                                <td>
                                    <div class="ln">{{ $pay->createdBy instanceof \App\Models\Admin ? 'System' : ($pay->createdBy->name ?? 'N/A') }}</div>
                                </td>
                                <td>
                                    @if($pay->screenshot)
                                        <a href="{{ asset('storage/' . $pay->screenshot) }}" target="_blank" class="ra-btn sm" title="View Proof"><i class="bi bi-file-earmark-image"></i></a>
                                    @else
                                        <span style="color:var(--t4); font-style:italic; font-size:10px">No Proof</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.payments.create', $pay->order_id) }}" class="ra-btn" title="View Details"><i class="bi bi-eye"></i></a>
                                        <form action="{{ route('admin.payments.destroy', $pay->id) }}" method="POST" onsubmit="return confirm('Delete this record?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="ra-btn danger"><i class="bi bi-trash-fill"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Total Transactions: {{ $payments->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection