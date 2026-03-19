@extends('sale.layout.app')

@section('title', 'Edit Lead')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('sale.leads.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Leads
                    </a>
                    <span style="color:var(--t4);font-size:11px;">›</span>
                    <span style="font-size:13px;font-weight:600;color:var(--t2);">{{ $lead->company ?? 'DataFirst Corp' }}</span>
                </div>
                <h1 class="page-title">Edit Lead</h1>
                <p class="page-desc">Update the details for this lead</p>
            </div>
        </div>

        <form action="" method="POST">
            @csrf
            @method('PUT')

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
                                    <label class="form-lbl">Company <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="company" class="form-inp"
                                        value="{{ old('company', $lead->company ?? 'DataFirst Corp') }}"
                                        placeholder="Company name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Person <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="contact_person" class="form-inp"
                                        value="{{ old('contact_person', $lead->contact_person ?? 'Abhishek') }}"
                                        placeholder="Full name" required>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Business Type <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="business_type" class="form-inp"
                                        value="{{ old('business_type', $lead->business_type ?? 'Technology') }}"
                                        placeholder="e.g. E-commerce, Healthcare, Education…">
                                </div>

                                {{-- Email — multiple (pre-filled) --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email</label>
                                    <div id="edit-email-list"></div>
                                    <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('edit-email-list')">
                                        <i class="bi bi-plus-lg"></i> Add Email
                                    </button>
                                </div>

                                {{-- Phone — multiple + country code (pre-filled) --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone</label>
                                    <div id="edit-phone-list"></div>
                                    <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('edit-phone-list')">
                                        <i class="bi bi-plus-lg"></i> Add Phone
                                    </button>
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Address</label>
                                    <textarea name="address" class="form-inp" rows="3"
                                        placeholder="Full address…">{{ old('address', $lead->address ?? '') }}</textarea>
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
                                    <label class="form-lbl">Service Need</label>
                                    <select name="service_need" class="form-inp">
                                        <option value="">— Select Service —</option>
                                        @foreach(['Web','Design','Mark'] as $svc)
                                        <option {{ old('service_need', $lead->service_need ?? '') === $svc ? 'selected' : '' }}>{{ $svc }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Source</label>
                                    <select name="lead_source" class="form-inp">
                                        <option value="">— Select Source —</option>
                                        @foreach(['Web','Linkedin','Referral','Walk-in'] as $src)
                                        <option {{ old('lead_source', $lead->lead_source ?? '') === $src ? 'selected' : '' }}>{{ $src }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Priority</label>
                                    <select name="priority" class="form-inp">
                                        <option value="">— Select Priority —</option>
                                        @foreach(['Cold','Warm','Hot 🔥','Lost'] as $p)
                                        <option {{ old('priority', $lead->priority ?? '') === $p ? 'selected' : '' }}>{{ $p }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Lead Status</label>
                                    <select name="status" class="form-inp">
                                        <option value="">— Select Status —</option>
                                        @foreach([
                                        'Not Responding','Not Interested','Not Required',
                                        'Location Issue','Job','Not Inquired','Respond',
                                        'Interested','Language Barrier','Booked','Budget Issue'
                                        ] as $st)
                                        <option {{ old('status', $lead->status ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                        @endforeach
                                    </select>
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
                            <div class="card-sub">Select one or more team members</div>
                        </div>
                        <div class="card-body">

                            <!-- ASSIGN TO — multi-select -->
                            <div class="ms-wrap" id="editAssignWrap">
                                <div class="ms-trigger" onclick="toggleMs('editAssignWrap')">
                                    <div class="ms-pills" id="editAssignPills">
                                        <span class="ms-placeholder">Select team members…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="editAssignDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'editAssignDropdown')">
                                    </div>
                                    <div class="ms-opts">
                                        @php
                                        $assigned = $lead->assign_to ?? [];
                                        $members = [
                                        ['initials'=>'RK','name'=>'Rahul Kumar','bg'=>'linear-gradient(135deg,#6366f1,#06b6d4)'],
                                        ['initials'=>'PS','name'=>'Priya Sharma','bg'=>'linear-gradient(135deg,#ec4899,#f59e0b)'],
                                        ['initials'=>'NK','name'=>'Neha Kapoor','bg'=>'linear-gradient(135deg,#10b981,#06b6d4)'],
                                        ['initials'=>'AS','name'=>'Arjun Singh','bg'=>'linear-gradient(135deg,#8b5cf6,#ec4899)'],
                                        ['initials'=>'RM','name'=>'Ravi Mehta','bg'=>'linear-gradient(135deg,#f59e0b,#ef4444)'],
                                        ['initials'=>'KR','name'=>'Kiran Rao','bg'=>'linear-gradient(135deg,#14b8a6,#6366f1)'],
                                        ];
                                        @endphp
                                        @foreach($members as $m)
                                        <label class="ms-opt">
                                            <input type="checkbox" name="assign_to[]"
                                                value="{{ $m['name'] }}"
                                                onchange="updateMs('editAssignWrap')"
                                                {{ in_array($m['name'], $assigned) ? 'checked' : '' }}>
                                            <span class="ms-ava" style="background:{{ $m['bg'] }}">{{ $m['initials'] }}</span>
                                            {{ $m['name'] }}
                                        </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:20px;display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:11px;">
                                    <i class="bi bi-floppy-fill"></i> Update Lead
                                </button>
                                <a href="{{ route('sale.leads.index') }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>


@include('sale.leads._multiselect_assets')
@include('sale.leads._phone_email_assets')


{{-- Pre-fill existing assigned members on page load --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Trigger updateMs so pills render for any pre-checked boxes
        updateMs('editAssignWrap');

        // Pre-seed one email + phone row for edit
        if (document.getElementById('edit-email-list').children.length === 0) addEmailRow('edit-email-list');
        if (document.getElementById('edit-phone-list').children.length === 0) addPhoneRow('edit-phone-list');
    });
</script>

@endsection