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
            <div id="statsWrap"
                style="display:grid;grid-template-columns:repeat(2,1fr);gap:14px;margin-bottom:24px;max-width:600px;">
                <div class="dash-card" style="padding:20px 22px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div
                            style="width:44px;height:44px;border-radius:11px;background:rgba(16,185,129,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-check-circle-fill" style="font-size:20px;color:#10b981;"></i>
                        </div>
                        <span
                            style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;">Collected</span>
                    </div>
                    <div
                        style="font-size:26px;font-weight:800;color:#10b981;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">
                        ₹{{ number_format($totalCollected, 0) }}</div>
                    <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Collected Amount</div>
                    <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                        <div
                            style="height:100%;width:{{ $totalOrderValue > 0 ? (min(100, ($totalCollected / $totalOrderValue) * 100)) : 0 }}%;background:#10b981;border-radius:4px;">
                        </div>
                    </div>
                </div>

                <div class="dash-card" style="padding:20px 22px;">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:14px;">
                        <div
                            style="width:44px;height:44px;border-radius:11px;background:rgba(239,68,68,.14);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-hourglass-split" style="font-size:20px;color:#ef4444;"></i>
                        </div>
                        <span
                            style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:20px;background:rgba(239,68,68,.1);color:#ef4444;">Outstanding</span>
                    </div>
                    <div
                        style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;font-family:var(--mono);">
                        ₹{{ number_format($totalOutstanding, 0) }}</div>
                    <div style="font-size:12px;color:var(--t3);font-weight:500;margin-top:5px;">Total Outstanding Balance
                    </div>
                    <div style="margin-top:10px;height:4px;border-radius:4px;background:var(--b1);overflow:hidden;">
                        <div
                            style="height:100%;width:{{ $totalOrderValue > 0 ? (max(0, $totalOutstanding) / $totalOrderValue) * 100 : 0 }}%;background:#ef4444;border-radius:4px;">
                        </div>
                    </div>
                </div>
            </div>

            <div class="dash-grid" id="tableWrap">
                <div class="dash-card span-12">
                    <div class="card-head">
                        <div>
                            <div class="card-title">All Transactions</div>
                            <div class="card-sub">Total {{ $payments->count() }} transaction entries</div>
                        </div>
                        <div class="card-actions mb-2">
                            <form action="{{ route($routePrefix . '.payment.index') }}" method="GET"
                                class="card-actions mb-0">
                                <div class="global-search">
                                    <i class="bi bi-search"></i>
                                    <input type="text" name="q" id="searchQuery" value="{{ request('q') }}"
                                        placeholder="Search Company / Order #...">
                                    <button type="submit" class="btn-primary-solid sm" style="display:none;">Search</button>
                                </div>
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
                                        <td>
                                            <div class="ls">{{ $pay->transaction_date->format('d M Y') }}</div>
                                        </td>
                                        <td>
                                            <a href="{{ route($routePrefix . '.orders.show', $pay->order_id) }}"
                                                style="text-decoration:none">
                                                <span class="mono"
                                                    style="color:var(--accent); font-weight:700;">#ORD-{{ 1000 + $pay->order_id }}</span>
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
                                        <td><span class="src-tag google-type"
                                                style="padding:2px 7px;font-size:10px">{{ $pay->payment_method ?? 'N/A' }}</span>
                                        </td>
                                        <td><span class="money-cell"
                                                style="color:#10b981; font-size:14px;">₹{{ number_format($pay->amount, 0) }}</span>
                                        </td>
                                        <td><span class="mono" style="font-size:11px">{{ $pay->transaction_id ?? 'N/A' }}</span>
                                        </td>
                                        <td>
                                            @if($pay->createdBy)
                                                <div class="ln">{{ $pay->createdBy->name }}</div>
                                                @if($pay->created_by_type === \App\Models\Sale::class)
                                                    <div class="ls" style="font-size:10px">{{ $pay->createdBy->email }}</div>
                                                @endif
                                            @else
                                                <div class="ln">System</div>
                                            @endif
                                        </td>
                                        <td>
                                            @if($pay->screenshot)
                                                <a href="{{ asset('storage/' . $pay->screenshot) }}" target="_blank"
                                                    class="ra-btn sm" title="View Proof"><i
                                                        class="bi bi-file-earmark-image"></i></a>
                                            @else
                                                <span style="color:var(--t4); font-style:italic; font-size:10px">No Proof</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="row-actions">
                                                <a href="{{ route($routePrefix . '.payments.create', $pay->order_id) }}"
                                                    class="ra-btn" title="View Details"><i class="bi bi-eye"></i></a>
                                                @if($routePrefix == 'admin')
                                                    <button class="ra-btn danger"
                                                        onclick="confirmDelete('{{ route($routePrefix . '.payments.destroy', $pay->id) }}')">
                                                        <i class="bi bi-trash-fill"></i>
                                                    </button>
                                                @endif
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

    {{-- DELETE MODAL --}}
    <div class="modal-backdrop" id="deleteModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Delete Payment Entry</span>
                <button class="modal-close" onclick="closeModal('deleteModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div
                    style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Delete this transaction?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this
                    payment record?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button
                    style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;"
                    onclick="document.getElementById('deletePaymentForm').submit()">
                    <i class="bi bi-trash3-fill"></i> Confirm Deletion
                </button>
            </div>
        </div>
    </div>

    <form id="deletePaymentForm" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            const form = document.getElementById('deletePaymentForm');
            form.action = url;
            openModal('deleteModal');
        }

        window.updateFilters = function () {
            const form = document.querySelector('.card-actions form') || document.querySelector('.card-actions');
            const formData = new FormData(form);
            const params = new URLSearchParams(formData);
            const url = new URL(window.location.pathname, window.location.origin);
            url.search = params.toString();
            fetchAndReplace(url);
        };

        async function fetchAndReplace(url) {
            const tableWrap = document.getElementById('tableWrap');
            const statsWrap = document.getElementById('statsWrap');
            if (tableWrap) tableWrap.style.opacity = '0.5';

            try {
                const response = await fetch(url.toString(), {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newTable = doc.getElementById('tableWrap');
                if (newTable && tableWrap) {
                    tableWrap.innerHTML = newTable.innerHTML;
                }

                const newStats = doc.getElementById('statsWrap');
                if (newStats && statsWrap) {
                    statsWrap.innerHTML = newStats.innerHTML;
                }

                if (tableWrap) tableWrap.style.opacity = '1';
                window.history.pushState({}, '', url);
            } catch (error) {
                console.error('AJAX error:', error);
                if (tableWrap) tableWrap.style.opacity = '1';
            }
        }

        let debounceTimer;
        document.addEventListener('input', function (e) {
            if (e.target && e.target.id === 'searchQuery') {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(updateFilters, 500);
            }
        });

        // Intercept pagination clicks
        document.addEventListener('click', function (e) {
            const paginationLink = e.target.closest('.tf-pagination a');
            if (paginationLink) {
                e.preventDefault();
                fetchAndReplace(new URL(paginationLink.href));
            }
        });
    </script>
@endsection