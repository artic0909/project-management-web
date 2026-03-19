@extends('admin.layout.app')

@section('title', 'Add Lead')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('admin.leads.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Leads
                    </a>
                </div>
                <h1 class="page-title">Add New Lead</h1>
                <p class="page-desc">Fill in the details to create a new lead</p>
            </div>
        </div>

        <form action="" method="POST">
            @csrf

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
                                    <input type="text" name="company" class="form-inp" placeholder="Company name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact Person <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="contact_person" class="form-inp" placeholder="Full name" required>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Business Type <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="business_type" class="form-inp" placeholder="e.g. E-commerce, Healthcare, Education…">
                                </div>

                                {{-- Email — multiple --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Email</label>
                                    <div id="add-email-list"></div>
                                    <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addEmailRow('add-email-list')">
                                        <i class="bi bi-plus-lg"></i> Add Email
                                    </button>
                                </div>

                                {{-- Phone — multiple + country code --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Phone</label>
                                    <div id="add-phone-list"></div>
                                    <button type="button" class="btn-ghost" style="margin-top:6px;padding:4px 10px;font-size:12px;" onclick="addPhoneRow('add-phone-list')">
                                        <i class="bi bi-plus-lg"></i> Add Phone
                                    </button>
                                </div>

                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Address</label>
                                    <textarea name="address" class="form-inp" rows="3" placeholder="Full address…"></textarea>
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
                                        <option>Web</option>
                                        <option>Design</option>
                                        <option>Mark</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Source</label>
                                    <select name="lead_source" class="form-inp">
                                        <option value="">— Select Source —</option>
                                        <option>Web</option>
                                        <option>Linkedin</option>
                                        <option>Referral</option>
                                        <option>Walk-in</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Lead Priority</label>
                                    <select name="priority" class="form-inp">
                                        <option value="">— Select Priority —</option>
                                        <option>Cold</option>
                                        <option>Warm</option>
                                        <option>Hot 🔥</option>
                                        <option>Lost</option>
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Lead Status</label>
                                    <select name="status" class="form-inp">
                                        <option value="">— Select Status —</option>
                                        <option>Not Responding</option>
                                        <option>Not Interested</option>
                                        <option>Not Required</option>
                                        <option>Location Issue</option>
                                        <option>Job</option>
                                        <option>Not Inquired</option>
                                        <option>Respond</option>
                                        <option>Interested</option>
                                        <option>Language Barrier</option>
                                        <option>Booked</option>
                                        <option>Budget Issue</option>
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
                            <div class="ms-wrap" id="addAssignWrap">
                                <div class="ms-trigger" onclick="toggleMs('addAssignWrap')">
                                    <div class="ms-pills" id="addAssignPills">
                                        <span class="ms-placeholder">Select team members…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="addAssignDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'addAssignDropdown')">
                                    </div>
                                    <div class="ms-opts">
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Rahul Kumar" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#6366f1,#06b6d4)">RK</span>Rahul Kumar</label>
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Priya Sharma" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#ec4899,#f59e0b)">PS</span>Priya Sharma</label>
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Neha Kapoor" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#10b981,#06b6d4)">NK</span>Neha Kapoor</label>
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Arjun Singh" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#8b5cf6,#ec4899)">AS</span>Arjun Singh</label>
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Ravi Mehta" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">RM</span>Ravi Mehta</label>
                                        <label class="ms-opt"><input type="checkbox" name="assign_to[]" value="Kiran Rao" onchange="updateMs('addAssignWrap')"><span class="ms-ava" style="background:linear-gradient(135deg,#14b8a6,#6366f1)">KR</span>Kiran Rao</label>
                                    </div>
                                </div>
                            </div>

                            <div style="margin-top:20px;display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:11px;">
                                    <i class="bi bi-plus-lg"></i> Add Lead
                                </button>
                                <a href="{{ route('admin.leads.index') }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;">
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


@include('admin.leads._multiselect_assets')
@include('admin.leads._phone_email_assets')

@endsection