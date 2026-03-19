@extends('sale.layout.app')

@section('title', 'Create Order')

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
                <h1 class="page-title">Create New Order</h1>
                <p class="page-desc">Fill in the details below to create a new order</p>
            </div>
        </div>

        <form action="" method="POST">
            @csrf

            <div class="dash-grid">

                {{-- LEFT — 8 --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Order Info --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-bag-fill" style="color:var(--accent);margin-right:6px;"></i>Order Information</div>
                            <div class="card-sub">Client details and order specifics</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="company_name" class="form-inp" placeholder="Company name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="client_name" class="form-inp" placeholder="Full name" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Email</label>
                                    <input type="email" name="email" class="form-inp" placeholder="email@company.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Phone</label>
                                    <input type="tel" name="phone" class="form-inp" placeholder="+91 XXXXX XXXXX">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name</label>
                                    <input type="text" name="domain_name" class="form-inp" placeholder="example.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Service / Product</label>
                                    <input type="text" name="service" class="form-inp" placeholder="What are we delivering?">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Order Value <span style="color:#ef4444">*</span></label>
                                    <input type="text" name="order_value" class="form-inp" placeholder="₹ Amount" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Terms</label>
                                    <select name="payment_terms" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option>Full Advance</option>
                                        <option>50-50</option>
                                        <option>Milestone</option>
                                        <option>Net 30</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Delivery Date</label>
                                    <input type="date" name="delivery_date" class="form-inp">
                                </div>

                                {{-- Assign Sales — multi-select --}}
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Assign Sales Person</label>
                                    <div class="ms-wrap" id="salesWrap">
                                        <div class="ms-trigger" onclick="toggleMs('salesWrap')">
                                            <div class="ms-pills"><span class="ms-placeholder">Select sales members…</span></div>
                                            <i class="bi bi-chevron-down ms-arrow"></i>
                                        </div>
                                        <div class="ms-dropdown" id="salesDropdown">
                                            <div class="ms-search-wrap">
                                                <i class="bi bi-search"></i>
                                                <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'salesDropdown')">
                                            </div>
                                            <div class="ms-opts">
                                                @php
                                                $salesMembers = [
                                                ['initials'=>'RK','name'=>'Rahul Kumar','role'=>'Sales Lead','bg'=>'linear-gradient(135deg,#6366f1,#06b6d4)'],
                                                ['initials'=>'PS','name'=>'Priya Sharma','role'=>'Sales Executive','bg'=>'linear-gradient(135deg,#ec4899,#f59e0b)'],
                                                ['initials'=>'NK','name'=>'Neha Kapoor','role'=>'Business Dev','bg'=>'linear-gradient(135deg,#10b981,#06b6d4)'],
                                                ['initials'=>'AS','name'=>'Arjun Singh','role'=>'Account Manager','bg'=>'linear-gradient(135deg,#8b5cf6,#ec4899)'],
                                                ['initials'=>'RM','name'=>'Ravi Mehta','role'=>'Sales Executive','bg'=>'linear-gradient(135deg,#f59e0b,#ef4444)'],
                                                ['initials'=>'KR','name'=>'Kiran Rao','role'=>'Sales Associate','bg'=>'linear-gradient(135deg,#14b8a6,#6366f1)'],
                                                ];
                                                @endphp
                                                @foreach($salesMembers as $m)
                                                <label class="ms-opt">
                                                    <input type="checkbox" name="sales_person[]" value="{{ $m['name'] }}" onchange="updateMs('salesWrap')">
                                                    <span class="ms-ava" style="background:{{ $m['bg'] }}">{{ $m['initials'] }}</span>
                                                    <div>
                                                        <div style="font-size:12.5px;font-weight:600;">{{ $m['name'] }}</div>
                                                        <div style="font-size:10.5px;color:var(--t3);">{{ $m['role'] }}</div>
                                                    </div>
                                                </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-geo-alt-fill" style="color:#06b6d4;margin-right:6px;"></i>Address</div>
                            <div class="card-sub">Client location details</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">City</label>
                                    <input type="text" name="city" class="form-inp" placeholder="City">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Region / State</label>
                                    <input type="text" name="state" class="form-inp" placeholder="State or Province">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Zip Code</label>
                                    <input type="text" name="zip_code" class="form-inp" placeholder="Zip / PIN">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Street address…"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Marketing --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div style="display:flex;align-items:center;gap:10px;width:100%;">
                                <div>
                                    <div class="card-title"><i class="bi bi-megaphone-fill" style="color:#8b5cf6;margin-right:6px;"></i>Marketing Order Details</div>
                                    <div class="card-sub">Fill only for marketing orders</div>
                                </div>
                                <label style="margin-left:auto;display:flex;align-items:center;gap:7px;cursor:pointer;font-size:12.5px;font-weight:600;color:var(--t3);">
                                    <input type="checkbox" id="mktToggle" onchange="toggleMktSection()" style="accent-color:var(--accent);width:14px;height:14px;">
                                    Enable
                                </label>
                            </div>
                        </div>
                        <div class="card-body" id="mktBody" style="display:none;">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="mkt_payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option>Pending</option>
                                        <option>Paid</option>
                                        <option>Overdue</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="mkt_starting_date" class="form-inp">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-inp" placeholder="Plan name">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="mkt_username" class="form-inp" placeholder="Account username">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="mkt_password" class="form-inp" placeholder="Account password">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT — 4 --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    <div class="dash-card" style="position:sticky;top:80px;overflow:visible;">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-send-fill" style="color:#10b981;margin-right:6px;"></i>Submit Order</div>
                            <div class="card-sub">Review and create</div>
                        </div>
                        <div class="card-body">

                            {{-- Order type --}}
                            <div style="background:var(--bg3);border:1px solid var(--b1);border-radius:var(--r-sm);padding:12px 14px;margin-bottom:16px;">
                                <div style="font-size:10.5px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;color:var(--t3);margin-bottom:8px;">Order Type</div>
                                <div style="display:flex;flex-direction:column;gap:6px;">
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;color:var(--t2);">
                                        <input type="radio" name="order_type" value="website" checked style="accent-color:var(--accent);">
                                        <i class="bi bi-globe2" style="color:#06b6d4;"></i> Website Order
                                    </label>
                                    <label style="display:flex;align-items:center;gap:8px;cursor:pointer;font-size:13px;font-weight:500;color:var(--t2);">
                                        <input type="radio" name="order_type" value="marketing" style="accent-color:var(--accent);">
                                        <i class="bi bi-megaphone-fill" style="color:#8b5cf6;"></i> Marketing Order
                                    </label>
                                </div>
                            </div>

                            {{-- Order Status --}}
                            <div class="form-row" style="margin-bottom:16px;">
                                <label class="form-lbl">Order Status</label>
                                <select name="order_status" class="form-inp">
                                    <option value="">— Select —</option>
                                    @foreach(['Pending','In Progress','Completed','On Hold','Cancelled'] as $st)
                                    <option {{ old('order_status', $order->order_status ?? '') === $st ? 'selected' : '' }}>{{ $st }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Assign Developer — multi-select --}}
                            <div class="form-row" style="margin-bottom:16px;">
                                <label class="form-lbl">Assign Developer</label>
                                <div class="ms-wrap" id="devWrap">
                                    <div class="ms-trigger" onclick="toggleMs('devWrap')">
                                        <div class="ms-pills"><span class="ms-placeholder">Select developers…</span></div>
                                        <i class="bi bi-chevron-down ms-arrow"></i>
                                    </div>
                                    <div class="ms-dropdown" id="devDropdown">
                                        <div class="ms-search-wrap">
                                            <i class="bi bi-search"></i>
                                            <input type="text" class="ms-search" placeholder="Search…" oninput="filterMs(this,'devDropdown')">
                                        </div>
                                        <div class="ms-opts">
                                            @php
                                            $devMembers = [
                                            ['initials'=>'RK','name'=>'Rahul Kumar','role'=>'Backend','bg'=>'linear-gradient(135deg,#6366f1,#06b6d4)'],
                                            ['initials'=>'PS','name'=>'Priya Sharma','role'=>'Frontend','bg'=>'linear-gradient(135deg,#ec4899,#f59e0b)'],
                                            ['initials'=>'NK','name'=>'Neha Kapoor','role'=>'Figma Designer','bg'=>'linear-gradient(135deg,#10b981,#06b6d4)'],
                                            ['initials'=>'AS','name'=>'Arjun Singh','role'=>'UI/UX','bg'=>'linear-gradient(135deg,#8b5cf6,#ec4899)'],
                                            ['initials'=>'RM','name'=>'Ravi Mehta','role'=>'Backend','bg'=>'linear-gradient(135deg,#f59e0b,#ef4444)'],
                                            ['initials'=>'KR','name'=>'Kiran Rao','role'=>'Frontend','bg'=>'linear-gradient(135deg,#14b8a6,#6366f1)'],
                                            ];
                                            @endphp
                                            @foreach($devMembers as $m)
                                            <label class="ms-opt">
                                                <input type="checkbox" name="developer[]" value="{{ $m['name'] }}" onchange="updateMs('devWrap')">
                                                <span class="ms-ava" style="background:{{ $m['bg'] }}">{{ $m['initials'] }}</span>
                                                <div>
                                                    <div style="font-size:12.5px;font-weight:600;">{{ $m['name'] }}</div>
                                                    <div style="font-size:10.5px;color:var(--t3);">{{ $m['role'] }}</div>
                                                </div>
                                            </label>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div style="display:flex;flex-direction:column;gap:8px;">
                                <button type="submit" class="btn-primary-solid" style="width:100%;justify-content:center;padding:11px;">
                                    <i class="bi bi-plus-lg"></i> Create Order
                                </button>
                                <a href="{{ route('sale.orders.index') }}" class="btn-ghost" style="width:100%;justify-content:center;padding:10px;">
                                    Cancel
                                </a>
                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>

@include('sale.orders.multiselect-assets')

<script>
    function toggleMktSection() {
        document.getElementById('mktBody').style.display =
            document.getElementById('mktToggle').checked ? 'block' : 'none';
    }
</script>

@endsection