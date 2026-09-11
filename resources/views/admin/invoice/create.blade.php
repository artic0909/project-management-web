@extends('admin.layout.app')

@section('title', 'Create Invoice')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

<style>
    /* ─── INVOICE RESPONSIVE & COMPONENT STYLING ─── */
    .invoice-form-layout {
        display: grid;
        grid-template-columns: minmax(0, 1.75fr) minmax(0, 1.25fr);
        gap: 24px;
        align-items: start;
    }

    @media (max-width: 1100px) {
        .invoice-form-layout {
            grid-template-columns: minmax(0, 1.4fr) minmax(0, 1.2fr);
            gap: 18px;
        }
    }

    @media (max-width: 992px) {
        .invoice-form-layout {
            grid-template-columns: 1fr;
            gap: 20px;
        }
    }

    .left-col, .right-col {
        display: flex;
        flex-direction: column;
        gap: 20px;
        min-width: 0;
    }

    @media (min-width: 993px) {
        .sticky-summary {
            position: sticky;
            top: calc(var(--topbar-h, 58px) + 20px);
            z-index: 10;
        }
    }

    /* Page Header */
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        margin-bottom: 24px;
    }

    /* Dash Cards */
    .dash-card {
        background: var(--bg2);
        border: 1px solid var(--b3);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .card-head {
        padding: 14px 18px;
        border-bottom: 1px solid var(--b3);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: var(--bg3);
        gap: 10px;
        flex-wrap: wrap;
    }

    .card-title {
        font-size: 14px;
        font-weight: 700;
        color: var(--t1);
        display: flex;
        align-items: center;
        gap: 8px;
        margin: 0;
    }

    .card-body {
        padding: 18px;
    }

    @media (max-width: 576px) {
        .card-head {
            padding: 12px 14px;
        }
        .card-body {
            padding: 14px;
        }
    }

    /* Responsive Form Grids */
    .invoice-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    @media (max-width: 640px) {
        .invoice-grid {
            grid-template-columns: 1fr;
            gap: 12px;
        }
    }

    .invoice-grid .full-span {
        grid-column: 1 / -1;
    }

    .bank-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }

    @media (max-width: 480px) {
        .bank-grid {
            grid-template-columns: 1fr;
            gap: 10px;
        }
    }

    .form-row {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-lbl {
        font-size: 12.5px;
        font-weight: 600;
        color: var(--t2);
        margin: 0;
    }

    .sm-lbl {
        font-size: 12px;
    }

    .form-inp {
        width: 100%;
        height: 42px;
        padding: 8px 12px;
        font-size: 13.5px;
        background: var(--bg3);
        border: 1px solid var(--b3);
        border-radius: 8px;
        color: var(--t1);
        transition: var(--transition);
        outline: none;
    }

    .form-inp:focus {
        border-color: var(--accent);
        box-shadow: 0 0 0 3px var(--accent-bg);
    }

    textarea.form-inp {
        height: auto;
        min-height: 72px;
        resize: vertical;
    }

    .form-inp.sm {
        height: 36px;
        padding: 5px 8px;
        font-size: 13px;
        border-radius: 6px;
    }

    /* Input Prefix Group */
    .input-prefix-group {
        display: flex;
        align-items: stretch;
        width: 100%;
    }

    .prefix-badge {
        background: var(--bg4, var(--b1));
        border: 1px solid var(--b3);
        border-right: none;
        padding: 0 14px;
        display: inline-flex;
        align-items: center;
        font-weight: 700;
        font-size: 13.5px;
        color: var(--t1);
        border-radius: 8px 0 0 8px;
        white-space: nowrap;
        user-select: none;
    }

    .prefix-input {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
        flex: 1;
    }

    /* Line Items Table Responsive Container */
    .table-responsive-wrapper {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    #itemsTable {
        width: 100%;
        min-width: 620px;
        margin-bottom: 0;
        border-collapse: separate;
        border-spacing: 0;
    }

    #itemsTable th {
        padding: 10px 12px;
        font-size: 11.5px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: var(--t2);
        background: var(--bg3);
        border-bottom: 1px solid var(--b3);
        white-space: nowrap;
    }

    #itemsTable td {
        padding: 8px 10px;
        vertical-align: middle;
        border-bottom: 1px solid var(--b3);
        background: transparent;
    }

    #itemsTable tbody tr:last-child td {
        border-bottom: none;
    }

    .item-row:hover td {
        background: var(--bg3);
    }

    .btn-remove-row {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: none;
        background: transparent;
        color: #ef4444;
        cursor: pointer;
        transition: var(--transition);
        padding: 0;
        font-size: 15px;
    }

    .btn-remove-row:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #dc2626;
    }

    /* Summary Calculation List */
    .summary-container {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 13.5px;
        color: var(--t1);
        gap: 10px;
    }

    .summary-label {
        font-weight: 500;
        color: var(--t2);
        margin: 0;
    }

    .tax-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .tax-input-wrap {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tax-inp {
        width: 65px;
        text-align: right;
    }

    .adj-inp {
        width: 110px;
        text-align: right;
    }

    .tax-val, .summary-val {
        min-width: 75px;
        text-align: right;
        font-weight: 600;
        color: var(--t1);
    }

    .summary-divider {
        height: 1px;
        background: var(--b3);
        margin: 4px 0;
    }

    .total-row {
        font-size: 16px;
        font-weight: 800;
        color: var(--t1);
        padding-top: 4px;
    }

    .total-val {
        color: var(--accent);
        font-size: 19px;
        font-weight: 800;
    }

    .font-mono {
        font-family: var(--mono, monospace);
    }

    /* Select2 Customization */
    .select2-container--default .select2-selection--single {
        background-color: var(--bg3) !important;
        border: 1px solid var(--b3) !important;
        border-radius: 8px !important;
        height: 42px !important;
        display: flex !important;
        align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: var(--t1) !important;
        padding-left: 12px !important;
        font-size: 13.5px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px !important;
        right: 8px !important;
    }
    .select2-dropdown {
        background-color: var(--bg2) !important;
        border: 1px solid var(--b3) !important;
        color: var(--t1) !important;
        border-radius: 8px !important;
        box-shadow: var(--shadow-md) !important;
        z-index: 1050;
    }
    .select2-search--dropdown {
        padding: 8px !important;
    }
    .select2-search__field {
        background-color: var(--bg3) !important;
        border: 1px solid var(--b3) !important;
        color: var(--t1) !important;
        border-radius: 6px !important;
        padding: 6px 10px !important;
    }
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: var(--accent) !important;
        color: #fff !important;
    }
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: var(--b2) !important;
    }
    .select2-results__option {
        padding: 8px 12px !important;
        font-size: 13px !important;
    }
