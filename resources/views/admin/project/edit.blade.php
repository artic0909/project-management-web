@extends('admin.layout.app')

@section('title', 'Edit Project - ' . $project->project_name)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-project-edit">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Project</h1>
                <p class="page-desc">Modify details for <span style="color:var(--accent);font-weight:600;">{{ $project->project_name }}</span></p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.projects.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="dash-card" style="border-left:4px solid #ef4444;margin-bottom:20px;padding:12px 18px;">
                <div style="color:#ef4444;font-weight:700;margin-bottom:8px;display:flex;align-items:center;gap:8px;">
                    <i class="bi bi-exclamation-triangle-fill"></i> Please fix the following errors:
                </div>
                <ul style="margin:0;padding-left:20px;color:var(--t3);font-size:13px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="dash-grid">

                {{-- ══ LEFT COL — 8 spans ══ --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                    
                    {{-- ── LINK TO ORDER (Optional) ── --}}
                    <div class="dash-card" style="overflow:visible;border:1px solid var(--accent);box-shadow:0 0 20px rgba(99,102,241,0.08);">
                        <div class="card-head" style="border-bottom:1px solid var(--b1);background:rgba(99,102,241,0.03);">
                             <div class="card-title"><i class="bi bi-cart-fill" style="color:var(--accent);margin-right:6px;"></i>Link to Order</div>
                             <div class="card-sub">Select an order to refresh details</div>
                        </div>
                        <div class="card-body" style="padding:15px 20px;">
                            <div class="order-select-wrap">
                                <input type="hidden" name="order_id" id="selectedOrderId" value="{{ old('order_id', $project->order_id) }}">
                                <div class="os-trigger" onclick="toggleOs()">
                                    <div class="os-selected-text">
                                        @if($project->order)
                                            {{ $project->order->company_name ?? 'No Company' }} <span style="color:var(--t4);font-weight:400;margin-left:8px;">({{ $project->order->domain_name ?? 'N/A' }})</span>
                                        @else
                                            <span class="os-placeholder">— Select Order (Optional) —</span>
                                        @endif
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="os-dropdown shadow-lg">
                                    <div class="os-search-box">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="os-search-inp" placeholder="Search orders..." onkeyup="filterOs(this.value)">
                                    </div>
                                    <div class="os-options">
                                        <div class="os-opt {{ !$project->order_id ? 'active' : '' }}" onclick="selectOrder('')">
                                            <div class="os-opt-main" style="color:var(--t4)">None / Manual Entry</div>
                                        </div>
                                        @foreach($orders as $o)
                                            <div class="os-opt {{ $project->order_id == $o->id ? 'active' : '' }}" data-id="{{ $o->id }}" onclick="selectOrder('{{ $o->id }}')">
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
                                <div class="card-title"><i class="bi bi-info-circle" style="color:#6366f1;margin-right:6px;"></i>Basic Information</div>
                                <div class="card-sub">Core project and client details</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Project Name / Domain *</label>
                                    <input type="text" name="project_name" class="form-inp" placeholder="e.g. novatech.io" required value="{{ old('project_name', $project->project_name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name *</label>
                                    <input type="text" name="client_name" class="form-inp" placeholder="Full name" required value="{{ old('client_name', $project->client_name) }}">
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email ID(s)</label>
                                    <div id="edit-email-list">
                                        {{-- Multi-email rows injected by JS --}}
                                    </div>
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone Number(s)</label>
                                    <div id="edit-phone-list">
                                        {{-- Multi-phone rows injected by JS --}}
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="company_name" class="form-inp" placeholder="Company name" value="{{ old('company_name', $project->company_name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-inp" value="{{ old('starting_date', $project->starting_date ? $project->starting_date->format('Y-m-d') : '') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-inp" placeholder="e.g. Dynamic" value="{{ old('plan_name', $project->plan_name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option value="Paid" {{ old('payment_status', $project->payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Partial" {{ old('payment_status', $project->payment_status) == 'Partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="Pending" {{ old('payment_status', $project->payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="username" class="form-inp" placeholder="Account username" value="{{ old('username', $project->username) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="password" class="form-inp" placeholder="Account password" value="{{ old('password', $project->password) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">No. of Mail IDs</label>
                                    <input type="number" name="no_of_mail_ids" class="form-inp" placeholder="e.g. 5" value="{{ old('no_of_mail_ids', $project->no_of_mail_ids) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Mail Password</label>
                                    <input type="text" name="mail_password" class="form-inp" placeholder="Mail password" value="{{ old('mail_password', $project->mail_password) }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Domain, Server Book</label>
                                    <input type="text" name="domain_server_book" class="form-inp" placeholder="Domain & server info" value="{{ old('domain_server_book', $project->domain_server_book) }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1;margin-bottom:0;">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Full address…">{{ old('full_address', $project->full_address) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Website Details --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-globe2" style="color:#06b6d4;margin-right:6px;"></i>Website Project Details</div>
                                <div class="card-sub">Technical specifications</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name</label>
                                    <input type="text" name="domain_name" class="form-inp" placeholder="example.com" value="{{ old('domain_name', $project->domain_name) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Hosting Provider</label>
                                    <input type="text" name="hosting_provider" class="form-inp" placeholder="Hostinger, GoDaddy…" value="{{ old('hosting_provider', $project->hosting_provider) }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">CMS / Platform</label>
                                    <select name="cms_platform" id="cmsSelect" class="form-inp" onchange="toggleCustomCms()">
                                        <option value="">— Select —</option>
                                        <option value="WordPress" {{ old('cms_platform', $project->cms_platform) == 'WordPress' ? 'selected' : '' }}>WordPress</option>
                                        <option value="Shopify" {{ old('cms_platform', $project->cms_platform) == 'Shopify' ? 'selected' : '' }}>Shopify</option>
                                        <option value="Custom" {{ old('cms_platform', $project->cms_platform) == 'Custom' ? 'selected' : '' }}>Custom</option>
                                        <option value="other" {{ old('cms_platform', $project->cms_platform) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Number of Pages</label>
                                    <input type="number" name="no_of_pages" class="form-inp" placeholder="e.g. 10" value="{{ old('no_of_pages', $project->no_of_pages) }}">
                                </div>
                                <div class="form-row" id="customCmsRow" style="display:{{ old('cms_platform', $project->cms_platform) == 'other' ? 'flex' : 'none' }};grid-column:1/-1;">
                                    <label class="form-lbl">Specify CMS *</label>
                                    <input type="text" name="cms_custom" class="form-inp" placeholder="Specify other platform" value="{{ old('cms_custom', $project->cms_custom) }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Required Features</label>
                                    <textarea name="required_features" class="form-inp" rows="2" placeholder="Login, payment gateway, blog…">{{ old('required_features', $project->required_features) }}</textarea>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Reference Websites</label>
                                    <input type="text" name="reference_websites" class="form-inp" placeholder="https://…" value="{{ old('reference_websites', $project->reference_websites) }}">
                                </div>
                                <div class="form-row" style="margin-bottom:0;">
                                    <label class="form-lbl">Website Payment Status</label>
                                    <select name="website_payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option value="Paid" {{ old('website_payment_status', $project->website_payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                        <option value="Partial" {{ old('website_payment_status', $project->website_payment_status) == 'Partial' ? 'selected' : '' }}>Partial</option>
                                        <option value="Pending" {{ old('website_payment_status', $project->website_payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Communication & Tracking --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-chat-dots" style="color:#ec4899;margin-right:6px;"></i>Communication & Tracking</div>
                                <div class="card-sub">Notes and status logging</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Last Update Date</label>
                                    <input type="date" name="last_update_date" class="form-inp" value="{{ old('last_update_date', $project->last_update_date ? $project->last_update_date->format('Y-m-d') : '') }}">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Feedback Summary</label>
                                    <input type="text" name="client_feedback_summary" class="form-inp" placeholder="Latest feedback summary" value="{{ old('client_feedback_summary', $project->client_feedback_summary) }}">
                                </div>
                                <div class="form-row" style="grid-column:1/-1;margin-bottom:0;">
                                    <label class="form-lbl">Internal Notes</label>
                                    <textarea name="internal_notes" class="form-inp" rows="3" placeholder="Private internal notes…">{{ old('internal_notes', $project->internal_notes) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Right Column: Timeline, Assign, Financial --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">
                    
                    {{-- Timeline & Status --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-calendar3" style="color:#f59e0b;margin-right:6px;"></i>Project Timeline</div>
                                <div class="card-sub">Schedule & progress</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Project Status *</label>
                                <select name="project_status" class="form-inp" required>
                                    <option value="New" {{ old('project_status', $project->project_status) == 'New' ? 'selected' : '' }}>New</option>
                                    <option value="Design Phase" {{ old('project_status', $project->project_status) == 'Design Phase' ? 'selected' : '' }}>Design Phase</option>
                                    <option value="Development" {{ old('project_status', $project->project_status) == 'Development' ? 'selected' : '' }}>Development</option>
                                    <option value="Testing" {{ old('project_status', $project->project_status) == 'Testing' ? 'selected' : '' }}>Testing</option>
                                    <option value="Completed" {{ old('project_status', $project->project_status) == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="On Hold" {{ old('project_status', $project->project_status) == 'On Hold' ? 'selected' : '' }}>On Hold</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Project Start Date</label>
                                <input type="date" name="project_start_date" class="form-inp" value="{{ old('project_start_date', $project->project_start_date ? $project->project_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Expected Delivery Date</label>
                                <input type="date" name="expected_delivery_date" class="form-inp" value="{{ old('expected_delivery_date', $project->expected_delivery_date ? $project->expected_delivery_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="form-row" style="margin-bottom:0;">
                                <label class="form-lbl">Actual Delivery Date</label>
                                <input type="date" name="actual_delivery_date" class="form-inp" value="{{ old('actual_delivery_date', $project->actual_delivery_date ? $project->actual_delivery_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Dynamic Developer Assignment --}}
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-people-fill" style="color:#10b981;margin-right:6px;"></i>Assign To</div>
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
                                        @php
                                            $gradients = [
                                                'linear-gradient(135deg,#6366f1,#06b6d4)',
                                                'linear-gradient(135deg,#ec4899,#f59e0b)',
                                                'linear-gradient(135deg,#10b981,#06b6d4)',
                                                'linear-gradient(135deg,#8b5cf6,#ec4899)',
                                                'linear-gradient(135deg,#f59e0b,#ef4444)',
                                                'linear-gradient(135deg,#14b8a6,#6366f1)'
                                            ];
                                            $assignedIds = $project->developers->pluck('id')->toArray();
                                        @endphp
                                        @foreach($developers as $index => $dev)
                                            @php
                                                $words = explode(' ', $dev->name);
                                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                            @endphp
                                            <label class="ms-opt">
                                                <input type="checkbox" name="assign_to[]" value="{{ $dev->id }}" 
                                                    data-name="{{ $dev->name }}" data-initials="{{ $initials }}"
                                                    onchange="updateMs('addAssignWrap')"
                                                    {{ in_array($dev->id, old('assign_to', $assignedIds)) ? 'checked' : '' }}>
                                                <span class="ms-ava" style="background:{{ $gradients[$index % count($gradients)] }}">{{ $initials }}</span>
                                                <div style="display:flex;flex-direction:column;">
                                                    <span style="font-weight:500;color:var(--t1);">{{ $dev->name }}</span>
                                                    <span style="font-size:11px;color:var(--t3);">{{ $dev->email }}</span>
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
                                <input type="number" name="project_price" id="projectPrice" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()" required value="{{ old('project_price', $project->project_price) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Advance Payment</label>
                                <input type="number" name="advance_payment" id="advancePayment" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()" value="{{ old('advance_payment', $project->advance_payment) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Remaining Amount</label>
                                <div style="padding:9px 12px;background:var(--bg4);border:1px solid var(--b1);border-radius:var(--r-sm);font-size:13px;font-weight:700;color:#ef4444;font-family:var(--mono);" id="remainingDisplay">₹ {{ number_format(old('remaining_amount', $project->remaining_amount), 2) }}</div>
                                <input type="hidden" name="remaining_amount" id="remainingHidden" value="{{ old('remaining_amount', $project->remaining_amount) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Financial Payment Status</label>
                                <select name="financial_payment_status" class="form-inp">
                                    <option value="">— Select —</option>
                                    <option value="Pending" {{ old('financial_payment_status', $project->financial_payment_status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="Partial" {{ old('financial_payment_status', $project->financial_payment_status) == 'Partial' ? 'selected' : '' }}>Partial</option>
                                    <option value="Paid" {{ old('financial_payment_status', $project->financial_payment_status) == 'Paid' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>
                            <div class="form-row" style="margin-bottom:0;">
                                <label class="form-lbl">Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-inp" placeholder="INV-XXXX" value="{{ old('invoice_number', $project->invoice_number) }}">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <button type="submit" class="btn-primary-solid" style="justify-content:center;width:100%;padding:11px;">
                            <i class="bi bi-floppy-fill"></i> Update Project
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
        const customRow = document.getElementById('customCmsRow');
        if (customRow) {
            customRow.style.display = val === 'other' ? 'flex' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleCustomCms();
        calcRemaining();
        updateMs('addAssignWrap');

        // Seed Existing Emails
        @if($project->emails && is_array($project->emails))
            @foreach($project->emails as $email)
                addEmailRow('edit-email-list', '{{ $email }}');
            @endforeach
        @endif
        
        // Seed Existing Phones
        @if($project->phones && is_array($project->phones))
            @foreach($project->phones as $phone)
                @if(is_array($phone))
                    addPhoneRow('edit-phone-list', '{{ $phone['num'] }}', '{{ $phone['code'] }}');
                @else
                    addPhoneRow('edit-phone-list', '{{ $phone }}');
                @endif
            @endforeach
        @endif
    });
</script>

@include('admin.project._multiselect_assets')
@include('admin.project._order_select_assets')
@include('admin.project._phone_email_assets')

@endsection
