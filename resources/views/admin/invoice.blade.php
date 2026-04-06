<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - #PAY-{{ $payment->id }}</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --primary: #6366f1;
            --dark: #1e293b;
            --light: #f8fafc;
            --border: #e2e8f0;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: 'Plus Jakarta Sans', 'Inter', sans-serif;
            background-color: #f1f5f9;
            color: var(--text-main);
            line-height: 1.5;
            -webkit-print-color-adjust: exact;
        }

        .invoice-container {
            max-width: 850px;
            margin: 40px auto;
            background: white;
            padding: 50px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.05);
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        /* Watermark */
        .invoice-container::before {
            content: 'PAID';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 15rem;
            font-weight: 900;
            color: rgba(16, 185, 129, 0.05);
            pointer-events: none;
            z-index: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 50px;
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-size: 28px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 32px;
            font-weight: 900;
            color: var(--dark);
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .invoice-meta {
            margin-bottom: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        .meta-section h3 {
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1px;
            margin-bottom: 12px;
            border-bottom: 2px solid var(--primary);
            display: inline-block;
        }

        .meta-content p {
            margin-bottom: 4px;
            font-size: 14px;
        }

        .meta-content strong { color: var(--dark); }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            position: relative;
            z-index: 1;
        }

        .items-table th {
            text-align: left;
            padding: 15px;
            background: var(--light);
            font-size: 12px;
            text-transform: uppercase;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
        }

        .items-table td {
            padding: 20px 15px;
            border-bottom: 1px solid var(--border);
            font-size: 14px;
        }

        .total-section {
            display: flex;
            justify-content: flex-end;
            position: relative;
            z-index: 1;
        }

        .total-box {
            width: 300px;
            background: var(--light);
            padding: 20px;
            border-radius: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .total-row.grand-total {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 2px solid var(--border);
            font-size: 20px;
            font-weight: 800;
            color: var(--primary);
        }

        .footer {
            margin-top: 100px;
            text-align: center;
            color: var(--text-muted);
            font-size: 12px;
            border-top: 1px solid var(--border);
            padding-top: 30px;
            position: relative;
            z-index: 1;
        }

        /* Floating Print Button */
        .print-btn {
            position: fixed;
            bottom: 40px;
            right: 40px;
            background: var(--primary);
            color: white;
            border: none;
            padding: 15px 25px;
            border-radius: 50px;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 10px 30px rgba(99, 102, 241, 0.4);
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            z-index: 1000;
        }

        .print-btn:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(99, 102, 241, 0.5);
        }

        @media print {
            .print-btn { display: none !important; }
            body { background: white; }
            .invoice-container { 
                margin: 0; 
                box-shadow: none; 
                padding: 30px;
                width: 100%;
                max-width: 100%;
            }
        }
    </style>
</head>
<body>

    <button class="print-btn" onclick="window.print()">
        <i class="bi bi-printer-fill"></i> Print Now
    </button>

    <div class="invoice-container">
        <!-- Decoration side color -->
        <div style="position:absolute; top:0; left:0; width:6px; height:100%; background:var(--primary);"></div>

        <div class="header">
            <div class="brand">
                <a href="#" class="brand-logo">
                    <i class="bi bi-shield-lock-fill"></i> CRM
                </a>
                <p style="margin-top:10px; font-size:12px; color:var(--text-muted);">
                    ERP Platform for Modern Teams<br>
                    123 Business Avenue, Suite 500<br>
                    New York, NY 10001
                </p>
            </div>
            <div class="invoice-title">
                <h1>Invoice</h1>
                <p style="color:var(--text-muted); font-weight:600;">#PAY-{{ str_pad($payment->id, 5, '0', STR_PAD_LEFT) }}</p>
                <p style="margin-top:15px; font-size:14px;">Date: <strong>{{ $payment->transaction_date->format('M d, Y') }}</strong></p>
            </div>
        </div>

        <div class="invoice-meta">
            <div class="meta-section">
                <h3>Bill To</h3>
                <div class="meta-content" style="margin-top: 15px;">
                    <p style="font-size: 18px; font-weight: 700; color: var(--dark);">{{ $payment->order->client_name }}</p>
                    <p><strong>{{ $payment->order->company_name }}</strong></p>
                    <p>{{ $payment->order->emails[0] ?? '' }}</p>
                    <p>{{ $payment->order->phones[0]['number'] ?? '' }}</p>
                </div>
            </div>
            <div class="meta-section">
                <h3>Payment Details</h3>
                <div class="meta-content" style="margin-top: 15px;">
                    <p>Method: <strong>{{ $payment->payment_method }}</strong></p>
                    <p>Ref ID: <span class="mono" style="font-size:12px; font-weight:600;">{{ $payment->transaction_id ?? 'N/A' }}</span></p>
                    <p>Status: <span style="background:rgba(16, 185, 129, 0.1); color:#10b981; padding:3px 8px; border-radius:5px; font-size:12px; font-weight:700;">{{ $payment->status->name ?? 'PAID' }}</span></p>
                    <p>Order Ref: <strong>#ORD-{{ $payment->order_id }}</strong></p>
                </div>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align:right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div style="font-weight:700; color:var(--dark); margin-bottom:5px;">Service Payment</div>
                        <div style="font-size:12px; color:var(--text-muted);">
                            Payment contribution for services under Order #{{ $payment->order_id }}
                        </div>
                    </td>
                    <td style="text-align:right; font-weight:700; font-size:16px;">₹{{ number_format($payment->amount, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <div class="total-section">
            <div class="total-box">
                <div class="total-row">
                    <span>Subtotal</span>
                    <span style="font-weight:600;">₹{{ number_format($payment->amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax (0%)</span>
                    <span style="font-weight:600;">₹0.00</span>
                </div>
                <div class="total-row grand-total">
                    <span>Total</span>
                    <span>₹{{ number_format($payment->amount, 2) }}</span>
                </div>
            </div>
        </div>

        @if($payment->notes)
        <div style="margin-top:40px; position:relative; z-index:1;">
            <h3 style="font-size:12px; text-transform:uppercase; color:var(--text-muted); margin-bottom:10px;">Notes</h3>
            <p style="font-size:13px; color:var(--text-muted); font-style:italic;">
                {{ $payment->notes }}
            </p>
        </div>
        @endif

        <div class="footer">
            <p style="font-weight:700; color:var(--dark); margin-bottom:5px;">Thank you for your business!</p>
            <p>If you have any questions about this invoice, please contact support@crm.com</p>
        </div>
    </div>

</body>
</html>
