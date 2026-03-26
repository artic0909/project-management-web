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

        {{-- SUMMARY BOXES --}}
        <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;max-width:600px;">

            {{-- 1. Paid Amount --}}
            <div class="dash-card" style="padding:20px 22px;">
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                    <div style="width:44px;height:44px;border-radius:11px;background:rgba(16,185,129,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-check-circle-fill" style="font-size:20px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Collected</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#10b981;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹{{ number_format($totalCollected / 100000, 2) }}L</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Paid Amount (₹{{ number_format($totalCollected, 0) }})</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalValue > 0 ? ($totalCollected / $totalValue) * 100 : 0 }}%;background:#10b981;border-radius:4px;"></div>
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
                <div style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">₹{{ number_format($totalOutstanding / 100000, 2) }}L</div>
                <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Unpaid Amount (₹{{ number_format($totalOutstanding, 0) }})</div>
                <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalValue > 0 ? (max(0, $totalOutstanding) / $totalValue) * 100 : 0 }}%;background:#ef4444;border-radius:4px;"></div>
                </div>
            </div>

        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Recent Payments</div>
                        <div class="card-sub">Showing status for {{ $orders->count() }} orders</div>
                    </div>
                    <div class="card-actions mb-2">
                        <form action="{{ route('admin.payments.index') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Search...">
                                <button type="submit" class="btn-primary-solid sm">Search</button>
                            </div>
                            
                            <select name="status_id" class="filter-select" onchange="this.form.submit()">
                                <option value="">All Status</option>
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
                                <th>Order ID</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Order Date</th>
                                <th>Order Value</th>
                                <th>Paid Amount</th>
                                <th>Total Due</th>
                                <th>Status</th>
                                <th>Assign To</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            @php 
                                $paid = $order->payments->sum('amount');
                                $due = $order->order_value - $paid;
                                $progress = $order->order_value > 0 ? ($paid / $order->order_value) * 100 : 0;
                            @endphp
                            <tr>
                                <td><span class="mono">#ORD-{{ 1000 + $order->id }}</span></td>
                                <td>
                                    <div class="lead-cell">
                                        <div class="mini-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ strtoupper(substr($order->company_name, 0, 2)) }}</div>
                                        <div>
                                            <div class="ln">{{ $order->company_name }}</div>
                                            <div class="ls">{{ $order->emails[0] ?? 'N/A' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="ln">{{ $order->client_name }}</div>
                                    <div class="ls">{{ $order->phones[0]['number'] ?? 'N/A' }}</div>
                                </td>
                                <td><div class="ls">{{ $order->created_at->format('d M Y') }}</div></td>
                                <td><span class="money-cell">₹{{ number_format($order->order_value, 0) }}</span></td>
                                <td><span class="money-cell" style="color:#10b981">₹{{ number_format($paid, 0) }}</span></td>
                                <td><span class="money-cell" style="color:{{ $due > 0 ? '#ef4444' : '#10b981' }}">₹{{ number_format($due, 0) }}</span></td>
                                <td>
                                    <span class="status-pill {{ $progress >= 100 ? 'paid' : ($progress > 0 ? 'partial' : 'pending') }}">
                                        {{ $progress >= 100 ? 'Paid' : ($progress > 0 ? 'Partial' : 'Pending') }}
                                    </span>
                                </td>
                                <td>
                                    <div class="ln">{{ $order->assignments->first()->sale->name ?? 'N/A' }}</div>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.payments.create', $order->id) }}" class="ra-btn"><i class="bi bi-wallet2"></i></a>
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="ra-btn"><i class="bi bi-eye-fill"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing {{ $orders->count() }} Orders</span>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection