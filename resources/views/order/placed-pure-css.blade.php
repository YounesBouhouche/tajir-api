@props(['order'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        /* Reset and base styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background-color: #f3f4f6;
            line-height: 1.5;
            color: #374151;
        }

        /* Main container */
        .email-container {
            max-width: 672px;
            margin: 0 auto;
            background-color: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        /* Header styles */
        .header {
            background: linear-gradient(to right, #2563eb, #1e40af);
            color: #ffffff;
            padding: 2rem;
            text-align: center;
        }

        .header h1 {
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #dbeafe;
            font-size: 1.125rem;
        }

        /* Section styles */
        .section {
            padding: 2rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1rem;
        }

        .section-title-lg {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 1.5rem;
        }

        /* Order summary header */
        .order-summary {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .order-number {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .order-date {
            color: #6b7280;
        }

        .status-badge {
            background-color: #dcfce7;
            color: #166534;
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-weight: 600;
            display: inline-block;
            align-self: flex-start;
        }

        /* Grid layout */
        .grid {
            display: grid;
            gap: 1.5rem;
        }

        .grid-2 {
            grid-template-columns: 1fr;
        }

        /* Address sections */
        .address-section h4 {
            font-weight: 500;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .address-content {
            color: #6b7280;
        }

        .address-content p {
            margin-bottom: 0.25rem;
        }

        .address-content .name {
            font-weight: 500;
        }

        /* Product items */
        .products-container {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .product-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background-color: #f9fafb;
            border-radius: 0.5rem;
        }

        .product-image {
            width: 4rem;
            height: 4rem;
            background-color: #e5e7eb;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 0.5rem;
        }

        .product-image svg {
            width: 2rem;
            height: 2rem;
            color: #9ca3af;
        }

        .product-details {
            flex: 1;
        }

        .product-name {
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 0.25rem;
        }

        .product-description,
        .product-quantity {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .product-price {
            text-align: right;
        }

        .product-price p {
            font-weight: 600;
            color: #1f2937;
        }

        /* Order total */
        .total-section {
            max-width: 24rem;
            margin-left: auto;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.75rem;
            color: #6b7280;
        }

        .total-divider {
            border-top: 1px solid #e5e7eb;
            padding-top: 0.75rem;
            margin-top: 0.75rem;
        }

        .total-final {
            display: flex;
            justify-content: space-between;
            font-size: 1.25rem;
            font-weight: 700;
            color: #1f2937;
        }

        /* Shipping info */
        .shipping-info {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 0.5rem;
            padding: 1rem;
        }

        .shipping-header {
            display: flex;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .shipping-header svg {
            width: 1.25rem;
            height: 1.25rem;
            color: #2563eb;
            margin-right: 0.5rem;
        }

        .shipping-header span {
            font-weight: 500;
            color: #1e40af;
        }

        .shipping-date {
            color: #1d4ed8;
            margin-bottom: 0.25rem;
        }

        .shipping-note {
            color: #2563eb;
            font-size: 0.875rem;
        }

        /* Support section */
        .support-section {
            background-color: #f9fafb;
            text-align: center;
        }

        .support-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }

        .support-description {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .support-buttons {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            justify-content: center;
        }

        .support-button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            border-radius: 0.5rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }

        .support-button-primary {
            background-color: #2563eb;
            color: #ffffff;
        }

        .support-button-primary:hover {
            background-color: #1d4ed8;
        }

        .support-button-secondary {
            background-color: #4b5563;
            color: #ffffff;
        }

        .support-button-secondary:hover {
            background-color: #374151;
        }

        .support-button svg {
            width: 1.25rem;
            height: 1.25rem;
            margin-right: 0.5rem;
        }

        /* Footer */
        .footer {
            background-color: #1f2937;
            color: #ffffff;
            padding: 2rem;
            text-align: center;
        }

        .footer-main {
            color: #d1d5db;
            margin-bottom: 0.5rem;
        }

        .footer-copyright {
            color: #9ca3af;
            font-size: 0.875rem;
        }

        /* Responsive design */
        @media (min-width: 768px) {
            .order-summary {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
            }

            .grid-2 {
                grid-template-columns: 1fr 1fr;
            }

            .support-buttons {
                flex-direction: row;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>Order Confirmed!</h1>
            <p>Thank you for your purchase</p>
        </div>

        <!-- Order Summary Header -->
        <div class="section">
            <div class="order-summary">
                <div>
                    <div class="order-number">Order #{{ $order->id ?? 'ORDER_NUMBER' }}</div>
                    <p class="order-date">
                        Barcode: {{ $order->barcode }}
                        <br>
                        Placed on {{ $order->created_at ? $order->created_at->format('F j, Y') : 'ORDER_DATE' }}
                    </p>
                </div>
                <div class="status-badge">Confirmed</div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="section">
            <h3 class="section-title">Customer Information</h3>
            <div class="grid grid-2">
                <div class="address-section">
                    <h4>Billing Address</h4>
                    <div class="address-content">
                        <p class="name">{{ $order->user->name ?? 'CUSTOMER_NAME' }}</p>
                        <p>{{ $order->billing_address->address_line_1 ?? 'ADDRESS_LINE_1' }}</p>
                        @if($order->billing_address->address_line_2 ?? false)
                        <p>{{ $order->billing_address->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->address->city ?? 'CITY' }}, {{ $order->address->state ?? 'STATE' }} {{ $order->address->zip_code ?? 'ZIP' }}</p>
                        <p>{{ $order->address->country ?? 'COUNTRY' }}</p>
                    </div>
                </div>
                <div class="address-section">
                    <h4>Shipping Address</h4>
                    <div class="address-content">
                        <p>{{ $order->address->street_address ?? 'ADDRESS_LINE' }}</p>
                        <p>{{ $order->address->city ?? 'CITY' }}, {{ $order->address->state ?? 'STATE' }} {{ $order->address->zip_code ?? 'ZIP' }}</p>
                        <p>{{ $order->address->country ?? 'COUNTRY' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="section">
            <h3 class="section-title-lg">Order Items</h3>
            <div class="products-container">
                @if($order->products ?? false)
                    @foreach($order->products as $product)
                    <div class="product-item">
                        <div class="product-image">
                            @if($product->productImages->first())
                                <img src="{{ $product->productImages->first()->url }}" alt="{{ $product->name }}">
                            @else
                                <svg style="fill: none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="product-details">
                            <div class="product-name">{{ $product->name }}</div>
                            <p class="product-description">{{ $product->description ?? 'Product description' }}</p>
                            <p class="product-quantity">Quantity: {{ $product->pivot->quantity ?? 1 }}</p>
                        </div>
                        <div class="product-price">
                            <p>${{ number_format($product->pivot->price ?? $product->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Placeholder for when no products are loaded -->
                    <div class="product-item">
                        <div class="product-image">
                            <svg style="fill: none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="product-details">
                            <div class="product-name">PRODUCT_NAME</div>
                            <p class="product-description">PRODUCT_DESCRIPTION</p>
                            <p class="product-quantity">Quantity: QUANTITY</p>
                        </div>
                        <div class="product-price">
                            <p>$PRICE</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Total -->
        <div class="section">
            <div class="total-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal ?? $order->total ?? 0, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>${{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Tax:</span>
                    <span>${{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
                <div class="total-divider">
                    <div class="total-final">
                        <span>Total:</span>
                        <span>${{ number_format($order->total ?? $order->price ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="section">
            <h3 class="section-title">Shipping Information</h3>
            <div class="shipping-info">
                <div class="shipping-header">
                    <svg style="fill: none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1M8 7h8m-8 0l-2 9a2 2 0 002 2h8a2 2 0 002-2L16 7m-8 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h4"></path>
                    </svg>
                    <span>Estimated Delivery</span>
                </div>
                <p class="shipping-date">{{ $order->delivery_eta->format('F j, Y') ?? 'ESTIMATED_DELIVERY_DATE' }}</p>
                <p class="shipping-note">Tracking information will be sent to your email once your order ships.</p>
            </div>
        </div>

        <!-- Customer Support -->
        <div class="section support-section">
            <h3 class="support-title">Need Help?</h3>
            <p class="support-description">If you have any questions about your order, feel free to contact us.</p>
            <div class="support-buttons">
                <a href="mailto:support@example.com" class="support-button support-button-primary">
                    <svg style="fill: none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email Support
                </a>
                <a href="tel:+1234567890" class="support-button support-button-secondary">
                    <svg style="fill: none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    Call Us
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p class="footer-main">Thank you for shopping with us!</p>
            <p class="footer-copyright">© 2025 Tajir. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
