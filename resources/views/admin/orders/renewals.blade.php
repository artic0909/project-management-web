@extends('admin.layout.app')

@section('title', 'Upcoming Renewals')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-dashboard">

        <div class="page-header">
            <div>
                <h1 class="page-title">Upcoming Renewals</h1>
                <p class="page-desc">Orders expiring in the next 3 days</p>
            </div>

            <div class="d-flex gap-2">
                @if($routePrefix == 'admin')
                <button type="button" class="btn-primary-solid sm" id="bulkDeleteOrdersBtn" style="display: none; background: #dc2626; border-color: #dc2626; color: white;" onclick="bulkDeleteSelectedOrders()">
                    <i class="bi bi-trash-fill"></i> Bulk Delete
                </button>
                @endif
                
                <a href="{{ route($routePrefix . '.orders.create') }}" class="btn-primary-solid sm">
                    <i class="bi bi-plus-lg"></i> Add Order
                </a>
            </div>
        </div>

        <!-- KPI SUMMARY CARDS -->
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
                        <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;white-space:nowrap;">Total Renewals</span>
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
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.orders.renewals') }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(16,185,129,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-bag-check-fill" style="font-size:17px;color:#10b981;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(16,185,129,.1);color:#10b981;white-space:nowrap;">All Renewals</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $totalOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Total Renewals</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:100%;background:#10b981;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card {{ request('is_marketing') == '1' ? 'active' : '' }}" 
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.orders.renewals', ['is_marketing' => 1]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(139,92,246,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-megaphone-fill" style="font-size:17px;color:#8b5cf6;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(139,92,246,.1);color:#8b5cf6;white-space:nowrap;">Marketing</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">{{ $marketingOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Marketing Renewals</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrders > 0 ? ($marketingOrders / $totalOrders) * 100 : 0 }}%;background:#8b5cf6;border-radius:3px;"></div>
                </div>
            </div>

            <div class="dash-card" style="padding:16px 18px;">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(6,182,212,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-currency-rupee" style="font-size:17px;color:#06b6d4;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(6,182,212,.1);color:#06b6d4;white-space:nowrap;">Renewal Value</span>
                </div>
                <div style="font-size:22px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1;">₹{{ number_format($totalValue, 0) }}</div>
                <div style="font-size:11px;color:var(--t3);font-weight:600;margin-top:4px;">Total Value</div>
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
                style="padding:16px 18px; cursor:pointer;" onclick="window.location.href='{{ route($routePrefix . '.orders.renewals', ['status_id' => ($cancelStatus->id ?? '')]) }}'">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:12px;">
                    <div style="width:38px;height:38px;border-radius:10px;background:rgba(239,68,68,.13);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-x-circle-fill" style="font-size:17px;color:#ef4444;"></i>
                    </div>
                    <span style="font-size:10px;font-weight:700;padding:2px 7px;border-radius:20px;background:rgba(239,68,68,.1);color:#ef4444;white-space:nowrap;">Cancelled</span>
                </div>
                <div style="font-size:26px;font-weight:800;color:#ef4444;letter-spacing:-.5px;line-height:1;">{{ $cancelledOrders }}</div>
                <div style="font-size:11.5px;color:var(--t3);font-weight:500;margin-top:4px;">Renewals Cancelled</div>
                <div style="margin-top:10px;height:3px;border-radius:3px;background:var(--b1);overflow:hidden;">
                    <div style="height:100%;width:{{ $totalOrders > 0 ? ($cancelledOrders / $totalOrders) * 100 : 0 }}%;background:#ef4444;border-radius:3px;"></div>
                </div>
            </div>

        </div>

        <div class="dash-grid">
            <div class="dash-card span-12">
                <div class="card-head">
                    <div>
                        <div class="card-title">Orders for Renewal</div>
                        <div class="card-sub" id="orderTableSub">Showing {{ $orders->count() }} of {{ $totalOrders }} renewals</div>
                    </div>
                    <div class="card-actions mb-2">

                        <form action="{{ route($routePrefix . '.orders.renewals') }}" method="GET" class="card-actions mb-0">
                            <div class="global-search">
                                <i class="bi bi-search"></i>
                                <input type="text" name="q" id="searchQuery" value="{{ request('q') }}" placeholder="Search renewals...">
                                <button type="submit" class="btn-primary-solid sm" style="display:none;">Search</button>
                            </div>

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

                            @if($routePrefix == 'admin')
                            <select name="assigned_to" class="filter-select" onchange="updateFilters()">
                                <option value="">Sales Person</option>
                                @foreach($allSales as $sale)
                                    <option value="{{ $sale->id }}" {{ request('assigned_to') == $sale->id ? 'selected' : '' }}>{{ $sale->name }}</option>
                                @endforeach
                            </select>
                            @endif
                            <select name="per_page" class="filter-select" onchange="updateFilters()">
                                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10 Rows</option>
                                <option value="20" {{ (request('per_page') == 20 || !request('per_page')) ? 'selected' : '' }}>20 Rows</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Rows</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Rows</option>
                                <option value="all" {{ request('per_page') == 'all' ? 'selected' : '' }}>All Rows</option>
                            </select>
                        </form>
                    </div>
                </div>

                <div id="tableWrap">
                    <div class="table-wrap">
                    <table class="data-table" id="ordersTable">
                        <thead>
                            <tr>
                                @if($routePrefix == 'admin')
                                <th style="width: 40px; text-align: center;">
                                    <input type="checkbox" id="selectAllOrders" onclick="toggleAllOrders(this)" style="cursor: pointer;">
                                </th>
                                @endif
                                <th>SL.</th>
                                <th>Order ID</th>
                                <th>Renewal Date</th>
                                <th>Days Left</th>
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
                            @php
                                $diff = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($order->renewal_date)->startOfDay(), false);
                                $daysText = $diff == 0 ? 'Today' : ($diff == 1 ? 'Tomorrow' : "$diff Days");
                                $badgeClass = $diff <= 1 ? 'danger' : 'warn';
                            @endphp
                            <tr data-order-type="{{ $order->is_marketing ? 'marketing' : 'website' }}" 
                                data-status="{{ strtolower($order->status->name ?? '') }}"
                                data-service="{{ $order->service_id }}">
                                @if($routePrefix == 'admin')
                                <td style="text-align: center;">
                                    <input type="checkbox" class="order-checkbox" name="order_ids[]" value="{{ $order->id }}" onclick="updateBulkDeleteButtonOrders()" style="cursor: pointer;">
                                </td>
                                @endif
                                <td style="color:var(--t4);font-size:12px;font-weight:600;">{{ $loop->iteration + ($orders->currentPage() - 1) * $orders->perPage() }}</td>
                                <td><span class="mono">{{ $order->order_number ?? '#ORD-'.$order->id }}</span></td>
                                <td><div class="ls" style="font-size:12px; font-weight:700; color:var(--t1);">{{ \Carbon\Carbon::parse($order->renewal_date)->format('d M Y') }}</div></td>
                                <td>
                                    <span class="date-cell {{ $badgeClass }}">
                                        {{ $daysText }}
                                    </span>
                                </td>
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
                                <td>
                                    @foreach($order->services as $service)
                                        <span class="status-pill mb-2" style="background:{{ ($service->color ?? '#6366f1') }}20; color:{{ $service->color ?? '#6366f1' }};">{{ $service->name }}</span>
                                    @endforeach
                                </td>
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
                                        @php
                                            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                                            $phoneList = is_array($order->phones) ? $order->phones : [];
                                            $emailList = is_array($order->emails) ? $order->emails : [];
                                            $fullPhones = [];
                                            foreach($phoneList as $p) {
                                                $fullPhones[] = ($codes[$p['code_idx'] ?? ''] ?? '') . ($p['number'] ?? '');
                                            }
                                        @endphp
                                        <a href="javascript:void(0)" class="ra-btn phone" 
                                           onclick="handleContactClick(event, 'tel', {{ json_encode($fullPhones) }})" title="Call Client">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="javascript:void(0)" class="ra-btn email" 
                                           onclick="handleContactClick(event, 'mailto', {{ json_encode($emailList) }})" title="Email Client">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>

                                        <a href="{{ route($routePrefix . '.orders.show', $order->id) }}" class="ra-btn" title="View"><i class="bi bi-eye-fill"></i></a>
                                        <a href="{{ route($routePrefix . '.orders.edit', $order->id) }}" class="ra-btn" title="Edit"><i class="bi bi-pencil-fill"></i></a>
                                        <a href="{{ route($routePrefix . '.payments.create', $order->id) }}" class="ra-btn" title="Payments"><i class="bi bi-wallet2"></i></a>
                                        @if($routePrefix == 'admin')
                                        <button class="ra-btn danger" title="Delete" onclick="confirmDelete('{{ route($routePrefix . '.orders.destroy', $order->id) }}')"><i class="bi bi-trash-fill"></i></button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table-footer" style="padding:16px 20px; border-top:1px solid var(--b2); display:flex; justify-content:space-between; align-items:center; background:var(--bg2);">
                    <span class="tf-info" id="ordersCount" style="font-size:13px; color:var(--t3); font-weight:500;">Showing {{ $orders->count() }} of {{ $orders->total() }} Renewals</span>
                    <div class="tf-pagination">
                        {{ $orders->links('admin.includes.pagination') }}
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

    <!-- DELETE MODAL -->
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

    <!-- BULK DELETE MODAL -->
    <div class="modal-backdrop" id="bulkDeleteOrdersModal">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="modal-hd" style="border-bottom:1px solid #fecaca;">
                <span style="color:#dc2626;">Bulk Delete Orders</span>
                <button class="modal-close" onclick="closeModal('bulkDeleteOrdersModal')"><i class="bi bi-x-lg"></i></button>
            </div>
            <div class="modal-bd" style="text-align:center;padding:32px 24px;">
                <div style="width:64px;height:64px;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="bi bi-trash3-fill" style="font-size:28px;color:#dc2626;"></i>
                </div>
                <h3 style="margin:0 0 8px;font-size:18px;font-weight:600;color:var(--t1);">Are you sure?</h3>
                <p style="margin:0;font-size:14px;color:var(--t3);line-height:1.6;">Are you sure you want to delete the <strong id="bulkDeleteOrdersCount">0</strong> selected orders?<br>This action <strong style="color:#dc2626;">cannot be undone.</strong></p>
            </div>
            <div class="modal-ft" style="border-top:1px solid #fecaca;">
                <button class="btn-ghost" onclick="closeModal('bulkDeleteOrdersModal')">Cancel</button>
                <button type="button" id="executeBulkDeleteOrdersBtn" onclick="executeBulkDeleteOrders()" style="background:#dc2626;color:#fff;border:none;border-radius:8px;padding:8px 18px;font-size:14px;font-weight:500;cursor:pointer;display:flex;align-items:center;gap:6px;">
                    <i class="bi bi-trash3-fill"></i> Delete Orders
                </button>
            </div>
        </div>
    </div>

    <form id="deleteOrderForm" action="" method="POST" style="display:none;">
        @csrf
        @method('DELETE')
    </form>

    <!-- ── Contact Selection Modal ── -->
    <div class="modal fade" id="contactSelectionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg2); border-color: var(--b2); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div class="modal-header" style="border-bottom-color: var(--b1);">
                    <h5 class="modal-title" id="contactSelectionModalLabel" style="color: #ef4444; font-weight: 700;">Select Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="list-group list-group-flush" id="contactSelectionOptions">
                    </div>
                </div>
                <div class="modal-footer" style="border-top-color: var(--b1);">
                    <button type="button" class="btn btn-secondary sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmDelete(url) {
            const form = document.getElementById('deleteOrderForm');
            form.action = url;
            openModal('deleteModal');
        }

        window.updateFilters = function() {
            const form = document.querySelector('.card-actions form');
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

                const newSub = doc.getElementById('orderTableSub');
                const oldSub = document.getElementById('orderTableSub');
                if (newSub && oldSub) {
                    oldSub.innerHTML = newSub.innerHTML;
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

        document.addEventListener('click', function(e) {
            const paginationLink = e.target.closest('.tf-pagination a');
            if (paginationLink) {
                e.preventDefault();
                fetchAndReplace(new URL(paginationLink.href));
            }
        });

        function toggleAllOrders(source) {
            const checkboxes = document.querySelectorAll('.order-checkbox');
            checkboxes.forEach(cb => cb.checked = source.checked);
            updateBulkDeleteButtonOrders();
        }

        function updateBulkDeleteButtonOrders() {
            const checkedCount = document.querySelectorAll('.order-checkbox:checked').length;
            const bulkBtn = document.getElementById('bulkDeleteOrdersBtn');
            if (bulkBtn) {
                bulkBtn.style.display = checkedCount > 0 ? 'inline-flex' : 'none';
            }
        }

        function bulkDeleteSelectedOrders() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            if (checkedBoxes.length === 0) return;
            document.getElementById('bulkDeleteOrdersCount').innerText = checkedBoxes.length;
            openModal('bulkDeleteOrdersModal');
        }

        function executeBulkDeleteOrders() {
            const checkedBoxes = document.querySelectorAll('.order-checkbox:checked');
            const ids = Array.from(checkedBoxes).map(cb => cb.value);
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route($routePrefix . '.orders.bulk-destroy') }}";
            form.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            ids.forEach(id => {
                form.innerHTML += `<input type="hidden" name="ids[]" value="${id}">`;
            });
            document.body.appendChild(form);
            form.submit();
        }

        function handleContactClick(e, protocol, options) {
            e.preventDefault();
            if (!options || options.length === 0) return;
            if (options.length === 1) {
                window.location.href = protocol + ':' + options[0];
                return;
            }
            const optionsGroup = document.getElementById('contactSelectionOptions');
            const titleEl = document.getElementById('contactSelectionModalLabel');
            titleEl.textContent = 'Select ' + (protocol === 'tel' ? 'Phone Number' : 'Email Address');
            optionsGroup.innerHTML = '';
            options.forEach(opt => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action d-flex align-items-center gap-3 py-3';
                item.style.cssText = 'background: transparent; color: var(--t2); border-bottom: 1px solid var(--b1);';
                item.href = protocol + ':' + opt;
                item.innerHTML = `<i class="bi bi-${protocol === 'tel' ? 'telephone' : 'envelope'}"></i><span>${opt}</span>`;
                optionsGroup.appendChild(item);
            });
            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('contactSelectionModal'));
            modal.show();
        }
    </script>
</main>

<style>
    .type-badge { padding: 3px 9px; border-radius: 99px; font-size: 11px; font-weight: 600; text-transform: uppercase; }
    .marketing-type { background: rgba(139, 92, 246, .12); color: #8b5cf6; }
    .website-type { background: rgba(6, 182, 212, .12); color: #06b6d4; }
</style>

@endsection
