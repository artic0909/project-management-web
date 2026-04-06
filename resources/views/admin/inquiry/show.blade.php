@extends('admin.layout.app')

@section('title', 'Inquiry Details')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('admin.inquiry.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Inquiries
                    </a>
                </div>
                <h1 class="page-title">Inquiry Details</h1>
                <p class="page-desc">Viewing request from <strong>{{ $inquiry->company_name }}</strong></p>
            </div>
            <div style="display:flex; gap:10px; align-items:center;">
                <!-- @if($inquiry->status != 'converted')
                    <div class="conv-btn-wrap">
                        <a href="{{ route('admin.leads.create', ['inquiry_id' => $inquiry->id]) }}" class="conv-btn lead">
                            <i class="bi bi-person-plus-fill"></i> <span>Convert to Lead</span>
                        </a>
                        <a href="{{ route('admin.orders.create', ['inquiry_id' => $inquiry->id]) }}" class="conv-btn order">
                            <i class="bi bi-check-circle-fill"></i> <span>Convert to Order</span>
                        </a>
                    </div>
                @endif -->
                <a href="{{ route('admin.inquiry.edit', $inquiry->id) }}" class="btn-ghost sm">
                    <i class="bi bi-pencil-square"></i>Edit Inquiry
                </a>
            </div>
        </div>



        <div class="dash-grid">
            {{-- Left Column --}}
            <div class="dash-card span-4" style="height:fit-content;">
                <div class="card-head">
                    <div class="card-title">Client Identity</div>
                </div>
                <div class="card-body" style="padding:0 18px 24px;">
                    @php 
                        $emails = (array)$inquiry->emails;
                        $phones = (array)$inquiry->phones;
                        $initials = strtoupper(substr($inquiry->company_name ?? 'C', 0, 1) . substr($inquiry->client_name ?? 'N', 0, 1));
                    @endphp

                    <div style="display:flex;flex-direction:column;align-items:center;padding:24px 0 20px;border-bottom:1px solid var(--b1);text-align:center;">
                        <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;margin-bottom:14px;box-shadow:0 8px 30px rgba(99,102,241,.3);">{{ $initials }}</div>
                        <div style="font-size:19px;font-weight:800;color:var(--t1);letter-spacing:-.4px;">{{ $inquiry->company_name }}</div>
                        <div style="font-size:13px;color:var(--t3);margin-top:4px;">{{ $emails[0] ?? 'N/A' }}</div>
                        
                        <div style="margin-top:12px;display:flex;gap:6px; flex-wrap:wrap; justify-content:center;">
                            @foreach($services as $s)
                                <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;background:rgba(99,102,241,0.1);color:var(--accent);border:1px solid rgba(99,102,241,0.2);">{{ $s }}</span>
                            @endforeach
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:4px;margin-top:16px;">
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $inquiry->client_name }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-megaphone-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Sources</div>
                                <div class="detail-val">
                                    @forelse($sources as $src)
                                        <span style="display:inline-block; margin-right:5px; color:var(--accent);">#{{ $src }}</span>
                                    @empty
                                        <span style="color:var(--t4);">No Sources</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-globe"></i></div>
                            <div>
                                <div class="detail-lbl">Domain / URL</div>
                                <div class="detail-val"><a href="https://{{ $inquiry->domain_name }}" target="_blank" style="color:var(--accent); text-decoration:none;">{{ $inquiry->domain_name ?? 'N/A' }}</a></div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Full Address</div>
                                <div class="detail-val">{{ $inquiry->full_address }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-pin-map-fill"></i></div>
                            <div>
                                <div class="detail-lbl">City / Region</div>
                                <div class="detail-val">{{ $inquiry->city }}, {{ $inquiry->state }} ({{ $inquiry->zip_code }})</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                
                {{-- Metrics --}}
                <div class="dash-card">
                    <div class="card-body">
                        <div class="detail-kpis">
                            <div class="dk-item">
                                <div class="dk-val">₹{{ number_format($inquiry->order_value, 0) }}</div>
                                <div class="dk-lbl">Budget</div>
                            </div>
                            <div class="dk-item">
                                @php
                                    $statusClr = ['pending' => '#f59e0b', 'reviewed' => '#0ea5e9', 'converted' => '#10b981', 'rejected' => '#ef4444'];
                                    $clr = $statusClr[$inquiry->status] ?? '#6366f1';
                                @endphp
                                <div class="dk-val" style="color:{{ $clr }};">{{ ucfirst($inquiry->status) }}</div>
                                <div class="dk-lbl">Status</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val">{{ $inquiry->created_at->format('d M Y') }}</div>
                                <div class="dk-lbl">Submission Date</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="font-size:13px; font-family:var(--font-mono);">{{ $inquiry->ip_address }}</div>
                                <div class="dk-lbl">IP Address</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px; display:flex; align-items:center; gap:6px;">
                            <i class="bi bi-envelope-fill"></i> Email Directory
                        </div>
                        @foreach($emails as $email)
                            <div style="font-size:13px; color:var(--t2); padding:10px 14px; background:var(--bg3); border-radius:10px; border:1px solid var(--b1); margin-bottom:8px; display:flex; align-items:center; justify-content:space-between; transition:var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--b1)'">
                                <span>{{ $email }}</span>
                                <div style="display:flex; gap:6px;">
                                    <a href="mailto:{{ $email }}" class="ra-btn sm" style="background:var(--accent); color:#fff; border:none;"><i class="bi bi-send-fill"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px; display:flex; align-items:center; gap:6px;">
                            <i class="bi bi-telephone-fill"></i> Phone Directory
                        </div>
                        @php
                            $codes = [0=>'+93',1=>'+355',2=>'+213',3=>'+376',4=>'+244',5=>'+54',6=>'+61',7=>'+43',8=>'+880',9=>'+32',10=>'+55',11=>'+1',12=>'+86',13=>'+57',14=>'+45',15=>'+20',16=>'+33',17=>'+49',18=>'+233',19=>'+30',20=>'+91',21=>'+62',22=>'+98',23=>'+964',24=>'+353',25=>'+972',26=>'+39',27=>'+81',28=>'+962',29=>'+254',30=>'+965',31=>'+961',32=>'+60',33=>'+52',34=>'+212',35=>'+977',36=>'+31',37=>'+64',38=>'+234',39=>'+47',40=>'+968',41=>'+92',42=>'+63',43=>'+48',44=>'+351',45=>'+974',46=>'+7',47=>'+966',48=>'+65',49=>'+27',50=>'+34',51=>'+94',52=>'+46',53=>'+41',54=>'+886',55=>'+66',56=>'+90',57=>'+971',58=>'+44',59=>'+1',60=>'+84',61=>'+260',62=>'+263'];
                        @endphp
                        @foreach($phones as $phone)
                            @php
                                $code = $codes[$phone['code_idx'] ?? ''] ?? '';
                                $fullNum = $code . $phone['number'];
                            @endphp
                            <div style="font-size:13px; color:var(--t2); padding:10px 14px; background:var(--bg3); border-radius:10px; border:1px solid var(--b1); margin-bottom:8px; display:flex; align-items:center; justify-content:space-between; transition:var(--transition);" onmouseover="this.style.borderColor='var(--accent)'" onmouseout="this.style.borderColor='var(--b1)'">
                                <span>{{ $code }} {{ $phone['number'] }}</span>
                                <div style="display:flex; gap:6px;">
                                    <a href="tel:{{ $fullNum }}" class="ra-btn sm" style="background:#10b981; color:#fff; border:none;"><i class="bi bi-telephone-fill"></i></a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                        {{-- Quick Action Center --}}
        <div class="dash-card" style="margin-bottom: 20px;">
            <div class="card-head" style="display:flex; align-items:baseline; gap:12px;">
                <div class="card-title" style="margin:0;">Action Center</div>
                <div class="card-sub" style="margin:0; opacity:0.7;">Quickly update status or project notes</div>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.inquiry.status', $inquiry->id) }}" method="POST">
                    @csrf
                    <div style="display:grid; grid-template-columns: 1fr 240px; gap:20px; align-items:start;">
                        {{-- Notes on Left (Swapped) --}}
                        <div class="form-row" style="margin:0;">
                            <label class="form-lbl">Quick Internal Notes</label>
                            <textarea name="notes" class="form-inp" rows="2" placeholder="Update project requirements or internal notes…">{{ $inquiry->notes }}</textarea>
                        </div>

                        {{-- Status on Right (Swapped) --}}
                        <div style="display:flex; flex-direction:column; gap:16px;">
                            <div class="form-row" style="margin:0;">
                                <label class="form-lbl">Inquiry Status</label>
                                <select name="status" class="form-inp" style="border-left: 4px solid var(--accent);">
                                    <option value="pending" {{ $inquiry->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="reviewed" {{ $inquiry->status == 'reviewed' ? 'selected' : '' }}>Reviewed</option>
                                    <!-- <option value="converted" {{ $inquiry->status == 'converted' ? 'selected' : '' }}>Converted</option> -->
                                    <option value="rejected" {{ $inquiry->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary-solid sm w-100" style="height:42px;">
                                <i class="bi bi-check2-circle"></i> Save Quick Update
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
            </div>
        </div>
    </div>
</main>

<style>
    .conv-btn-wrap { display: flex; border: 1px solid var(--b1); border-radius: 12px; overflow: hidden; background: var(--bg2); }
    .conv-btn { display: flex; align-items: center; gap: 8px; padding: 8px 16px; font-size: 13px; font-weight: 700; text-decoration: none; transition: var(--transition); border-right: 1px solid var(--b1); }
    .conv-btn:last-child { border-right: none; }
    .conv-btn.lead { color: var(--accent); background: rgba(99,102,241,0.05); }
    .conv-btn.lead:hover { background: var(--accent); color: #fff; }
    .conv-btn.order { color: #10b981; background: rgba(16,185,129,0.05); }
    .conv-btn.order:hover { background: #10b981; color: #fff; }

    .detail-kpis { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; }
    .dk-item { text-align: center; }
    .dk-val { font-size: 20px; font-weight: 800; color: var(--t1); margin-bottom: 4px; letter-spacing: -0.5px; }
    .dk-lbl { font-size: 11px; font-weight: 600; color: var(--t3); text-transform: uppercase; letter-spacing: 0.1em; }
    
    .detail-row { display:flex; align-items:flex-start; gap:14px; padding:12px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icon { width:38px; height:38px; border-radius:12px; background:var(--bg4); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--accent); font-size:16px; border:1px solid var(--b1); }
    .detail-lbl { font-size:10.5px; color:var(--t4); font-weight:700; text-transform:uppercase; letter-spacing:0.8px; margin-bottom: 3px; }
    .detail-val { font-size:14px; font-weight:700; color:var(--t1); line-height:1.5; }
    
    .ra-btn.sm { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:13px; }
    .w-100 { width: 100%; }
</style>

@endsection
