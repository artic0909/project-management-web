@extends('admin.layout.app')

@section('title', 'Order Details')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('sale.orders.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Orders
                    </a>
                </div>
                <h1 class="page-title">Order Details</h1>
                <p class="page-desc">Viewing details for <strong>{{ $order->company_name }}</strong></p>
            </div>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('sale.payments.create', $order->id) }}" class="btn-primary-solid sm">
                    <i class="bi bi-wallet2"></i> Add Payment
                </a>
                <a href="{{ route('sale.orders.edit', $order->id) }}" class="btn-primary-solid sm">
                    <i class="bi bi-pencil-fill"></i> Edit Order
                </a>
            </div>
        </div>

        <div class="dash-grid">
            
            {{-- Left Column: Identity & Contact (Leads Style) --}}
            <div class="dash-card span-4" style="height:fit-content;">
                <div class="card-head">
                    <div class="card-title">Identity & Contact</div>
                </div>
                <div class="card-body" style="padding:0 18px 24px;">
                    @php 
                        $emails = $order->emails ?? [];
                        $phones = $order->phones ?? [];
                        $initials = strtoupper(substr($order->company_name, 0, 1) . substr($order->client_name, 0, 1));
                    @endphp

                    <div style="display:flex;flex-direction:column;align-items:center;padding:24px 0 20px;border-bottom:1px solid var(--b1);text-align:center;">
                        <div style="width:72px;height:72px;border-radius:20px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:26px;font-weight:800;color:#fff;margin-bottom:14px;box-shadow:0 8px 30px rgba(99,102,241,.3);">{{ $initials }}</div>
                        <div style="font-size:19px;font-weight:800;color:var(--t1);letter-spacing:-.4px;">{{ $order->company_name }}</div>
                        <div style="font-size:13px;color:var(--t3);margin-top:4px;">{{ $emails[0] ?? 'No primary email' }}</div>
                        <div style="margin-top:12px;display:flex;gap:6px; flex-wrap:wrap; justify-content:center;">
                            <span class="src-tag website-type" style="padding:3px 10px; border-radius:6px; font-size:10.5px; font-weight:700;">{{ $order->is_marketing ? 'Marketing' : 'Website' }}</span>
                            <span style="font-size:11px;font-weight:700;padding:3px 10px;border-radius:6px;background:rgba(99,102,241,.1);color:var(--accent);">{{ $order->service->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:4px;margin-top:16px;">
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-person-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $order->client_name }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-geo-alt-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Full Address</div>
                                <div class="detail-val">{{ $order->full_address ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-globe"></i></div>
                            <div>
                                <div class="detail-lbl">Domain</div>
                                <div class="detail-val">{{ $order->domain_name ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <div class="detail-icon"><i class="bi bi-person-badge-fill"></i></div>
                            <div>
                                <div class="detail-lbl">Created By</div>
                                <div class="detail-val">
                                    @if($order->createdBy instanceof \App\Models\Admin)
                                        <span>System</span>
                                    @elseif($order->createdBy)
                                        <span>{{ $order->createdBy->name }}</span>
                                        <div style="font-size:10px; color:var(--t3)">{{ $order->createdBy->email }}</div>
                                    @else
                                        <span>System</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right Column: Actions & Details (span-8) --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">
                
                {{-- KPI Strip --}}
                <div class="dash-card">
                    <div class="card-body">
                        <div class="detail-kpis">
                            <div class="dk-item">
                                <div class="dk-val">₹{{ number_format($order->order_value, 2) }}</div>
                                <div class="dk-lbl">Order Value</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:{{ $order->status->color ?? 'var(--accent)' }};">{{ $order->status->name }}</div>
                                <div class="dk-lbl">Status</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val">{{ $order->created_at->format('d M Y') }}</div>
                                <div class="dk-lbl">Order Date</div>
                            </div>
                            <div class="dk-item">
                                <div class="dk-val" style="color:var(--t2);">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : 'N/A' }}</div>
                                <div class="dk-lbl">Delivery</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Row for quick Communication & Assigned Team --}}
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="dash-card" style="padding:20px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px;">Communication</div>
                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @if(count($emails) > 1)
                                <button class="btn-primary-ghost" style="width:100%; justify-content:center;" onclick="openModal('emailModal')">
                                    <i class="bi bi-envelope"></i> Choose Email ({{ count($emails) }})
                                </button>
                            @else
                                <a href="mailto:{{ $emails[0] ?? '' }}" class="btn-primary-ghost" style="width:100%; justify-content:center;">
                                    <i class="bi bi-envelope"></i> Send Primary Email
                                </a>
                            @endif

                            @if(count($phones) > 1)
                                <button class="btn-primary-ghost" style="width:100%; justify-content:center;" onclick="openModal('phoneModal')">
                                    <i class="bi bi-telephone"></i> Choose Phone ({{ count($phones) }})
                                </button>
                            @else
                                <a href="tel:{{ $phones[0]['number'] ?? '' }}" class="btn-primary-ghost" style="width:100%; justify-content:center;">
                                    <i class="bi bi-telephone"></i> Call Primary Number
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="dash-card" style="padding:20px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;letter-spacing:1px;">Assigned Team</div>
                        <div style="display:flex; flex-direction:column; gap:10px;">
                            @foreach($order->assignments as $assign)
                                <div style="display:flex;align-items:center;gap:10px;">
                                    <div style="width:32px;height:32px;border-radius:50%;background:var(--bg4);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:800;color:var(--t2);">{{ strtoupper(substr($assign->sale->name, 0, 1)) }}</div>
                                    <div style="display:flex; flex-direction:column;">
                                        <span style="font-size:13px;font-weight:700;color:var(--t1);">{{ $assign->sale->name }}</span>
                                        <span style="font-size:11px;color:var(--t3);">{{ $assign->sale->email }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- QUICK UPDATE CARD --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div>
                            <div class="card-title">Quick Status Update</div>
                            <div class="card-sub">Fast update status and payment terms</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sale.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div class="form-grid" style="display:grid; grid-template-columns: repeat(2, 1fr); gap:16px;">
                                <div class="form-row">
                                    <label class="form-lbl">Order Status</label>
                                    <select name="status_id" class="form-inp">
                                        @foreach($orderStatuses as $st)
                                            <option value="{{ $st->id }}" {{ $order->status_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Terms</label>
                                    <select name="payment_terms_id" class="form-inp">
                                        @foreach($paymentStatuses as $st)
                                            <option value="{{ $st->id }}" {{ $order->payment_terms_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @if($order->is_marketing)
                                <div class="form-row">
                                    <label class="form-lbl">Mkt Payment Status</label>
                                    <select name="mkt_payment_status_id" class="form-inp">
                                        @foreach($paymentStatuses as $st)
                                            <option value="{{ $st->id }}" {{ $order->mkt_payment_status_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                @endif
                                <div class="form-row" style="grid-column: 1/-1; display:flex; justify-content:flex-end; margin-bottom:0;">
                                    <button type="submit" class="btn-primary-solid">Update Status</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Marketing Details (Only if applicable) --}}
                @if($order->is_marketing)
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title"><i class="bi bi-megaphone-fill" style="color:#8b5cf6;margin-right:6px;"></i>Marketing Details</div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="od-row">
                                <span class="od-lbl">Plan Name</span>
                                <span class="od-val">{{ $order->plan_name ?? 'N/A' }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Username</span>
                                <span class="od-val mono">{{ $order->mkt_username ?? 'N/A' }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Password</span>
                                <span class="od-val">
                                    <span id="mktPwVal">••••••••</span>
                                    <button type="button" onclick="toggleMktPw('{{ $order->mkt_password }}')" style="background:none;border:none;color:var(--accent);cursor:pointer;margin-left:8px;">
                                        <i class="bi bi-eye-fill" id="mktPwIcon"></i>
                                    </button>
                                </span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Starting Date</span>
                                <span class="od-val">{{ $order->mkt_starting_date ? \Carbon\Carbon::parse($order->mkt_starting_date)->format('d M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ALL CONTACT POINTS --}}
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:16px;">
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;">Email Directory</div>
                        @foreach($emails as $email)
                            <div style="font-size:13px; color:var(--t2); padding:8px 10px; background:var(--bg3); border-radius:8px; border:1px solid var(--b1); margin-bottom:6px; display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-envelope-at" style="color:var(--t3)"></i> {{ $email }}
                            </div>
                        @endforeach
                    </div>
                    <div class="dash-card" style="padding:18px;">
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;color:var(--t4);margin-bottom:12px;">Phone Directory</div>
                        @foreach($phones as $phone)
                            <div style="font-size:13px; color:var(--t2); padding:8px 10px; background:var(--bg3); border-radius:8px; border:1px solid var(--b1); margin-bottom:6px; display:flex; align-items:center; gap:8px;">
                                <i class="bi bi-telephone-outbound" style="color:var(--t3)"></i> {{ $phone['number'] }}
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>

<style>
    .detail-kpis {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
    }
    .dk-item {
        text-align: center;
    }
    .dk-val {
        font-size: 18px;
        font-weight: 800;
        color: var(--t1);
        margin-bottom: 4px;
    }
    .dk-lbl {
        font-size: 11px;
        font-weight: 600;
        color: var(--t3);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .od-row {
        display: flex;
        flex-direction: column;
        gap: 4px;
        padding: 10px;
        background: var(--bg3);
        border: 1px solid var(--b1);
        border-radius: var(--r-sm);
    }
    .od-lbl {
        font-size: 10px;
        font-weight: 700;
        color: var(--t4);
        text-transform: uppercase;
    }
    .od-val {
        font-size: 13.5px;
        font-weight: 500;
        color: var(--t1);
    }
    .detail-row { display:flex; align-items:center; gap:12px; padding:11px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icon { width:34px; height:34px; border-radius:10px; background:var(--bg4); display:flex; align-items:center; justify-content:center; flex-shrink:0; color:var(--t3); font-size:15px; }
    .detail-lbl { font-size:10px; color:var(--t4); font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-bottom: 2px; }
    .detail-val { font-size:13px; font-weight:700; color:var(--t1); }

    .mkt-section .form-grid { padding: 14px; background: var(--bg3); }
</style>

<script>
    let mktPwVisible = false;
    function toggleMktPw(pw) {
        mktPwVisible = !mktPwVisible;
        document.getElementById('mktPwVal').textContent = mktPwVisible ? pw : '••••••••';
        document.getElementById('mktPwIcon').className  = mktPwVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
    }
</script>


{{-- Email Choice Modal --}}
<div class="modal-backdrop" id="emailModal">
    <div class="modal-box">
        <div class="modal-hd">
            <span>Choose Email</span>
            <button class="modal-close" onclick="closeModal('emailModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($order->emails as $email)
                    <a href="mailto:{{ $email }}" class="btn-primary-ghost" style="justify-content:space-between; padding:12px 16px;">
                        <span>{{ $email }}</span>
                        <i class="bi bi-envelope-at-fill"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

{{-- Phone Choice Modal --}}
<div class="modal-backdrop" id="phoneModal">
    <div class="modal-box">
        <div class="modal-hd">
            <span>Choose Phone Number</span>
            <button class="modal-close" onclick="closeModal('phoneModal')"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-bd">
            <div style="display:flex; flex-direction:column; gap:10px;">
                @foreach($order->phones as $phone)
                    <a href="tel:{{ $phone['number'] }}" class="btn-primary-ghost" style="justify-content:space-between; padding:12px 16px;">
                        <span>{{ $phone['number'] }}</span>
                        <i class="bi bi-telephone-fill"></i>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>

@endsection
