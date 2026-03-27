@extends('admin.layout.app')

@section('title', 'Add Project')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-add-project">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Add New Project</h1>
                <p class="page-desc">Fill in the details below to create a new project</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
            </div>
        </div>

        <form action="{{ route('admin.projects.store') }}" method="POST">
            @csrf

            @if(session('success'))
                <div class="alert alert-success" style="padding:12px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger" style="padding:12px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:16px;">
                    @foreach($errors->all() as $error)
                        <p style="margin:0;"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="dash-grid">

                {{-- ══ LEFT COL — 8 spans ══ --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- ── LINK TO ORDER (Optional) ── --}}
                    <div class="dash-card" style="overflow:visible;border:1px solid var(--accent);box-shadow:0 0 20px rgba(99,102,241,0.08);">
                        <div class="card-head" style="border-bottom:1px solid var(--b1);background:rgba(99,102,241,0.03);">
                             <div class="card-title"><i class="bi bi-cart-fill" style="color:var(--accent);margin-right:6px;"></i>Link to Order</div>
                             <div class="card-sub">Select an existing order to auto-fill details</div>
                        </div>
                        <div class="card-body" style="padding:15px 20px;">
                            <div class="order-select-wrap">
                                <input type="hidden" name="order_id" id="selectedOrderId" value="{{ old('order_id') }}">
                                <div class="os-trigger" onclick="toggleOs()">
                                    <div class="os-selected-text">
                                        <span class="os-placeholder">— Select Order (Optional) —</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="os-dropdown shadow-lg">
                                    <div class="os-search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="os-search-inp" placeholder="Search orders by company, domain, or name..." onkeyup="filterOs(this.value)">
                                    </div>
                                    <div class="os-options">
                                        <div class="os-opt" onclick="selectOrder('')">
                                            <div class="os-opt-main" style="color:var(--t4)">None / Manual Entry</div>
                                        </div>
                                        @foreach($orders as $o)
                                            <div class="os-opt" data-id="{{ $o->id }}" onclick="selectOrder('{{ $o->id }}')">
                                                <div class="os-opt-main">
                                                    <span>{{ $o->company_name ?? 'No Company' }}</span>
                                                    <span class="os-date">{{ $o->created_at->format('d M Y') }}</span>
                                                </div>
                                                <div class="os-opt-sub">
                                                    <span><i class="bi bi-globe" style="margin-right:3px"></i>{{ $o->domain_name ?? 'N/A' }}</span>
                                                    <span><i class="bi bi-person" style="margin-right:3px"></i>{{ $o->client_name }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Basic Information --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-person-vcard-fill" style="color:var(--accent);margin-right:6px;"></i>Basic Information</div>
                                <div class="card-sub">Client and account details</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Project Name / Domain *</label>
                                    <input type="text" name="project_name" class="form-inp" placeholder="e.g. novatech.io" required value="{{ old('project_name') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name *</label>
                                    <input type="text" name="client_name" class="form-inp" placeholder="Full name" required value="{{ old('client_name') }}">
                                </div>
                                
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email ID(s)</label>
                                    <div id="add-email-list">
                                        {{-- Multi-email rows injected by JS --}}
                                    </div>
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone Number(s)</label>
                                    <div id="add-phone-list">
                                        {{-- Multi-phone rows injected by JS --}}
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="company_name" class="form-inp" placeholder="Company name" value="{{ old('company_name') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-inp" value="{{ old('starting_date') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-inp" placeholder="e.g. dynamick" value="{{ old('plan_name') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="payment_status_id" class="form-inp">
                                        <option value="">— Select —</option>
                                        @foreach($statuses['payment_statuses'] as $ps)
                                            <option value="{{ $ps->id }}" {{ old('payment_status_id') == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="username" class="form-inp" placeholder="Account username" value="{{ old('username') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="password" class="form-inp" placeholder="Account password" value="{{ old('password') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">No. of Mail IDs</label>
                                    <input type="number" name="no_of_mail_ids" class="form-inp" placeholder="e.g. 5" min="0" value="{{ old('no_of_mail_ids', 0) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Mail Password</label>
                                    <input type="text" name="mail_password" class="form-inp" placeholder="Mail password" value="{{ old('mail_password') }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Domain, Server Book</label>
                                    <input type="text" name="domain_server_book" class="form-inp" placeholder="Domain registrar, server details, control panel…" value="{{ old('domain_server_book') }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Street address, city, state, PIN…">{{ old('full_address') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Website Project Details --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-globe2" style="color:#06b6d4;margin-right:6px;"></i>Website Project Details</div>
                                <div class="card-sub">Technical specifications for the website</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name</label>
                                    <input type="text" name="domain_name" class="form-inp" placeholder="example.com" value="{{ old('domain_name') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Hosting Provider</label>
                                    <input type="text" name="hosting_provider" class="form-inp" placeholder="Hostinger, GoDaddy, AWS…" value="{{ old('hosting_provider') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">CMS / Platform</label>
                                    <select name="cms_platform" class="form-inp" id="cmsSelect" onchange="toggleCustomCms()">
                                        <option value="">— Select —</option>
                                        <option value="WordPress" {{ old('cms_platform') == 'WordPress' ? 'selected' : '' }}>WordPress</option>
                                        <option value="Shopify" {{ old('cms_platform') == 'Shopify' ? 'selected' : '' }}>Shopify</option>
                                        <option value="Custom" {{ old('cms_platform') == 'Custom' ? 'selected' : '' }}>Custom</option>
                                        <option value="other" {{ old('cms_platform') == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="form-row" id="customCmsRow" style="display:{{ old('cms_platform') == 'other' ? 'flex' : 'none' }};">
                                    <label class="form-lbl">Specify Platform</label>
                                    <input type="text" name="cms_custom" class="form-inp" placeholder="e.g. Wix, Webflow, Laravel…" value="{{ old('cms_custom') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Number of Pages</label>
                                    <input type="number" name="no_of_pages" class="form-inp" placeholder="e.g. 10" min="1" value="{{ old('no_of_pages', 1) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Website Payment Status</label>
                                    <select name="website_payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option value="Pending" {{ old('website_payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Partial" {{ old('website_payment_status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="Paid" {{ old('website_payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Required Features</label>
                                    <textarea name="required_features" class="form-inp" rows="2" placeholder="Login, payment gateway, product catalogue, blog, multilingual…">{{ old('required_features') }}</textarea>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Reference Websites</label>
                                    <input type="text" name="reference_websites" class="form-inp" placeholder="https://example1.com, https://example2.com" value="{{ old('reference_websites') }}">
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                {{-- ══ RIGHT COL — 4 spans ══ --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Project Timeline --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-calendar3" style="color:#f59e0b;margin-right:6px;"></i>Project Timeline</div>
                                <div class="card-sub">Schedule and status</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Project Start Date</label>
                                <input type="date" name="project_start_date" class="form-inp" value="{{ old('project_start_date') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Expected Delivery Date</label>
                                <input type="date" name="expected_delivery_date" class="form-inp" value="{{ old('expected_delivery_date') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Actual Delivery Date</label>
                                <input type="date" name="actual_delivery_date" class="form-inp" value="{{ old('actual_delivery_date') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Project Status</label>
                                <select name="project_status_id" class="form-inp">
                                    <option value="">— Select —</option>
                                    @foreach($statuses['project_statuses'] as $ps)
                                        <option value="{{ $ps->id }}" {{ old('project_status_id') == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Assign To — Multiple Developers --}}
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-people-fill" style="color:#10b981;margin-right:6px;"></i>Assign To (Developers)</div>
                            <div class="card-sub">Select one or more team members</div>
                        </div>
                        <div class="card-body">
                            <div class="ms-wrap" id="addAssignWrap">
                                <div class="ms-trigger" onclick="toggleMs('addAssignWrap')">
                                    <div class="ms-pills" id="addAssignPills">
                                        <span class="ms-placeholder">Select developers…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="addAssignDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'addAssignDropdown')">
                                    </div>
                                    <div class="ms-opts">
                                        @foreach($developers as $index => $dev)
                                            @php
                                                $words = explode(' ', $dev->name);
                                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                            @endphp
                                            <label class="ms-opt">
                                                <input type="checkbox" name="assign_to[]" value="{{ $dev->id }}" 
                                                    data-name="{{ $dev->name }}" data-initials="{{ $initials }}"
                                                    onchange="updateMs('addAssignWrap')">
                                                <span class="ms-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">{{ $initials }}</span>
                                                <div style="display:flex;flex-direction:column;">
                                                    <span style="font-weight:500;color:var(--t1);">{{ $dev->name }}</span>
                                                    <span style="font-size:11px;color:var(--t3);">{{ $dev->designation }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Assign To — Multiple Sales Persons --}}
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-person-badge-fill" style="color:var(--accent);margin-right:6px;"></i>Assign To (Sales)</div>
                            <div class="card-sub">Select one or more sales personnel</div>
                        </div>
                        <div class="card-body">
                            <div class="ms-wrap" id="addSaleAssignWrap">
                                <div class="ms-trigger" onclick="toggleMs('addSaleAssignWrap')">
                                    <div class="ms-pills" id="addSaleAssignPills">
                                        <span class="ms-placeholder">Select sales staff…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="addSaleAssignDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'addSaleAssignDropdown')">
                                    </div>
                                    <div class="ms-opts">
                                        @foreach($salesPersons as $index => $sale)
                                            @php
                                                $words = explode(' ', $sale->name);
                                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                            @endphp
                                            <label class="ms-opt">
                                                <input type="checkbox" name="sales_person_ids[]" value="{{ $sale->id }}" 
                                                    data-name="{{ $sale->name }}" data-initials="{{ $initials }}"
                                                    onchange="updateMs('addSaleAssignWrap')">
                                                <span class="ms-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">{{ $initials }}</span>
                                                <div style="display:flex;flex-direction:column;">
                                                    <span style="font-weight:500;color:var(--t1);">{{ $sale->name }}</span>
                                                    <span style="font-size:11px;color:var(--t3);">{{ $sale->email }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Fields --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-currency-rupee" style="color:#8b5cf6;margin-right:6px;"></i>Financial Fields</div>
                            <div class="card-sub">Pricing & payment info</div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Project Price *</label>
                                <input type="number" name="project_price" id="projectPrice" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()" required value="{{ old('project_price') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Advance Payment</label>
                                <input type="number" name="advance_payment" id="advancePayment" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()" value="{{ old('advance_payment', 0) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Remaining Amount</label>
                                <div style="padding:9px 12px;background:var(--bg4);border:1px solid var(--b1);border-radius:var(--r-sm);font-size:13px;font-weight:700;color:#ef4444;font-family:var(--mono);" id="remainingDisplay">₹ {{ old('remaining_amount', 0) }}</div>
                                <input type="hidden" name="remaining_amount" id="remainingHidden" value="{{ old('remaining_amount', 0) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Financial Payment Status</label>
                                <select name="financial_payment_status" class="form-inp">
                                    <option value="">— Select —</option>
                                    <option value="Pending" {{ old('financial_payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Partial" {{ old('financial_payment_status') == 'Partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="Paid" {{ old('financial_payment_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                            <div class="form-row" style="margin-bottom:0;">
                                <label class="form-lbl">Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-inp" placeholder="INV-XXXX" value="{{ old('invoice_number') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <button type="submit" class="btn-primary-solid" style="justify-content:center;width:100%;padding:11px;">
                            <i class="bi bi-plus-lg"></i> Create Project
                        </button>
                        <a href="{{ route('admin.projects.index') }}" class="btn-ghost" style="justify-content:center;width:100%;padding:10px;">
                            Cancel
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>

<script>
    function calcRemaining() {
        const price = parseFloat(document.getElementById('projectPrice').value) || 0;
        const advance = parseFloat(document.getElementById('advancePayment').value) || 0;
        const rem = Math.max(0, price - advance);
        document.getElementById('remainingDisplay').textContent = '₹ ' + rem.toLocaleString('en-IN');
        document.getElementById('remainingHidden').value = rem;
    }

    function toggleCustomCms() {
        const val = document.getElementById('cmsSelect').value;
        document.getElementById('customCmsRow').style.display = val === 'other' ? 'flex' : 'none';
    }

    // Set today as default for dates
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="project_start_date"]').value = today;

        // Trigger multi-select update
        updateMs('addAssignWrap');
        updateMs('addSaleAssignWrap');
    });
</script>

@include('admin.project._multiselect_assets')
@include('admin.project._order_select_assets')
@include('admin.project._phone_email_assets')

@endsection
