<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - #PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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

        /* Static Header Image */
        .header-image {
            width: 100%;
            display: block;
        }

        /* Content Area */
        .content {
            padding: 30px 40px;
            position: relative;
            z-index: 2;
            margin-top: -120px;
        }

        /* Company Info */
        .sender-info {
            margin-bottom: 25px;
        }
        .sender-info h2 {
            font-size: 18px;
            font-weight: 800;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .sender-info p {
            font-size: 12px;
            color: var(--text-muted);
            max-width: 350px;
            line-height: 1.5;
        }

        /* Meta Area */
        .meta-grid {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
            margin-bottom: 30px;
        }

        .bill-to h3, .invoice-meta h3 {
            font-size: 15px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 13px;
        }
        .meta-label { font-weight: 700; color: #000; width: 120px; flex-shrink: 0; }
        .meta-value { flex: 1; text-align: left; }
        .dots { border-bottom: 1px solid #999; flex: 1; height: 14px; margin-left: 5px; }

        /* Table */
        .invoice-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .invoice-table th {
            background: #e2e8f0;
            padding: 10px 15px;
            text-align: left;
            font-size: 12px;
            font-weight: 800;
            border: 1px solid #ccc;
        }
        .invoice-table td {
            padding: 10px 15px;
            font-size: 13px;
            border: 1px solid #ccc;
            height: 35px;
        }

        /* Summary Area */
        .bottom-section {
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            gap: 40px;
        }

        .summary-box {
            font-size: 14px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            align-items: flex-end;
        }
        .summary-label { font-weight: 800; min-width: 100px; }
        .summary-value { border-bottom: 1px solid #999; flex: 1; text-align: right; font-weight: 700; padding: 0 5px; }

        .notes-section, .bank-section {
            margin-bottom: 25px;
        }
        .section-title { font-size: 14px; font-weight: 800; margin-bottom: 8px; border-bottom: 1px solid #eee; padding-bottom: 4px; }
        .notes-list { list-style: none; font-size: 12px; color: var(--text-muted); }
        .notes-list li { margin-bottom: 4px; display: flex; gap: 8px; }

        .bank-details { font-size: 12px; }
        .bank-row { display: flex; margin-bottom: 4px; align-items: flex-end; }
        .bank-label { font-weight: 700; width: 100px; }
        .bank-line { border-bottom: 1px solid #ccc; flex: 1; height: 14px; }

        /* Footer */
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

        /* Watermark */
        .watermark {
            position: absolute;
            bottom: 60px;
            right: 40px;
            width: 200px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 1;
        }

        /* Print Styles */
        @media print {
            body { padding: 0; background: none; }
            .invoice-card { box-shadow: none; margin: 0; width: 100%; max-width: 100%; }
            .no-print { display: none; }
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
        }
    </style>
</head>
<body>

    <button class="print-btn no-print" onclick="window.print()">
        <i class="bi bi-printer-fill"></i> Print Invoice
    </button>

    <div class="invoice-card">
        <!-- Header Image -->
        <img src="{{ asset('invtop.png') }}" alt="Header" class="header-image">

        <div class="content">
            <!-- Sender -->
            <div class="sender-info">
                <h2>StandsWeb</h2>
                <p>
                    KannamangalaPost, Whitefield Main Road, Bengaluru Rural<br>
                    Karnataka 560067 GSTIN: 29JTKPS5068C1Z1 Contact: +91 86606 32597 | Email: zentrics@gmail.com
                </p>
            </div>

            <!-- Meta & Client -->
            <div class="meta-grid">
                <div class="bill-to">
                    <h3>Bill To:</h3>
                    <div class="meta-row">
                        <span class="meta-label">Client Name:</span>
                        <span class="meta-value">{{ $payment->order->client_name }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Address:</span>
                        <span class="meta-value">{{ $payment->order->full_address ?? '____________________________' }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">GSTIN:</span>
                        <span class="meta-value">____________________________</span>
                    </div>
                </div>

                <div class="invoice-meta">
                    <div class="meta-row">
                        <span class="meta-label">Invoice No:</span>
                        <span class="meta-value">#PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Invoice Date:</span>
                        <span class="meta-value">{{ $payment->transaction_date->format('d-m-Y') }}</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Due Date:</span>
                        <span class="meta-value">_________________</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Place of Supply:</span>
                        <span class="meta-value">{{ $payment->order->state ?? '_________________' }}</span>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <table class="invoice-table">
                <thead>
                    <tr>
                        <th width="50%">Item & Description</th>
                        <th width="15%">HSN/SAC</th>
                        <th width="10%">Qty</th>
                        <th width="12%">Rate</th>
                        <th width="13%">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <strong>Project Payment</strong><br>
                            <span style="font-size:11px; color:var(--text-muted)">Payment for order #ORD-{{ $payment->order_id }}</span>
                        </td>
                        <td></td>
                        <td>1</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                        <td>{{ number_format($payment->amount, 2) }}</td>
                    </tr>
                    <!-- Empty rows for spacing to match design -->
                    @for($i=0; $i<6; $i++)
                    <tr>
                        <td>&nbsp;</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    @endfor
                </tbody>
            </table>

            <!-- Bottom Section -->
            <div class="bottom-section">
                <div class="left-col">
                    <div class="notes-section">
                        <div class="section-title">Notes:</div>
                        <ul class="notes-list">
                            <li><i class="bi bi-dot"></i> Looking forward for your business.</li>
                            <li><i class="bi bi-dot"></i> Rates are subject to change without prior notification.</li>
                        </ul>
                    </div>

                    <div class="bank-section">
                        <div class="section-title">Bank Details:</div>
                        <div class="bank-details">
                            <div class="bank-row">
                                <span class="bank-label">Account Name:</span>
                                <div class="bank-line"></div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Bank Name:</span>
                                <div class="bank-line"></div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Account Number:</span>
                                <div class="bank-line"></div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">IFSC Code:</span>
                                <div class="bank-line"></div>
                            </div>
                            <div class="bank-row">
                                <span class="bank-label">Branch:</span>
                                <div class="bank-line"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="right-col">
                    <div class="summary-box">
                        <div class="summary-row">
                            <span class="summary-label">Subtotal:</span>
                            <span class="summary-value">{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">CGST (9%):</span>
                            <span class="summary-value">0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">SGST (9%):</span>
                            <span class="summary-value">0.00</span>
                        </div>
                        <div class="summary-row">
                            <span class="summary-label">Adjustment:</span>
                            <span class="summary-value">0.00</span>
                        </div>
                        <div class="summary-row" style="margin-top:10px; color:var(--navy); font-size:16px;">
                            <span class="summary-label">Total:</span>
                            <span class="summary-value">₹{{ number_format($payment->amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Watermark -->
        <img src="{{ asset('logo.png') }}" class="watermark" alt="Watermark">

        <!-- Footer -->
        <div class="invoice-footer">
            <div class="footer-item">
                <i class="bi bi-globe"></i>
                <span>www.standsweb.in</span>
            </div>
            <div class="footer-item">
                <i class="bi bi-envelope-fill"></i>
                <span>standsweb@gmail.com</span>
            </div>
            <div class="footer-item">
                <i class="bi bi-telephone-fill"></i>
                <span>+91 86606 32597</span>
            </div>
        </div>
    </div>

</body>
</html>
