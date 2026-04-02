@extends('admin.layout.app')

@section('title', 'Order Details')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page">

        <div class="page-header" style="margin-bottom:24px;">
            <div>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:8px;">
                    <a href="{{ route('sale.orders.index') }}"
                        style="display:flex;align-items:center;gap:6px;font-size:13px;font-weight:700;color:var(--t3);text-decoration:none;transition:var(--transition);padding:6px 12px;background:var(--bg3);border:1px solid var(--b1);border-radius:10px;"
                        onmouseover="this.style.color='var(--accent)';this.style.borderColor='var(--accent)'" 
                        onmouseout="this.style.color='var(--t3)';this.style.borderColor='var(--b1)'">
                        <i class="bi bi-arrow-left"></i> All Orders
                    </a>
                </div>
                <h1 class="page-title" style="font-size:24px;font-weight:800;letter-spacing:-0.5px;">Order Details</h1>
                <p class="page-desc" style="color:var(--t3);font-size:14px;">Reviewing project scope and contact information for <strong>{{ $order->company_name }}</strong></p>
            </div>
            <div style="display:flex;gap:12px;align-items:center;">
                <a href="{{ route('sale.payments.create', $order->id) }}" class="btn-primary-solid" style="padding:10px 20px; border-radius:12px; font-weight:700;">
                    <i class="bi bi-wallet2"></i> Add Payment
                </a>
                <a href="{{ route('sale.orders.edit', $order->id) }}" class="btn-primary-solid" style="padding:10px 20px; border-radius:12px; font-weight:700; background:var(--bg3); border:1px solid var(--b1); color:var(--t1);">
                    <i class="bi bi-pencil-fill"></i> Edit Order
                </a>
            </div>
        </div>

        <div class="dash-grid" style="gap:24px;">
            
            {{-- Left Sidebar: Identity & Controls --}}
            <div class="span-4" style="display:flex;flex-direction:column;gap:24px;">
                
                {{-- Identity Card --}}
                <div class="dash-card" style="overflow:hidden;">
                    @php 
                        $emails = $order->emails ?? [];
                        $phones = $order->phones ?? [];
                        $initials = strtoupper(substr($order->company_name, 0, 1) . substr($order->client_name, 0, 1) ?: 'C');
                    @endphp

                    <div style="padding:32px 24px; text-align:center; background:linear-gradient(to bottom, var(--bg3), var(--bg2)); border-bottom:1px solid var(--b1);">
                        <div style="width:80px;height:80px;border-radius:24px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:800;color:#fff;margin:0 auto 16px;box-shadow:0 12px 30px rgba(99,102,241,.3), 0 0 0 4px var(--bg2);">{{ $initials }}</div>
                        <div style="font-size:20px;font-weight:800;color:var(--t1);letter-spacing:-.5px;line-height:1.2;">{{ $order->company_name }}</div>
                        <div style="font-size:13px;color:var(--t3);margin-top:6px;font-weight:500;">{{ $emails[0] ?? 'No primary email' }}</div>
                        <div style="margin-top:16px;display:flex;gap:6px; flex-wrap:wrap; justify-content:center;">
                            <!-- <span class="src-tag website-type" style="padding:4px 12px; border-radius:8px; font-size:11px; font-weight:700; background:var(--bg4); border:1px solid var(--b2);">{{ $order->is_marketing ? 'Marketing' : 'Website' }}</span> -->
                            <span style="font-size:11px;font-weight:700;padding:4px 12px;border-radius:8px;background:var(--accent-bg);color:var(--accent);border:1px solid var(--accent);">{{ $order->service->name ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div style="padding:16px 24px 24px;">
                        <div class="detail-row">
                            <i class="bi bi-person-fill detail-icn"></i>
                            <div>
                                <div class="detail-lbl">Contact Person</div>
                                <div class="detail-val">{{ $order->client_name }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <i class="bi bi-geo-alt-fill detail-icn"></i>
                            <div>
                                <div class="detail-lbl">Full Address</div>
                                <div class="detail-val" style="line-height:1.4;">{{ $order->full_address ?? 'N/A' }}</div>
                            </div>
                        </div>
                        <div class="detail-row">
                            <i class="bi bi-globe detail-icn"></i>
                            <div>
                                <div class="detail-lbl">Domain</div>
                                <div class="detail-val mono" style="color:var(--accent);">{{ $order->domain_name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="card-body" style="padding:16px;">
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($emails as $idx => $email)
                                    <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg3); padding:10px 14px; border-radius:10px; border:1px solid var(--b1);">
                                        <span style="font-size:13px; font-weight:600; color:var(--t2);">{{ $email }}</span>
                                        <a href="mailto:{{ $email }}" style="color:var(--t4); transition:0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t4)'">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="card-body" style="padding:16px;">
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($phones as $idx => $phone)
                                    <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg3); padding:10px 14px; border-radius:10px; border:1px solid var(--b1);">
                                        <span style="font-size:13px; font-weight:600; color:var(--t2);">{{ $phone['number'] }}</span>
                                        <a href="tel:{{ $phone['number'] }}" style="color:var(--t4); transition:0.2s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='var(--t4)'">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Update Card --}}
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px; border-bottom:1px solid var(--b1);">
                        <div class="card-title" style="font-size:14px;"><i class="bi bi-lightning-charge-fill" style="color:var(--accent);margin-right:6px;"></i>Quick Update</div>
                    </div>
                    <div class="card-body" style="padding:20px;">
                        <form action="{{ route('sale.orders.updateStatus', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <div style="display:flex; flex-direction:column; gap:16px;">
                                <div class="form-row">
                                    <label class="form-lbl" style="font-size:10px;">Order Status</label>
                                    <select name="status_id" class="form-inp" style="height:38px; font-size:13px; border-radius:10px;">
                                        @foreach($orderStatuses as $st)
                                            <option value="{{ $st->id }}" {{ $order->status_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl" style="font-size:10px;">Payment Terms</label>
                                    <select name="payment_terms_id" class="form-inp" style="height:38px; font-size:13px; border-radius:10px;">
                                        @foreach($paymentStatuses as $st)
                                            <option value="{{ $st->id }}" {{ $order->payment_terms_id == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                               <div style="display:flex;justify-content:flex-end;margin-top:20px;">
                                    <button type="submit" class="btn-primary-solid">
                                        <i class="bi bi-save"></i> Synchronize Updates
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Assigned Team --}}
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px; border-bottom:1px solid var(--b1);">
                        <div class="card-title" style="font-size:14px;"><i class="bi bi-people-fill" style="margin-right:6px;"></i>Assigned Team</div>
                    </div>
                    <div class="card-body" style="padding:16px 20px;">
                        <div style="display:flex; flex-direction:column; gap:12px;">
                            @forelse($order->assignments as $assign)
                                <div style="display:flex;align-items:center;gap:12px;background:var(--bg3);padding:10px;border-radius:12px;border:1px solid var(--b1);">
                                    <div style="width:36px;height:36px;border-radius:10px;background:var(--accent);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:800;color:#fff;">{{ strtoupper(substr($assign->sale->name, 0, 1)) }}</div>
                                    <div style="display:flex; flex-direction:column; gap:2px;">
                                        <span style="font-size:13.5px;font-weight:700;color:var(--t1);line-height:1;">{{ $assign->sale->name }}</span>
                                        <span style="font-size:10px;color:var(--t3);font-weight:600;text-transform:uppercase;letter-spacing:0.5px;">Sales Executive</span>
                                    </div>
                                </div>
                            @empty
                                <div style="text-align:center; color:var(--t4); font-size:12px; padding:10px;">No one assigned yet.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

            </div>

            {{-- Right Column: Stats & Notes --}}
            <div class="span-8" style="display:flex;flex-direction:column;gap:24px;">
                
                {{-- KPI Grid --}}
                <div style="display:grid; grid-template-columns: repeat(4, 1fr); gap:16px;">
                    <div class="dash-card" style="padding:20px; border-left:4px solid var(--accent);">
                        <div style="font-size:10px; font-weight:700; color:var(--t4); text-transform:uppercase; margin-bottom:8px;">Order Value</div>
                        <div style="font-size:18px; font-weight:800; color:var(--t1);">₹{{ number_format($order->order_value, 0) }}</div>
                    </div>
                    <div class="dash-card" style="padding:20px; border-left:4px solid {{ $order->status->color ?? 'var(--accent)' }};">
                        <div style="font-size:10px; font-weight:700; color:var(--t4); text-transform:uppercase; margin-bottom:8px;">Status</div>
                        <div style="font-size:18px; font-weight:800; color:{{ $order->status->color ?? 'var(--accent)' }};">{{ $order->status->name }}</div>
                    </div>
                    <div class="dash-card" style="padding:20px; border-left:4px solid #10b981;">
                        <div style="font-size:10px; font-weight:700; color:var(--t4); text-transform:uppercase; margin-bottom:8px;">Order Date</div>
                        <div style="font-size:18px; font-weight:800; color:var(--t1);">{{ $order->created_at->format('d M Y') }}</div>
                    </div>
                    <div class="dash-card" style="padding:20px; border-left:4px solid #f59e0b;">
                        <div style="font-size:10px; font-weight:700; color:var(--t4); text-transform:uppercase; margin-bottom:8px;">Delivery</div>
                        <div style="font-size:18px; font-weight:800; color:var(--t1);">{{ $order->delivery_date ? \Carbon\Carbon::parse($order->delivery_date)->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>

                {{-- Notes History - Main Section --}}
                <div class="dash-card" style="height:fit-content;">
                    @include('admin.orders._notes_history', ['order' => $order, 'routePrefix' => 'sale'])
                </div>

                {{-- Marketing Details --}}
                @if($order->is_marketing)
                <div class="dash-card">
                    <div class="card-head" style="padding:16px 20px; border-bottom:1px solid var(--b1); display:flex; justify-content:space-between; align-items:center;">
                        <div class="card-title" style="font-size:15px; font-weight:800; display:flex; align-items:center; gap:8px;">
                            <i class="bi bi-megaphone-fill" style="color:#8b5cf6;"></i> Marketing Account Details
                        </div>
                        <span class="src-tag" style="background:rgba(139, 92, 246, 0.1); color:#8b5cf6; border:1px solid #8b5cf6;">Marketing Active</span>
                    </div>
                    <div class="card-body" style="padding:24px;">
                        <div style="display:grid;grid-template-columns:repeat(auto-fit, minmax(200px, 1fr));gap:20px;">
                            <div class="mkt-info-box">
                                <span class="mkt-lbl">Plan / Package</span>
                                <span class="mkt-val">{{ $order->plan_name ?? 'N/A' }}</span>
                            </div>
                            <div class="mkt-info-box">
                                <span class="mkt-lbl">Username</span>
                                <span class="mkt-val mono">{{ $order->mkt_username ?? 'N/A' }}</span>
                            </div>
                            <div class="mkt-info-box">
                                <span class="mkt-lbl">Password</span>
                                <div style="display:flex; align-items:center; gap:8px;">
                                    <span id="mktPwVal" class="mkt-val mono">••••••••</span>
                                    <button type="button" onclick="toggleMktPw('{{ $order->mkt_password }}')" style="width:28px; height:28px; border-radius:6px; background:var(--bg3); display:flex; align-items:center; justify-content:center; color:var(--accent); transition:0.2s;">
                                        <i class="bi bi-eye-fill" id="mktPwIcon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mkt-info-box">
                                <span class="mkt-lbl">Start Date</span>
                                <span class="mkt-val">{{ $order->mkt_starting_date ? \Carbon\Carbon::parse($order->mkt_starting_date)->format('d M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Communication Directories --}}
                <!-- <div style="display:grid; grid-template-columns: 1fr 1fr; gap:20px;">
                    <div class="dash-card">
                        <div class="card-head" style="padding:14px 18px; border-bottom:1px solid var(--b1);">
                            <div class="card-title" style="font-size:11px; text-transform:uppercase; color:var(--accent);">Email Directory</div>
                        </div>
                        <div class="card-body" style="padding:16px;">
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($emails as $idx => $email)
                                    <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg3); padding:10px 14px; border-radius:10px; border:1px solid var(--b1);">
                                        <span style="font-size:13px; font-weight:600; color:var(--t2);">{{ $email }}</span>
                                        <a href="mailto:{{ $email }}" style="color:var(--t4); transition:0.2s;" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--t4)'">
                                            <i class="bi bi-envelope-fill"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="dash-card">
                        <div class="card-head" style="padding:14px 18px; border-bottom:1px solid var(--b1);">
                            <div class="card-title" style="font-size:11px; text-transform:uppercase; color:#10b981;">Phone Directory</div>
                        </div>
                        <div class="card-body" style="padding:16px;">
                            <div style="display:flex; flex-direction:column; gap:8px;">
                                @foreach($phones as $idx => $phone)
                                    <div style="display:flex; align-items:center; justify-content:space-between; background:var(--bg3); padding:10px 14px; border-radius:10px; border:1px solid var(--b1);">
                                        <span style="font-size:13px; font-weight:600; color:var(--t2);">{{ $phone['number'] }}</span>
                                        <a href="tel:{{ $phone['number'] }}" style="color:var(--t4); transition:0.2s;" onmouseover="this.style.color='#10b981'" onmouseout="this.style.color='var(--t4)'">
                                            <i class="bi bi-telephone-fill"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div> -->

            </div>
        </div>
    </div>
</main>

<style>
    .detail-row { display:flex; align-items:center; gap:16px; padding:16px 0; border-bottom:1px solid var(--b1); }
    .detail-row:last-child { border-bottom:none; }
    .detail-icn { width:38px; height:38px; border-radius:12px; background:var(--bg3); border:1px solid var(--b1); display:flex; align-items:center; justify-content:center; color:var(--t3); font-size:16px; flex-shrink:0; }
    .detail-lbl { font-size:9.5px; font-weight:700; color:var(--t4); text-transform:uppercase; letter-spacing:0.8px; margin-bottom:4px; }
    .detail-val { font-size:13.5px; font-weight:700; color:var(--t1); }

    .mkt-info-box { display:flex; flex-direction:column; gap:6px; padding:16px; background:var(--bg3); border:1px solid var(--b1); border-radius:14px; }
    .mkt-lbl { font-size:10px; font-weight:800; color:var(--t4); text-transform:uppercase; letter-spacing:0.5px; }
    .mkt-val { font-size:14px; font-weight:700; color:var(--t1); }
    .mkt-val.mono { font-family: var(--mono); color:var(--accent); }
</style>

<script>
    let mktPwVisible = false;
    function toggleMktPw(pw) {
        mktPwVisible = !mktPwVisible;
        document.getElementById('mktPwVal').textContent = mktPwVisible ? pw : '••••••••';
        document.getElementById('mktPwIcon').className  = mktPwVisible ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill';
    }
</script>

@include('admin.orders._notes_assets', ['routePrefix' => 'sale'])


{{-- Email Choice Modal --}}
<div class="modal fade" id="emailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--bg2); border:1px solid var(--b2); border-radius:16px; box-shadow:var(--shadow-lg);">
            <div class="modal-header" style="border-bottom:1px solid var(--b1); padding:20px;">
                <h5 class="modal-title" style="font-size:18px; font-weight:800; color:var(--t1);">Choose Contact Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter:var(--close-filter);"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach($order->emails as $email)
                        <a href="mailto:{{ $email }}" class="btn-primary-ghost" style="justify-content:space-between; padding:14px 20px; border-radius:12px; background:var(--bg3);">
                            <span style="font-weight:700;">{{ $email }}</span>
                            <i class="bi bi-envelope-at-fill" style="color:var(--accent);"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer" style="border:none; padding:0 20px 20px;">
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal" style="width:100%; border:1px solid var(--b1); color:var(--t3); font-weight:700; border-radius:10px;">Close Window</button>
            </div>
        </div>
    </div>
</div>

{{-- Phone Choice Modal --}}
<div class="modal fade" id="phoneModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:var(--bg2); border:1px solid var(--b2); border-radius:16px; box-shadow:var(--shadow-lg);">
            <div class="modal-header" style="border-bottom:1px solid var(--b1); padding:20px;">
                <h5 class="modal-title" style="font-size:18px; font-weight:800; color:var(--t1);">Choose Contact Phone</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="filter:var(--close-filter);"></button>
            </div>
            <div class="modal-body" style="padding:20px;">
                <div style="display:flex; flex-direction:column; gap:12px;">
                    @foreach($order->phones as $phone)
                        <a href="tel:{{ $phone['number'] }}" class="btn-primary-ghost" style="justify-content:space-between; padding:14px 20px; border-radius:12px; background:var(--bg3);">
                            <span style="font-weight:700;">{{ $phone['number'] }}</span>
                            <i class="bi bi-telephone-fill" style="color:#10b981;"></i>
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="modal-footer" style="border:none; padding:0 20px 20px;">
                <button type="button" class="btn btn-ghost" data-bs-dismiss="modal" style="width:100%; border:1px solid var(--b1); color:var(--t3); font-weight:700; border-radius:10px;">Close Window</button>
            </div>
        </div>
    </div>
</div>

    </div> {{-- End page --}}
</main> {{-- End page-area --}}

@endsection
