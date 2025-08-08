<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Arr;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Get products list
        $items = $this->variants()->get()->all();

        // Convert to array
        $json = array_map(function ($item) {
            $product = $item->product;
            return [
                'id' => $item->id,
                'product' => $product->id,
                'name' => $item->name,
                'price' => $item->price,
                'promoted_price' => $item->promoted_price,
                'promoted_price_end' => $item->promoted_price_end,
                'quantity' => $item->pivot->quantity,
            ];
        }, $items);

        // Group by id
        $ids = array_unique(array_map(fn ($item) => $item['product'], $json));
        $products = [];
        foreach ($ids as $id) {
            $variants = array_map(
                fn($item) => Arr::except($item, ['product']),
                array_filter($json, fn ($item) => $item['product'] == $id)
            );
            $price = array_sum(array_map(fn ($variant) => $variant['price'] * $variant['quantity'], $variants));
            $products[] = [
                'id' => $id,
                'product' => ProductResource::make(Product::findOrFail($id)),
                'variants' => $variants,
                'price' => $price
            ];
        }

        return [
            'id' => $this->id,
            'barcode' => $this->barcode,
            'subtotal' => $this->subtotal,
            'shipping' => $this->shipping,
            'discount' => $this->discount,
            'total' => $this->total,
            'placed' => boolval($this->placed),
            'shipping_address' => isset($this->shippingAddress) ? ShippingAddressResource::make($this->shippingAddress): null,
            'products' => $products,
            'status' => $this->status,
            'date' => $this->created_at,
        ];
    }
}
