<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: #000000;
            padding: 40px 20px;
            text-align: center;
        }

        .header h1 {
            color: #EAB308;
            margin: 0;
            font-size: 28px;
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .header p {
            color: #ffffff;
            margin-top: 10px;
            font-size: 14px;
            opacity: 0.8;
        }

        .content {
            padding: 40px;
        }

        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #000;
        }

        .order-info {
            background: #fafafa;
            border: 1px solid #eee;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
        }

        .order-info p {
            margin: 5px 0;
            font-size: 14px;
        }

        .order-number {
            color: #EAB308;
            font-weight: bold;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        .items-table th {
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #eee;
            font-size: 12px;
            text-transform: uppercase;
            color: #888;
        }

        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .items-table .price {
            text-align: right;
            font-weight: bold;
        }

        .totals {
            float: right;
            width: 250px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
            font-size: 14px;
        }

        .total-row.grand-total {
            border-top: 2px solid #000;
            margin-top: 10px;
            padding-top: 10px;
            font-weight: bold;
            font-size: 18px;
            color: #EAB308;
        }

        .footer {
            background: #000;
            color: #ffffff;
            padding: 30px;
            text-align: center;
            font-size: 12px;
        }

        .footer p {
            margin: 5px 0;
            opacity: 0.6;
        }

        .btn {
            display: inline-block;
            padding: 12px 25px;
            background: #EAB308;
            color: #000;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 20px;
        }

        .delivery-card {
            border-left: 4px solid #EAB308;
            background: #fffbeb;
            padding: 15px;
            border-radius: 0 6px 6px 0;
            margin-bottom: 30px;
        }

        .delivery-card h4 {
            margin: 0 0 10px 0;
            font-size: 14px;
            text-transform: uppercase;
        }

        .delivery-card p {
            margin: 2px 0;
            font-size: 13px;
            color: #666;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Jabulani Group</h1>
            <p>Quality Products | Seamless Logistics</p>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $order->customer_name }},</div>
            <p>Thank you for choosing <strong>Jabulani Group</strong>. We are pleased to confirm that your order
                <strong>#{{ $order->order_number }}</strong> has been received and is currently being prepared for
                fulfillment.
            </p>

            <div class="order-info">
                <p><strong>Order Status:</strong> {{ ucfirst($order->status) }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('M d, Y H:i') }}</p>
                <p><strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}</p>
                <p><strong>Order Type:</strong> {{ ucfirst($order->order_type) }}</p>
            </div>

            <div class="delivery-card">
                <h4>Fulfillment Details</h4>
                @if($order->order_type === 'delivery')
                    <p><strong>Site Address:</strong> {{ $order->customer_address }}</p>
                    <p>{{ $order->customer_city }}, {{ $order->customer_postal_code }}</p>
                @else
                    <p><strong>Collection Point:</strong> {{ $order->store->name }}</p>
                    <p>{{ $order->store->address }}</p>
                @endif
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item Description</th>
                        <th>Qty</th>
                        <th style="text-align: right;">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td class="price">R{{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals" style="width: 100%;">
                <div style="float: right; width: 200px;">
                    <div class="total-row">
                        <span>Subtotal:</span>
                        <span>R{{ number_format($order->total - $order->vat, 2) }}</span>
                    </div>
                    <div class="total-row">
                        <span>VAT (15%):</span>
                        <span>R{{ number_format($order->vat, 2) }}</span>
                    </div>
                    <div class="total-row grand-total">
                        <span>Total:</span>
                        <span>R{{ number_format($order->total, 2) }}</span>
                    </div>
                </div>
                <div style="clear: both;"></div>
            </div>

            <div style="text-align: center;">
                <p style="font-size: 14px; margin-top: 40px; color: #666;">You can track your order status in real-time
                    through our portal.</p>
                <a href="{{ route('order.track') }}" class="btn">Track Your Order</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Jabulani Group. All rights reserved.</p>
            <p>Johannesburg, South Africa | support@jabulanigroup.co.za</p>
            <p>Jabulani Group - Delivering Excellence Across the Continent.</p>
        </div>
    </div>
</body>

</html>