<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 28px;
        }
        .order-info {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 25px;
        }
        .order-info p {
            margin: 5px 0;
        }
        .order-info strong {
            color: #555;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .items-table th {
            background-color: #4CAF50;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }
        .items-table tr:hover {
            background-color: #f5f5f5;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 15px;
            border-top: 2px solid #4CAF50;
        }
        .total-section p {
            margin: 5px 0;
            font-size: 18px;
        }
        .total-section .total-amount {
            font-size: 24px;
            font-weight: bold;
            color: #4CAF50;
        }
        .shipping-info {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 5px;
            margin-top: 25px;
        }
        .shipping-info h3 {
            margin-top: 0;
            color: #555;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>✓ Order Confirmed!</h1>
            <p>Thank you for your order</p>
        </div>

        <p>Hello {{ $order->user->name }},</p>
        <p>Your order has been successfully placed and is being processed.</p>

        <div class="order-info">
            <p><strong>Order ID:</strong> #{{ $order->id }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y') }}</p>
            <p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>
        </div>

        <h3>Order Details</h3>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->variants as $variant)
                <tr>
                    <td>{{ $variant->product->name }}</td>
                    <td>{{ $variant->name }}</td>
                    <td>{{ $variant->pivot->quantity }}</td>
                    <td>${{ number_format($variant->price, 2) }}</td>
                    <td>${{ number_format($variant->price * $variant->pivot->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <p class="total-amount">Total: ${{ number_format($order->total_price, 2) }}</p>
        </div>

        @if($order->shippingAddress)
        <div class="shipping-info">
            <h3>Shipping Address</h3>
            <p>{{ $order->shippingAddress->street_address }}</p>
            <p>{{ $order->shippingAddress->city }}, {{ $order->shippingAddress->state }} {{ $order->shippingAddress->zip_code }}</p>
            <p>{{ $order->shippingAddress->country }}</p>
        </div>
        @endif

        <div class="footer">
            <p>If you have any questions about your order, please contact us.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>

