@extends('admin.layout.app')

@section('title', 'Create Invoice')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Force Dark Mode for Line Items Table */
        #itemsTable {
            border-color: var(--b3) !important;
        }
        #itemsTable thead {
            background: var(--b1) !important;
        }
        #itemsTable thead th {
            color: var(--t1) !important;
            border-bottom: 1px solid var(--b3) !important;
            background: var(--b1) !important;
            font-weight: 700 !important;
        }
        #itemsTable tbody td {
            border-color: var(--b3) !important;
            background: transparent !important;
            color: var(--t1) !important;
        }
        .item-row:hover {
            background: var(--bg2) !important;
        }
        .form-inp.sm {
            background: var(--bg3) !important;
            border-color: var(--b3) !important;
            color: var(--t1) !important;
        }

        /* Select2 Dark Mode Fix */
        .select2-container--default .select2-selection--single {
            background-color: var(--bg3) !important;
            border: 1px solid var(--b3) !important;
            border-radius: 8px !important;
            height: 42px !important;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: var(--t1) !important;
            padding-left: 12px !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px !important;
        }
        .select2-dropdown {
            background-color: var(--bg2) !important;
            border: 1px solid var(--b3) !important;
            color: var(--t1) !important;
        }
        .select2-search__field {
            background-color: var(--bg3) !important;
            border: 1px solid var(--b3) !important;
            color: var(--t1) !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: var(--accent) !important;
        }
        .select2-container--default .select2-results__option[aria-selected=true] {
            background-color: var(--b2) !important;
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
            <div class="form-grid-2-1">
                <div class="left-col" style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Client & Meta -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-person-fill"></i> Client & Invoice Details</div>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
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
                                    <input type="text" name="invoice_no" value="{{ old('invoice_no', $invoice_no) }}" class="form-inp" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" class="form-inp" required>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Due Date</label>
                                    <input type="date" name="due_date" value="{{ old('due_date') }}" class="form-inp">
                                </div>
                                <div class="form-row" style="grid-column: 1/-1;">
                                    <label class="form-lbl">Client Name <span class="text-danger">*</span></label>
                                    <input type="text" name="client_name" id="client_name" value="{{ old('client_name', $selectedOrder->client_name ?? '') }}" class="form-inp" required>
                                </div>
                                <div class="form-row" style="grid-column: 1/-1;">
                                    <label class="form-lbl">Client Address</label>
                                    <textarea name="client_address" id="client_address" class="form-inp" rows="2">{{ old('client_address', $selectedOrder->full_address ?? '') }}</textarea>
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Client GSTIN</label>
                                    <input type="text" name="client_gstin" value="{{ old('client_gstin') }}" class="form-inp" placeholder="Optional">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">Place of Supply</label>
                                    <input type="text" name="place_of_supply" id="place_of_supply" value="{{ old('place_of_supply', $selectedOrder->state ?? '') }}" class="form-inp">
                                </div>
                                <div class="form-row">
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

                    <!-- Items Table -->
                    <div class="dash-card">
                        <div class="card-head" style="display: flex; justify-content: space-between; align-items: center;">
                            <div class="card-title"><i class="bi bi-list-ul"></i> Line Items</div>
                            <button type="button" class="btn-ghost sm" onclick="addItemRow()">
                                <i class="bi bi-plus-lg"></i> Add Item
                            </button>
                        </div>
                        <div class="card-body" style="padding: 0;">
                            <table class="table" id="itemsTable">
                                <thead style="background: var(--b2); color: var(--t1);">
                                    <tr>
                                        <th width="40%">Description</th>
                                        <th width="15%">HSN/SAC</th>
                                        <th width="10%">Qty</th>
                                        <th width="15%">Rate</th>
                                        <th width="15%">Amount</th>
                                        <th width="5%"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- First Row --}}
                                    <tr class="item-row">
                                        <td><input type="text" name="items[0][desc]" class="form-inp sm" value="Project Payment" required></td>
                                        <td><input type="text" name="items[0][hsn]" class="form-inp sm"></td>
                                        <td><input type="number" name="items[0][qty]" class="form-inp sm qty" value="1" min="1" step="any" oninput="calcRow(this)"></td>
                                        <td><input type="number" name="items[0][rate]" class="form-inp sm rate" value="0.00" min="0" step="any" oninput="calcRow(this)"></td>
                                        <td><input type="number" name="items[0][amount]" class="form-inp sm amount" value="0.00" readonly></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Sender Details -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-building"></i> Sender Details (Optional)</div>
                            <span style="font-size:11px; opacity:0.6;">Leave blank to use default (Standsweb)</span>
                        </div>
                        <div class="card-body">
                            <div class="form-grid">
                                <div class="form-row">
                                    <label class="form-lbl">Company Name</label>
                                    <input type="text" name="sender_name" value="{{ old('sender_name') }}" class="form-inp" placeholder="Standsweb">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl">GSTIN</label>
                                    <input type="text" name="sender_gstin" value="{{ old('sender_gstin') }}" class="form-inp" placeholder="29JTKPS5068C1Z1">
                                </div>
                                <div class="form-row" style="grid-column: 1/-1;">
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

                <div class="right-col" style="display: flex; flex-direction: column; gap: 20px;">
                    <!-- Totals -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title"><i class="bi bi-calculator"></i> Summary</div>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 15px;">
                                <div class="summary-item" style="display: flex; justify-content: space-between; font-weight: 500;">
                                    <span>Subtotal</span>
                                    <span>₹<span id="subtotal_text">0.00</span></span>
                                    <input type="hidden" name="subtotal" id="subtotal_val" value="0">
                                </div>
                                <div class="summary-item">
                                    <label class="form-lbl">CGST (%)</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="number" id="cgst_p" class="form-inp sm" value="9" oninput="calcTotals()">
                                        <span id="cgst_text" style="min-width: 60px; text-align: right;">0.00</span>
                                        <input type="hidden" name="cgst" id="cgst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <label class="form-lbl">SGST (%)</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="number" id="sgst_p" class="form-inp sm" value="9" oninput="calcTotals()">
                                        <span id="sgst_text" style="min-width: 60px; text-align: right;">0.00</span>
                                        <input type="hidden" name="sgst" id="sgst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <label class="form-lbl">IGST (%)</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input type="number" id="igst_p" class="form-inp sm" value="0" oninput="calcTotals()">
                                        <span id="igst_text" style="min-width: 60px; text-align: right;">0.00</span>
                                        <input type="hidden" name="igst" id="igst_val" value="0">
                                    </div>
                                </div>
                                <div class="summary-item">
                                    <label class="form-lbl">Adjustment</label>
                                    <input type="number" name="adjustment" id="adjustment" class="form-inp sm" value="0" step="any" oninput="calcTotals()">
                                </div>
                                <hr style="border: none; border-top: 1px solid var(--border);">
                                <div class="summary-item" style="display: flex; justify-content: space-between; font-weight: 800; font-size: 18px; color: var(--navy);">
                                    <span>Total</span>
                                    <span>₹<span id="total_text">0.00</span></span>
                                    <input type="hidden" name="total" id="total_val" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="card-foot" style="padding: 20px;">
                            <button type="submit" class="btn-primary-solid" style="width: 100%; justify-content: center;">
                                <i class="bi bi-check-circle-fill"></i> Create & View Invoice
                            </button>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Notes</div>
                        </div>
                        <div class="card-body">
                            <textarea name="notes" class="form-inp" rows="3" placeholder="Additional notes...">Looking forward for your business.
