<?php

namespace App\Http\Controllers;

use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends BaseController
{
    public function index()
    {
        $cart = Cart::whereBelongsTo(Auth::user());
        if (!isset($cart))
            return $this->sendError('Not found');
        return $this->sendResponse(CartResource::make($cart));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.product' => 'required|int',
            'items.*.variant' => 'required|int',
            'items.*.quantity' => 'required|int',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $items = $validator->getData()['items'];
        $nonExistingItems = array_filter($items, fn($item) => !Product::exists($item['product']));
        if (!empty($nonExistingItems))
            return $this->sendError(
                'Some products does not exist',
                [
                    'ids' => implode(',', array_map(fn($item) => $item['product'], $nonExistingItems))
                ]
            );

        $cart = Auth::user()->getCart();
        foreach ($items as $item) {
            $cart->products()->attach($item['product'], Arr::only($item, ['variant', 'quantity']));
        }

        return $this->sendResponse(CartResource::make($cart), code: 201);
    }

    public function remove(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*' => 'int',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $cart = Auth::user()->cart;

        if (!isset($cart))
            return $this->sendError('Not found');

        $products = $cart->products()->get();
        foreach ($validator->getData()['items'] as $item) {
            if (isset($products[$item]))
                $cart->products()->detach($products[$item]->getKey());
        }
        return $this->sendResponse(CartResource::make($cart));
    }

    public function destroy()
    {
        Auth::user()->cart->products()->detach();
        Auth::user()->cart->delete();
        return $this->sendResponse('Done');
    }

    public function checkout()
    {
        // Check for cart existence
        if (!Auth::user()->hasCart())
            return $this->sendError('Not found');

        // Generate data
        $cart = Auth::user()->cart;
        $order = Auth::user()->orders()->create([
            'barcode' => fake()->iban(fake()->countryCode()),
            'subtotal' => $cart->total(),
            'total' => $cart->total(),
        ]);
        foreach ($cart->products as $item) {
            $order->products()->attach($item, [
                'variant' => $item->pivot->variant,
                'quantity' => $item->pivot->quantity,
            ]);
        }
        $this->destroy();

        return $this->sendResponse(OrderResource::make($order), code: 201);
    }
}
