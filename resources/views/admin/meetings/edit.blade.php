@extends('admin.layout.app')

@section('title', 'Edit Meeting: ' . $meeting->meeting_title)

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-meetings-edit">
        
        <!-- ── PAGE HEADER ── -->
        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.meetings.index') }}" style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;">
                        <i class="bi bi-arrow-left"></i> Back to Schedule
                    </a>
                </div>
                <h1 class="page-title">Edit Meeting</h1>
                <p class="page-desc">Modify meeting details and status.</p>
            </div>
        </div>

        <div class="dash-grid">
            <div class="span-8">
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px;">
                        <div class="card-title">Meeting Details</div>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <form action="{{ route($routePrefix . '.meetings.update', $meeting->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            
                            <div class="form-grid" style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                                
                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Title</label>
                                    <input type="text" name="meeting_title" class="form-inp" value="{{ $meeting->meeting_title }}" required>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Related To (Target)</label>
                                    <select name="meeting_type" id="meetingType" class="form-inp" required onchange="toggleTargets()">
                                        <option value="lead" {{ $meeting->meeting_type == 'lead' ? 'selected' : '' }}>Lead</option>
                                        <option value="order" {{ $meeting->meeting_type == 'order' ? 'selected' : '' }}>Order</option>
                                        <option value="project" {{ $meeting->meeting_type == 'project' ? 'selected' : '' }}>Project</option>
                                    </select>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Select Target</label>
                                    <div class="target-select-wrap">
                                        <input type="hidden" name="lead_id" id="hidden_lead_id" value="{{ $meeting->lead_id }}">
                                        <input type="hidden" name="order_id" id="hidden_order_id" value="{{ $meeting->order_id }}">
                                        <input type="hidden" name="project_id" id="hidden_project_id" value="{{ $meeting->project_id }}">
                                        
                                        <div class="ts-trigger" onclick="toggleTs()">
                                            <div class="ts-selected-text">
                                                @if($meeting->meeting_type == 'lead' && $meeting->lead)
                                                    {{ $meeting->lead->company }} <span style="color:var(--t4);font-weight:500;margin-left:8px;font-size:11px;">({{ $meeting->lead->domain ?? $meeting->lead->email }})</span>
                                                @elseif($meeting->meeting_type == 'order' && $meeting->order)
                                                    Order #{{ $meeting->order->id }} <span style="color:var(--t4);font-weight:500;margin-left:8px;font-size:11px;">({{ $meeting->order->lead->company ?? 'No Company' }})</span>
                                                @elseif($meeting->meeting_type == 'project' && $meeting->project)
                                                    {{ $meeting->project->name }} <span style="color:var(--t4);font-weight:500;margin-left:8px;font-size:11px;">({{ $meeting->project->project_id }})</span>
                                                @else
                                                    <span class="ts-placeholder">— Select Target —</span>
                                                @endif
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
                                    <input type="date" name="meeting_date" class="form-inp" value="{{ $meeting->meeting_date->format('Y-m-d') }}" required>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Meeting Time</label>
                                    <input type="time" name="meeting_time" class="form-inp" value="{{ $meeting->meeting_time }}" required>
                                </div>

                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Link</label>
                                    <input type="url" name="meeting_link" class="form-inp" value="{{ $meeting->meeting_link }}">
                                </div>

                                <div class="form-row" style="grid-column: 1 / -1;">
                                    <label class="form-lbl">Meeting Description</label>
                                    <textarea name="meeting_description" class="form-inp" rows="4">{{ $meeting->meeting_description }}</textarea>
                                </div>

                                <div class="form-row">
                                    <label class="form-lbl">Status</label>
                                    <select name="status" class="form-inp" required>
                                        <option value="pending" {{ $meeting->status == 'pending' ? 'selected' : '' }}>Upcoming / Pending</option>
                                        <option value="rescheduled" {{ $meeting->status == 'rescheduled' ? 'selected' : '' }}>Rescheduled</option>
                                        <option value="completed" {{ $meeting->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                        <option value="canceled" {{ $meeting->status == 'canceled' ? 'selected' : '' }}>Canceled</option>
                                    </select>
                                </div>

                            </div>

                            <div style="margin-top:30px; display:flex; gap:10px;">
                                <button type="submit" class="btn-primary-solid">Update Schedule</button>
                                <a href="{{ route($routePrefix . '.meetings.index') }}" class="btn-primary-ghost">Cancel</a>
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
                            <label class="form-lbl">Sales Team</label>
                            <div class="multi-select-wrap">
                                @foreach($sales as $sale)
                                    <label class="check-item">
                                        <input type="checkbox" name="assignsale_ids[]" value="{{ $sale->id }}" {{ in_array($sale->id, $meeting->assignsale_ids ?? []) ? 'checked' : '' }}>
                                        <div style="display:flex; flex-direction:column; line-height:1.2;">
                                            <span>{{ $sale->name }}</span>
                                            <small style="font-size:10px; color:var(--t4); font-weight:500;">{{ $sale->email }}</small>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        @if($routePrefix == 'admin')
                        <div id="devSection" style="display:none;">
                            <div class="form-row" style="margin-top:20px;">
                                <label class="form-lbl">Developers</label>
                                <div class="multi-select-wrap">
                                    @foreach($developers as $dev)
                                        <label class="check-item">
                                            <input type="checkbox" name="assigndev_ids[]" value="{{ $dev->id }}" {{ in_array($dev->id, (array)$meeting->assigndev_ids ?? []) ? 'checked' : '' }}>
                                            <div style="display:flex; flex-direction:column; line-height:1.2;">
                                                <span>{{ $dev->name }}</span>
                                                <small style="font-size:10px; color:var(--t4); font-weight:500;">{{ $dev->email }}</small>
                                            </div>
                                        </label>
                                    @endforeach
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

@include('admin.meetings._target_select_assets')

@endsection
