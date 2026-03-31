@extends('admin.layout.app')

@section('title', 'Schedule Meeting')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-sale-create">
        
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route('sale.meetings.index') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;">
                        <i class="bi bi-arrow-left"></i> Back to Schedule
                    </a>
                </div>
                <h1 class="page-title">New Meeting</h1>
                <p class="page-desc">Schedule a briefing for your leads or projects.</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;"><div class="card-title">Meeting Details</div></div>
                    <div class="card-body" style="padding:24px;">
                        <form action="{{ route('sale.meetings.store') }}" method="POST">
                            @csrf
                            <div class="form-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Title</label>
                                    <input type="text" name="meeting_title" class="form-inp" placeholder="e.g. Lead Follow-up Discussion" required>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Related To</label>
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
                                    <label class="form-lbl">Date</label>
                                    <input type="date" name="meeting_date" class="form-inp" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Time</label>
                                    <input type="time" name="meeting_time" class="form-inp" required>
                                </div>
                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Link</label>
                                    <input type="url" name="meeting_link" class="form-inp" placeholder="https://zoom.us/...">
                                </div>
                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Brief Description</label>
                                    <textarea name="meeting_description" class="form-inp" rows="4"></textarea>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Status</label>
                                    <select name="status" class="form-inp" required>
                                        <option value="pending">Upcoming</option>
                                        <option value="rescheduled">Rescheduled</option>
                                        <option value="completed">Completed</option>
                                        <option value="canceled">Canceled</option>
                                    </select>
                                </div>
                            </div>

                            <div style="margin-top:30px; display:flex; gap:10px;">
                                <button type="submit" class="btn-primary-solid">Schedule Meeting</button>
                                <a href="{{ route('sale.meetings.index') }}" class="btn-primary-ghost">Cancel</a>
                            </div>
                    </div>
                </div>
            </div>

            <div class="span-4">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;"><div class="card-title">Invite Team</div></div>
                    <div class="card-body" style="padding:20px;">
                        <p style="font-size:12px; color:var(--t4); margin-bottom:15px;">You will be automatically assigned as the primary salesperson.</p>
                        <div class="form-row">
                            <label class="form-lbl">Additional Sales</label>
                            <div class="multi-select-wrap">
                                @foreach($sales as $sale)
                                    @if($sale->id !== auth()->guard('sale')->id())
                                        <label class="check-item" data-id="{{ $sale->id }}">
                                            <input type="checkbox" name="assignsale_ids[]" value="{{ $sale->id }}">
                                            <div style="display:flex; flex-direction:column; line-height:1.2;">
                                                <span>{{ $sale->name }}</span>
                                                <small style="font-size:10px; color:var(--t4); font-weight:500;">{{ $sale->email }}</small>
                                            </div>
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div id="devSection" style="display:none;">
                            <div class="form-row" style="margin-top:20px;">
                                <label class="form-lbl">Developers</label>
                                <div class="multi-select-wrap">
                                    @foreach($developers as $dev)
                                        <label class="check-item" data-id="{{ $dev->id }}">
                                            <input type="checkbox" name="assigndev_ids[]" value="{{ $dev->id }}">
                                            <div style="display:flex; flex-direction:column; line-height:1.2;">
                                                <span>{{ $dev->name }}</span>
                                                <small style="font-size:10px; color:var(--t4); font-weight:500;">{{ $dev->email }}</small>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </div>
    </div>
</main>

<style>
    .multi-select-wrap { display: flex; flex-direction: column; gap: 8px; max-height: 250px; overflow-y: auto; padding-right: 5px; }
    .check-item { display: flex; align-items: center; gap: 10px; padding: 8px 12px; background: var(--bg3); border: 1px solid var(--b1); border-radius: 8px; cursor: pointer; transition: 0.2s; }
    .check-item:hover { border-color: var(--accent); }
    .check-item input { width: 14px; height: 14px; margin: 0; }
    .check-item span { font-size: 13px; font-weight: 700; color: var(--t2); }
</style>

@include('admin.meetings._target_select_assets')

@endsection
