@extends('admin.layout.app')

@section('title', 'Create Order')

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
                <h1 class="page-title">Convert Lead to Order</h1>
                <p class="page-desc">Finalize contract details and initiate delivery workflow</p>
            </div>
        </div>

        <form action="{{ route($routePrefix . '.orders.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if($lead)
                <input type="hidden" name="lead_id" value="{{ $lead->id }}">
            @endif

            <div class="dash-grid">

                {{-- LEFT — 8 --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Order Info --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-bag-fill" style="color:var(--accent);margin-right:6px;"></i>Order Information</div>
                            <div class="card-sub">Client details and order specifics inherited from Lead</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="company_name" class="form-inp @error('company_name') is-invalid @enderror" value="{{ old('company_name', $lead->company ?? '') }}" placeholder="Company name">
                                    @error('company_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="client_name" class="form-inp @error('client_name') is-invalid @enderror" value="{{ old('client_name', $lead->contact_person ?? '') }}" placeholder="Full name">
                                    @error('client_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Emails <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <div id="order-email-list"></div>
                                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                                    @error('email. <span style="color:#ef4444">*</span>')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Phones <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <div id="order-phone-list"></div>
                                    @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                                    @error('phone. <span style="color:#ef4444">*</span>')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="domain_name" class="form-inp @error('domain_name') is-invalid @enderror" value="{{ old('domain_name') }}" placeholder="example.com">
                                    @error('domain_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Service / Product <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    @php $leadServiceIds = isset($lead) ? $lead->services->pluck('id')->toArray() : []; @endphp
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
                                                            {{ in_array($service->id, $leadServiceIds) ? 'checked' : '' }}>
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
                                    @php $leadSourceIds = isset($lead) ? $lead->sources->pluck('id')->toArray() : []; @endphp
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
                                                            {{ in_array($source->id, $leadSourceIds) ? 'checked' : '' }}>
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
                                    <input type="number" name="order_value" class="form-inp @error('order_value') is-invalid @enderror" value="{{ old('order_value') }}" placeholder="₹ Amount">
                                    @error('order_value')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Discount (Optional)</label>
                                    <input type="number" step="0.01" name="discount" class="form-inp @error('discount') is-invalid @enderror" value="{{ old('discount') }}" placeholder="₹ Discount Amount">
                                    @error('discount')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Terms <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <select name="payment_terms_id" class="form-inp @error('payment_terms_id') is-invalid @enderror">
                                        <option value="">— Select Terms —</option>
                                        @foreach($paymentStatuses as $ps)
                                            <option value="{{ $ps->id }}" {{ old('payment_terms_id') == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('payment_terms_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Delivery Date <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="date" name="delivery_date" class="form-inp @error('delivery_date') is-invalid @enderror" value="{{ old('delivery_date') }}">
                                    @error('delivery_date')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="dash-card" style="margin-bottom:16px;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-plus-circle-dotted" style="color:var(--accent);margin-right:6px;"></i>Add New Payment Entry</div>
                            <div class="card-sub">Record an installment or final payment (Optional)</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Payment Date <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="date" name="transaction_date" id="payDate" class="form-inp @error('transaction_date') is-invalid @enderror" value="{{ old('transaction_date', date('Y-m-d')) }}">
                                    @error('transaction_date')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Amount Received (₹) <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="amount" id="payAmount" class="form-inp @error('amount') is-invalid @enderror" value="{{ old('amount') }}" placeholder="e.g. 50000" step="0.01">
                                    @error('amount')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Mode <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <select name="payment_method" class="form-inp @error('payment_method') is-invalid @enderror">
                                        <option value="Bank Transfer" {{ old('payment_method') == 'Bank Transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="UPI" {{ old('payment_method') == 'UPI' ? 'selected' : '' }}>UPI / GPay / PhonePe</option>
                                        <option value="Cheque" {{ old('payment_method') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="Other" {{ old('payment_method') == 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('payment_method')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Reference / UTR ID</label>
                                    <input type="text" name="transaction_id" class="form-inp @error('transaction_id') is-invalid @enderror" value="{{ old('transaction_id') }}" placeholder="Transaction ref code">
                                    @error('transaction_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Payment Proof / Screenshot</label>
                                    <input type="file" name="screenshot" class="form-inp" accept="image/*,application/pdf">
                                    <p style="font-size:11px; color:var(--t3); margin-top:4px;">Upload PNG, JPG, or PDF (Max 5MB)</p>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Internal Notes</label>
                                    <textarea name="notes" class="form-inp" rows="3" placeholder="Mention installment details or bank info..."></textarea>
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
                                    <input type="text" name="city" class="form-inp @error('city') is-invalid @enderror" value="{{ old('city') }}" placeholder="City">
                                    @error('city')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Region / State <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="state" class="form-inp @error('state') is-invalid @enderror" value="{{ old('state') }}" placeholder="State or Province">
                                    @error('state')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="zip_code" class="form-inp @error('zip_code') is-invalid @enderror" value="{{ old('zip_code') }}" placeholder="6-digit ZIP" pattern="\d{6}" title="Please enter exactly 6 digits">
                                    @error('zip_code')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <textarea name="full_address" class="form-inp @error('full_address') is-invalid @enderror" rows="2" placeholder="Street address…">{{ old('full_address', $lead->address ?? '') }}</textarea>
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
                                    <input type="checkbox" name="is_marketing" value="1" id="mktToggle" onchange="toggleMktSection()" style="accent-color:var(--accent);width:14px;height:14px;">
                                    Enable
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="mktBody" style="display:none;">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="mkt_payment_status_id" class="form-inp">
                                        <option value="">— Select —</option>
                                        @foreach($paymentStatuses as $ps)
                                            <option value="{{ $ps->id }}" {{ old('mkt_payment_status_id') == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('mkt_payment_status_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="mkt_starting_date" class="form-inp @error('mkt_starting_date') is-invalid @enderror" value="{{ old('mkt_starting_date') }}">
                                    @error('mkt_starting_date')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
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
                                                            {{ is_array(old('plan_ids')) && in_array($plan->id, old('plan_ids')) ? 'checked' : '' }}>
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
                                    <input type="text" name="mkt_username" class="form-inp @error('mkt_username') is-invalid @enderror" value="{{ old('mkt_username') }}" placeholder="Account username">
                                    @error('mkt_username')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="mkt_password" class="form-inp @error('mkt_password') is-invalid @enderror" value="{{ old('mkt_password') }}" placeholder="Account password">
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
                            <div class="card-title"><i class="bi bi-send-fill" style="color:#10b981;margin-right:6px;"></i>Confirm Conversion</div>
                            <div class="card-sub">Review and initiate delivery</div>
                        </div>
                        <div class="card-body">

                            {{-- Order Status --}}
                            <div class="form-row" style="margin-bottom:16px;">
                                <label class="form-lbl">Order Status <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                <select name="status_id" class="form-inp @error('status_id') is-invalid @enderror">
                                    <option value="">— Select Status —</option>
                                    @foreach($orderStatuses as $st)
                                    <option value="{{ $st->id }}" {{ old('status_id') == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                    @endforeach
                                </select>
                                @error('status_id')<span class="field-error">{{ $message }}</span>@enderror
                            </div>

                          
                            {{-- Assign Sales — multi-select --}}
                            @php 
                                $assignedIds = isset($lead) ? $lead->assignments->pluck('assigned_to')->toArray() : [];
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
                                                        {{ (is_array(old('sales_person')) && in_array($m->id, old('sales_person'))) || (!old('sales_person') && in_array($m->id, $assignedIds)) ? 'checked' : '' }}>
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

                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:11px;">
                                    <i class="bi bi-check-all"></i> Finalize Order
                                </button>
                                <a href="{{ route($routePrefix . '.leads.show', $lead->id ?? 0) }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;text-decoration:none;">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                    {{-- Internal Notes --}}
                    <div class="dash-card" style="z-index:-1;">
                        <div class="card-head" style="padding:16px 18px; border-bottom:1px solid var(--b1);">
                            <div class="card-title">Order Notes History</div>
                        </div>
                        <div class="card-body" style="padding:18px;">
                            <div style="position:relative;">
                                <textarea name="notes" class="form-inp @error('notes') is-invalid @enderror" rows="5" placeholder="Add initial order note..." style="border-radius:12px; font-size:13px; min-height:120px;">{{ old('notes') }}</textarea>
                                @error('notes')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>

@include('admin.orders.multiselect-assets')
@include('admin.leads._phone_email_assets')
@include('admin.orders._validation_assets')

<script>
    function toggleMktSection() {
        const isEnabled = document.getElementById('mktToggle').checked;
        document.getElementById('mktBody').style.display = isEnabled ? 'block' : 'none';
        
        // Auto select Marketing radio/type if needed (if you still have those)
        // Note: The previous logic used 'typeMarketing' which was removed in recent edit.
    }

    function checkServiceType() {
        const wrap = document.getElementById('serviceWrap');
        const checkedLabels = [...wrap.querySelectorAll('.ms-optHas input:checked')].map(cb => cb.closest('.ms-opt').textContent.toLowerCase());
        
        // Alternative: get via data names
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
        checkServiceType(); // Check initially

        // Hydrate Emails/Phones
        const leadEmails = @json($lead->emails ?? []);
        const leadPhones = @json($lead->phones ?? []);

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
