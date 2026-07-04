<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - STW{{ $invoice->invoice_no }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- favicon png -->
    <link rel="icon" type="image/png" href="{{ asset('./logo.png') }}">
    
    <style>
        :root {
            --navy: #00112c;
            --blue: #0056b3;
            --light-blue: #cce5ff;
            --grey: #f8f9fa;
            --border: #dee2e6;
            --text: #333;
            --text-muted: #666;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0f2f5;
            color: var(--text);
            line-height: 1.4;
            padding: 40px 0;
            -webkit-print-color-adjust: exact;
        }

        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #fff;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
            min-height: 1120px; /* A4-ish height */
        }

        .header-image {
            width: 100%;
            display: block;
        }

        .content {
            padding: 20px 30px 60px 30px;
            position: relative;
            z-index: 2;
            margin-top: -105px;
        }

        .sender-info {
            margin-bottom: 15px;
        }
        .sender-info h2 {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 3px;
            text-transform: uppercase;
        }
        .sender-info p {
            font-size: 11px;
            color: var(--text-muted);
            max-width: 350px;
            line-height: 1.4;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
            margin-bottom: 15px;
        }

        .bill-to h3, .invoice-meta h3 {
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 6px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }
        .meta-label { font-weight: 700; color: #000; width: 110px; flex-shrink: 0; }
        .meta-value { flex: 1; text-align: left; }

        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .invoice-table th {
            background: #e2e8f0;
            padding: 6px 10px;
            text-align: left;
            font-size: 11px;
            font-weight: 800;
            border: 1px solid #ccc;
        }
        .invoice-table td {
            padding: 6px 10px;
            font-size: 11px;
            border: 1px solid #ccc;
            height: 28px;
        }

        .bottom-section {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 20px;
        }

        .summary-box {
            font-size: 12px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            align-items: flex-end;
        }
        .summary-label { font-weight: 800; min-width: 100px; }
        .summary-value {  flex: 1; text-align: right; font-weight: 700; padding: 0 5px; }

        .notes-section, .bank-section, .terms-section {
            margin-bottom: 15px;
        }
        .section-title { font-size: 12px; font-weight: 800; margin-bottom: 5px; border-bottom: 1px solid #eee; padding-bottom: 2px; }
        .notes-list { list-style: none; font-size: 11px; color: var(--text-muted); }
        .notes-list li { margin-bottom: 3px; display: flex; gap: 6px; }

        .bank-details { font-size: 11px; }
        .bank-row { display: flex; margin-bottom: 2px; align-items: flex-end; }
        .bank-label { font-weight: 700; width: 100px; }
        .bank-line {  flex: 1; height: 14px; }

        .invoice-footer {
            background: var(--navy);
            color: #fff;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 12px;
            position: absolute;
            bottom: 0;
            width: 100%;
        }
        .footer-item { display: flex; align-items: center; gap: 8px; }
        .footer-item i { color: var(--light-blue); font-size: 16px; }

        .watermark {
            position: absolute;
            bottom: 60px;
            right: 40px;
            width: 200px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 1;
        }

        @media print {
            body { padding: 0 !important; background: none !important; }
            .invoice-card { box-shadow: none !important; margin: 0 !important; width: 100% !important; max-width: 100% !important; min-height: unset !important; }
            .no-print { display: none !important; }
        }

        .print-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: var(--navy);
            color: #fff;
            border: none;
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
            transition: all 0.3s ease;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
        }

        .back-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            background: #fff;
            color: var(--navy);
            border: 1px solid var(--navy);
            padding: 12px 24px;
            border-radius: 30px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            z-index: 100;
            transition: all 0.3s ease;
        }
        .back-btn:hover {
            background: var(--grey);
        }
    </style>
