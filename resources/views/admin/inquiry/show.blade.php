@extends('admin.layout.app')

@section('title', 'Inquiry Details')

@section('content')

<style>
    /* ─── INQUIRY SHOW PAGE RESPONSIVE STYLES ─── */
    .inquiry-show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 22px;
    }

    .inquiry-show-layout {
        display: grid;
        grid-template-columns: 350px minmax(0, 1fr);
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .inquiry-show-layout {
            grid-template-columns: 320px minmax(0, 1fr);
            gap: 18px;
        }
    }

    @media (max-width: 992px) {
        .inquiry-show-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .left-profile-col, .right-content-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 0;
    }

    /* Client Profile Card */
    .client-avatar-lg {
        width: 76px;
        height: 76px;
        border-radius: 20px;
        background: linear-gradient(135deg, #6366f1 0%, #06b6d4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 28px;
        font-weight: 800;
        color: #fff;
        margin-bottom: 14px;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.3);
    }

    .profile-hero {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 24px 18px 20px;
        border-bottom: 1px solid var(--b3);
        text-align: center;
    }

    .profile-title {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        letter-spacing: -0.4px;
        line-height: 1.3;
    }

    .profile-sub {
        font-size: 13px;
        color: var(--t3);
        margin-top: 4px;
        word-break: break-all;
    }

    .service-chip {
        font-size: 11px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 6px;
        background: var(--accent-bg);
        color: var(--accent);
        border: 1px solid rgba(99, 102, 241, 0.25);
    }

    /* Detail Rows */
    .detail-list {
        display: flex;
        flex-direction: column;
        gap: 6px;
        padding: 16px 18px;
    }

    .detail-row {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 10px 0;
        border-bottom: 1px solid var(--b3);
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: var(--bg3);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: var(--accent);
        font-size: 15px;
        border: 1px solid var(--b3);
    }

    .detail-lbl {
        font-size: 11px;
        color: var(--t3);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        margin-bottom: 3px;
    }

    .detail-val {
        font-size: 13.5px;
        font-weight: 600;
        color: var(--t1);
        line-height: 1.4;
        word-break: break-word;
    }

    /* KPI Ribbon */
    .inquiry-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 16px;
    }

    @media (max-width: 640px) {
        .inquiry-kpis {
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
    }

    @media (max-width: 380px) {
        .inquiry-kpis {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    .kpi-box {
        background: var(--bg2);
        border: 1px solid var(--b3);
        border-radius: 12px;
        padding: 16px;
        text-align: center;
        box-shadow: var(--shadow-sm);
    }

    .kpi-val {
        font-size: 20px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 4px;
        letter-spacing: -0.5px;
        font-family: var(--mono, monospace);
    }

    .kpi-lbl {
        font-size: 11px;
        font-weight: 700;
        color: var(--t3);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* Directories 2-Col Grid */
    .directory-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    @media (max-width: 680px) {
        .directory-grid {
            grid-template-columns: 1fr;
            gap: 14px;
        }
    }

    .directory-item {
        font-size: 13px;
        color: var(--t1);
        padding: 10px 12px;
        background: var(--bg3);
        border-radius: 8px;
        border: 1px solid var(--b3);
        margin-bottom: 8px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        transition: var(--transition);
    }

    .directory-item:hover {
        border-color: var(--accent);
        background: var(--bg2);
    }

    .directory-item:last-child {
        margin-bottom: 0;
    }

    .dir-btn {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        text-decoration: none;
        transition: var(--transition);
        border: none;
        cursor: pointer;
    }

    .dir-btn.email {
        background: var(--accent);
        color: #fff;
    }
    .dir-btn.email:hover {
        background: var(--accent2);
    }

    .dir-btn.phone {
        background: #10b981;
        color: #fff;
    }
    .dir-btn.phone:hover {
        background: #059669;
    }

    .dir-btn.wa {
        background: #25d366;
        color: #fff;
    }
    .dir-btn.wa:hover {
        background: #128c7e;
    }

    /* Convert Button */
    .btn-convert-order {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 18px;
        background: #10b981;
        color: #fff;
        border-radius: 8px;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);
    }

    .btn-convert-order:hover {
        background: #059669;
        color: #fff;
        transform: translateY(-1px);
        box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    }

    /* Action Center Layout */
    .action-center-form {
        display: grid;
        grid-template-columns: 1fr 240px;
        gap: 16px;
        align-items: start;
    }

    @media (max-width: 640px) {
        .action-center-form {
            grid-template-columns: 1fr;
            gap: 14px;
        }
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page">

        <!-- PAGE HEADER -->
        <div class="inquiry-show-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route($routePrefix . '.inquiry.index') }}" class="btn-ghost sm">
                        <i class="bi bi-arrow-left"></i> Back to Inquiries
                    </a>
                    <span style="font-size: 12px; color: var(--t3);">•</span>
                    <span style="font-size: 12px; color: var(--t3); font-weight: 600; font-family: var(--mono, monospace);">
                        #INQ-{{ str_pad($inquiry->id, 4, '0', STR_PAD_LEFT) }}
                    </span>
                </div>
                <h1 class="page-title">Inquiry Details</h1>
                <p class="page-desc">Viewing request from <strong>{{ $inquiry->company_name }}</strong></p>
            </div>
            
            <div class="header-actions" style="display:flex; align-items:center; flex-wrap:wrap; gap:10px;">
                @if(auth('admin')->check() || auth('sale')->check())
                    <a href="{{ route($routePrefix . '.orders.create', ['inquiry_id' => $inquiry->id]) }}" class="btn-convert-order">
                        <i class="bi bi-check-circle-fill"></i> Convert to Order
                    </a>
                @endif
                <a href="{{ route($routePrefix . '.inquiry.edit', $inquiry->id) }}" class="btn-ghost sm">
                    <i class="bi bi-pencil-square"></i> Edit Inquiry
                </a>
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="inquiry-show-layout">
            
            <!-- LEFT COLUMN: Client Identity Profile -->
            <div class="left-profile-col">
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-vcard-fill"></i> Client Profile</div>
                    </div>
                    
                    @php 
                        $emails = (array)$inquiry->emails;
                        $phones = (array)$inquiry->phones;
                        $initials = strtoupper(substr($inquiry->company_name ?? 'C', 0, 1) . substr($inquiry->client_name ?? 'N', 0, 1));
                    @endphp

                    <div class="profile-hero">
                        <div class="client-avatar-lg">{{ $initials }}</div>
                        <div class="profile-title">{{ $inquiry->company_name }}</div>
                        <div class="profile-sub">{{ $emails[0] ?? 'No primary email' }}</div>
                        
                        @if(!empty($services) && count($services) > 0)
                        <div style="margin-top:14px; display:flex; gap:6px; flex-wrap:wrap; justify-content:center;">
                            @foreach($services as $s)
                                <span class="service-chip"><i class="bi bi-gear-fill" style="font-size: 9px;"></i> {{ $s }}</span>
                            @endforeach
                        </div>
                        @endif
                    </div>

                    <div class="detail-list">
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $inquiry->client_name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-megaphone-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Lead Source</div>
                                <div class="detail-val">
                                    @forelse($sources as $src)
                                        <span style="display:inline-block; margin-right:6px; color:var(--accent); font-weight:700;">#{{ $src }}</span>
                                    @empty
                                        <span style="color:var(--t3);">Direct / Not Specified</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-globe"></i></div>
                            <div>
                                <div class="detail-lbl">Domain / Website</div>
                                <div class="detail-val">
                                    @if($inquiry->domain_name)
                                        <a href="https://{{ $inquiry->domain_name }}" target="_blank" rel="noopener noreferrer" style="color:var(--accent); text-decoration:none;">
                                            {{ $inquiry->domain_name }} <i class="bi bi-box-arrow-up-right" style="font-size:11px;"></i>
                                        </a>
                                    @else
                                        <span style="color:var(--t3);">N/A</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-layers-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Converted Orders</div>
                                <div class="detail-val">
                                    <span style="display:inline-flex; align-items:center; gap:4px; padding:3px 10px; border-radius:12px; background:rgba(16, 185, 129, 0.15); color:#10b981; font-size:12px; font-weight:700;">
                                        <i class="bi bi-check2"></i> {{ $inquiry->orders()->count() }} Order(s)
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Full Address</div>
                                <div class="detail-val">{{ $inquiry->full_address ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-pin-map-fill"></i></div>
                            <div>
                                <div class="detail-lbl">City & State</div>
                                <div class="detail-val">{{ $inquiry->city ?? '' }}{{ $inquiry->city && $inquiry->state ? ', ' : '' }}{{ $inquiry->state ?? '' }} {{ $inquiry->zip_code ? '(' . $inquiry->zip_code . ')' : '' }}</div>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-clock-history"></i></div>
                            <div>
                                <div class="detail-lbl">Submitted On</div>
                                <div class="detail-val">{{ $inquiry->created_at->format('d M Y, h:i A') }} <span style="font-size:11.5px; color:var(--t3); font-weight:normal;">({{ $inquiry->created_at->diffForHumans() }})</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Metrics, Contacts, Staff, Actions, Orders -->
            <div class="right-content-col">
                
                <!-- 4-KPI RIBBON -->
                <div class="inquiry-kpis">
                    <div class="kpi-box">
                        <div class="kpi-val" style="color:var(--accent);">₹{{ number_format($inquiry->order_value ?? 0, 0) }}</div>
                        <div class="kpi-lbl">Estimated Budget</div>
                    </div>
                    
                    <div class="kpi-box">
                        @php
                            $statusClr = ['pending' => '#f59e0b', 'reviewed' => '#0ea5e9', 'converted' => '#10b981', 'rejected' => '#ef4444'];
                            $clr = $statusClr[$inquiry->status] ?? '#6366f1';
                        @endphp
                        <div class="kpi-val" style="color:{{ $clr }}; font-size:17px; text-transform:uppercase;">
                            {{ ucfirst($inquiry->status) }}
                        </div>
                        <div class="kpi-lbl">Current Status</div>
                    </div>

                    <div class="kpi-box">
                        <div class="kpi-val" style="font-size:15px; color:var(--t1);">{{ $inquiry->created_at->format('d M Y') }}</div>
                        <div class="kpi-lbl">Submission Date</div>
                    </div>

                    <div class="kpi-box">
                        <div class="kpi-val" style="font-size:13.5px; color:var(--t2);">{{ $inquiry->ip_address ?? '127.0.0.1' }}</div>
                        <div class="kpi-lbl">Client IP</div>
                    </div>
                </div>

                <!-- CONTACT DIRECTORIES (EMAIL & PHONE) -->
                <div class="directory-grid">
                    <!-- Email Directory -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-envelope-fill"></i> Email Directory</div>
                        </div>
                        <div class="card-body">
                            @forelse($emails as $email)
                                <div class="directory-item">
                                    <span style="font-weight:600; word-break:break-all;">{{ $email }}</span>
                                    <div style="display:flex; gap:6px; flex-shrink:0;">
                                        <a href="mailto:{{ $email }}" class="dir-btn email" title="Send Email">
                                            <i class="bi bi-send-fill"></i>
                                        </a>
                                        <button type="button" class="dir-btn" style="background:var(--bg4); color:var(--t2);" title="Copy Email" onclick="navigator.clipboard.writeText('{{ $email }}'); alert('Email copied!');">
                                            <i class="bi bi-copy"></i>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div style="color:var(--t3); font-size:13px; text-align:center; padding:12px;">No email addresses provided</div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Phone Directory -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-telephone-fill"></i> Phone Directory</div>
                        </div>
                        <div class="card-body">
                            @php
                                $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                            @endphp
                            @forelse($phones as $phone)
                                @php
                                    $code = $codes[$phone['code_idx'] ?? ''] ?? '';
                                    $rawNum = $phone['number'] ?? '';
                                    $fullNum = preg_replace('/[^0-9]/', '', $code . $rawNum);
                                @endphp
                                <div class="directory-item">
                                    <span style="font-weight:700; font-family:var(--mono, monospace);">{{ $code }} {{ $rawNum }}</span>
                                    <div style="display:flex; gap:6px; flex-shrink:0;">
                                        <a href="tel:{{ $code }}{{ $rawNum }}" class="dir-btn phone" title="Call Number">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                        <a href="https://wa.me/{{ $fullNum }}" target="_blank" rel="noopener noreferrer" class="dir-btn wa" title="WhatsApp Message">
                                            <i class="bi bi-whatsapp"></i>
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div style="color:var(--t3); font-size:13px; text-align:center; padding:12px;">No phone numbers provided</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- ASSIGN PERSONNEL -->
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-person-check-fill"></i> Assigned Sales Personnel</div>
                        <span style="font-size:12px; color:var(--t3);">{{ count($assignedIds) }} staff assigned</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route($routePrefix . '.inquiry.assign', $inquiry->id) }}" method="POST">
                            @csrf
                            <div class="form-row" style="margin-bottom:16px; position:relative; z-index:99;">
                                <label class="form-lbl">Select Sales Representatives</label>
                                <div class="ms-wrap" id="salesWrap">
                                    <div class="ms-trigger" onclick="toggleMs('salesWrap')">
                                        <div class="ms-pills"><span class="ms-placeholder">Select staff members…</span></div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="salesDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search staff…" oninput="filterMs(this,'salesDropdown')">
                                            <span class="ms-all-btn" onclick="toggleAllMs('salesWrap','salesDropdown')">Select All</span>
                                        </div>
                                        <div class="ms-opts">
                                            @foreach($sales as $m)
                                                @php 
                                                    $initials = strtoupper(substr($m->name, 0, 2)); 
                                                    $colors = ['#6366f1','#ec4899','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                                                    $bg = $colors[$m->id % count($colors)];
                                                @endphp
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="sales_person[]" value="{{ $m->id }}" 
                                                        data-name="{{ $m->name }}" data-initials="{{ $initials }}"
                                                        onchange="updateMs('salesWrap')"
                                                        {{ in_array($m->id, $assignedIds) ? 'checked' : '' }}>
                                                    <span class="ms-ava" style="background:{{ $bg }}">{{ $initials }}</span>
                                                    <div>
                                                        <div style="font-size:12.5px;font-weight:600;color:var(--t1);">{{ $m->name }}</div>
                                                        <div style="font-size:10.5px;color:var(--t3);">{{ $m->email }}</div>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @error('sales_person')<span class="field-error">{{ $message }}</span>@enderror
                            </div>
                            <div style="display:flex; justify-content:flex-end;">
                                <button type="submit" class="btn-primary-solid sm" style="height:40px; padding:0 20px;">
                                    <i class="bi bi-check2-circle"></i> Save Assignments
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ACTION CENTER: QUICK NOTES & STATUS -->
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-lightning-charge-fill"></i> Action Center</div>
                        <span style="font-size:12px; color:var(--t3);">Update inquiry status and private notes</span>
                    </div>
                    <div class="card-body">
                        <form action="{{ route($routePrefix . '.inquiry.status', $inquiry->id) }}" method="POST">
                            @csrf
                            <div class="action-center-form">
                                <!-- Notes Left -->
                                <div class="form-row" style="margin:0;">
                                    <label class="form-lbl">Internal Notes / Requirements</label>
                                    <textarea name="notes" class="form-inp" rows="3" placeholder="Enter private project notes, discussion points, or customer requirements…">{{ $inquiry->notes }}</textarea>
                                </div>

                                <!-- Status Right -->
                                <div style="display:flex; flex-direction:column; gap:14px;">
                                    <div class="form-row" style="margin:0;">
                                        <label class="form-lbl">Inquiry Status</label>
                                        <select name="status" class="form-inp" style="border-left: 4px solid var(--accent); height:42px;">
                                            <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="reviewed" {{ $inquiry->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                            <option value="rejected" {{ $inquiry->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>
                                    </div>
                                    <button type="submit" class="btn-primary-solid sm" style="height:42px; width:100%; justify-content:center;">
                                        <i class="bi bi-save2-fill"></i> Save Quick Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- ASSOCIATED ORDERS (IF ANY) -->
                @if($inquiry->orders && $inquiry->orders->count() > 0)
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-receipt"></i> Associated Orders</div>
                        <span class="status-pill" style="background:#10b98115; color:#10b981;">{{ $inquiry->orders->count() }} Converted Order(s)</span>
                    </div>
                    <div class="card-body" style="padding:0;">
                        <div class="table-responsive-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Order No</th>
                                        <th>Date</th>
                                        <th>Order Value</th>
                                        <th>Status</th>
                                        <th style="text-align:right;">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($inquiry->orders as $ord)
                                    <tr>
                                        <td>
                                            <a href="{{ route($routePrefix . '.orders.show', $ord->id) }}" style="font-weight:700; color:var(--accent); text-decoration:none; font-family:var(--mono, monospace);">
                                                #{{ $ord->order_number }}
                                            </a>
                                        </td>
                                        <td>{{ $ord->created_at->format('d M Y') }}</td>
                                        <td style="font-family:var(--mono, monospace); font-weight:700;">₹{{ number_format($ord->order_value, 2) }}</td>
                                        <td>
                                            <span class="status-pill" style="background:var(--bg3); color:var(--t1); border:1px solid var(--b3);">
                                                {{ $ord->status->name ?? 'Active' }}
                                            </span>
                                        </td>
                                        <td style="text-align:right;">
                                            <a href="{{ route($routePrefix . '.orders.show', $ord->id) }}" class="btn-ghost sm">
                                                <i class="bi bi-eye"></i> View Order
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif

            </div>
        </div>
    </div>
</main>

@include('admin.orders.multiselect-assets')

@endsection
