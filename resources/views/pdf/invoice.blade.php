<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .invoice-box {
            padding: 30px;
        }

        .header {
            margin-bottom: 40px;
            border-bottom: 2px solid #EAB308;
            padding-bottom: 20px;
        }

        .header table {
            width: 100%;
            border: none;
        }

        .header td {
            vertical-align: top;
        }

        .title {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            text-transform: uppercase;
            margin: 0;
        }

        .company-info {
            text-align: right;
            line-height: 1.4;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            color: #EAB308;
        }

        .billing-info {
            margin-bottom: 40px;
        }

        .billing-info table {
            width: 100%;
        }

        .billing-info td {
            width: 50%;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            font-size: 10px;
            color: #888;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .items-table th {
            background: #fdf6b2;
            border: 1px solid #eee;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            text-transform: uppercase;
        }

        .items-table td {
            border: 1px solid #eee;
            padding: 10px;
            line-height: 1.4;
        }

        .price-col {
            text-align: right;
            width: 100px;
        }

        .summary {
            float: right;
            width: 250px;
        }

        .summary table {
            width: 100%;
        }

        .summary td {
            padding: 5px 0;
        }

        .summary .total {
            border-top: 2px solid #000;
            font-weight: bold;
            font-size: 14px;
            padding-top: 10px;
        }

        .footer {
            position: fixed;
            bottom: 30px;
            left: 30px;
            right: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #888;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <table>
                <tr>
                    <td>
                        <div class="title">Invoice</div>
                        <p style="margin-top: 10px;">
                            <strong>Order Number:</strong> {{ $order->order_number }}<br>
                            <strong>Invoice Date:</strong> {{ $order->created_at->format('d M Y') }}<br>
                            <strong>Due Date:</strong> Upon Receipt
                        </p>
                    </td>
                    <td class="company-info">
                        @if(!empty($settings['invoice_logo']) && file_exists(public_path('storage/' . $settings['invoice_logo'])))
                            <img src="data:image/{{ pathinfo($settings['invoice_logo'], PATHINFO_EXTENSION) }};base64,{{ base64_encode(file_get_contents(public_path('storage/' . $settings['invoice_logo']))) }}"
                                 style="max-height: 50px; margin-bottom: 10px;" alt="Logo">
                        @endif
                        <div class="company-name">{{ $settings['invoice_company_name'] ?? 'Jabulani Group' }}</div>
                        <p>
                            {!! nl2br(e($settings['invoice_company_address'] ?? "123 Logistics Way\nJohannesburg, 2000\nSouth Africa")) !!}<br>
                            <strong>Phone:</strong> {{ $settings['invoice_company_phone'] ?? '' }}<br>
                            <strong>Email:</strong> {{ $settings['invoice_company_email'] ?? 'finance@jabulanigroup.co.za' }}
                        </p>
                    </td>
                </tr>
            </table>
        </div>

        <div class="billing-info">
            <table>
                <tr>
                    <td>
                        <div class="label">Billed To</div>
                        <strong>{{ $order->customer_name }}</strong><br>
                        {{ $order->customer_email }}<br>
                        {{ $order->customer_phone }}
                    </td>
                    <td>
                        <div class="label">Shipping / Fulfillment</div>
                        @if($order->order_type === 'delivery')
                            <strong>Address:</strong><br>
                            {{ $order->customer_address }}<br>
                            {{ $order->customer_city }}, {{ $order->customer_postal_code }}
                        @else
                            <strong>Collection Point:</strong><br>
                            {{ $order->store->name }}<br>
                            {{ $order->store->address }}
                        @endif
                    </td>
                </tr>
            </table>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align: center; width: 60px;">Qty</th>
                    <th class="price-col">Unit Price</th>
                    <th class="price-col">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name }}</strong><br>
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td class="price-col">R{{ number_format($item->price, 2) }}</td>
                        <td class="price-col">R{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <table>
                <tr class="total">
                    <td>Total Amount</td>
                    <td style="text-align: right; color: #EAB308;">R{{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        <div style="clear: both; margin-top: 60px;">
            <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
            @if($order->payment_method === 'eft' && count($eft_accounts) > 0)
                <div style="background: #fdf6b2; padding: 15px; border-radius: 4px; font-size: 10px;">
                    <strong>Official Settlement Accounts for EFT:</strong><br>
                    @foreach($eft_accounts as $index => $acc)
                        {{ $index + 1 }}. {{ $acc['bank'] }} | {{ $acc['name'] }} | {{ $acc['number'] }} | Code: {{ $acc['code'] }}<br>
                    @endforeach
                    Reference: <strong>{{ $order->order_number }}</strong>
                </div>
            @endif
        </div>

        <div class="footer">
            <p>{{ $settings['invoice_footer_text'] ?? 'Thank you for your business. For any queries, please contact our support team.' }}</p>
            <p>{{ $settings['invoice_company_name'] ?? 'Jabulani Group' }} (Pty) Ltd | {{ $settings['invoice_registration_number'] ?? 'Reg No: 2023/123456/07' }}</p>
        </div>
    </div>
</body>

</html>