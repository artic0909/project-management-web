@extends('admin.layout.app')

@section('title', 'Edit Inquiry')

@section('content')
<main class="page-area" id="pageArea">
    <div class="page">
        <div class="page-header">
            <div>
                <h1 class="page-title">Edit Inquiry</h1>
                <p class="page-desc">Modify potential project request details from <strong>{{ $inquiry->company_name }}</strong></p>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.inquiry.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        @if($errors->any())
            <div class="alert alert-danger" style="padding:12px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:16px;">
                @foreach($errors->all() as $error)
                    <p style="margin:0;"><i class="bi bi-exclamation-triangle-fill"></i> {{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.inquiry.update', $inquiry->id) }}" method="POST" id="editInquiryForm">
            @csrf
            @method('PUT')

            <div class="dash-grid">
                {{-- Left Side: Basic Info --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Project Discovery</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="company_name" value="{{ old('company_name', $inquiry->company_name) }}" class="form-inp" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Person</label>
                                    <input type="text" name="client_name" value="{{ old('client_name', $inquiry->client_name) }}" class="form-inp" required>
                                </div>
                            </div>

                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name / URL</label>
                                    <input type="text" name="domain_name" value="{{ old('domain_name', $inquiry->domain_name) }}" class="form-inp" placeholder="e.g. example.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Project Budget (₹)</label>
                                    <input type="number" name="order_value" value="{{ old('order_value', $inquiry->order_value) }}" class="form-inp">
                                </div>
                            </div>

                            <div class="form-row">
                                <label class="form-lbl">Project Requirements / Notes</label>
                                <textarea name="notes" class="form-inp" rows="5">{{ old('notes', $inquiry->notes) }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Classification & Interest</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Services Interested In</label>
                                    @php $selectedServices = (array)old('service_ids', $inquiry->service_ids); @endphp
                                    <div class="ms-wrap" id="serviceWrap">
                                        <div class="ms-trigger" onclick="toggleMs('serviceWrap')" data-placeholder="Select services…">
                                            <div class="ms-pills" id="servicePills">
                                                <span class="ms-placeholder">Select services…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="serviceDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search services…" oninput="filterMs(this,'serviceDropdown')">
                                                <span class="ms-all-btn" onclick="toggleAllMs('serviceWrap','serviceDropdown')">Select All</span>
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($services as $s)
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="service_ids[]" value="{{ $s->id }}" 
                                                            data-name="{{ $s->name }}"
                                                            onchange="updateMs('serviceWrap')"
                                                            {{ in_array($s->id, $selectedServices) ? 'checked' : '' }}>
                                                        <span>{{ $s->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Lead Sources</label>
                                    @php $selectedSources = (array)old('source_ids', $inquiry->source_ids); @endphp
                                    <div class="ms-wrap" id="sourceWrap">
                                        <div class="ms-trigger" onclick="toggleMs('sourceWrap')" data-placeholder="Select sources…">
                                            <div class="ms-pills" id="sourcePills">
                                                <span class="ms-placeholder">Select sources…</span>
                                            </div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="sourceDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search sources…" oninput="filterMs(this,'sourceDropdown')">
                                                <span class="ms-all-btn" onclick="toggleAllMs('sourceWrap','sourceDropdown')">Select All</span>
                                            </div>
                                            <div class="ms-opts">
                                                @foreach($sources as $src)
                                                    <label class="ms-opt">
                                                        <input type="checkbox" name="source_ids[]" value="{{ $src->id }}" 
                                                            data-name="{{ $src->name }}"
                                                            onchange="updateMs('sourceWrap')"
                                                            {{ in_array($src->id, $selectedSources) ? 'checked' : '' }}>
                                                        <span>{{ $src->name }}</span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Location Details</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">City</label>
                                    <input type="text" name="city" value="{{ old('city', $inquiry->city) }}" class="form-inp">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">State / Region</label>
                                    <input type="text" name="state" value="{{ old('state', $inquiry->state) }}" class="form-inp">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">ZIP Code</label>
                                    <input type="text" name="zip_code" value="{{ old('zip_code', $inquiry->zip_code) }}" class="form-inp">
                                </div>
                            </div>
                            <div class="form-row" style="margin-top:16px;">
                                <label class="form-lbl">Full Address</label>
                                <textarea name="full_address" class="form-inp" rows="2">{{ old('full_address', $inquiry->full_address) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Contact & Status --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Contact Directory</div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Email Directory</label>
                                <div id="edit-email-list"></div>
                            </div>

                            <div class="form-row" style="margin-top:20px;">
                                <label class="form-lbl">Phone Directory</label>
                                <div id="edit-phone-list"></div>
                            </div>
                        </div>
                    </div>

                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Workflow Status</div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Current Inquiry Status</label>
                                <select name="status" class="form-inp" style="border-left: 4px solid var(--accent);">
                                    <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="reviewed" {{ $inquiry->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <!-- <option value="converted" {{ $inquiry->status == 'converted' ? 'selected' : '' }}>Converted</option> -->
                                    <option value="rejected" {{ $inquiry->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>

                            <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                                <button type="submit" class="btn-primary-solid">
                                    <i class="bi bi-save"></i> Synchronize Updates
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

@include('admin.leads._multiselect_assets')
@include('admin.leads._phone_email_assets')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hydrate Multi-select pills
        if(typeof updateMs === 'function') {
            updateMs('serviceWrap');
            updateMs('sourceWrap');
        }

        // Hydrate Emails
        const oldEmails = @json(old('email'));
        const existingEmails = (oldEmails && oldEmails.length > 0) ? [] : @json((array)$inquiry->emails);
        const emailListId = 'edit-email-list';

        if (existingEmails.length > 0) {
            existingEmails.forEach(val => addEmailRow(emailListId, val));
        } else if (!oldEmails || oldEmails.length === 0) {
            addEmailRow(emailListId);
        }

        // Hydrate Phones
        const oldPhones = @json(old('phone'));
        const existingPhones = (oldPhones && oldPhones.length > 0) ? [] : @json((array)$inquiry->phones);
        const phoneListId = 'edit-phone-list';

        if (existingPhones.length > 0) {
            existingPhones.forEach(obj => {
                addPhoneRow(phoneListId, obj.number || '', obj.code_idx || null);
            });
        } else if (!oldPhones || oldPhones.length === 0) {
            addPhoneRow(phoneListId);
        }
    });
</script>

<style>
    .w-100 { width: 100%; }
    .form-grid { grid-template-columns: 1fr 1fr; }
    @media (max-width: 1200px) {
        .form-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection
