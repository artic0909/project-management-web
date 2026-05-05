@extends('admin.layout.app')

@section('title', 'Invoices List')

@section('content')
<style>
    /* Dark Mode Fixes for Index */
    .filter-grid {
        background: var(--bg2);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--b3);
    }

    .table-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 24px;
        background: var(--bg2);
        border-top: 1px solid var(--b3);
    }

    .tf-info {
        font-size: 13px;
        color: var(--t3);
        font-weight: 500;
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page" id="page-invoices-index">
        <div class="page-header">
            <div>
                <h1 class="page-title">Invoices</h1>
                <p class="page-desc">Generate and manage client invoices</p>
            </div>
            <div class="header-actions">
                <a href="{{ route($routePrefix . '.invoices.create') }}" class="btn-primary-solid sm">
                    <i class="bi bi-plus-lg"></i> Create Invoice
                </a>
            </div>
        </div>

        <!-- Filters -->
        <!-- <div class="dash-card" style="margin-bottom: 24px; border:none; background:transparent;">
            <div class="card-body" style="padding:0;">
                <form action="{{ route($routePrefix . '.invoices.index') }}" method="GET" class="filter-grid"
                    style="display: grid; grid-template-columns: 1fr 0.8fr 0.8fr 180px; gap: 15px; align-items: flex-end;">
                    <div class="form-row">
                        <label class="form-lbl" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7;">Search Invoices</label>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-inp"
                            placeholder="Invoice No, Client Name..." style="height: 42px;">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7;">From Date</label>
                        <input type="date" name="from_date" value="{{ request('from_date') }}" class="form-inp" style="height: 42px;">
                    </div>
                    <div class="form-row">
                        <label class="form-lbl" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px; opacity: 0.7;">To Date</label>
                        <input type="date" name="to_date" value="{{ request('to_date') }}" class="form-inp" style="height: 42px;">
                    </div>
                    <div class="form-actions" style="display: flex; gap: 8px;">
                        <button type="submit" class="btn-primary-solid" style="flex: 1; height: 42px; display: flex; align-items: center; justify-content: center; gap: 8px; font-weight: 700; border-radius: 8px;">
                            <i class="bi bi-search" style="font-size: 14px;"></i> <span>Search</span>
                        </button>
                        <a href="{{ route($routePrefix . '.invoices.index') }}" class="btn-ghost"
                            style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; border-radius: 8px; border: 1px solid var(--b3); background: var(--bg3);" 
                            title="Clear Filters">
                            <i class="bi bi-arrow-counterclockwise" style="font-size: 18px;"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div> -->

        <div class="dash-card">
            <div class="card-body" style="padding: 0;">
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>SL.</th>
                                <th>Invoice No</th>
                                <th>Date</th>
                                <th>Client</th>
                                <th>Order #</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($invoices as $invoice)
                                <tr>
                                    <td style="color:var(--t4);font-size:12px;font-weight:600;">
                                        {{ $loop->iteration + ($invoices->currentPage() - 1) * $invoices->perPage() }}</td>
                                    <td>
                                        <div style="font-weight: 700; color: var(--accent);">{{ $invoice->invoice_no }}
                                        </div>
                                    </td>
                                    <td><span
                                            style="font-size: 13px; font-weight: 500;">{{ $invoice->invoice_date->format('d M, Y') }}</span>
                                    </td>
                                    <td>
                                        <div style="font-weight: 600; color: var(--t1);">{{ $invoice->client_name }}</div>
                                        <div style="font-size: 11px; color: var(--t3);">
                                            {{ $invoice->client_gstin ?? 'No GSTIN' }}</div>
                                    </td>
                                    <td>
                                        @if($invoice->order)
                                            <a href="{{ route($routePrefix . '.orders.show', $invoice->order_id) }}"
                                                style="text-decoration:none;">
                                                <span class="mono"
                                                    style="color: var(--accent); font-weight: 700;">#{{ $invoice->order->order_number }}</span>
                                            </a>
                                        @else
                                            <span style="color: var(--t4);">—</span>
                                        @endif
                                    </td>
                                    <td><span
                                            style="font-weight: 800; color: #10b981; font-size: 15px;">₹{{ number_format($invoice->total, 2) }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusColor = '#6366f1';
                                            if($invoice->status == 'PAID') $statusColor = '#10b981';
                                            if($invoice->status == 'UNPAID') $statusColor = '#ef4444';
                                            if($invoice->status == 'PROFORMA') $statusColor = '#f59e0b';
                                        @endphp
                                        <span class="status-pill" style="background:{{ $statusColor }}20; color:{{ $statusColor }}; border: 1px solid {{ $statusColor }}30;">
                                            {{ $invoice->status }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="row-actions">
                                            <a href="{{ route($routePrefix . '.invoices.show', $invoice->id) }}"
                                                class="ra-btn" title="View/Print" target="_blank">
                                                <i class="bi bi-printer-fill"></i>
                                            </a>
                                            <a href="{{ route($routePrefix . '.invoices.copy', $invoice->id) }}"
                                                class="ra-btn" title="Duplicate">
                                                <i class="bi bi-files"></i>
                                            </a>
                                            <a href="{{ route($routePrefix . '.invoices.edit', $invoice->id) }}"
                                                class="ra-btn" title="Edit">
                                                <i class="bi bi-pencil-fill"></i>
                                            </a>
                                            <form action="{{ route($routePrefix . '.invoices.destroy', $invoice->id) }}"
                                                method="POST" onsubmit="return confirm('Delete this invoice?')"
                                                style="margin:0;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="ra-btn danger" title="Delete">
                                                    <i class="bi bi-trash-fill"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" style="text-align: center; padding: 60px;">
                                        <div style="opacity: 0.2;">
                                            <i class="bi bi-receipt"
                                                style="font-size: 64px; display: block; margin-bottom: 10px;"></i>
                                            <span style="font-size: 18px; font-weight: 600;">No Invoices Found</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="table-footer">
                    <span class="tf-info">Showing {{ $invoices->firstItem() ?? 0 }} to {{ $invoices->lastItem() ?? 0 }}
                        of {{ $invoices->total() }} Invoices</span>
                    <div class="tf-pagination">
                        {{ $invoices->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection