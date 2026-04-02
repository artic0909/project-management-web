@extends('admin.layout.app')

@section('title', 'Edit Order')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.orders.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Orders
                    </a>
                </div>
                <h1 class="page-title">Edit Order</h1>
                <p class="page-desc">Modify details for {{ $order->company_name }}</p>
            </div>
        </div>

        <form action="{{ route($routePrefix . '.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="dash-grid">

                {{-- LEFT — 8 --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Order Info --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-bag-fill" style="color:var(--accent);margin-right:6px;"></i>Order Information</div>
                            <div class="card-sub">Client details and order specifics</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="company_name" class="form-inp" value="{{ old('company_name', $order->company_name) }}" placeholder="Company name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="client_name" class="form-inp" value="{{ old('client_name', $order->client_name) }}" placeholder="Full name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Emails</label>
                                    <div id="order-email-list"></div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Phones</label>
                                    <div id="order-phone-list"></div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name</label>
                                    <input type="text" name="domain_name" class="form-inp" value="{{ old('domain_name', $order->domain_name) }}" placeholder="example.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Service / Product</label>
                                    <select name="service_id" id="serviceSelect" class="form-inp" onchange="checkServiceType()">
                                        <option value="">— Select Service —</option>
                                        @foreach($services as $service)
                                            <option value="{{ $service->id }}" {{ old('service_id', $order->service_id) == $service->id ? 'selected' : '' }}>{{ $service->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Order Value <span style="color:#ef4444">*</span></label>
                                    <input type="number" name="order_value" class="form-inp" value="{{ old('order_value', $order->order_value) }}" placeholder="₹ Amount" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Advance Payment</label>
                                    <input type="number" name="advance_payment" class="form-inp" value="{{ old('advance_payment', $order->advance_payment) }}" placeholder="₹ Advance Received">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Terms</label>
                                    <select name="payment_terms_id" class="form-inp">
                                        <option value="">— Select Terms —</option>
                                        @foreach($paymentStatuses as $ps)
                                            <option value="{{ $ps->id }}" {{ old('payment_terms_id', $order->payment_terms_id) == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-inp" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-geo-alt-fill" style="color:#06b6d4;margin-right:6px;"></i>Address</div>
                            <div class="card-sub">Client location details</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">City</label>
                                    <input type="text" name="city" class="form-inp" value="{{ old('city', $order->city) }}" placeholder="City">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Region / State</label>
                                    <input type="text" name="state" class="form-inp" value="{{ old('state', $order->state) }}" placeholder="State or Province">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code</label>
                                    <input type="text" name="zip_code" class="form-inp" value="{{ old('zip_code', $order->zip_code) }}" placeholder="Zip / PIN">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Street address…">{{ old('full_address', $order->full_address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Marketing --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div style="display:flex;align-items:center;gap:10px;width:100%;">
                                <div>
                                    <div class="card-title"><i class="bi bi-megaphone-fill" style="color:#8b5cf6;margin-right:6px;"></i>Marketing Order Details</div>
                                    <div class="card-sub">Fill only for marketing orders</div>
                                </div>
                                <label style="margin-left:auto;display:flex;align-items:center;gap:7px;cursor:pointer;font-size:12.5px;font-weight:600;color:var(--t3);">
                                    <input type="checkbox" name="is_marketing" value="1" id="mktToggle" onchange="toggleMktSection()" {{ old('is_marketing', $order->is_marketing) ? 'checked' : '' }} style="accent-color:var(--accent);width:14px;height:14px;">
                                    Enable
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="mktBody" style="{{ old('is_marketing', $order->is_marketing) ? 'display:block' : 'display:none' }};">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="mkt_payment_status_id" class="form-inp">
                                        <option value="">— Select —</option>
                                        @foreach($paymentStatuses as $ps)
                                            <option value="{{ $ps->id }}" {{ old('mkt_payment_status_id', $order->mkt_payment_status_id) == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="mkt_starting_date" class="form-inp" value="{{ old('mkt_starting_date', $order->mkt_starting_date ? $order->mkt_starting_date->format('Y-m-d') : '') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-inp" value="{{ old('plan_name', $order->plan_name) }}" placeholder="Plan name">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="mkt_username" class="form-inp" value="{{ old('mkt_username', $order->mkt_username) }}" placeholder="Account username">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="mkt_password" class="form-inp" value="{{ old('mkt_password', $order->mkt_password) }}" placeholder="Account password">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT — 4 --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    <div class="dash-card" style="position:sticky;top:80px;overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-send-fill" style="color:#10b981;margin-right:6px;"></i>Update Order</div>
                            <div class="card-sub">Review and save changes</div>
                        </div>
                        <div class="card-body">

                            {{-- Order Status --}}
                            <div class="form-row" style="margin-bottom:16px;">
                                <label class="form-lbl">Order Status</label>
                                <select name="status_id" class="form-inp" required>
                                    <option value="">— Select Status —</option>
                                    @foreach($orderStatuses as $st)
                                    <option value="{{ $st->id }}" {{ old('status_id', $order->status_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                          
                            {{-- Assign Sales — multi-select --}}
                            @php 
                                $assignedIds = old('sales_person', $order->assignments->pluck('assigned_to')->toArray());
                            @endphp
                            <div class="form-row" style="margin-bottom:20px;">
                                <label class="form-lbl">Assign Personnel</label>
                                <div class="ms-wrap" id="salesWrap">
                                    <div class="ms-trigger" onclick="toggleMs('salesWrap')">
                                        <div class="ms-pills"><span class="ms-placeholder">Select staff members…</span></div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="salesDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search staff…" oninput="filterMs(this,'salesDropdown')">
                                            <span class="ms-all-btn" onclick="toggleAllMs('salesWrap','salesDropdown')">Select All</span>
                                        </div>
                                        <div class="ms-opts">
                                            @foreach($sales as $m)
                                                @php 
                                                    $initials = strtoupper(substr($m->name, 0, 2)); 
                                                    $colors = ['#6366f1','#ec4899','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                                                    $bg = $colors[$m->id % count($colors)];
                                                @endphp
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="sales_person[]" value="{{ $m->id }}" 
                                                        data-name="{{ $m->name }}" data-initials="{{ $initials }}"
                                                        onchange="updateMs('salesWrap')"
                                                        {{ in_array($m->id, $assignedIds) ? 'checked' : '' }}>
                                                    <span class="ms-ava" style="background:{{ $bg }}">{{ $initials }}</span>
                                                    <div>
                                                        <div style="font-size:12.5px;font-weight:600;color:var(--t1);">{{ $m->name }}</div>
                                                        <div style="font-size:10.5px;color:var(--t3);">{{ $m->email }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:11px;">
                                    <i class="bi bi-check-all"></i> Update Order
                                </button>
                                <a href="{{ route($routePrefix . '.orders.index') }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;text-decoration:none;">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                    

                </div>

            </div>
        </form>
        <div class="dash-grid" style="margin-top: 16px;">
            <div class="span-8">
                {{-- Order Notes History Card --}}
                @include('admin.orders._notes_history')
            </div>
        </div>
    </div>
</main>

@include('admin.orders.multiselect-assets')
@include('admin.leads._phone_email_assets')
@include('admin.orders._notes_assets')

<script>
    function toggleMktSection() {
        const isEnabled = document.getElementById('mktToggle').checked;
        document.getElementById('mktBody').style.display = isEnabled ? 'block' : 'none';
    }

    function checkServiceType() {
        const sel = document.getElementById('serviceSelect');
        const text = sel.options[sel.selectedIndex].text.toLowerCase();
        
        const mktCheck = document.getElementById('mktToggle');
        if(text.includes('marketing')) {
            mktCheck.checked = true;
            toggleMktSection();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(typeof updateMs === 'function') updateMs('salesWrap');
        // Initial state for marketing if pre-checked
        toggleMktSection();

        // Hydrate Emails/Phones
        const leadEmails = @json($order->emails ?? []);
        const leadPhones = @json($order->phones ?? []);

        if (leadEmails.length > 0) {
            leadEmails.forEach(email => addEmailRow('order-email-list', email));
        } else {
            addEmailRow('order-email-list');
        }

        if (leadPhones.length > 0) {
            leadPhones.forEach(phone => addPhoneRow('order-phone-list', phone.number, phone.code_idx));
        } else {
            addPhoneRow('order-phone-list');
        }
    });
</script>

@endsection