Rates are subject to change without prior notification.</textarea>
                        </div>
                    </div>

                    <!-- Bank Details -->
                    <div class="dash-card">
                        <div class="card-head">
                            <div class="card-title">Bank Details</div>
                        </div>
                        <div class="card-body">
                            <div style="display: flex; flex-direction: column; gap: 10px;">
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
                                    <input type="text" name="bank_details[account_number]" class="form-inp sm" value="44128332491">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">IFSC Code</label>
                                    <input type="text" name="bank_details[ifsc]" class="form-inp sm" value="SBIN0003242">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">Branch</label>
                                    <input type="text" name="bank_details[branch]" class="form-inp sm" value="ACB Debagram">
                                </div>
                                <div class="form-row">
                                    <label class="form-lbl sm-lbl">SWIFT Code</label>
                                    <input type="text" name="bank_details[swift]" class="form-inp sm" value="SBININBB812">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>

<style>
    .sm { padding: 4px 8px; font-size: 13px; height: 32px; }
    .form-grid-2-1 { display: grid; grid-template-columns: 2fr 1fr; gap: 20px; }
    @media (max-width: 992px) { .form-grid-2-1 { grid-template-columns: 1fr; } }
</style>

<script>
    let rowIdx = 1;

    function addItemRow() {
        const tbody = document.querySelector('#itemsTable tbody');
        const tr = document.createElement('tr');
        tr.className = 'item-row';
        tr.innerHTML = `
            <td><input type="text" name="items[${rowIdx}][desc]" class="form-inp sm" required></td>
            <td><input type="text" name="items[${rowIdx}][hsn]" class="form-inp sm"></td>
            <td><input type="number" name="items[${rowIdx}][qty]" class="form-inp sm qty" value="1" min="1" step="any" oninput="calcRow(this)"></td>
            <td><input type="number" name="items[${rowIdx}][rate]" class="form-inp sm rate" value="0.00" min="0" step="any" oninput="calcRow(this)"></td>
            <td><input type="number" name="items[${rowIdx}][amount]" class="form-inp sm amount" value="0.00" readonly></td>
            <td><button type="button" class="text-danger" onclick="this.closest('tr').remove(); calcTotals();" style="border:none; background:none; cursor:pointer;"><i class="bi bi-x-circle"></i></button></td>
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

        const cgst_p = parseFloat(document.getElementById('cgst_p').value) || 0;
        const sgst_p = parseFloat(document.getElementById('sgst_p').value) || 0;
        const igst_p = parseFloat(document.getElementById('igst_p').value) || 0;
        const adj = parseFloat(document.getElementById('adjustment').value) || 0;

        const cgst = subtotal * (cgst_p / 100);
        const sgst = subtotal * (sgst_p / 100);
        const igst = subtotal * (igst_p / 100);
        const total = subtotal + cgst + sgst + igst + adj;

        document.getElementById('subtotal_text').innerText = subtotal.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('subtotal_val').value = subtotal.toFixed(2);

        document.getElementById('cgst_text').innerText = cgst.toFixed(2);
        document.getElementById('cgst_val').value = cgst.toFixed(2);

        document.getElementById('sgst_text').innerText = sgst.toFixed(2);
        document.getElementById('sgst_val').value = sgst.toFixed(2);

        document.getElementById('igst_text').innerText = igst.toFixed(2);
        document.getElementById('igst_val').value = igst.toFixed(2);

        document.getElementById('total_text').innerText = total.toLocaleString('en-IN', {minimumFractionDigits: 2});
        document.getElementById('total_val').value = total.toFixed(2);
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
            if (firstRate && parseFloat(firstRate.value) === 0) {
                firstRate.value = opt.dataset.amount || 0;
                calcRow(firstRate);
            }
        }
    }

    // Initial calc
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
