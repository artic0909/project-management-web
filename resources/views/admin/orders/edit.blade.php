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
            <div class="header-actions">
                <a href="{{ route($routePrefix . '.projects.create', $order->id) }}" class="btn-primary-solid sm" style="background:#8b5cf6;border-color:#8b5cf6;">
                    <i class="bi bi-plus-square-fill"></i> Convert to Project
                </a>
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
                                    <label class="form-lbl">Company Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="company_name" class="form-inp @error('company_name') is-invalid @enderror" value="{{ old('company_name', $order->company_name) }}" placeholder="Company name">
                                    @error('company_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="client_name" class="form-inp @error('client_name') is-invalid @enderror" value="{{ old('client_name', $order->client_name) }}" placeholder="Full name">
                                    @error('client_name')<span class="field-error">{{ $message }}</span>@enderror
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
                                    <input type="text" name="domain_name" class="form-inp @error('domain_name') is-invalid @enderror" value="{{ old('domain_name', $order->domain_name) }}" placeholder="example.com">
                                    @error('domain_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Service / Product <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    @php $selectedServiceIds = $order->services->pluck('id')->toArray(); @endphp
                                    <div class="ms-wrap" id="serviceWrap">
                                        <div class="ms-trigger" onclick="toggleMs('serviceWrap')">
                                            <div class="ms-pills" id="servicePills">
                                                <span class="ms-placeholder">Select services…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="serviceDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'serviceDropdown')">
                                                <span class="ms-all-btn" onclick="toggleAllMs('serviceWrap','serviceDropdown')">Select All</span>
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($services as $service)
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" 
                                                            data-name="{{ $service->name }}"
                                                            onchange="updateMs('serviceWrap'); checkServiceType();"
                                                            {{ in_array($service->id, $selectedServiceIds) ? 'checked' : '' }}>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $service->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('service_ids')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Sources <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    @php $selectedSourceIds = $order->sources->pluck('id')->toArray(); @endphp
                                    <div class="ms-wrap" id="sourceWrap">
                                        <div class="ms-trigger" onclick="toggleMs('sourceWrap')">
                                            <div class="ms-pills" id="sourcePills">
                                                <span class="ms-placeholder">Select sources…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="sourceDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'sourceDropdown')">
                                                <span class="ms-all-btn" onclick="toggleAllMs('sourceWrap','sourceDropdown')">Select All</span>
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($sources as $source)
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="source_ids[]" value="{{ $source->id }}" 
                                                            data-name="{{ $source->name }}"
                                                            onchange="updateMs('sourceWrap')"
                                                            {{ in_array($source->id, $selectedSourceIds) ? 'checked' : '' }}>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $source->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('source_ids')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Order Value <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="order_value" class="form-inp @error('order_value') is-invalid @enderror" value="{{ old('order_value', $order->order_value) }}" placeholder="₹ Amount">
                                    @error('order_value')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Discount (Optional)</label>
                                    <input type="number" step="0.01" name="discount" class="form-inp @error('discount') is-invalid @enderror" value="{{ old('discount', $order->discount) }}" placeholder="₹ Discount Amount">
                                    @error('discount')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Advance Payment</label>
                                    <input type="number" name="advance_payment" class="form-inp @error('advance_payment') is-invalid @enderror" value="{{ old('advance_payment', $order->advance_payment) }}" placeholder="₹ Advance Received">
                                    @error('advance_payment')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Terms</label>
                                    <select name="payment_terms_id" class="form-inp @error('payment_terms_id') is-invalid @enderror">
                                        <option value="">— Select Terms —</option>
                                        @foreach($paymentStatuses as $ps)
                                            <option value="{{ $ps->id }}" {{ old('payment_terms_id', $order->payment_terms_id) == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_terms_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-inp @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date', $order->delivery_date ? $order->delivery_date->format('Y-m-d') : '') }}">
                                    @error('delivery_date')<span class="field-error">{{ $message }}</span>@enderror
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
                                    <label class="form-lbl">City <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="city" class="form-inp @error('city') is-invalid @enderror" value="{{ old('city', $order->city) }}" placeholder="City">
                                    @error('city')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Region / State <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="state" class="form-inp @error('state') is-invalid @enderror" value="{{ old('state', $order->state) }}" placeholder="State or Province">
                                    @error('state')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="zip_code" class="form-inp @error('zip_code') is-invalid @enderror" value="{{ old('zip_code', $order->zip_code) }}" placeholder="6-digit ZIP" pattern="\d{6}" title="Please enter exactly 6 digits">
                                    @error('zip_code')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp @error('full_address') is-invalid @enderror" rows="2" placeholder="Street address…">{{ old('full_address', $order->full_address) }}</textarea>
                                    @error('full_address')<span class="field-error">{{ $message }}</span>@enderror
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
                                    @error('mkt_payment_status_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="mkt_starting_date" class="form-inp @error('mkt_starting_date') is-invalid @enderror" value="{{ old('mkt_starting_date', $order->mkt_starting_date ? $order->mkt_starting_date->format('Y-m-d') : '') }}">
                                    @error('mkt_starting_date')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    @php $selectedPlanIds = old('plan_ids', $order->plans->pluck('id')->toArray()); @endphp
                                    <div class="ms-wrap" id="planWrap">
                                        <div class="ms-trigger" onclick="toggleMs('planWrap')" data-placeholder="Select plans…">
                                            <div class="ms-pills" id="planPills">
                                                <span class="ms-placeholder">Select plans…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="planDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search plans…" oninput="filterMs(this,'planDropdown')">
                                                <span class="ms-all-btn" onclick="toggleAllMs('planWrap','planDropdown')">Select All</span>
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($plans as $plan)
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="plan_ids[]" value="{{ $plan->id }}" 
                                                            data-name="{{ $plan->name }}"
                                                            onchange="updateMs('planWrap')"
                                                            {{ in_array($plan->id, $selectedPlanIds) ? 'checked' : '' }}>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $plan->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('plan_ids')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="mkt_username" class="form-inp @error('mkt_username') is-invalid @enderror" value="{{ old('mkt_username', $order->mkt_username) }}" placeholder="Account username">
                                    @error('mkt_username')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="mkt_password" class="form-inp @error('mkt_password') is-invalid @enderror" value="{{ old('mkt_password', $order->mkt_password) }}" placeholder="Account password">
                                    @error('mkt_password')<span class="field-error">{{ $message }}</span>@enderror
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
                                <label class="form-lbl">Order Status <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                <select name="status_id" class="form-inp @error('status_id') is-invalid @enderror">
                                    <option value="">— Select Status —</option>
                                    @foreach($orderStatuses as $st)
                                    <option value="{{ $st->id }}" {{ old('status_id', $order->status_id) == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                                @error('status_id')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                          
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
                                    @error('sales_person')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>

                            <div style="display:flex;flex-direction:column;gap:8px; padding: 0 18px 18px;">
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
            </form>

            <div class="dash-grid" style="margin-top: 24px;">
                <div class="span-8">
                    {{-- Order Notes History Card --}}
                    @include('admin.orders._notes_history')
                </div>
            </div>
        </div>
    </div>
</main>

@include('admin.orders.multiselect-assets')
@include('admin.leads._phone_email_assets')
@include('admin.orders._notes_assets')
@include('admin.orders._validation_assets')

<script>
    function toggleMktSection() {
        const isEnabled = document.getElementById('mktToggle').checked;
        document.getElementById('mktBody').style.display = isEnabled ? 'block' : 'none';
    }

    function checkServiceType() {
        const wrap = document.getElementById('serviceWrap');
        const checkedNames = [...wrap.querySelectorAll('input[name="service_ids[]"]:checked')].map(cb => cb.dataset.name.toLowerCase());
        
        const mktCheck = document.getElementById('mktToggle');
        if(checkedNames.some(name => name.includes('marketing'))) {
            mktCheck.checked = true;
            toggleMktSection();
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        if(typeof updateMs === 'function') {
            updateMs('salesWrap');
            updateMs('serviceWrap');
            updateMs('sourceWrap');
            updateMs('planWrap');
        }
        // Initial state for marketing if pre-checked
        toggleMktSection();

        // Check for old input first, otherwise use model data
        const oldEmails = @json(old('email'));
        const leadEmails = (oldEmails && oldEmails.length > 0) ? [] : @json($order->emails ?? []);
        const emailListId = 'order-email-list';

        if (leadEmails.length > 0) {
            leadEmails.forEach(email => addEmailRow(emailListId, email));
        } else if (!oldEmails || oldEmails.length === 0) {
            // Only add if no old and no existing
            addEmailRow(emailListId);
        }

        // Check for old input first, otherwise use model data
        const oldPhones = @json(old('phone'));
        const leadPhones = (oldPhones && oldPhones.length > 0) ? [] : @json($order->phones ?? []);
        const phoneListId = 'order-phone-list';

        if (leadPhones.length > 0) {
            leadPhones.forEach(phone => addPhoneRow(phoneListId, phone.number, phone.code_idx));
        } else if (!oldPhones || oldPhones.length === 0) {
            // Only add if no old and no existing
            addPhoneRow(phoneListId);
        }
    });
</script>

@endsection
