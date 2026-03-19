@extends('sale.layout.app')

@section('title', 'Add Project')

@section('content')

<main class="page-area" id="pageArea">
    <div class="page" id="page-add-project">

        <!-- Page Header -->
        <div class="page-header">
            <div>
                <h1 class="page-title">Add New Project</h1>
                <p class="page-desc">Fill in the details below to create a new project</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('sale.projects.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to Projects
                </a>
            </div>
        </div>

        <form action="" method="POST">
            @csrf
            <div class="dash-grid">

                {{-- ══ LEFT COL — 8 spans ══ --}}
                <div class="span-8" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Basic Information --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-person-vcard-fill" style="color:var(--accent);margin-right:6px;"></i>Basic Information</div>
                                <div class="card-sub">Client and account details</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Project Name / Domain *</label>
                                    <input type="text" name="project_name" class="form-inp" placeholder="e.g. novatech.io">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Name *</label>
                                    <input type="text" name="client_name" class="form-inp" placeholder="Full name">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Email ID</label>
                                    <input type="email" name="email" class="form-inp" placeholder="email@company.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Phone Number</label>
                                    <input type="tel" name="phone" class="form-inp" placeholder="+91 XXXXX XXXXX">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="company_name" class="form-inp" placeholder="Company name">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Starting Date</label>
                                    <input type="date" name="starting_date" class="form-inp">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Plan Name</label>
                                    <input type="text" name="plan_name" class="form-inp" placeholder="e.g. dynamick">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Payment Status</label>
                                    <select name="payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option>Pending</option>
                                        <option>Partial</option>
                                        <option>Paid</option>
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Username</label>
                                    <input type="text" name="username" class="form-inp" placeholder="Account username">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Password</label>
                                    <input type="text" name="password" class="form-inp" placeholder="Account password">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">No. of Mail IDs</label>
                                    <input type="number" name="no_of_mail_ids" class="form-inp" placeholder="e.g. 5" min="0">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Mail Password</label>
                                    <input type="text" name="mail_password" class="form-inp" placeholder="Mail password">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Domain, Server Book</label>
                                    <input type="text" name="domain_server_book" class="form-inp" placeholder="Domain registrar, server details, control panel…">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Full Address</label>
                                    <textarea name="full_address" class="form-inp" rows="2" placeholder="Street address, city, state, PIN…"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Website Project Details --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-globe2" style="color:#06b6d4;margin-right:6px;"></i>Website Project Details</div>
                                <div class="card-sub">Technical specifications for the website</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Domain Name</label>
                                    <input type="text" name="domain_name" class="form-inp" placeholder="example.com">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Hosting Provider</label>
                                    <input type="text" name="hosting_provider" class="form-inp" placeholder="Hostinger, GoDaddy, AWS…">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">CMS / Platform</label>
                                    <select name="cms_platform" class="form-inp" id="cmsSelect" onchange="toggleCustomCms()">
                                        <option value="">— Select —</option>
                                        <option>WordPress</option>
                                        <option>Shopify</option>
                                        <option>Custom</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <div class="form-row" id="customCmsRow" style="display:none;">
                                    <label class="form-lbl">Specify Platform</label>
                                    <input type="text" name="cms_custom" class="form-inp" placeholder="e.g. Wix, Webflow, Laravel…">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Number of Pages</label>
                                    <input type="number" name="no_of_pages" class="form-inp" placeholder="e.g. 10" min="1">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Website Payment Status</label>
                                    <select name="website_payment_status" class="form-inp">
                                        <option value="">— Select —</option>
                                        <option>Pending</option>
                                        <option>Partial</option>
                                        <option>Paid</option>
                                    </select>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Required Features</label>
                                    <textarea name="required_features" class="form-inp" rows="2" placeholder="Login, payment gateway, product catalogue, blog, multilingual…"></textarea>
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Reference Websites</label>
                                    <input type="text" name="reference_websites" class="form-inp" placeholder="https://example1.com, https://example2.com">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Communication & Tracking --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-chat-dots-fill" style="color:#10b981;margin-right:6px;"></i>Communication & Tracking</div>
                                <div class="card-sub">Feedback and internal notes</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Last Update Date</label>
                                    <input type="date" name="last_update_date" class="form-inp">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client Feedback</label>
                                    <input type="text" name="client_feedback" class="form-inp" placeholder="Client feedback summary">
                                </div>
                                <div class="form-row" style="grid-column:1/-1">
                                    <label class="form-lbl">Internal Notes</label>
                                    <textarea name="internal_notes" class="form-inp" rows="3" placeholder="Internal notes visible only to the team…"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- ══ RIGHT COL — 4 spans ══ --}}
                <div class="span-4" style="display:flex;flex-direction:column;gap:16px;">

                    {{-- Project Timeline --}}
                    <div class="dash-card" style="position:sticky;top:80px;">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-calendar3" style="color:#f59e0b;margin-right:6px;"></i>Project Timeline</div>
                                <div class="card-sub">Schedule and status</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Project Start Date</label>
                                <input type="date" name="project_start_date" class="form-inp">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Expected Delivery Date</label>
                                <input type="date" name="expected_delivery_date" class="form-inp">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Actual Delivery Date</label>
                                <input type="date" name="actual_delivery_date" class="form-inp">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Project Status</label>
                                <select name="project_status" class="form-inp">
                                    <option value="">— Select —</option>
                                    <option>New</option>
                                    <option>Design Phase</option>
                                    <option>Development</option>
                                    <option>Testing</option>
                                    <option>Completed</option>
                                    <option>On Hold</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Financial Fields --}}
                    <div class="dash-card">
                        <div class="card-head">
                            <div>
                                <div class="card-title"><i class="bi bi-currency-rupee" style="color:#8b5cf6;margin-right:6px;"></i>Financial Fields</div>
                                <div class="card-sub">Pricing & payment info</div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-row">
                                <label class="form-lbl">Project Price *</label>
                                <input type="number" name="project_price" id="projectPrice" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Advance Payment</label>
                                <input type="number" name="advance_payment" id="advancePayment" class="form-inp" placeholder="₹ Amount" oninput="calcRemaining()">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Remaining Amount</label>
                                <div style="padding:9px 12px;background:var(--bg4);border:1px solid var(--b1);border-radius:var(--r-sm);font-size:13px;font-weight:700;color:#ef4444;font-family:var(--mono);" id="remainingDisplay">₹ 0</div>
                                <input type="hidden" name="remaining_amount" id="remainingHidden" value="0">
                            </div>
                            <div class="form-row">
                                <label class="form-lbl">Payment Status</label>
                                <select name="financial_payment_status" class="form-inp">
                                    <option value="">— Select —</option>
                                    <option>Pending</option>
                                    <option>Partial</option>
                                    <option>Paid</option>
                                </select>
                            </div>
                            <div class="form-row" style="margin-bottom:0;">
                                <label class="form-lbl">Invoice Number</label>
                                <input type="text" name="invoice_number" class="form-inp" placeholder="INV-XXXX">
                            </div>
                        </div>
                    </div>

                    {{-- Submit --}}
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        <button type="submit" class="btn-primary-solid" style="justify-content:center;width:100%;padding:11px;">
                            <i class="bi bi-plus-lg"></i> Create Project
                        </button>
                        <a href="{{ route('sale.projects.index') }}" class="btn-ghost" style="justify-content:center;width:100%;padding:10px;">
                            Cancel
                        </a>
                    </div>

                </div>

            </div>
        </form>

    </div>
</main>

<script>
    function calcRemaining() {
        const price = parseFloat(document.getElementById('projectPrice').value) || 0;
        const advance = parseFloat(document.getElementById('advancePayment').value) || 0;
        const rem = Math.max(0, price - advance);
        document.getElementById('remainingDisplay').textContent = '₹ ' + rem.toLocaleString('en-IN');
        document.getElementById('remainingHidden').value = rem;
    }

    function toggleCustomCms() {
        const val = document.getElementById('cmsSelect').value;
        document.getElementById('customCmsRow').style.display = val === 'other' ? 'flex' : 'none';
    }

    // Set today as default for dates
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date().toISOString().split('T')[0];
        document.querySelector('input[name="project_start_date"]').value = today;
        document.querySelector('input[name="last_update_date"]').value = today;
    });
</script>

@endsection