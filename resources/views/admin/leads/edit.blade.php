@extends('admin.layout.app')

@section('title', 'Edit Lead: ' . $lead->company)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.leads.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Leads
                    </a>
                    <span style="color:var(--t4);font-size:11px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">{{ $lead->company }}</span>
                </div>
                <h1 class="page-title">Edit Lead Profile</h1>
                <p class="page-desc">Modify existing lead data and team assignments</p>
            </div>

            <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                    <div style="display:flex; gap:10px; margin-bottom:5px;">
                        <a href="{{ route($routePrefix . '.orders.create', ['lead_id' => $lead->id]) }}" class="btn-primary-solid">
                            <i class="bi bi-box-arrow-in-right"></i> Convert To Order
                        </a>
                    </div>
                </div>
        </div>

        <form action="{{ route($routePrefix . '.leads.update', $lead->id) }}" method="POST">
            @csrf
            @method('PUT')
            
            @if(session('success'))
                <div class="alert alert-success" style="padding:12px;background:#dcfce7;color:#166534;border-radius:8px;margin-bottom:16px;">
                    <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
                </div>
            @endif



            <div class="dash-grid">

                {{-- ══ LEFT COL — 8 ══ --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Company & Contact --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-building" style="color:var(--accent);margin-right:6px;"></i>Company & Contact</div>
                            <div class="card-sub">Basic lead identification</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company</label>
                                    <input type="text" name="company" class="form-inp @error('company') is-invalid @enderror" value="{{ old('company', $lead->company) }}" placeholder="Company name">
                                    @error('company')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Person <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="contact_person" class="form-inp @error('contact_person') is-invalid @enderror" value="{{ old('contact_person', $lead->contact_person) }}" placeholder="Full name">
                                    @error('contact_person')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Business Type <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="business_type" class="form-inp @error('business_type') is-invalid @enderror" value="{{ old('business_type', $lead->business_type) }}" placeholder="e.g. E-commerce, Healthcare, Education…">
                                    @error('business_type')<span class="field-error">{{ $message }}</span>@enderror
                                </div>

                                {{-- Email — multiple (pre-filled by JS) --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email Addresses</label>
                                    <div id="edit-email-list"></div>
                                    @error('email')<span class="field-error">{{ $message }}</span>@enderror
                                    @error('email.*')<span class="field-error">One or more emails are invalid.</span>@enderror
                                </div>

                                {{-- Phone — multiple + country code (pre-filled by JS) --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone Numbers</label>
                                    <div id="edit-phone-list"></div>
                                    @error('phone')<span class="field-error">{{ $message }}</span>@enderror
                                    @error('phone.*')<span class="field-error">One or more phone numbers are invalid.</span>@enderror
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address <span style="color:#ef4444">*</span></label>
                                    <textarea name="address" class="form-inp @error('address') is-invalid @enderror" rows="2" placeholder="Full address…">{{ old('address', $lead->address) }}</textarea>
                                    @error('address')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">State</label>
                                    <input type="text" name="state" class="form-inp @error('state') is-invalid @enderror" value="{{ old('state', $lead->state) }}" placeholder="State">
                                    @error('state')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code</label>
                                    <input type="number" name="zip_code" class="form-inp @error('zip_code') is-invalid @enderror" value="{{ old('zip_code', $lead->zip_code) }}" placeholder="6-digit ZIP" pattern="\d{6}" title="Please enter exactly 6 digits">
                                    @error('zip_code')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Lead Classification --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-sliders" style="color:#f59e0b;margin-right:6px;"></i>Lead Classification</div>
                            <div class="card-sub">Service, source, priority and status</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Service Need <span style="color:#ef4444">*</span></label>
                                    @php $selectedServiceIds = $lead->services->pluck('id')->toArray(); @endphp
                                    <div class="ms-wrap" id="serviceWrap">
                                        <div class="ms-trigger @error('service_ids') is-invalid @enderror" onclick="toggleMs('serviceWrap')" data-placeholder="Select services…">
                                            <div class="ms-pills" id="servicePills">
                                                <span class="ms-placeholder">Select services…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        @error('service_ids')<span class="field-error">{{ $message }}</span>@enderror
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
                                                            onchange="updateMs('serviceWrap')"
                                                            {{ in_array($service->id, old('service_ids', $selectedServiceIds)) ? 'checked' : '' }}>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $service->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Sources</label>
                                    @php $selectedSourceIds = $lead->sources->pluck('id')->toArray(); @endphp
                                    <div class="ms-wrap" id="sourceWrap">
                                        <div class="ms-trigger @error('source_ids') is-invalid @enderror" onclick="toggleMs('sourceWrap')" data-placeholder="Select sources…">
                                            <div class="ms-pills" id="sourcePills">
                                                <span class="ms-placeholder">Select sources…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        @error('source_ids')<span class="field-error">{{ $message }}</span>@enderror
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
                                                            {{ in_array($source->id, old('source_ids', $selectedSourceIds)) ? 'checked' : '' }}>
                                                        <div style="display:flex;flex-direction:column;">
                                                            <span style="font-weight:500;color:var(--t1);">{{ $source->name }}</span>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Priority</label>
                                    <select name="priority" class="form-inp @error('priority') is-invalid @enderror">
                                        <option value="">— Select Priority —</option>
                                        @foreach(['Cold', 'Warm', 'Hot 🔥'] as $p)
                                            <option value="{{ $p }}" {{ old('priority', $lead->priority) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                    @error('priority')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Status</label>
                                    <select name="status_id" class="form-inp @error('status_id') is-invalid @enderror">
                                        <option value="">— Select Status —</option>
                                        @foreach($statuses as $status)
                                            <option value="{{ $status->id }}" {{ old('status_id', $lead->status_id) == $status->id ? 'selected' : '' }}>{{ $status->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('status_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Campaign</label>
                                    <select name="campaign_id" class="form-inp @error('campaign_id') is-invalid @enderror">
                                        <option value="">— Select Campaign —</option>
                                        @foreach($campaigns as $campaign)
                                            <option value="{{ $campaign->id }}" {{ old('campaign_id', $lead->campaign_id) == $campaign->id ? 'selected' : '' }}>{{ $campaign->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('campaign_id')<span class="field-error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    

                </div>

                {{-- ══ RIGHT COL — 4 (sticky) ══ --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    <div class="dash-card" style="position:sticky;top:80px;overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-people-fill" style="color:#10b981;margin-right:6px;"></i>Assign To</div>
                            <div class="card-sub">Choose who can follow-up</div>
                        </div>
                        <div class="card-body">

                            <!-- ASSIGN TO — multi-select -->
                            @php
                                $assignedIds = $lead->assignments->pluck('assigned_to')->toArray();
                            @endphp
                            <div class="ms-wrap" id="editAssignWrap">
                                <div class="ms-trigger" onclick="toggleMs('editAssignWrap')" data-placeholder="Select team members…">
                                    <div class="ms-pills" id="editAssignPills">
                                        <span class="ms-placeholder">Select team members…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="editAssignDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'editAssignDropdown')">
                                        <span class="ms-all-btn" onclick="toggleAllMs('editAssignWrap','editAssignDropdown')">Select All</span>
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
                                        @endphp
                                        @foreach($sales as $index => $sale)
                                            @php
                                                $words = explode(' ', $sale->name);
                                                $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                            @endphp
                                            <label class="ms-opt">
                                                <input type="checkbox" name="assign_to[]" value="{{ $sale->id }}" 
                                                    data-name="{{ $sale->name }}" data-initials="{{ $initials }}"
                                                    onchange="updateMs('editAssignWrap')"
                                                    {{ in_array($sale->id, old('assign_to', $assignedIds)) ? 'checked' : '' }}>
                                                <span class="ms-ava" style="background:{{ $gradients[$index % count($gradients)] }}">{{ $initials }}</span>
                                                <div style="display:flex;flex-direction:column;">
                                                    <span style="font-weight:500;color:var(--t1);">{{ $sale->name }}</span>
                                                    <span style="font-size:11px;color:var(--t3);">{{ $sale->email }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:24px;display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:12px;">
                                    <i class="bi bi-save"></i> Update Profile
                                </button>
                                <a href="{{ route($routePrefix . '.leads.index') }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;">
                                    Back to Pipeline
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </form>
        <div class="dash-grid" style="margin-top: 16px;">
            <div class="span-8">
{{-- Lead Notes History Card --}}
                    @include('admin.leads._notes_history')
            </div>
        </div>
    </div>
</main>


@include('admin.leads._multiselect_assets')
@include('admin.leads._phone_email_assets')
@include('admin.leads._notes_assets')
@include('admin.leads._validation_assets')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hydrate MS pills
        if(typeof updateMs === 'function') {
            updateMs('editAssignWrap');
            updateMs('serviceWrap');
            updateMs('sourceWrap');
        }

        // Hydrate multi-row Emails
        const existingEmails = @json($lead->emails ?? []);
        const emailListId = 'edit-email-list';
        if (existingEmails.length > 0) {
            existingEmails.forEach(val => addEmailRow(emailListId, val));
        } else {
            addEmailRow(emailListId);
        }

        // Hydrate multi-row Phones
        const existingPhones = @json($lead->phones ?? []);
        const phoneListId = 'edit-phone-list';
        if (existingPhones.length > 0) {
            existingPhones.forEach(obj => {
                addPhoneRow(phoneListId, obj.number || '', obj.code_idx || null);
            });
        } else {
            addPhoneRow(phoneListId);
        }
    });
</script>

@endsection