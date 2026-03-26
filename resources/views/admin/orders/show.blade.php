@extends('admin.layout.app')

@section('title', 'Order Details')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px;">
                    <a href="{{ route('admin.orders.index') }}"
                        style="display:flex;align-items:center;gap:5px;font-size:13px;font-weight:600;color:var(--t3);text-decoration:none;transition:var(--transition);"
                        onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t3)'">
                        <i class="bi bi-arrow-left"></i> All Orders
                    </a>
                </div>
                <h1 class="page-title">Order Details</h1>
                <p class="page-desc">Viewing details for <strong>{{ $order->company_name }}</strong></p>
            </div>
            <div style="display:flex;gap:10px;">
                <a href="{{ route('admin.orders.edit', $order->id) }}" class="btn-primary-solid sm">
                    <i class="bi bi-pencil-fill"></i> Edit Order
                </a>
            </div>
        </div>

        <div class="dash-grid">
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

                {{-- Order Info --}}
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">General Information</div>
                    </div>
                    <div class="card-body">
                        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                            <div class="od-row">
                                <span class="od-lbl">Company Name</span>
                                <span class="od-val">{{ $order->company_name }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Client Name</span>
                                <span class="od-val">{{ $order->client_name }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Emails</span>
                                <span class="od-val">
                                    @foreach($order->emails ?? [] as $email)
                                        <div>{{ $email }}</div>
                                    @endforeach
                                </span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Phones</span>
                                <span class="od-val">
                                    @foreach($order->phones ?? [] as $phone)
                                        <div>{{ $phone['number'] }}</div>
                                    @endforeach
                                </span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Domain</span>
                                <span class="od-val">{{ $order->domain_name ?? 'N/A' }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Service</span>
                                <span class="od-val">{{ $order->service->name ?? 'N/A' }}</span>
                            </div>
                            <div class="od-row">
                                <span class="od-lbl">Payment Terms</span>
                                <span class="od-val">{{ $order->paymentTerms->name ?? 'N/A' }}</span>
                            </div>
                            <div class="od-row" style="grid-column: 1/-1;">
                                <span class="od-lbl">Full Address</span>
                                <span class="od-val">{{ $order->full_address ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Marketing Details --}}
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
                                <span class="od-lbl">Payment Status</span>
                                <span class="od-val">{{ $order->mktPaymentStatus->name ?? 'N/A' }}</span>
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
            </div>

            <div class="span-4">
                <div class="dash-card">
                    <div class="card-head">
                        <div class="card-title">Assigned Personnel</div>
                    </div>
                    <div class="card-body">
                        <div style="display:flex;flex-direction:column;gap:12px;">
                            @forelse($order->assignments as $assign)
                                <div style="display:flex;align-items:center;gap:10px;padding:8px;background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);">
                                    @php 
                                        $initials = strtoupper(substr($assign->sale->name, 0, 2)); 
                                        $colors = ['#6366f1','#ec4899','#10b981','#f59e0b','#ef4444','#8b5cf6'];
                                        $bg = $colors[$assign->sale->id % count($colors)];
                                    @endphp
                                    <div style="width:36px;height:36px;border-radius:50%;background:{{ $bg }};display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:12px;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div style="font-size:13px;font-weight:600;color:var(--t1);">{{ $assign->sale->name }}</div>
                                        <div style="font-size:11px;color:var(--t3);">{{ $assign->sale->email }}</div>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center;padding:20px;color:var(--t4);font-size:13px;">No personnel assigned.</div>
                            @endforelse
                        </div>
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
</style>

<script>
    let mktPwVisible = false;
    function toggleMktPw(pw) {
        mktPwVisible = !mktPwVisible;
        document.getElementById('mktPwVal').textContent = mktPwVisible ? pw : '••••••••';
        document.getElementById('mktPwIcon').className  = mktPwVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
    }
</script>

@endsection
