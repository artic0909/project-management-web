@extends('admin.layout.app')

@section('title', 'Edit Project - ' . $project->project_name)

@section('content')

<style>
      /* Timeline Styling */
        .feedback-timeline {
            display: flex;
            flex-direction: column;
            gap: 24px;
            position: relative;
            margin-left: 10px;
            padding-top: 10px;
        }

        .feedback-timeline::after {
            content: '';
            position: absolute;
            left: 100px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--b1);
        }

        .timeline-item {
            display: flex;
            gap: 40px;
        }

        .timeline-meta {
            width: 80px;
            text-align: right;
            flex-shrink: 0;
        }

        .tm-date {
            font-size: 12px;
            font-weight: 700;
            color: var(--t1);
        }

        .tm-time {
            font-size: 10px;
            color: var(--t4);
            margin-top: 2px;
        }

        .timeline-content {
            flex: 1;
            position: relative;
            padding-bottom: 10px;
        }

        .timeline-content::after {
            content: '';
            position: absolute;
            left: -24px;
            top: 6px;
            width: 10px;
            height: 10px;
            background: #fff;
            border: 2px solid var(--accent);
            border-radius: 50%;
            z-index: 2;
        }

        .ts-head {
            font-size: 14px;
            font-weight: 700;
            color: var(--t1);
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ts-head i {
            color: var(--accent);
            font-size: 12px;
        }

        .ts-notes {
            font-size: 13px;
            color: var(--t2);
            padding: 12px;
            background: var(--bg3);
            border-radius: 8px;
            border: 1px solid var(--b1);
            white-space: pre-wrap;
            line-height: 1.5;
        }

        .timeline-empty {
            text-align: center;
            padding: 40px;
            color: var(--t4);
        }

        .timeline-empty i {
            font-size: 32px;
            display: block;
            margin-bottom: 10px;
            opacity: 0.3;
        }
</style>

<main class="page-area" id="pageArea">
    <div class="page" id="page-project-edit">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Project</h1>
                <p class="page-desc">Modify details for <span style="color:var(--accent);font-weight:600;">{{ $project->project_code }}</span></p>
            </div>
            <div class="header-actions">
                <a href="{{ route($routePrefix . '.projects.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <form action="{{ route($routePrefix . '.projects.update', $project->id) }}" method="POST">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="alert alert-success" style="padding:12px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif



            <div class="dash-grid">

                {{-- ══ LEFT COL — 8 spans ══ --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Section 1: Identity & Contact --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-person-vcard-fill" style="color:var(--accent);margin-right:6px;"></i>Project Identity & Contact</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                {{-- IDs Row --}}
                                <div class="form-row">
                                    <label class="form-lbl">Order ID <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <div class="order-select-wrap">
                                        <input type="hidden" name="order_id" id="selectedOrderId" value="{{ old('order_id', $project->order_id) }}">
                                        <div class="os-trigger" onclick="toggleOs()">
                                            <div class="os-selected-text">
                                                @if($project->order)
                                                    {{ $project->order->id }} - {{ $project->order->company_name }}
                                                @else
                                                    <span class="os-placeholder">— Select Order —</span>
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
                                                @foreach($orders as $o)
                                                    <div class="os-opt {{ $project->order_id == $o->id ? 'active' : '' }}" data-id="{{ $o->id }}" onclick="selectOrder('{{ $o->id }}')">
                                                        <div class="os-opt-main">
                                                            <span>#{{ $o->id }} - {{ $o->company_name ?? 'No Company' }}</span>
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
                                <div class="form-row">
                                    <label class="form-lbl">Project ID <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" class="form-inp" value="{{ $project->project_code }}" readonly style="background:var(--bg3);color:var(--accent);font-weight:700;">
                                </div>

                                {{-- Name Row --}}
                                <div class="form-row">
                                    <label class="form-lbl">First Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="first_name" class="form-inp" placeholder="First name" value="{{ old('first_name', $project->first_name) }}">
                                    @error('first_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Last Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="last_name" class="form-inp" placeholder="Last name" value="{{ old('last_name', $project->last_name) }}">
                                    @error('last_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                {{-- Contact Info --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email ID(s) <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <div id="edit-email-list">
                                        {{-- Multi-email rows injected by JS --}}
                                    </div>
                                    @error('email.*')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone Number(s) <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <div id="edit-phone-list">
                                        {{-- Multi-phone rows injected by JS --}}
                                    </div>
                                    @error('phone.*')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Company Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="company_name" class="form-inp" placeholder="Enter company name" value="{{ old('company_name', $project->company_name) }}">
                                    @error('company_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 2: Address Details --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-geo-alt-fill" style="color:#ef4444;margin-right:6px;"></i>Address Details</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">State / Region / Province <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="state" class="form-inp" placeholder="State" value="{{ old('state', $project->state) }}">
                                    @error('state')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">City <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="city" class="form-inp" placeholder="City" value="{{ old('city', $project->city) }}">
                                    @error('city')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Street, landmark, etc.">{{ old('full_address', $project->full_address) }}</textarea>
                                    @error('full_address')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="zip_code" class="form-inp" placeholder="6-digit ZIP" pattern="\d{6}" value="{{ old('zip_code', $project->zip_code) }}">
                                    @error('zip_code')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 3: Website & Platform --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-globe2" style="color:#06b6d4;margin-right:6px;"></i>Website & Platform</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="domain_name" class="form-inp" placeholder="example.com" value="{{ old('domain_name', $project->domain_name) }}">
                                    @error('domain_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    @php $assignedPlanIds = $project->plans->pluck('id')->toArray(); @endphp
                                    <div class="ms-wrap" id="planWrap">
                                        <div class="ms-trigger" onclick="toggleMs('planWrap')">
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
                                                            {{ in_array($plan->id, old('plan_ids', $assignedPlanIds)) ? 'checked' : '' }}>
                                                        <span style="font-weight:500;color:var(--t1);">{{ $plan->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    @error('plan_ids')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Website Username <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="username" class="form-inp" placeholder="Username" value="{{ old('username', $project->username) }}">
                                    @error('username')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Website Password <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="password" class="form-inp" placeholder="Password" value="{{ old('password', $project->password) }}">
                                    @error('password')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">CMS / Platform <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    @php
                                        $standardPlatforms = ['WordPress', 'Shopify', 'Custom'];
                                        $isOthers = !in_array($project->cms_platform, $standardPlatforms) && !empty($project->cms_platform);
                                    @endphp
                                    <div style="display:flex;gap:12px;">
                                        <select name="cms_platform" class="form-inp" style="flex:1" id="cmsSelect" onchange="toggleCustomCms()">
                                            <option value="">— Select —</option>
                                            <option value="WordPress" {{ old('cms_platform', $project->cms_platform) == 'WordPress' ? 'selected' : '' }}>WordPress</option>
                                            <option value="Shopify" {{ old('cms_platform', $project->cms_platform) == 'Shopify' ? 'selected' : '' }}>Shopify</option>
                                            <option value="Custom" {{ old('cms_platform', $project->cms_platform) == 'Custom' ? 'selected' : '' }}>Custom</option>
                                            <option value="Others" {{ (old('cms_platform') == 'Others' || $isOthers) ? 'selected' : '' }}>Others</option>
                                        </select>
                                        <input type="text" name="cms_custom" id="cmsCustomInput" class="form-inp" style="flex:1; display: {{ (old('cms_platform') == 'Others' || $isOthers) ? 'block' : 'none' }};" placeholder="Specify platform..." value="{{ old('cms_custom', $isOthers ? $project->cms_platform : $project->cms_custom) }}">
                                    </div>
                                    @error('cms_platform')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Section 4: Hosting & Domain Provider --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-server" style="color:#8b5cf6;margin-right:6px;"></i>Hosting & Domain Provider</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Provider Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="domain_provider_name" class="form-inp" placeholder="e.g. GoDaddy" value="{{ old('domain_provider_name', $project->domain_provider_name) }}">
                                    @error('domain_provider_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Domain Renewal Price <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="domain_renewal_price" class="form-inp" placeholder="₹ Amount" value="{{ old('domain_renewal_price', $project->domain_renewal_price) }}">
                                    @error('domain_renewal_price')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Hosting Provider Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="hosting_provider_name" class="form-inp" placeholder="e.g. Hostinger" value="{{ old('hosting_provider_name', $project->hosting_provider_name) }}">
                                    @error('hosting_provider_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Hosting Renewal Price <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="number" name="hosting_renewal_price" class="form-inp" placeholder="₹ Amount" value="{{ old('hosting_renewal_price', $project->hosting_renewal_price) }}">
                                    @error('hosting_renewal_price')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Primary Domain Name <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                    <input type="text" name="primary_domain_name" class="form-inp" placeholder="Primary domain" value="{{ old('primary_domain_name', $project->primary_domain_name) }}">
                                    @error('primary_domain_name')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ══ RIGHT COL — 4 spans ══ --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Timeline & Status --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-calendar3" style="color:#f59e0b;margin-right:6px;"></i>Timeline & Status</div>
                        </div>
                        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                            <div class="form-row">
                                <label class="form-lbl">Project Status <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                <select name="project_status_id" class="form-inp">
                                    @foreach($statuses['project_statuses'] as $ps)
                                        <option value="{{ $ps->id }}" {{ old('project_status_id', $project->project_status_id) == $ps->id ? 'selected' : '' }}>{{ $ps->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Order Date Create <span style="color:#ef4444"> <span style="color:#ef4444">*</span></span></label>
                                <input type="date" name="order_date_create" class="form-inp" value="{{ old('order_date_create', $project->order_date_create ? $project->order_date_create->format('Y-m-d') : ($project->order ? $project->order->created_at->format('Y-m-d') : date('Y-m-d'))) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Project Start Date</label>
                                <input type="date" name="project_start_date" class="form-inp" value="{{ old('project_start_date', $project->project_start_date ? $project->project_start_date->format('Y-m-d') : '') }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Complete Date (Expected)</label>
                                <input type="date" name="expected_delivery_date" class="form-inp" value="{{ old('expected_delivery_date', $project->expected_delivery_date ? $project->expected_delivery_date->format('Y-m-d') : '') }}">
                            </div>
                        </div>
                    </div>

                    {{-- Project Specifics --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-tools" style="color:#10b981;margin-right:6px;"></i>Project Specifics</div>
                        </div>
                        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                            <div class="form-row">
                                <label class="form-lbl">Reference Websites</label>
                                <input type="text" name="reference_websites" class="form-inp" placeholder="Comma separated URLs" value="{{ old('reference_websites', $project->reference_websites) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">No. of Mail IDs</label>
                                <input type="number" name="no_of_mail_ids" class="form-inp" min="0" value="{{ old('no_of_mail_ids', $project->no_of_mail_ids) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Mail Password</label>
                                <input type="text" name="mail_password" class="form-inp" value="{{ old('mail_password', $project->mail_password) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Number of Pages</label>
                                <input type="number" name="no_of_pages" class="form-inp" min="1" value="{{ old('no_of_pages', $project->no_of_pages) }}">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Extra Features</label>
                                <textarea name="extra_features" class="form-inp" rows="3" placeholder="Enter additional feature details...">{{ old('extra_features', $project->extra_features) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- Assignments --}}
                    <div class="dash-card" style="overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-people-fill" style="color:#6366f1;margin-right:6px;"></i>Assignments</div>
                        </div>
                        <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                            <div class="form-row">
                                <label class="form-lbl">Assign to Sales</label>
                                @php $assignedSaleIds = $project->salesPersons->pluck('id')->toArray(); @endphp
                                <div class="ms-wrap" id="saleAssignWrap">
                                    <div class="ms-trigger" onclick="toggleMs('saleAssignWrap')">
                                        <div class="ms-pills" id="saleAssignPills">
                                            <span class="ms-placeholder">Select Sales Personnel…</span>
                                        </div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="saleAssignDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search..." oninput="filterMs(this,'saleAssignDropdown')">
                                            <span class="ms-all-btn" onclick="toggleAllMs('saleAssignWrap','saleAssignDropdown')">Select All</span>
                                        </div>
                                        <div class="ms-opts">
                                            @foreach($salesPersons as $sale)
                                                @php 
                                                    $initials = strtoupper(substr($sale->name, 0, 2)); 
                                                    $colors = ['#6366f1','#ec4899','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                                                    $bg = $colors[$sale->id % count($colors)];
                                                @endphp
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="sales_person_ids[]" value="{{ $sale->id }}" 
                                                        data-name="{{ $sale->name }}" data-initials="{{ $initials }}"
                                                        onchange="updateMs('saleAssignWrap')"
                                                        {{ in_array($sale->id, old('sales_person_ids', $assignedSaleIds)) ? 'checked' : '' }}>
                                                    <span class="ms-ava" style="background:{{ $bg }}">{{ $initials }}</span>
                                                    <div>
                                                        <div style="font-size:12.5px;font-weight:600;color:var(--t1);">{{ $sale->name }}</div>
                                                        <div style="font-size:10.5px;color:var(--t3);">{{ $sale->email }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Assign to Developers</label>
                                @php $assignedDevIds = $project->developers->pluck('id')->toArray(); @endphp
                                <div class="ms-wrap" id="devAssignWrap">
                                    <div class="ms-trigger" onclick="toggleMs('devAssignWrap')">
                                        <div class="ms-pills" id="devAssignPills">
                                            <span class="ms-placeholder">Select Developers…</span>
                                        </div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="devAssignDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search..." oninput="filterMs(this,'devAssignDropdown')">
                                            <span class="ms-all-btn" onclick="toggleAllMs('devAssignWrap','devAssignDropdown')">Select All</span>
                                        </div>
                                        <div class="ms-opts">
                                            @foreach($developers as $dev)
                                                @php 
                                                    $initials = strtoupper(substr($dev->name, 0, 2)); 
                                                    $colors = ['#10b981','#6366f1','#f59e0b','#ec4899','#ef4444','#06b6d4'];
                                                    $bg = $colors[$dev->id % count($colors)];
                                                @endphp
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="assign_to[]" value="{{ $dev->id }}" 
                                                        data-name="{{ $dev->name }}" data-initials="{{ $initials }}"
                                                        onchange="updateMs('devAssignWrap')"
                                                        {{ in_array($dev->id, old('assign_to', $assignedDevIds)) ? 'checked' : '' }}>
                                                    <span class="ms-ava" style="background:{{ $bg }}">{{ $initials }}</span>
                                                    <div>
                                                        <div style="font-size:12.5px;font-weight:600;color:var(--t1);">{{ $dev->name }}</div>
                                                        <div style="font-size:10.5px;color:var(--t3);">{{ $dev->email }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div style="display:flex;flex-direction:column;gap:8px;margin-top:10px;">
                        <input type="hidden" name="project_name" id="projectNameFull">
                        
                        {{-- Hidden placeholders if needed --}}
                        <input type="hidden" name="project_price" value="{{ old('project_price', $project->project_price) }}">
                        @if($project->services->isEmpty()) <input type="hidden" name="service_ids[]" value="1"> @endif
                        @if($project->sources->isEmpty()) <input type="hidden" name="source_ids[]" value="1"> @endif

                        <button type="submit" class="btn-primary-solid" style="padding:12px;justify-content:center;" onclick="document.getElementById('projectNameFull').value = document.querySelector('input[name=\'domain_name\']').value">
                            <i class="bi bi-floppy-fill"></i> Save Changes
                        </button>
                        <a href="{{ route($routePrefix . '.projects.index') }}" class="btn-ghost" style="padding:12px;justify-content:center;">Cancel</a>
                    </div>

                </div>

            </div>
        </form>

<div class="dash-grid mt-4">

                <div class="span-8" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- Quick Update Form --}}
                    <div class="dash-card" style="border: 2px solid var(--accent);box-shadow: 0 10px 30px rgba(99,102,241,0.1);">
                        <div class="card-head" style="background:rgba(99,102,241,0.05);border-bottom:1px solid var(--b1);">
                            <div class="card-title"><i class="bi bi-lightning-charge-fill" style="color:var(--accent);margin-right:6px;"></i>Notes/Client Feedbacks</div>
                        </div>
                        <div class="card-body" style="padding:15px;">
                            <form action="{{ route($routePrefix . '.projects.quickUpdate', $project->id) }}" method="POST">
                                @csrf
                                <div class="form-row" style="margin-bottom:12px;">
                                    <label class="form-lbl" style="font-size:9px;">Latest Feedback / Notes</label>
                                    <textarea name="internal_notes" class="form-inp" rows="2" style="font-size:12px;padding:8px;" placeholder="Add a quick note..."></textarea>
                                </div>
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:8px;font-size:13px;">
                                    Update Now
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                                    {{-- Communication History & Tracking --}}
                    <div class="dash-card span-8">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-chat-quote-fill"
                                    style="color:#ec4899;margin-right:8px;"></i>Communication History</div>
                            <div class="card-sub">{{ $project->feedbacks->count() }} updates recorded</div>
                        </div>
                        <div class="card-body">
                            <div class="feedback-timeline">
                                @forelse($project->feedbacks()->latest()->get() as $fb)
                                    <div class="timeline-item">
                                        <div class="timeline-meta">
                                            <div class="tm-date">
                                                {{ $fb->last_update_date ? $fb->last_update_date->format('d M, Y') : $fb->created_at->format('d M, Y') }}
                                            </div>
                                            <div class="tm-time">{{ $fb->created_at->format('h:i A') }}</div>
                                        </div>
                                        <div class="timeline-content">
                                            <div class="timeline-summary">
                                                @if($fb->status)
                                                    <div style="margin-bottom:8px;">
                                                        @php $sClass = strtolower(str_replace(' ', '-', $fb->status)); @endphp
                                                        <span class="proj-status {{ $sClass }}"
                                                            style="transform:scale(0.85);transform-origin:left;">{{ $fb->status }}</span>
                                                    </div>
                                                @endif
                                                @if($fb->feedback_summary)
                                                    <div class="ts-head">
                                                        <i class="bi bi-chat-dots"></i>
                                                        {{ $fb->feedback_summary }}
                                                    </div>
                                                @endif
                                                @if($fb->internal_notes)
                                                    <div class="ts-notes">{{ $fb->internal_notes }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="timeline-empty">
                                        <i class="bi bi-chat-square-dots"></i>
                                        No communication updates recorded for this project.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
</div>

    </div>
</main>

<script>
    function toggleCustomCms() {
        const val = document.getElementById('cmsSelect').value;
        const input = document.getElementById('cmsCustomInput');
        if (input) {
            input.style.display = (val === 'Others') ? 'block' : 'none';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        toggleCustomCms();
        // Initialize multi-selects
        if(typeof updateMs === 'function') {
            updateMs('saleAssignWrap');
            updateMs('devAssignWrap');
            updateMs('planWrap');
            updateMs('serviceWrap');
            updateMs('sourceWrap');
        }
    });
</script>

@include('admin.project._multiselect_assets')
@include('admin.project._order_select_assets')
@include('admin.project._phone_email_assets')
@include('admin.project._validation_assets')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const emailListId = 'edit-email-list';
        const phoneListId = 'edit-phone-list';

        // Seed Existing Emails
        @if($project->emails)
            @php $emails = is_array($project->emails) ? $project->emails : [$project->emails]; @endphp
            @foreach($emails as $email)
                if(typeof addEmailRow === 'function') addEmailRow(emailListId, '{{ $email }}');
            @endforeach
        @endif
        
        // Seed Existing Phones
        @if($project->phones)
            @php $phones = is_array($project->phones) ? $project->phones : [$project->phones]; @endphp
            @foreach($phones as $phone)
                @if(is_array($phone))
                    if(typeof addPhoneRow === 'function') addPhoneRow(phoneListId, '{{ $phone['num'] ?? '' }}', '{{ $phone['code'] ?? '' }}');
                @else
                    if(typeof addPhoneRow === 'function') addPhoneRow(phoneListId, '{{ $phone }}');
                @endif
            @endforeach
        @endif

        // Ensure at least one row exists if none were seeded
        if(typeof addEmailRow === 'function') {
            const emailList = document.getElementById(emailListId);
            if(emailList && emailList.children.length === 0) addEmailRow(emailListId);
        }
        if(typeof addPhoneRow === 'function') {
            const phoneList = document.getElementById(phoneListId);
            if(phoneList && phoneList.children.length === 0) addPhoneRow(phoneListId);
        }
        
        // Final button update for phone/email rows
        if(typeof updateButtons === 'function') {
            updateButtons(emailListId);
            updateButtons(phoneListId);
        }
    });
</script>

@endsection
