@extends('admin.layout.app')

@section('title', 'Project Details - ' . $project->project_name)

@section('content')

    <main class="page-area" id="pageArea">
        <div class="page" id="page-project-show">

            <!-- Page Header -->
            <div class="page-header">
                <div>
                    <div style="display:flex;align-items:center;gap:12px;margin-bottom:8px;">
                        @php
                            $statusClass = strtolower(str_replace(' ', '-', $project->project_status ?? 'new'));
                        @endphp
                        <span class="proj-status {{ $statusClass }}">{{ $project->project_status ?? 'New' }}</span>
                        @if($project->paymentStatus)
                            <span class="status-pill {{ strtolower($project->paymentStatus->name) == 'paid' ? 'paid' : 'pending' }}" style="font-size:10px;padding:2px 8px;">{{ $project->paymentStatus->name }}</span>
                        @endif
                           <span style="font-size:12px;color:var(--t4);font-weight:500;">#PRJ-{{ str_pad($project->id, 5, '0', STR_PAD_LEFT) }}</span>
                        @if($project->order_id)
                            <span
                                style="font-size:11px;background:var(--accent-bg);color:var(--accent);padding:2px 8px;border-radius:4px;font-weight:700;display:flex;align-items:center;gap:4px;">
                                <i class="bi bi-link-45deg"></i> Linked to Order #{{ $project->order_id }}
                            </span>
                        @endif
                    </div>
                    <h1 class="page-title">{{ $project->project_name }}</h1>
                    <p class="page-desc">{{ $project->company_name ?? 'Client: ' . $project->client_name }}</p>
                </div>
                <div class="header-actions">
                    <a href="{{ route('admin.projects.edit', $project->id) }}" class="btn-primary-solid sm">
                        <i class="bi bi-pencil-square"></i> Edit Project
                    </a>
                    <form action="{{ route('admin.projects.destroy', $project->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure?')" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn-ghost sm" style="color:#ef4444;">
                            <i class="bi bi-trash3"></i> Delete
                        </button>
                    </form>
                    <a href="{{ route('admin.projects.index') }}" class="btn-ghost sm">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="dash-grid">

                {{-- Left Column: Project Info & Technical --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- Client & Basic Info --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-person-badge-fill"
                                    style="color:var(--accent);margin-right:8px;"></i>Client & Contact Details</div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
                                <div>
                                    <div class="kv-item">
                                        <label>Client Name</label>
                                        <div class="val-lg">{{ $project->client_name }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Email Address(es)</label>
                                        <div class="val-list">
                                            @php $emails = is_array($project->emails) ? $project->emails : ($project->email ? [$project->email] : []); @endphp
                                            @forelse($emails as $email)
                                                <div class="val-pill"><i class="bi bi-envelope"></i> {{ $email }}</div>
                                            @empty
                                                <span class="val-na">No emails provided</span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Phone Number(s)</label>
                                        <div class="val-list">
                                            @php $phones = is_array($project->phones) ? $project->phones : ($project->phone ? [$project->phone] : []); @endphp
                                            @forelse($phones as $p)
                                                <div class="val-pill">
                                                    <i class="bi bi-telephone"></i>
                                                    {{ is_array($p) ? ($p['num'] ?? '') : $p }}
                                                </div>
                                            @empty
                                                <span class="val-na">No phones provided</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="kv-item">
                                        <label>Company Name</label>
                                        <div class="val-text">{{ $project->company_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Full Address</label>
                                        <div class="val-text" style="line-height:1.6;font-size:13px;">
                                            {{ $project->full_address ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Plan Details</label>
                                        <div class="val-text"><span
                                                class="badge-outline">{{ $project->plan_name ?? 'Standard' }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical & Server Details --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-server"
                                    style="color:#06b6d4;margin-right:8px;"></i>Website & Technical Specs</div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
                                <div>
                                    <div class="kv-item">
                                        <label>Domain Name</label>
                                        <div class="val-link"><i class="bi bi-globe"></i>
                                            {{ $project->domain_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Hosting Provider</label>
                                        <div class="val-text">{{ $project->hosting_provider ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>CMS / Platform</label>
                                        <div>
                                            @if($project->cms_platform)
                                                <span
                                                    class="cms-tag {{ strtolower($project->cms_platform) }}">{{ $project->cms_platform == 'other' ? $project->cms_custom : $project->cms_platform }}</span>
                                            @else
                                                <span class="val-na">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Domain/Server Book Info</label>
                                        <div class="val-text"
                                            style="font-size:12px;background:var(--bg4);padding:6px 10px;border-radius:4px;border:1px dashed var(--b1);color:var(--t3);">
                                            {{ $project->domain_server_book ?? 'No server booking info' }}</div>
                                    </div>
                                </div>
                                <div>
                                    <div class="kv-item">
                                        <label>Structure & Features</label>
                                        <div class="val-text"><i class="bi bi-file-earmark-code"
                                                style="margin-right:4px;"></i>{{ $project->no_of_pages ?? 0 }} Pages</div>
                                        <div style="font-size:12px;color:var(--t3);margin-top:6px;line-height:1.5;">
                                            {{ $project->required_features ?? 'No specific features listed.' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Reference Websites</label>
                                        <div class="val-text" style="font-size:12px;color:var(--accent);">
                                            {{ $project->reference_websites ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Mail Account Info</label>
                                        <div class="val-text">
                                            <span
                                                style="font-weight:700;color:var(--t1);">{{ $project->no_of_mail_ids ?? 0 }}</span>
                                            IDs
                                            @if($project->mail_password)
                                                <span class="val-secret" style="margin-left:8px;"><i class="bi bi-key"></i>
                                                    {{ $project->mail_password }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Credentials & Access --}}
                    <div class="dash-card" style="border-left:4px solid #f59e0b;">
                        <div class="card-head">
                            <div class="card-title" style="color:#b45309;"><i class="bi bi-shield-lock-fill"
                                    style="margin-right:8px;"></i>Login Credentials</div>
                        </div>
                        <div class="card-body" style="background:rgba(245,158,11,0.02)">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
                                <div
                                    style="background:var(--bg1);padding:15px;border-radius:10px;border:1px solid var(--b1);">
                                    <label
                                        style="display:block;font-size:10px;color:var(--t4);text-transform:uppercase;font-weight:800;margin-bottom:8px;">Dashboard
                                        Username</label>
                                    <div style="font-family:var(--mono);font-size:15px;color:var(--t1);font-weight:700;">
                                        {{ $project->username ?: 'N/A' }}</div>
                                </div>
                                <div
                                    style="background:var(--bg1);padding:15px;border-radius:10px;border:1px solid var(--b1);">
                                    <label
                                        style="display:block;font-size:10px;color:var(--t4);text-transform:uppercase;font-weight:800;margin-bottom:8px;">Dashboard
                                        Password</label>
                                    <div
                                        style="font-family:var(--mono);font-size:15px;color:var(--accent);font-weight:700;">
                                        {{ $project->password ?: 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>


                    {{-- Communication History & Tracking --}}
                    <div class="dash-card">
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

                {{-- Right Column: Quick Update, Financials, Team, Dates --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- Quick Update Form --}}
                    <div class="dash-card" style="border: 2px solid var(--accent);box-shadow: 0 10px 30px rgba(99,102,241,0.1);">
                        <div class="card-head" style="background:rgba(99,102,241,0.05);border-bottom:1px solid var(--b1);">
                            <div class="card-title"><i class="bi bi-lightning-charge-fill" style="color:var(--accent);margin-right:6px;"></i>Quick Update</div>
                        </div>
                        <div class="card-body" style="padding:15px;">
                            <form action="{{ route('admin.projects.quickUpdate', $project->id) }}" method="POST">
                                @csrf
                                <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">
                                    <div class="form-row" style="margin-bottom:0;">
                                        <label class="form-lbl" style="font-size:9px;">Project Status</label>
                                        <select name="project_status_id" class="form-inp" style="padding:6px 8px;font-size:12px;">
                                            @foreach($statuses['project_statuses'] as $s)
                                                <option value="{{ $s->id }}" {{ $project->project_status_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-row" style="margin-bottom:0;">
                                        <label class="form-lbl" style="font-size:9px;">Payment Status</label>
                                        <select name="payment_status_id" class="form-inp" style="padding:6px 8px;font-size:12px;">
                                            @foreach($statuses['payment_statuses'] as $s)
                                                <option value="{{ $s->id }}" {{ $project->payment_status_id == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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

                    {{-- Financials --}}
                    <div class="dash-card fb-top-accent">
                        <div class="card-head">
                            <div class="card-title text-accent"><i class="bi bi-currency-rupee"></i> Financial Summary</div>
                        </div>
                        <div class="card-body">
                            <div style="display:flex;flex-direction:column;gap:15px;">
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:13px;color:var(--t3);">Total Project Price</span>
                                    <span
                                        style="font-weight:800;color:var(--t1);font-size:20px;">₹{{ number_format($project->project_price, 0) }}</span>
                                </div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:13px;color:var(--t3);">Advance Payment</span>
                                    <span
                                        style="font-weight:700;color:#10b981;">₹{{ number_format($project->advance_payment, 0) }}</span>
                                </div>
                                <div style="height:1px;background:var(--accent);opacity:0.2;margin:5px 0;"></div>
                                <div style="display:flex;justify-content:space-between;align-items:center;">
                                    <span style="font-size:14px;font-weight:700;color:var(--t2);">Balance Due</span>
                                    <span
                                        style="font-weight:900;color:#ef4444;font-size:24px;">₹{{ number_format($project->remaining_amount, 0) }}</span>
                                </div>

                                <div style="margin-top:10px;">
                                    <div style="display:flex;gap:5px;flex-direction:column;">
                                        @php
                                            $fStatus = strtolower($project->financial_payment_status ?? 'pending');
                                            $fClass = $fStatus == 'paid' ? 'paid' : ($fStatus == 'partial' ? 'pending' : 'overdue');

                                            $wStatus = strtolower($project->website_payment_status ?? 'pending');
                                            $wClass = $wStatus == 'paid' ? 'paid' : ($wStatus == 'partial' ? 'pending' : 'overdue');
                                        @endphp
                                        <div
                                            style="display:flex;justify-content:space-between;font-size:11px;color:var(--t4);margin-bottom:4px;">
                                            <span>Financial Status:</span>
                                            <span class="status-pill {{ $fClass }}"
                                                style="transform: scale(0.9);">{{ $project->financial_payment_status ?? 'Pending' }}</span>
                                        </div>
                                        <div
                                            style="display:flex;justify-content:space-between;font-size:11px;color:var(--t4);">
                                            <span>Website Payment:</span>
                                            <span class="status-pill {{ $wClass }}"
                                                style="transform: scale(0.9);">{{ $project->website_payment_status ?? 'Pending' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Team Members --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Assigned Team</div>
                        </div>
                        <div class="card-body">
                            <div style="display:flex;flex-direction:column;gap:12px;">
                                @forelse($project->developers as $idx => $dev)
                                    <div
                                        style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--bg2);border-radius:12px;border:1px solid var(--b1);">
                                        @php
                                            $gradients = ['#6366f1, #06b6d4', '#ec4899, #f59e0b', '#10b981, #06b6d4', '#8b5cf6, #ec4899'];
                                            $words = explode(' ', $dev->name);
                                            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                        @endphp
                                        <div
                                            style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,{{ $gradients[$idx % count($gradients)] }});color:white;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;flex-shrink:0;">
                                            {{ $initials }}</div>
                                        <div style="display:flex;flex-direction:column;overflow:hidden;">
                                            <span
                                                style="font-size:14px;font-weight:700;color:var(--t1);">{{ $dev->name }} - {{ $dev->designation }}</span>
                                            <span style="font-size:11px;color:var(--t4);">{{ $dev->email }}</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-na-box">No developers assigned</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Timeline --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Project Timeline</div>
                        </div>
                        <div class="card-body">
                            <div style="display:flex;flex-direction:column;gap:18px;">
                                <div class="tl-item">
                                    <div class="tl-dot bullet-accent"></div>
                                    <div class="tl-line"></div>
                                    <div>
                                        <label>Start Date</label>
                                        <div class="tl-val">
                                            {{ $project->project_start_date ? $project->project_start_date->format('d M, Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot bullet-warn"></div>
                                    <div class="tl-line"></div>
                                    <div>
                                        <label>Exp. Delivery</label>
                                        <div class="tl-val">
                                            {{ $project->expected_delivery_date ? $project->expected_delivery_date->format('d M, Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
                                <div class="tl-item">
                                    <div class="tl-dot bullet-danger"></div>
                                    <div>
                                        <label>Actual Delivery</label>
                                        <div class="tl-val">
                                            {{ $project->actual_delivery_date ? $project->actual_delivery_date->format('d M, Y') : 'Pending' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </main>

    <style>
        /* UI Tokens */
        .kv-item {
            margin-bottom: 20px;
        }

        .kv-item label {
            display: block;
            font-size: 10px;
            color: var(--t4);
            text-transform: uppercase;
            font-weight: 800;
            letter-spacing: 0.05em;
            margin-bottom: 6px;
        }

        .val-lg {
            font-size: 18px;
            font-weight: 800;
            color: var(--t1);
        }

        .val-text {
            font-size: 14px;
            color: var(--t2);
            font-weight: 500;
        }

        .val-link {
            font-size: 14px;
            color: var(--accent);
            font-weight: 700;
        }

        .val-na {
            color: var(--t4);
            font-style: italic;
            font-size: 13px;
        }

        .val-secret {
            font-family: var(--mono);
            color: var(--accent);
            background: var(--accent-bg);
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
        }

        .val-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 10px;
            border-radius: 20px;
            background: var(--bg3);
            border: 1px solid var(--b1);
            font-size: 12px;
            color: var(--t2);
            font-weight: 600;
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .val-list {
            display: flex;
            flex-wrap: wrap;
            margin-top: 5px;
        }

        .badge-outline {
            padding: 2px 8px;
            border-radius: 4px;
            border: 1px solid var(--b2);
            background: var(--bg2);
            color: var(--t3);
            font-size: 11px;
            font-weight: 700;
        }

        .proj-status {
            font-size: 11px;
            font-weight: 700;
            padding: 3px 12px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .proj-status::before {
            content: '';
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .proj-status.new {
            background: rgba(245, 158, 11, .12);
            color: #f59e0b;
        }

        .proj-status.new::before {
            background: #f59e0b;
        }

        .proj-status.design-phase {
            background: rgba(6, 182, 212, .12);
            color: #06b6d4;
        }

        .proj-status.design-phase::before {
            background: #06b6d4;
        }

        .proj-status.development {
            background: rgba(99, 102, 241, .12);
            color: #6366f1;
        }

        .proj-status.development::before {
            background: #6366f1;
        }

        .proj-status.testing {
            background: rgba(139, 92, 246, .12);
            color: #8b5cf6;
        }

        .proj-status.testing::before {
            background: #8b5cf6;
        }

        .proj-status.completed {
            background: rgba(16, 185, 129, .12);
            color: #10b981;
        }

        .proj-status.completed::before {
            background: #10b981;
        }

        .proj-status.on-hold {
            background: rgba(100, 116, 139, .12);
            color: #64748b;
        }

        .proj-status.on-hold::before {
            background: #64748b;
        }

        .cms-tag {
            font-size: 10px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 6px;
        }

        .cms-tag.wordpress {
            background: rgba(33, 117, 155, .1);
            color: #21759b;
            border: 1px solid rgba(33, 117, 155, .2);
        }

        .cms-tag.shopify {
            background: rgba(150, 191, 71, .1);
            color: #96bf47;
            border: 1px solid rgba(150, 191, 71, .2);
        }

        .cms-tag.custom {
            background: rgba(245, 158, 11, .1);
            color: #f59e0b;
            border: 1px solid rgba(245, 158, 11, .2);
        }

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

        /* Utility Helpers */
        .fb-top-accent {
            border-top: 4px solid var(--accent);
        }

        .text-accent {
            color: var(--accent);
        }

        .text-na-box {
            text-align: center;
            padding: 25px;
            background: var(--bg4);
            border-radius: 10px;
            border: 1px dashed var(--b1);
            color: var(--t4);
            font-size: 13px;
            font-style: italic;
        }

        .tl-item {
            display: flex;
            gap: 15px;
            position: relative;
        }

        .tl-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            margin-top: 4px;
            z-index: 2;
            flex-shrink: 0;
        }

        .tl-line {
            position: absolute;
            left: 4px;
            top: 14px;
            bottom: -14px;
            width: 2px;
            background: var(--b2);
        }

        .tl-val {
            font-size: 14px;
            color: var(--t2);
            font-weight: 600;
        }

        .tl-item label {
            display: block;
            font-size: 10px;
            color: var(--t4);
            text-transform: uppercase;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .bullet-accent {
            background: var(--accent);
        }

        .bullet-warn {
            background: #f59e0b;
        }

        .bullet-danger {
            background: #ef4444;
        }
    </style>

@endsection