</style>

<main class="page-area" id="pageArea">
    <div class="page" id="page-invoice-create">
        <div class="page-header">
            <div>
                <h1 class="page-title">Create New Invoice</h1>
                <p class="page-desc">Generate a professional invoice for your client</p>
            </div>
            <div class="header-actions">
                <a href="{{ route($routePrefix . '.invoices.index') }}" class="btn-ghost">
                    <i class="bi bi-arrow-left"></i> Back to List
                </a>
            </div>
        </div>

        <form action="{{ route($routePrefix . '.invoices.store') }}" method="POST" id="invoiceForm">
            @csrf
            <div class="invoice-form-layout">
                <!-- Left Column: Details, Items, Sender -->
                <div class="left-col">
                    <!-- Client & Invoice Meta -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">
                                <i class="bi bi-person-fill"></i> Client & Invoice Details
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="invoice-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Select Order (Optional)</label>
                                    <select name="order_id" id="order_id" class="form-inp select2" onchange="loadOrderDetails(this.value)">
                                        <option value="">— Select Order —</option>
                                        @foreach($orders as $order)
                                            <option value="{{ $order->id }}" {{ isset($selectedOrder) && $selectedOrder->id == $order->id ? 'selected' : '' }}
                                                data-client="{{ $order->client_name }}"
                                                data-address="{{ $order->full_address }}"
                                                data-state="{{ $order->state }}"
                                                data-amount="{{ $order->order_value }}">
                                                {{ $order->order_number }} - {{ $order->company_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Invoice No <span class="text-danger">*</span></label>
                                    <div class="input-prefix-group">
                                        <span class="prefix-badge">STW</span>
                                        <input type="text" name="invoice_no" value="{{ old('invoice_no', $invoice_no) }}" class="form-inp prefix-input font-mono" oninput="this.value = this.value.replace(/[^0-9]/g, '')" maxlength="6" inputmode="numeric" required>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-inp" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Due Date</label>
                                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-inp">
                                </div>
                                <div class="form-row full-span">
                                    <label class="form-lbl">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $selectedOrder->client_name ?? '') }}" class="form-inp" placeholder="Client or Company Name" required>
                                </div>
                                <div class="form-row full-span">
                                    <label class="form-lbl">Client Address</label>
                                    <textarea name="client_address" id="client_address" class="form-inp" rows="2" placeholder="Full client address...">{{ old('client_address', $selectedOrder->full_address ?? '') }}</textarea>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client GSTIN</label>
                                    <input type="text" name="client_gstin" value="{{ old('client_gstin') }}" class="form-inp" placeholder="e.g. 29AAAAA0000A1Z5">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Place of Supply</label>
                                    <input type="text" name="place_of_supply" id="place_of_supply" value="{{ old('place_of_supply', $selectedOrder->state ?? '') }}" class="form-inp" placeholder="State/Location">
                                </div>
                                <div class="form-row full-span">
                                    <label class="form-lbl">Invoice Status <span class="text-danger">*</span></label>
                                    <select name="status" class="form-inp" required>
                                        <option value="UNPAID" {{ old('status') == 'UNPAID' ? 'selected' : '' }}>UNPAID</option>
                                        <option value="PAID" {{ old('status') == 'PAID' ? 'selected' : '' }}>PAID</option>
                                        <option value="PROFORMA" {{ old('status') == 'PROFORMA' ? 'selected' : '' }}>PROFORMA</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Line Items -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">
                                <i class="bi bi-list-ul"></i> Line Items
                            </div>
                            <button type="button" class="btn-ghost sm" onclick="addItemRow()">
                                <i class="bi bi-plus-lg"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <div class="table-responsive-wrapper">
                                <table class="table" id="itemsTable">
                                    <thead>
                                        <tr>
                                            <th style="width: 38%;">Description</th>
                                            <th style="width: 16%;">HSN/SAC</th>
                                            <th style="width: 12%;">Qty</th>
                                            <th style="width: 16%;">Rate</th>
                                            <th style="width: 14%;">Amount</th>
                                            <th style="width: 4%;"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="item-row">
                                            <td><input type="text" name="items[0][desc]" class="form-inp sm" value="Project Payment" placeholder="Item description" required></td>
                                            <td><input type="text" name="items[0][hsn]" class="form-inp sm" placeholder="HSN/SAC"></td>
                                            <td><input type="number" name="items[0][qty]" class="form-inp sm qty font-mono" value="1" min="0.01" step="any" oninput="calcRow(this)"></td>
                                            <td><input type="number" name="items[0][rate]" class="form-inp sm rate font-mono" value="0.00" min="0" step="any" oninput="calcRow(this)"></td>
                                            <td><input type="number" name="items[0][amount]" class="form-inp sm amount font-mono" value="0.00" readonly></td>
                                            <td style="text-align: center;"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Sender Details -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">
                                <i class="bi bi-building"></i> Sender Details (Optional)
                            </div>
                            <span style="font-size: 11.5px; color: var(--t3);">Leave blank to use default</span>
                        </div>
                        <div class="card-body">
                            <div class="invoice-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name') }}" class="form-inp" placeholder="Standsweb">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">GSTIN</label>
                                    <input type="text" name="sender_gstin" value="{{ old('sender_gstin') }}" class="form-inp" placeholder="29JTKPS5068C1Z1">
                                </div>
                                <div class="form-row full-span">
                                    <label class="form-lbl">Address</label>
                                    <textarea name="sender_address" class="form-inp" rows="2" placeholder="KannamangalaPost, Whitefield Main Road, Bengaluru Rural, Karnataka 560067">{{ old('sender_address') }}</textarea>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Contact No</label>
                                    <input type="text" name="sender_contact" value="{{ old('sender_contact') }}" class="form-inp" placeholder="+91 86606 32597">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Email ID</label>
                                    <input type="email" name="sender_email" value="{{ old('sender_email') }}" class="form-inp" placeholder="zentrics@gmail.com">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Summary, Notes, Bank -->
                <div class="right-col">
                    <!-- Totals Summary (Sticky on Desktop) -->
                    <div class="dash-card sticky-summary">
                        <div class="card-head">
                            <div class="card-title">
                                <i class="bi bi-calculator"></i> Invoice Summary
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="summary-container">
                                <div class="summary-row">
                                    <span class="summary-label">Subtotal</span>
                                    <span class="summary-val font-mono">₹<span id="subtotal_text">0.00</span></span>
                                    <input type="hidden" name="subtotal" id="subtotal_val" value="0">
                                </div>
                                <div class="summary-row tax-row">
                                    <label class="summary-label" for="cgst_p">CGST (%)</label>
                                    <div class="tax-input-wrap">
                                        <input type="number" id="cgst_p" class="form-inp sm tax-inp font-mono" value="9" min="0" step="any" oninput="calcTotals()">
                                        <span class="tax-val font-mono">₹<span id="cgst_text">0.00</span></span>
                                        <input type="hidden" name="cgst" id="cgst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-row tax-row">
                                    <label class="summary-label" for="sgst_p">SGST (%)</label>
                                    <div class="tax-input-wrap">
                                        <input type="number" id="sgst_p" class="form-inp sm tax-inp font-mono" value="9" min="0" step="any" oninput="calcTotals()">
                                        <span class="tax-val font-mono">₹<span id="sgst_text">0.00</span></span>
                                        <input type="hidden" name="sgst" id="sgst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-row tax-row">
                                    <label class="summary-label" for="igst_p">IGST (%)</label>
                                    <div class="tax-input-wrap">
                                        <input type="number" id="igst_p" class="form-inp sm tax-inp font-mono" value="0" min="0" step="any" oninput="calcTotals()">
                                        <span class="tax-val font-mono">₹<span id="igst_text">0.00</span></span>
                                        <input type="hidden" name="igst" id="igst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-row tax-row">
                                    <label class="summary-label" for="adjustment">Adjustment (₹)</label>
                                    <input type="number" name="adjustment" id="adjustment" class="form-inp sm adj-inp font-mono" value="0" step="any" oninput="calcTotals()">
                                </div>
                                <div class="summary-divider"></div>
                                <div class="summary-row total-row">
                                    <span>Grand Total</span>
                                    <span class="total-val font-mono">₹<span id="total_text">0.00</span></span>
                                    <input type="hidden" name="total" id="total_val" value="0">
                                </div>
                            </div>
                        </div>
                        <div style="padding: 0 18px 18px 18px;">
                            <button type="submit" class="btn-primary-solid" style="width: 100%; height: 44px; font-size: 14.5px; font-weight: 600; justify-content: center;">
                                <i class="bi bi-check-circle-fill"></i> Create & View Invoice
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-sticky"></i> Notes / Terms</div>
                        </div>
                        <div class="card-body">
                            <textarea name="notes" class="form-inp" rows="3" placeholder="Additional notes or payment terms...">Looking forward for your business.
