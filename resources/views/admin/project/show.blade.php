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
                                <i class="bi bi-link-45deg"></i> Linked to Order #{{ $project->order_id }} - Created at: {{ \Carbon\Carbon::parse($project->order_date_create)->format('Y-m-d') }}
                            </span>
                        @endif
                    </div>
                    <h1 class="page-title">{{ $project->project_name }}</h1>
                    <p class="page-desc" style="margin-bottom:12px;">{{ $project->company_name ?? 'Client: ' . $project->client_name }}</p>
                    <div style="display:flex;gap:6px; flex-wrap:wrap;">
                        @foreach($project->services as $service)
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;background:rgba(99,102,241,0.1);color:var(--accent);border:1px solid rgba(99,102,241,0.2);">{{ $service->name }}</span>
                        @endforeach
                        @foreach($project->sources as $source)
                            <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2);">{{ $source->name }}</span>
                        @endforeach
                    </div>
                </div>
                <div class="header-actions">
                    @if($routePrefix == 'admin')
                    <a href="{{ route($routePrefix . '.projects.edit', $project->id) }}" class="btn-primary-solid sm">
                        <i class="bi bi-pencil-square"></i> Edit Project
                    </a>
                    @if($project->order_id)
                        <a href="{{ route($routePrefix . '.payments.create', $project->order_id) }}" class="btn-primary-solid sm">
                            <i class="bi bi-wallet2"></i> Add Payment
                        </a>
                    @endif
                    @endif
                    <a href="{{ route($routePrefix . '.projects.index') }}" class="btn-ghost sm">
                        <i class="bi bi-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>

            <div class="dash-grid">

                {{-- Left Column: Project Info & Technical --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:20px;">

                    {{-- Client & Basic Info ── --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-person-badge-fill" style="color:var(--accent);margin-right:8px;"></i>Client & Identity</div>
                            <div class="card-actions">
                                @php
                                    $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                                    $phoneList = is_array($project->phones) ? $project->phones : [];
                                    $emailList = is_array($project->emails) ? $project->emails : [];
                                    $fullPhones = [];
                                    foreach($phoneList as $p) {
                                        $fullPhones[] = (isset($p['code_idx']) && isset($codes[$p['code_idx']])) ? ($codes[$p['code_idx']] . ($p['num'] ?? '')) : (is_array($p) ? ($p['num'] ?? '') : $p);
                                    }
                                @endphp
                                <button class="btn-primary-solid sm" onclick="handleContactClick(event, 'tel', {{ json_encode($fullPhones) }})">
                                    <i class="bi bi-telephone-fill"></i> Call
                                </button>
                                <button class="btn-ghost sm" onclick="handleContactClick(event, 'mailto', {{ json_encode($emailList) }})" style="border:1px solid var(--b2);">
                                    <i class="bi bi-envelope-fill"></i> Email
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
                                <div>
                                    <div class="kv-item">
                                        <label>Full Name</label>
                                        <div class="val-lg">{{ $project->first_name ? $project->first_name . ' ' . $project->last_name : $project->client_name }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Company Presence</label>
                                        <div class="val-text" style="font-weight:700; color:var(--t1);">{{ $project->company_name ?? 'N/A' }}</div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Direct Contact</label>
                                        <div class="val-list">
                                            @forelse($phoneList as $p)
                                                <div class="val-pill">
                                                    <i class="bi bi-telephone"></i>
                                                    {{ is_array($p) ? ($p['num'] ?? '') : $p }}
                                                </div>
                                            @empty
                                                <span class="val-na">Phone not available</span>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <div class="kv-item">
                                        <label>Email Records</label>
                                        <div class="val-list">
                                            @forelse($emailList as $email)
                                                <div class="val-pill"><i class="bi bi-envelope"></i> {{ $email }}</div>
                                            @empty
                                                <span class="val-na">No emails found</span>
                                            @endforelse
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Location / Address</label>
                                        <div class="val-text" style="line-height:1.6;font-size:13px;">
                                            @if($project->full_address)
                                                {{ $project->full_address }}<br>
                                                {{ $project->city }}{{ $project->state ? ', ' . $project->state : '' }} {{ $project->zip_code }}
                                            @else
                                                <span class="val-na">N/A</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Technical & Infrastructure Specs ── --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-grid-3x3-gap-fill" style="color:#06b6d4;margin-right:8px;"></i>Infrastructure & Platform</div>
                        </div>
                        <div class="card-body">
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:30px;">
                                <div>
                                    {{-- Hosting Card --}}
                                    <div style="background:var(--bg3); padding:14px; border-radius:12px; border:1px solid var(--b1); margin-bottom:18px;">
                                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; border-bottom:1px solid var(--b2); padding-bottom:8px;">
                                            <i class="bi bi-hdd-network-fill" style="color:#0ea5e9;"></i>
                                            <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:var(--t3);">Hosting Infrastructure</span>
                                        </div>
                                        <div class="kv-item" style="margin-bottom:10px;">
                                            <label>Hosting Provider</label>
                                            <div class="val-text">{{ $project->hosting_provider_name ?? ($project->hosting_provider ?? 'N/A') }}</div>
                                        </div>
                                        <div class="kv-item" style="margin-bottom:0;">
                                            <label>Renewal Cost</label>
                                            <div class="val-text" style="color:#10b981; font-weight:700;">₹{{ number_format($project->hosting_renewal_price ?? 0, 0) }}</div>
                                        </div>
                                    </div>

                                    {{-- Domain Card --}}
                                    <div style="background:var(--bg3); padding:14px; border-radius:12px; border:1px solid var(--b1);">
                                        <div style="display:flex; align-items:center; gap:8px; margin-bottom:12px; border-bottom:1px solid var(--b2); padding-bottom:8px;">
                                            <i class="bi bi-globe-americas" style="color:var(--accent);"></i>
                                            <span style="font-size:11px; font-weight:800; text-transform:uppercase; color:var(--t3);">Domain Management</span>
                                        </div>
                                        <div class="kv-item" style="margin-bottom:10px;">
                                            <label>Primary Domain</label>
                                            <div class="val-link"><i class="bi bi-link-45deg"></i> {{ $project->domain_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="kv-item" style="margin-bottom:10px;">
                                            <label>Registrar / Provider</label>
                                            <div class="val-text">{{ $project->domain_provider_name ?? 'N/A' }}</div>
                                        </div>
                                        <div class="kv-item" style="margin-bottom:0;">
                                            <label>Registrar Renewal</label>
                                            <div class="val-text" style="color:#10b981; font-weight:700;">₹{{ number_format($project->domain_renewal_price ?? 0, 0) }}</div>
                                        </div>
                                    </div>
                                </div>

                                <div>
                                    <div class="kv-item">
                                        <label>CMS / Technical Platform</label>
                                        <div style="margin-top:4px;">
                                            @if($project->cms_platform)
                                                <span class="cms-tag {{ strtolower($project->cms_platform) }}" style="font-size:12px; padding:4px 12px;">
                                                    {{ $project->cms_platform == 'other' ? $project->cms_custom : $project->cms_platform }}
                                                </span>
                                            @else
                                                <span class="val-na">Platform Unknown</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Project Scope</label>
                                        <div class="val-text"><i class="bi bi-file-earmark-code" style="margin-right:4px;"></i>{{ $project->no_of_pages ?? 0 }} Pages Scheduled</div>
                                        <div style="font-size:12px; color:var(--t3); margin-top:8px; line-height:1.6; background:var(--bg4); padding:10px; border-radius:8px;">
                                            {{ $project->required_features ?? ($project->extra_features ?? 'No specific requirements listed.') }}
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Design Inspiration / Refs</label>
                                        <div class="val-text" style="font-size:12px; color:var(--accent); word-break:break-all;">
                                            {{ $project->reference_websites ?? 'N/A' }}
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Email Infrastructure</label>
                                        <div class="val-text">
                                            <span style="font-weight:800; color:var(--t1); background:rgba(99,102,241,0.1); padding:2px 8px; border-radius:4px;">{{ $project->no_of_mail_ids ?? 0 }} IDs</span>
                                            @if($project->mail_password)
                                                <span class="val-secret" style="margin-left:8px;"><i class="bi bi-shield-lock"></i> {{ $project->mail_password }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="kv-item">
                                        <label>Lead Source(s)</label>
                                        <div class="val-list">
                                            @forelse($project->sources as $source)
                                                <span style="font-size:10px;font-weight:700;padding:2px 8px;border-radius:4px;background:rgba(16,185,129,0.1);color:#10b981;border:1px solid rgba(16,185,129,0.2);">{{ $source->name }}</span>
                                            @empty
                                                <span class="val-na">No source data available</span>
                                            @endforelse
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
                            <form action="{{ route($routePrefix . '.projects.quickUpdate', $project->id) }}" method="POST">
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
                    <!-- <div class="dash-card fb-top-accent">
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
                    </div> -->

                    {{-- Team Members --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Project Handlers</div>
                        </div>
                        <div class="card-body">
                            <div style="display:flex;flex-direction:column;gap:20px;">
                                {{-- Sales Team --}}
                                <div>
                                    <label style="display:block;font-size:9px;font-weight:800;text-transform:uppercase;color:var(--t4);margin-bottom:10px;letter-spacing:0.05em;">Sales & Account</label>
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        @forelse($project->salesPersons as $idx => $sale)
                                            <div style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--bg2);border-radius:12px;border:1px solid var(--b1);">
                                                @php
                                                    $saleGradients = ['#f59e0b, #ef4444', '#ec4899, #8b5cf6'];
                                                    $sWords = explode(' ', $sale->name);
                                                    $sInitials = strtoupper(substr($sWords[0], 0, 1) . (isset($sWords[1]) ? substr($sWords[1], 0, 1) : ''));
                                                @endphp
                                                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,{{ $saleGradients[$idx % count($saleGradients)] }});color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">{{ $sInitials }}</div>
                                                <div style="display:flex;flex-direction:column;overflow:hidden;">
                                                    <span style="font-size:13px;font-weight:700;color:var(--t1);">{{ $sale->name }}</span>
                                                    <span style="font-size:10px;color:var(--t4);">{{ $sale->email }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div style="font-size:12px; color:var(--t4); padding:10px; border:1px dashed var(--b1); border-radius:8px; text-align:center;">No sales person assigned</div>
                                        @endforelse
                                    </div>
                                </div>

                                {{-- Dev Team --}}
                                <div>
                                    <label style="display:block;font-size:9px;font-weight:800;text-transform:uppercase;color:var(--t4);margin-bottom:10px;letter-spacing:0.05em;">Project fulfillment</label>
                                    <div style="display:flex;flex-direction:column;gap:10px;">
                                        @forelse($project->developers as $idx => $dev)
                                            <div
                                                style="display:flex;align-items:center;gap:12px;padding:10px;background:var(--bg2);border-radius:12px;border:1px solid var(--b1);">
                                                @php
                                                    $gradients = ['#6366f1, #06b6d4', '#10b981, #06b6d4'];
                                                    $words = explode(' ', $dev->name);
                                                    $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                                                @endphp
                                                <div
                                                    style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,{{ $gradients[$idx % count($gradients)] }});color:white;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;flex-shrink:0;">
                                                    {{ $initials }}</div>
                                                <div style="display:flex;flex-direction:column;overflow:hidden;">
                                                    <span
                                                        style="font-size:13px;font-weight:700;color:var(--t1);">{{ $dev->name }} - {{ $dev->designation }}</span>
                                                    <span style="font-size:10px;color:var(--t4);">{{ $dev->email }}</span>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-na-box">No developers assigned</div>
                                        @endforelse
                                    </div>
                                </div>
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
                                    <div class="tl-dot" style="background:var(--accent);"></div>
                                    <div class="tl-line"></div>
                                    <div>
                                        <label>Order Date</label>
                                        <div class="tl-val">
                                            {{ $project->order_date_create ? \Carbon\Carbon::parse($project->order_date_create)->format('d M, Y') : 'N/A' }}
                                        </div>
                                    </div>
                                </div>
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

    <!-- ── Contact Selection Modal (Bootstrap) ── -->
    <div class="modal fade" id="contactSelectionModal" tabindex="-1" aria-labelledby="contactSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="background: var(--bg2); border-color: var(--b2); border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.3);">
                <div class="modal-header" style="border-bottom-color: var(--b1);">
                    <h5 class="modal-title" id="contactSelectionModalLabel" style="color: #ef4444; font-weight: 700;">Select Option</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter: var(--close-filter);"></button>
                </div>
                <div class="modal-body p-0">
                    <div class="list-group list-group-flush" id="contactSelectionOptions">
                    </div>
                </div>
                <div class="modal-footer" style="border-top-color: var(--b1);">
                    <button type="button" class="btn btn-secondary sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleContactClick(e, protocol, options) {
            e.preventDefault();
            e.stopPropagation();

            if (!options || options.length === 0) {
                alert('No contact details available');
                return;
            }

            if (options.length === 1) {
                window.location.href = protocol + ':' + options[0];
                return;
            }

            const modalEl = document.getElementById('contactSelectionModal');
            const optionsGroup = document.getElementById('contactSelectionOptions');
            const titleEl = document.getElementById('contactSelectionModalLabel');

            titleEl.textContent = 'Select ' + (protocol === 'tel' ? 'Phone Number' : 'Email Address');
            optionsGroup.innerHTML = '';

            options.forEach(opt => {
                const item = document.createElement('a');
                item.className = 'list-group-item list-group-item-action d-flex align-items-center gap-3 py-3 border-bottom-0';
                item.style.cssText = 'background: transparent; color: var(--t2); border-bottom: 1px solid var(--b1) !important;';
                item.href = protocol + ':' + opt;
                item.innerHTML = `
                    <div style="width: 32px; height: 32px; border-radius: 8px; background: rgba(99,102,241,0.1); display: flex; align-items: center; justify-content: center;">
                        <i class="bi bi-${protocol === 'tel' ? 'telephone-fill' : 'envelope-fill'}" style="color: var(--accent);"></i>
                    </div>
                    <span style="font-weight: 600; font-size: 15px;">${opt}</span>
                `;
                item.onmouseover = () => { item.style.background = 'var(--bg3)'; item.style.color = 'var(--accent)'; };
                item.onmouseout = () => { item.style.background = 'transparent'; item.style.color = 'var(--t2)'; };
                item.onclick = (e) => {
                    const modal = bootstrap.Modal.getInstance(modalEl);
                    if (modal) modal.hide();
                };
                optionsGroup.appendChild(item);
            });

            const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
            modal.show();
        }
    </script>
@endsection