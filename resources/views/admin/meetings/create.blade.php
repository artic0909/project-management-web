@extends('admin.layout.app')

@php
    $guard = auth()->guard('admin')->check() ? 'admin' : (auth()->guard('sale')->check() ? 'sale' : 'developer');
@endphp

@section('title', 'Schedule New Meeting')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-create">
        
        <!-- ── PAGE HEADER ── -->
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.meetings.index') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;">
                        <i class="bi bi-arrow-left"></i> Back to Schedule
                    </a>
                </div>
                <h1 class="page-title">Schedule Meeting</h1>
                <p class="page-desc">Define meeting targets, participants, and schedule.</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Meeting Details</div>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <form action="{{ route($routePrefix . '.meetings.store') }}" method="POST">
                            @csrf
                            
                            <div class="form-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                                
                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Title</label>
                                    <input type="text" name="meeting_title" class="form-inp" placeholder="e.g. Project Kickoff or Lead Discovery" required>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Related To (Target)</label>
                                    <select name="meeting_type" id="meetingType" class="form-inp" required onchange="toggleTargets()">
                                        <option value="lead">Lead</option>
                                        <option value="order">Order</option>
                                        <option value="project">Project</option>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Select Target</label>
                                    <div class="target-select-wrap">
                                        <input type="hidden" name="lead_id" id="hidden_lead_id">
                                        <input type="hidden" name="order_id" id="hidden_order_id">
                                        <input type="hidden" name="project_id" id="hidden_project_id">
                                        
                                        <div class="ts-trigger" onclick="toggleTs()">
                                            <div class="ts-selected-text">
                                                <span class="ts-placeholder">— Select Target —</span>
                                            </div>
                                            <i class="bi bi-chevron-down ts-arrow"></i>
                                        </div>
                                        <div class="ts-dropdown">
                                            <div class="ts-search-box">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ts-search-inp" placeholder="Search target by name, company, or ID..." onkeyup="filterTs(this.value)">
                                            </div>
                                            <div class="ts-options">
                                                {{-- Options dynamically rendered by JS --}}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Meeting Date</label>
                                    <input type="date" name="meeting_date" class="form-inp" required>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Meeting Time</label>
                                    <input type="time" name="meeting_time" class="form-inp" required>
                                </div>

                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Link (Zoom / Google Meet / Teams)</label>
                                    <input type="url" name="meeting_link" class="form-inp" placeholder="https://meet.google.com/xxx-xxxx-xxx">
                                </div>

                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Description</label>
                                    <textarea name="meeting_description" class="form-inp" rows="4" placeholder="Points to discuss..."></textarea>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Current Status</label>
                                    <select name="status" class="form-inp" required>
                                        <option value="pending">Upcoming / Pending</option>
                                        <option value="rescheduled">Rescheduled</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                </div>

                            </div>

                            <div style="margin-top:30px; display:flex; gap:10px;">
                                <button type="submit" class="btn-primary-solid">Create Schedule</button>
                                <!-- <a href="{{ route($routePrefix . '.meetings.index') }}" class="btn-primary-ghost">Cancel</a> -->
                            </div>
                    </div>
                </div>
            </div>

            <div class="span-4">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Assign Participants</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <div class="form-row">
                            <label class="form-lbl">Sales Team (Internal)</label>
                            <div class="ms-wrap" id="assignSaleWrap">
                                <div class="ms-trigger" onclick="toggleMs('assignSaleWrap')">
                                    <div class="ms-pills" id="assignSalePills">
                                        <span class="ms-placeholder">Select sales team…</span>
                                    </div>
                                    <i class="bi bi-chevron-down ms-arrow"></i>
                                </div>
                                <div class="ms-dropdown" id="assignSaleDropdown">
                                    <div class="ms-search-wrap">
                                        <i class="bi bi-search"></i>
                                        <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'assignSaleDropdown')">
                                        <span class="ms-all-btn" onclick="toggleAllMs('assignSaleWrap','assignSaleDropdown')">Select All</span>
                                    </div>
                                    <div class="ms-opts">
                                        @foreach($sales as $sale)
                                            <label class="ms-opt">
                                                <input type="checkbox" name="assignsale_ids[]" value="{{ $sale->id }}" 
                                                    data-name="{{ $sale->name }}"
                                                    onchange="updateMs('assignSaleWrap')">
                                                <div style="display:flex;flex-direction:column;">
                                                    <span style="font-weight:700;color:var(--t1);font-size:13px;">{{ $sale->name }}</span>
                                                    <span style="font-size:11px;color:var(--t3);">{{ $sale->email }}</span>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        @if($routePrefix == 'admin')
                        <div id="devSection" style="display:none;">
                            <div class="form-row" style="margin-top:20px;">
                                <label class="form-lbl">Developers (Internal)</label>
                                <div class="ms-wrap" id="assignDevWrap">
                                    <div class="ms-trigger" onclick="toggleMs('assignDevWrap')">
                                        <div class="ms-pills" id="assignDevPills">
                                            <span class="ms-placeholder">Select developers…</span>
                                        </div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="assignDevDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'assignDevDropdown')">
                                            <span class="ms-all-btn" onclick="toggleAllMs('assignDevWrap','assignDevDropdown')">Select All</span>
                                        </div>
                                        <div class="ms-opts">
                                            @foreach($developers as $dev)
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="assigndev_ids[]" value="{{ $dev->id }}" 
                                                        data-name="{{ $dev->name }}"
                                                        onchange="updateMs('assignDevWrap')">
                                                    <div style="display:flex;flex-direction:column;">
                                                        <span style="font-weight:700;color:var(--t1);font-size:13px;">{{ $dev->name }}</span>
                                                        <span style="font-size:11px;color:var(--t3);">{{ $dev->email }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                </form>
            </div>
        </div>

    </div>
</main>

<style>
    .multi-select-wrap { display: flex; flex-direction: column; gap: 8px; max-height: 300px; overflow-y: auto; padding-right: 5px; }
    .check-item { display: flex; align-items: center; gap: 10px; padding: 10px 14px; background: var(--bg3); border: 1px solid var(--b1); border-radius: 10px; cursor: pointer; transition: 0.2s; }
    .check-item:hover { border-color: var(--accent); }
    .check-item input { width: 16px; height: 16px; margin: 0; cursor: pointer; }
    .check-item span { font-size: 13px; font-weight: 700; color: var(--t2); }
    .check-item input:checked + span { color: var(--accent); }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        updateMs('assignSaleWrap');
        updateMs('assignDevWrap');
    });
</script>

@include('admin.leads._multiselect_assets')
@include('admin.meetings._target_select_assets')
@endsection
