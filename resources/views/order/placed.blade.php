@props(['order'])

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans">
    <div class="max-w-2xl mx-auto bg-white shadow-lg">
        <!-- Header -->
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 text-white p-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold mb-2">Order Confirmed!</h1>
                <p class="text-blue-100 text-lg">Thank you for your purchase</p>
            </div>
        </div>

        <!-- Order Summary Header -->
        <div class="p-8 border-b border-gray-200">
            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                <div class="mb-4 md:mb-0">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-2">Order #{{ $order->id ?? 'ORDER_NUMBER' }}</h2>
                    <p class="text-gray-600">Placed on {{ $order->created_at ? $order->created_at->format('F j, Y') : 'ORDER_DATE' }}</p>
                </div>
                <div class="bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold">
                    {{ $order->placed ?? 'Confirmed' }}
                </div>
            </div>
        </div>

        <!-- Customer Information -->
        <div class="p-8 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Customer Information</h3>
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Billing Address</h4>
                    <div class="text-gray-600 space-y-1">
                        <p class="font-medium">{{ $order->user->name ?? 'CUSTOMER_NAME' }}</p>
                        <p>{{ $order->billing_address->address_line_1 ?? 'ADDRESS_LINE_1' }}</p>
                        @if($order->billing_address->address_line_2 ?? false)
                        <p>{{ $order->billing_address->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->address->city ?? 'CITY' }}, {{ $order->address->state ?? 'STATE' }} {{ $order->address->postal_code ?? 'ZIP' }}</p>
                        <p>{{ $order->address->country ?? 'COUNTRY' }}</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-700 mb-2">Shipping Address</h4>
                    <div class="text-gray-600 space-y-1">
                        <p class="font-medium">{{ $order->address->name ?? $order->user->name ?? 'CUSTOMER_NAME' }}</p>
                        <p>{{ $order->address->address_line_1 ?? 'ADDRESS_LINE_1' }}</p>
                        @if($order->address->address_line_2 ?? false)
                        <p>{{ $order->address->address_line_2 }}</p>
                        @endif
                        <p>{{ $order->address->city ?? 'CITY' }}, {{ $order->address->state ?? 'STATE' }} {{ $order->address->postal_code ?? 'ZIP' }}</p>
                        <p>{{ $order->address->country ?? 'COUNTRY' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-8 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-6">Order Items</h3>
            <div class="space-y-4">
                @if($order->products ?? false)
                    @foreach($order->products as $product)
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            @if($product->productImages->first())
                                <img src="{{ $product->productImages->first()->url }}" alt="{{ $product->name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">{{ $product->name }}</h4>
                            <p class="text-gray-600 text-sm">{{ $product->description ?? 'Product description' }}</p>
                            <p class="text-gray-600 text-sm">Quantity: {{ $product->pivot->quantity ?? 1 }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">${{ number_format($product->pivot->price ?? $product->price, 2) }}</p>
                        </div>
                    </div>
                    @endforeach
                @else
                    <!-- Placeholder for when no products are loaded -->
                    <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg">
                        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="font-medium text-gray-800">PRODUCT_NAME</h4>
                            <p class="text-gray-600 text-sm">PRODUCT_DESCRIPTION</p>
                            <p class="text-gray-600 text-sm">Quantity: QUANTITY</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-800">$PRICE</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Order Total -->
        <div class="p-8 border-b border-gray-200">
            <div class="max-w-md ml-auto space-y-3">
                <div class="flex justify-between text-gray-600">
                    <span>Subtotal:</span>
                    <span>${{ number_format($order->subtotal ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Shipping:</span>
                    <span>${{ number_format($order->shipping_cost ?? 0, 2) }}</span>
                </div>
                <div class="flex justify-between text-gray-600">
                    <span>Tax:</span>
                    <span>${{ number_format($order->tax ?? 0, 2) }}</span>
                </div>
                <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between text-xl font-bold text-gray-800">
                        <span>Total:</span>
                        <span>${{ number_format($order->total ?? 0, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Information -->
        <div class="p-8 border-b border-gray-200">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Shipping Information</h3>
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 0V6a2 2 0 012-2h4a2 2 0 012 2v1M8 7h8m-8 0l-2 9a2 2 0 002 2h8a2 2 0 002-2L16 7m-8 0V5a2 2 0 012-2h4a2 2 0 012 2v2m-6 0h4"></path>
                    </svg>
                    <span class="font-medium text-blue-800">Estimated Delivery</span>
                </div>
                <p class="text-blue-700">{{ $order->estimated_delivery ?? 'ESTIMATED_DELIVERY_DATE' }}</p>
                <p class="text-blue-600 text-sm mt-1">Tracking information will be sent to your email once your order ships.</p>
            </div>
        </div>

        <!-- Customer Support -->
        <div class="p-8 bg-gray-50">
            <div class="text-center">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Need Help?</h3>
                <p class="text-gray-600 mb-4">If you have any questions about your order, feel free to contact us.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="mailto:support@example.com" class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Email Support
                    </a>
                    <a href="tel:+1234567890" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 text-white font-medium rounded-lg hover:bg-gray-700 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        Call Us
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-800 text-white p-8 text-center">
            <p class="text-gray-300 mb-2">Thank you for shopping with us!</p>
            <p class="text-gray-400 text-sm">© 2025 Your Company Name. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