Rates are subject to change without prior notification.</textarea>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-bank"></i> Bank Details</div>
                        </div>
                        <div class="card-body">
                            <div class="bank-grid">
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">Account Name</label>
                                    <input type="text" name="bank_details[account_name]" class="form-inp sm" value="Standsweb">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">Bank Name</label>
                                    <input type="text" name="bank_details[bank_name]" class="form-inp sm" value="State Bank of India">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">Account Number</label>
                                    <input type="text" name="bank_details[account_number]" class="form-inp sm font-mono" value="44128332491">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">IFSC Code</label>
                                    <input type="text" name="bank_details[ifsc]" class="form-inp sm font-mono" value="SBIN0003242">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">Branch</label>
                                    <input type="text" name="bank_details[branch]" class="form-inp sm" value="ACB Debagram">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">SWIFT Code</label>
                                    <input type="text" name="bank_details[swift]" class="form-inp sm font-mono" value="SBININBB812">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<script>
    let rowIdx = 1;

    function addItemRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td><input type="text" name="items[${rowIdx}][desc]" class="form-inp sm" placeholder="Item description" required></td>
            <td><input type="text" name="items[${rowIdx}][hsn]" class="form-inp sm" placeholder="HSN/SAC"></td>
            <td><input type="number" name="items[${rowIdx}][qty]" class="form-inp sm qty font-mono" value="1" min="0.01" step="any" oninput="calcRow(this)"></td>
            <td><input type="number" name="items[${rowIdx}][rate]" class="form-inp sm rate font-mono" value="0.00" min="0" step="any" oninput="calcRow(this)"></td>
            <td><input type="number" name="items[${rowIdx}][amount]" class="form-inp sm amount font-mono" value="0.00" readonly></td>
            <td style="text-align: center;">
                <button type="button" class="btn-remove-row" title="Remove row" onclick="this.closest('tr').remove(); calcTotals();">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
        rowIdx++;
    }

    function calcRow(el) {
        const row = el.closest('tr');
        const qty = parseFloat(row.querySelector('.qty').value) || 0;
        const rate = parseFloat(row.querySelector('.rate').value) || 0;
        const amount = qty * rate;
        row.querySelector('.amount').value = amount.toFixed(2);
        calcTotals();
    }

    function calcTotals() {
        let subtotal = 0;
        document.querySelectorAll('.amount').forEach(inp => {
            subtotal += parseFloat(inp.value) || 0;
        });

        const cgst_p = parseFloat(document.getElementById('cgst_p')?.value) || 0;
        const sgst_p = parseFloat(document.getElementById('sgst_p')?.value) || 0;
        const igst_p = parseFloat(document.getElementById('igst_p')?.value) || 0;
        const adj = parseFloat(document.getElementById('adjustment')?.value) || 0;

        const cgst = subtotal * (cgst_p / 100);
        const sgst = subtotal * (sgst_p / 100);
        const igst = subtotal * (igst_p / 100);
        const total = subtotal + cgst + sgst + igst + adj;

        const subtotalEl = document.getElementById('subtotal_text');
        if (subtotalEl) subtotalEl.innerText = subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const subtotalVal = document.getElementById('subtotal_val');
        if (subtotalVal) subtotalVal.value = subtotal.toFixed(2);

        const cgstEl = document.getElementById('cgst_text');
        if (cgstEl) cgstEl.innerText = cgst.toFixed(2);
        const cgstVal = document.getElementById('cgst_val');
        if (cgstVal) cgstVal.value = cgst.toFixed(2);

        const sgstEl = document.getElementById('sgst_text');
        if (sgstEl) sgstEl.innerText = sgst.toFixed(2);
        const sgstVal = document.getElementById('sgst_val');
        if (sgstVal) sgstVal.value = sgst.toFixed(2);

        const igstEl = document.getElementById('igst_text');
        if (igstEl) igstEl.innerText = igst.toFixed(2);
        const igstVal = document.getElementById('igst_val');
        if (igstVal) igstVal.value = igst.toFixed(2);

        const totalEl = document.getElementById('total_text');
        if (totalEl) totalEl.innerText = total.toLocaleString('en-IN', {minimumFractionDigits: 2, maximumFractionDigits: 2});
        const totalVal = document.getElementById('total_val');
        if (totalVal) totalVal.value = total.toFixed(2);
    }

    function loadOrderDetails(orderId) {
        if (!orderId) return;
        const opt = document.querySelector(`#order_id option[value="${orderId}"]`);
        if (opt) {
            document.getElementById('client_name').value = opt.dataset.client || '';
            document.getElementById('client_address').value = opt.dataset.address || '';
            document.getElementById('place_of_supply').value = opt.dataset.state || '';
            
            // Set first row amount if empty
            const firstRate = document.querySelector('.rate');
            if (firstRate && (parseFloat(firstRate.value) === 0 || !firstRate.value)) {
                firstRate.value = opt.dataset.amount || 0;
                calcRow(firstRate);
            }
        }
    }

    // Initial calculation
    calcTotals();
</script>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: "Select an Order",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush
