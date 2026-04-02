@extends('admin.layout.app')

@section('title', 'All Orders')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <div class="page-header">
            <div>
                <h1 class="page-title">Your All Orders</h1>
            </div>

            <div class="d-flex gap-2">

                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-plus-fill"></i> Import
                </button>

                <button class="btn-primary-solid sm">
                    <i class="bi bi-file-earmark-spreadsheet"></i> Export
                </button>

                <a href="{{route('admin.orders.create')}}" class="btn-primary-solid sm">
                    <i class="bi bi-plus-lg"></i> Add Order
                </a>

            </div>

        </div>

        <!-- {{-- ═══════════════════════════════════════════════════
             6 KPI SUMMARY CARDS
        ════════════════════════════════════════════════════ --}} -->
        <div id="statsWrap" style="display:grid;grid-template-columns:repeat({{ request('assigned_to') ? 9 : 6 }},1fr);gap:14px;margin-bottom:24px;">

            @if(request('assigned_to'))
                @php
                    $selectedSalesPerson = $allSales->where('id', request('assigned_to'))->first();
                @endphp
                @if($selectedSalesPerson)
                <div class="dash-card active" style="padding:16px 18px; border: 2px solid var(--accent);">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-badge-fill" style="font-size:17px;color:#10b981;"></i>
                        </div>
                        <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;white-space:nowrap;">Total Orders</span>
                    </div>
                    <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $orders->total() }}</div>
                    <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">{{ $selectedSalesPerson->name }}</div>
                    <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                        <div style="height:100%;width:100%;background:#10b981;border-radius:3px;"></div>
                    </div>
                </div>

                <div class="dash-card active" style="padding:16px 18px; border: 2px solid #0ea5e9;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(14,165,233,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-telephone-fill" style="font-size:17px;color:#0ea5e9;"></i>
                        </div>
                        <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(14,165,233,.1);color:#0ea5e9;white-space:nowrap;">Total Calling</span>
                    </div>
                    <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $totalCallingFollowupsFiltered }}</div>
                    <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Assigned Calling</div>
                    <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                        <div style="height:100%;width:100%;background:#0ea5e9;border-radius:3px;"></div>
                    </div>
                </div>

                <div class="dash-card active" style="padding:16px 18px; border: 2px solid #f43f5e;">
                    <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                        <div style="width:38px;height:38px;border-radius:10px;background:rgba(244,63,94,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-chat-dots-fill" style="font-size:17px;color:#f43f5e;"></i>
                        </div>
                        <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(244,63,94,.1);color:#f43f5e;white-space:nowrap;">Total Message</span>
                    </div>
                    <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $totalMessageFollowupsFiltered }}</div>
                    <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Assigned Message</div>
                    <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                        <div style="height:100%;width:100%;background:#f43f5e;border-radius:3px;"></div>
                    </div>
                </div>
                @endif
            @endif

            <div class="dash-card {{ request()->has('q') || request()->has('status_id') || request()->has('service_id') || request()->has('is_marketing') ? '' : 'active' }}" 
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route('admin.orders.index') }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-bag-check-fill" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;white-space:nowrap;">All</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $totalOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Total Orders</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:100%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card {{ request('is_marketing') == '1' ? 'active' : '' }}" 
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route('admin.orders.index', ['is_marketing' => 1]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(139,92,246,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-megaphone-fill" style="font-size:17px;color:#8b5cf6;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(139,92,246,.1);color:#8b5cf6;white-space:nowrap;">Marketing</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $marketingOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Marketing Orders</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrders > 0 ? ($marketingOrders / $totalOrders) * 100 : 0 }}%;background:#8b5cf6;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(6,182,212,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-currency-rupee" style="font-size:17px;color:#06b6d4;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(6,182,212,.1);color:#06b6d4;white-space:nowrap;">Revenue</span>
                </div>
                <div style="font-size:22px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">₹{{ number_format($totalValue, 0) }}</div>
                <div style="font-size:11px;color:var(--t3);font-weight:600;margin-top:4px;">Total Order Value</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:100%;background:#06b6d4;border-radius:3px;"></div>
                </div>
            </div>
            
            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-cash-stack" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;white-space:nowrap;">Received</span>
                </div>
                <div style="font-size:22px;font-weight:800;color:#10b981;letter-spacing:-.5px;line-height:1;">₹{{ number_format($totalReceived, 0) }}</div>
                <div style="font-size:11px;color:var(--t3);font-weight:600;margin-top:4px;">Total Received</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalValue > 0 ? ($totalReceived / $totalValue) * 100 : 0 }}%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(245,158,11,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-hourglass-split" style="font-size:17px;color:#f59e0b;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(245,158,11,.1);color:#f59e0b;white-space:nowrap;">Pending</span>
                </div>
                <div style="font-size:22px;font-weight:800;color:#f59e0b;letter-spacing:-.5px;line-height:1;">₹{{ number_format($pendingValue, 0) }}</div>
                <div style="font-size:11px;color:var(--t3);font-weight:600;margin-top:4px;">Remaining Amounts</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalValue > 0 ? ($pendingValue / $totalValue) * 100 : 0 }}%;background:#f59e0b;border-radius:3px;"></div>
                </div>
            </div>

            @php 
                $cancelStatus = $allStatuses->where('name', 'cancel')->first();
            @endphp
            <div class="dash-card {{ request('status_id') == ($cancelStatus->id ?? 'xxx') ? 'active' : '' }}" 
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route('admin.orders.index', ['status_id' => ($cancelStatus->id ?? '')]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-x-circle-fill" style="font-size:17px;color:#ef4444;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(239,68,68,.1);color:#ef4444;white-space:nowrap;">Cancelled</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;">{{ $cancelledOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Orders Cancelled</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0 }}%;background:#ef4444;border-radius:3px;"></div>
                </div>
            </div>

        </div>
        <!-- {{-- END KPI CARDS --}} -->

        <div class="dash-grid" id="tableWrap">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Recent Orders</div>
                        <div class="card-sub" id="orderTableSub">Showing {{ $orders->count() }} of {{ $totalOrders }} orders</div>
                    </div>
                    <div class="card-actions mb-2">

                        <form action="{{ route('admin.orders.index') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search...">
                                <button type="submit" class="btn-primary-solid sm" style="display:none;">Search</button>
                            </div>

                            <!-- ══ DATE RANGE PICKER TRIGGER ══ -->
                            <button type="button" id="dateRangeTrigger" class="drp-trigger" onclick="toggleDatePicker()">
                                <i class="bi bi-calendar3"></i>
                                <span id="drpLabel">{{ request('start_date') ? request('start_date') . ' - ' . request('end_date') : 'All Time' }}</span>
                                <i class="bi bi-chevron-down drp-chevron" id="drpChevron"></i>
                            </button>

                            <!-- Hidden inputs for date range -->
                            <input type="hidden" name="start_date" id="drpStartInput" value="{{ request('start_date') }}">
                            <input type="hidden" name="end_date" id="drpEndInput" value="{{ request('end_date') }}">

                             <select name="is_marketing" class="filter-select" onchange="updateFilters()">
                                 <option value="">All Types</option>
                                 <option value="1" {{ request('is_marketing') == '1' ? 'selected' : '' }}>Marketing</option>
                                 <option value="0" {{ request('is_marketing') == '0' ? 'selected' : '' }}>Website</option>
                             </select>

                            <select name="service_id" class="filter-select" onchange="updateFilters()">
                                <option value="">All Services</option>
                                @foreach($allServices as $srv)
                                    <option value="{{ $srv->id }}" {{ request('service_id') == $srv->id ? 'selected' : '' }}>{{ $srv->name }}</option>
                                @endforeach
                            </select>

                            <select name="status_id" class="filter-select" onchange="updateFilters()">
                                <option value="">All Status</option>
                                @foreach($allStatuses as $st)
                                    <option value="{{ $st->id }}" {{ request('status_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                @endforeach
                            </select>

                            <select name="assigned_to" class="filter-select" onchange="updateFilters()">
                                <option value="">Sales Person</option>
                                @foreach($allSales as $sale)
                                    <option value="{{ $sale->id }}" {{ request('assigned_to') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                                @endforeach
                            </select>
                        </form>

                        <!-- {{-- Date Range Picker (replaces simple select) --}} -->
                        <div style="position:relative;">
                            @include('admin.includes.date-range-picker')
                        </div>
                    </div>
                </div>

                <div class="table-wrap">
                    <table class="data-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Company</th>
                                <th>Contact Person</th>
                                <th>Service</th>
                                <th>Value</th>
                                <th>Advance</th>
                                <th>Status</th>
                                <th>Created By</th>
                                <th>Sales Person</th>
                                <th>Followup</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr data-order-type="{{ $order->is_marketing ? 'marketing' : 'website' }}" 
                                data-status="{{ strtolower($order->status->name ?? '') }}"
                                data-service="{{ $order->service_id }}">
                                <td><span class="mono">#ORD-{{ 1000 + $order->id }}</span></td>
                                <td><div class="ls" style="font-size:12px; font-weight:600;">{{ $order->created_at->format('d M Y') }}</div></td>
                                <td>
                                    <span class="type-badge {{ $order->is_marketing ? 'marketing-type' : 'website-type' }}">
                                        {{ $order->is_marketing ? 'Marketing' : 'Website' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="lead-cell">
                                        @php 
                                            $initials = strtoupper(substr($order->company_name, 0, 2)); 
                                            $bg = 'linear-gradient(135deg,#6366f1,#06b6d4)';
                                            if($order->is_marketing) $bg = 'linear-gradient(135deg,#8b5cf6,#ec4899)';
                                        @endphp
                                        <div class="mini-ava" style="background:{{ $bg }}">{{ $initials }}</div>
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
                                <td><span class="src-tag">{{ $order->service->name ?? 'N/A' }}</span></td>
                                <td><span class="src-tag">₹{{ number_format($order->order_value, 0) }}</span></td>
                                <td><span class="src-tag" style="background:#10b98120; color:#10b981;">₹{{ number_format($order->advance_payment, 0) }}</span></td>
                                <td>
                                    <span class="status-pill" style="background:{{ ($order->status->color ?? '#6366f1') }}20; color:{{ $order->status->color ?? '#6366f1' }};">
                                        {{ $order->status->name ?? 'Pending' }}
                                    </span>
                                </td>
                                <td>
                                    @if($order->createdBy)
                                        <div class="ln">{{ $order->createdBy->name }}</div>
                                        <div class="ls" style="font-size:10px">{{ $order->createdBy->email }}</div>
                                    @else
                                        <div class="ln">System</div>
                                    @endif
                                </td>
                                <td>
                                    <div style="display:flex;flex-direction:column;gap:2px;">
                                        @foreach($order->assignments as $assign)
                                            <div class="ln" style="font-size:12.5px;">{{ $assign->sale->name }}</div>
                                            <div class="ls" style="font-size:10px;">{{ $assign->sale->email }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td>
                                    <span class="badge" style="background:rgba(99, 102, 241, 0.1); color:var(--accent); padding:4px 10px; border-radius:6px; font-weight:700; font-family:var(--font-mono); font-size:12px;">
                                        {{ $order->followups_count }}
                                    </span>
                                </td>
                                <td>
                                    <div class="row-actions">
                                        <a href="{{ route('admin.orders.show', $order->id) }}" class="ra-btn"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route('admin.orders.edit', $order->id) }}" class="ra-btn"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route('admin.orders.followup', $order->id) }}" class="ra-btn"><i class="bi bi-arrow-counterclockwise"></i></a>
                                        <a href="{{route('admin.payments.create', $order->id)}}" class="ra-btn"><i class="bi bi-wallet2"></i></a>
                                        <button class="ra-btn danger" onclick="confirmDelete('{{ route('admin.orders.destroy', $order->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer" style="padding:16px 20px; border-top:1px solid var(--b2); display:flex; justify-content:space-between; align-items:center; background:var(--bg2);">
                    <span class="tf-info" id="ordersCount" style="font-size:13px; color:var(--t3); font-weight:500;">Showing {{ $orders->count() }} of {{ $orders->total() }} Orders</span>
                    <div class="tf-pagination">
                        {{ $orders->links('admin.includes.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- {{-- DELETE MODAL --}} -->
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
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete this Order?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('deleteModal')">Cancel</button>
                <button style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;" onclick="document.getElementById('deleteOrderForm').submit()">
                    <i class="bi bi-trash3-fill"></i> Confirm Deletion
                </button>
            </div>
        </div>
    </div>


<!-- ORDER DETAIL MODAL — View Only -->
    <div class="modal-backdrop" id="orderDetailModal" onclick="closeModal('orderDetailModal')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd">
                <div style="display:flex;flex-direction:column;gap:2px;">
                    <span>Order #ORD-2847</span>
                    <span style="font-size:11px;font-weight:500;color:var(--t3);">TechCorp Pvt Ltd</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <a href="{{ route('admin.orders.edit', 1) }}"
                        class="btn-ghost" style="padding:5px 11px;font-size:12px;">
                        <i class="bi bi-pencil-fill"></i> Edit
                    </a>
                    <button class="modal-close" onclick="closeModal('orderDetailModal')"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>

            <div class="modal-bd">

                {{-- ── KPI Strip ── --}}
                <div class="detail-kpis" style="margin-bottom:20px;">
                    <div class="dk-item">
                        <div class="dk-val">₹8.5L</div>
                        <div class="dk-lbl">Order Value</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#10b981;">Paid</div>
                        <div class="dk-lbl">Payment</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val">18 Nov</div>
                        <div class="dk-lbl">Order Date</div>
                    </div>
                    <div class="dk-item">
                        <div class="dk-val" style="color:#f59e0b;">15 Dec</div>
                        <div class="dk-lbl">Delivery</div>
                    </div>
                </div>

                {{-- ── Order Info ── --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:14px;">

                    <div class="od-row">
                        <span class="od-lbl">Company Name</span>
                        <span class="od-val">TechCorp Pvt Ltd</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Client Name</span>
                        <span class="od-val">Rahul Sharma</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Email</span>
                        <span class="od-val">rahul@techcorp.io</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Phone</span>
                        <span class="od-val">+91 98765 43210</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Domain</span>
                        <span class="od-val">
                            <a href="https://techcorp.io" target="_blank" style="color:var(--accent);text-decoration:none;">techcorp.io</a>
                        </span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Service / Product</span>
                        <span class="od-val">Website Redesign</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Payment Terms</span>
                        <span class="od-val">Full Advance</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Order Type</span>
                        <span class="od-val">
                            <span style="font-size:11px;font-weight:700;padding:2px 9px;border-radius:20px;background:rgba(6,182,212,.12);color:#06b6d4;">Website</span>
                        </span>
                    </div>
                    <div class="od-row" style="grid-column:1/-1;">
                        <span class="od-lbl">Full Address</span>
                        <span class="od-val">204, Orbit Tower, Andheri East, Mumbai — 400069, Maharashtra</span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Assigned To (Developer)</span>
                        <span class="od-val" style="display:flex;align-items:center;gap:6px;">
                            <span style="width:20px;height:20px;border-radius:5px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:inline-flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#fff;">RK</span>
                            Rahul Kumar
                        </span>
                    </div>
                    <div class="od-row">
                        <span class="od-lbl">Created By</span>
                        <span class="od-val" style="display:flex;align-items:center;gap:6px;">
                            <span style="width:20px;height:20px;border-radius:5px;background:linear-gradient(135deg,#ec4899,#f59e0b);display:inline-flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#fff;">PS</span>
                            Priya Sharma
                        </span>
                    </div>

                </div>

                {{-- ── Marketing Section (read-only) ── --}}
                <div style="border:1px solid var(--b1);border-radius:var(--r);overflow:hidden;">
                    <div style="display:flex;align-items:center;gap:8px;background:var(--bg3);padding:9px 14px;border-bottom:1px solid var(--b1);">
                        <div style="width:24px;height:24px;border-radius:6px;background:rgba(139,92,246,.12);display:flex;align-items:center;justify-content:center;">
                            <i class="bi bi-megaphone-fill" style="font-size:11px;color:#8b5cf6;"></i>
                        </div>
                        <span style="font-size:11.5px;font-weight:700;color:var(--t2);">Marketing Order Details</span>
                    </div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;padding:12px 14px;">

                        <div class="od-row">
                            <span class="od-lbl">Plan Name</span>
                            <span class="od-val">Growth Plan</span>
                        </div>
                        <div class="od-row">
                            <span class="od-lbl">Payment Status</span>
                            <span class="od-val">
                                <span class="status-pill paid">Paid</span>
                            </span>
                        </div>
                        <div class="od-row">
                            <span class="od-lbl">Username</span>
                            <span class="od-val mono">datafirst_user</span>
                        </div>
                        <div class="od-row">
                            <span class="od-lbl">Password</span>
                            <span class="od-val" style="display:flex;align-items:center;gap:8px;">
                                <span id="mktPwVal" style="font-family:var(--mono);">••••••••</span>
                                <button type="button" onclick="toggleMktPw()"
                                    style="background:none;border:none;color:var(--accent);font-size:12px;cursor:pointer;padding:0;line-height:1;">
                                    <i class="bi bi-eye-fill" id="mktPwIcon"></i>
                                </button>
                            </span>
                        </div>
                        <div class="od-row" style="grid-column:1/-1;">
                            <span class="od-lbl">Assigned To (Sales)</span>
                            <span class="od-val" style="display:flex;align-items:center;gap:6px;">
                                <span style="width:20px;height:20px;border-radius:5px;background:linear-gradient(135deg,#10b981,#06b6d4);display:inline-flex;align-items:center;justify-content:center;font-size:8px;font-weight:700;color:#fff;">NK</span>
                                Neha Kapoor
                            </span>
                        </div>

                    </div>
                </div>

            </div>

            {{-- ── Footer ── --}}
            <div class="modal-ft" style="justify-content:space-between;">
                <button class="btn-ghost" onclick="closeModal('orderDetailModal')">
                    <i class="bi bi-x-lg"></i> Close
                </button>
                <div style="display:flex;gap:8px;">
                    <a href="#" target="_blank" id="modalPaymentLink"
                        class="btn-ghost" style="border-color:#10b981;color:#10b981;"
                        onmouseover="this.style.background='rgba(16,185,129,.08)'"
                        onmouseout="this.style.background='transparent'">
                        <i class="bi bi-wallet2"></i> Add Payment
                    </a>
                    <a href="{{ route('admin.orders.edit', 1) }}" class="btn-primary-solid">
                        <i class="bi bi-pencil-fill"></i> Edit Order
                    </a>
                </div>
            </div>

        </div>
    </div>

    <style>
        /* Read-only detail row */
        .od-row {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 9px 12px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            border-radius: var(--r-sm);
        }
        .od-lbl {
            font-size: 10.5px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--t3);
        }
        .od-val {
            font-size: 13px;
            font-weight: 500;
            color: var(--t1);
            line-height: 1.4;
        }
    </style>

    <script>
        let mktPwVisible = false;
        function toggleMktPw() {
            mktPwVisible = !mktPwVisible;
            document.getElementById('mktPwVal').textContent = mktPwVisible ? 'pass@1234' : '••••••••';
            document.getElementById('mktPwIcon').className  = mktPwVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
        }
    </script>

</main>

<style>
    /* ── Stat scroll row (same as leads page) ── */
    .stat-scroll-row {
        display: flex;
        gap: 10px;
        overflow-x: auto;
        padding-bottom: 4px;
        margin-bottom: 20px;
        scrollbar-width: none;
    }

    .stat-scroll-row::-webkit-scrollbar {
        display: none;
    }

    .stat-box {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 10px;
        background: var(--bg2);
        border: 1px solid var(--b1);
        border-radius: var(--r);
        padding: 11px 16px;
        min-width: 148px;
        cursor: pointer;
        transition: var(--transition);
        position: relative;
        overflow: hidden;
    }

    .stat-box::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: var(--sb-color);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }

    .stat-box:hover {
        border-color: var(--sb-color);
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, .12);
    }

    .stat-box:hover::after {
        transform: scaleX(1);
    }

    .stat-box.active {
        border-color: var(--sb-color);
        background: var(--bg3);
    }

    .stat-box.active::after {
        transform: scaleX(1);
    }

    .sb-icon {
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: color-mix(in srgb, var(--sb-color) 14%, transparent);
        color: var(--sb-color);
    }

    .sb-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -.4px;
        line-height: 1;
    }

    .sb-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 500;
        margin-top: 2px;
        white-space: nowrap;
    }

    .stat-section-lbl {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .1em;
        color: var(--t4);
        padding: 0 6px;
        display: flex;
        align-items: center;
        white-space: nowrap;
        flex-shrink: 0;
    }

    .stat-divider {
        width: 1px;
        height: 40px;
        background: var(--b2);
        flex-shrink: 0;
        margin: 0 4px;
    }

    /* ── Type badges ── */
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

    .marketing-type {
        background: rgba(139, 92, 246, .12);
        color: #8b5cf6;
    }

    .website-type {
        background: rgba(6, 182, 212, .12);
        color: #06b6d4;
    }

    /* ── Wider modal ── */
    .modal-box-lg {
        max-width: 760px !important;
        width: 90vw !important;
    }

    /* ── Marketing section ── */
    .mkt-section {
        margin-top: 20px;
        border: 1px solid var(--b2);
        border-radius: 10px;
        overflow: hidden;
    }

    .mkt-section-label {
        display: flex;
        align-items: center;
        gap: 8px;
        background: var(--accent-bg);
        padding: 10px 14px;
        font-size: 12px;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        border-bottom: 1px solid var(--b2);
    }

    .mkt-section-note {
        margin-left: auto;
        font-size: 11px;
        font-weight: 400;
        color: var(--t3);
        text-transform: none;
        letter-spacing: 0;
    }

    .mkt-section .form-grid {
        padding: 14px;
        background: var(--bg3);
    }
</style>

    <form id="deleteOrderForm" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function confirmDelete(url) {
            const form = document.getElementById('deleteOrderForm');
            form.action = url;
            openModal('deleteModal');
        }
        
    /* ── Stat box filter ── */
    function applyStatFilter(el) {
        const filterGroup = el.dataset.filter;
        // Deactivate same-group siblings
        document.querySelectorAll(`.stat-box[data-filter="${filterGroup}"]`)
            .forEach(b => b.classList.remove('active'));
        el.classList.add('active');

        const val = el.dataset.value;
        const rows = document.querySelectorAll('#ordersTable tbody tr');

        rows.forEach(row => {
            let show = true;
            if (filterGroup === 'type' && val !== 'all') {
                show = row.dataset.orderType === val;
            } else if (filterGroup === 'status') {
                show = row.dataset.status === val;
            } else if (filterGroup === 'service') {
                show = row.dataset.service === val;
            }
            row.style.display = show ? '' : 'none';
        });

        const visible = [...rows].filter(r => r.style.display !== 'none').length;
        document.getElementById('ordersCount').textContent =
            `Showing ${visible} of 247 Orders`;
    }

    /* ── Listen for date range applied ── */
    document.addEventListener('dateRangeApplied', function(e) {
        const { start, end } = e.detail;
        
        function formatDate(date) {
            if(!date) return '';
            let d = new Date(date),
                month = '' + (d.getMonth() + 1),
                day = '' + d.getDate(),
                year = d.getFullYear();
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
            return [year, month, day].join('-');
        }

        const sInp = document.getElementById('drpStartInput');
        const eInp = document.getElementById('drpEndInput');
        if(sInp && eInp) {
            sInp.value = formatDate(start);
            eInp.value = formatDate(end);
            updateFilters();
        }
    });

    window.updateFilters = function() {
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
    document.addEventListener('input', function(e) {
        if (e.target && e.target.id === 'searchQuery') {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(updateFilters, 500);
        }
    });

    // Intercept pagination clicks
    document.addEventListener('click', function(e) {
        const paginationLink = e.target.closest('.tf-pagination a');
        if (paginationLink) {
            e.preventDefault();
            fetchAndReplace(new URL(paginationLink.href));
        }
    });
</script>

@endsection