</head>
<body>

    @php
        $routePrefix = auth()->guard('admin')->check() ? 'admin' : 'sale';
    @endphp

    <a href="{{ route($routePrefix . '.invoices.index') }}" class="back-btn no-print" style="border-radius: 8px;">
        <i class="bi bi-arrow-left"></i> Back to List
    </a>

    <button class="print-btn no-print" onclick="printInvoice()" style="border-radius: 8px; background: var(--accent, #6366f1);">
        <i class="bi bi-printer-fill"></i> Print Invoice
    </button>

    <script>
        function printInvoice() {
            const btns = document.querySelectorAll('.no-print');
            btns.forEach(btn => btn.style.display = 'none');
            window.print();
        }
        window.onafterprint = function() {
            const btns = document.querySelectorAll('.no-print');
            btns.forEach(btn => btn.style.display = 'flex');
        };
    </script>

    <div class="invoice-card">
        <img src="{{ asset('invtop.png') }}" alt="Header" class="header-image">

        <div class="content">
            <div class="sender-info">
                <h2>{{ $invoice->sender_name ?? 'Standsweb' }}</h2>
                <p>
                    {!! nl2br(e($invoice->sender_address ?? "PS Qube, Action Area IID, Newtown, Kolkata, 700156")) !!}<br>
                    @if($invoice->sender_gstin) GSTIN: {{ $invoice->sender_gstin }} @else GSTIN: 29JTKPS5068C1Z1 @endif<br>
                    Contact: {{ $invoice->sender_contact ?? '+91 89270-43805' }}<br>
                    Email: {{ $invoice->sender_email ?? 'info@standsweb.com' }}
                </p>
            </div>

            <div class="meta-grid">
                <div class="bill-to">
                    <h3>Bill To:</h3>
                    <div class="meta-row">
                        <span class="meta-label">Client Name:</span>
                        <span class="meta-value">{{ $invoice->client_name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Address:</span>
                        <span class="meta-value">{{ $invoice->client_address ?? 'Not Provided' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">GSTIN:</span>
                        <span class="meta-value">{{ $invoice->client_gstin ?? 'Not Provided' }}</span>
                    </div>
                </div>

                <div class="invoice-meta">
                    <div class="meta-row">
                        <span class="meta-label">Invoice No:</span>
                        <span class="meta-value">STW{{ $invoice->invoice_no }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Invoice Date:</span>
                        <span class="meta-value">{{ $invoice->invoice_date->format('d-m-Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Due Date:</span>
                        <span class="meta-value">{{ $invoice->due_date ? $invoice->due_date->format('d-m-Y') : '_________________' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Place of Supply:</span>
                        <span class="meta-value">{{ $invoice->place_of_supply ?? '_________________' }}</span>
                    </div>
                    <div class="meta-row" style="margin-top: 10px;">
                        <span class="meta-label">Status:</span>
                        @php
                            $statusColor = '#6366f1';
                            if($invoice->status == 'PAID') $statusColor = '#000000ff';
                            if($invoice->status == 'UNPAID') $statusColor = '#000000ff';
                            if($invoice->status == 'PROFORMA') $statusColor = '#000000ff';
                        @endphp
                        <span class="meta-value" style="font-weight: 800; color: {{ $statusColor }}; text-transform: uppercase;">
                            {{ $invoice->status }}
                        </span>
                    </div>
                </div>
            </div>

            <table class="invoice-table">
                <thead>
                    <tr>
                        <th width="45%">Description</th>
                        <th width="15%">HSN/SAC</th>
                        <th width="10%">Qty</th>
                        <th width="15%">Rate</th>
                        <th width="15%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($invoice->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item['desc'] }}</strong>
                        </td>
                        <td>{{ $item['hsn'] ?? '' }}</td>
                        <td>{{ $item['qty'] }}</td>
                        <td>{{ number_format($item['rate'], 2) }}</td>
                        <td>{{ number_format($item['amount'], 2) }}</td>
                    </tr>
                    @endforeach
                    
                    {{-- Empty rows for design --}}
                    <!-- @for($i=0; $i < max(0, 6 - count($invoice->items)); $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor -->
                </tbody>
            </table>

            <div class="bottom-section">
                <div class="left-col">
                    <div class="bank-section" style="margin-bottom: 0;">
                        <div class="section-title">Bank Details:</div>
                        <div class="bank-details">
                            <div class="bank-row">
                                <span class="bank-label">Account Name:</span>
                                <div class="bank-line">{{ $invoice->bank_details['account_name'] ?? 'Standsweb' }}</div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Bank Name:</span>
                                <div class="bank-line">{{ $invoice->bank_details['bank_name'] ?? 'State Bank of India' }}</div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Account Number:</span>
                                <div class="bank-line">{{ $invoice->bank_details['account_number'] ?? '44128332491' }}</div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">IFSC Code:</span>
                                <div class="bank-line">{{ $invoice->bank_details['ifsc'] ?? 'SBIN0003242' }}</div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Branch:</span>
                                <div class="bank-line">{{ $invoice->bank_details['branch'] ?? 'ACB Debagram' }}</div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">SWIFT Code:</span>
                                <div class="bank-line">{{ $invoice->bank_details['swift'] ?? 'SBININBB812' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">{{ number_format($invoice->subtotal, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">CGST (9%):</span>
                            <span class="summary-value">{{ number_format($invoice->cgst, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">SGST (9%):</span>
                            <span class="summary-value">{{ number_format($invoice->sgst, 2) }}</span>
                        </div>
                        @if($invoice->igst > 0)
                        <div class="summary-row">
                            <span class="summary-label">IGST (18%):</span>
                            <span class="summary-value">{{ number_format($invoice->igst, 2) }}</span>
                        </div>
                        @endif
                        <div class="summary-row">
                            <span class="summary-label">Adjustment:</span>
                            <span class="summary-value">{{ number_format($invoice->adjustment, 2) }}</span>
                        </div>
                        <div class="summary-row" style="margin-top:5px; color:var(--navy); font-size:13px;">
                            <span class="summary-label">Total:</span>
                            <span class="summary-value">₹{{ number_format($invoice->total, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="terms-section" style="margin-top: 15px; border-top: 1px solid #eee; padding-top: 10px;">
                <div class="section-title" style="border-bottom: none; margin-bottom: 4px; padding-bottom: 0;">Terms & Conditions:</div>
                <ol style="font-size: 9.5px; color: var(--text-muted); padding-left: 15px; margin: 0; line-height: 1.4;">
                    <li style="margin-bottom: 2px;">Once payment is done, the amount is not refundable.</li>
                    <li style="margin-bottom: 2px;">A 100% advance payment will be required at the time of signing the contract.</li>
                    <li style="margin-bottom: 2px;">Retainer fees (does not include any advertising budget or tools used on the client's behalf)</li>
                    <li style="margin-bottom: 2px;">This amount is payable by Cheque, RTGS, NEFT or IMPS, UPI</li>
                    <li style="margin-bottom: 2px;">The retainer amount is subject to increment if the scope of work or duration increases beyond the expected deliverables or duration.</li>
                </ol>
            </div>
        </div>

        <img src="{{ asset('logo.png') }}" class="watermark" alt="Watermark">

        <div class="invoice-footer">
            <div class="footer-item">
                <i class="bi bi-globe"></i>
                <span>www.standsweb.com</span>
            </div>
            <div class="footer-item">
                <i class="bi bi-envelope-fill"></i>
                <span>info@standsweb.com</span>
            </div>
            <div class="footer-item">
                <i class="bi bi-telephone-fill"></i>
                <span>+91 89270-43805</span>
            </div>
        </div>
    </div>

</body>
</html>
