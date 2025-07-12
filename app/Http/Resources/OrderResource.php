<?php

namespace App\Http\Resources;

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
        $items = $this->products()->get()->all();

        // Convert to array
        $json = array_map(function ($item) {
            return [
                'id' => $item->id,
                'variant' => $item->pivot->variant,
                'quantity' => $item->pivot->quantity,
            ];
        }, $items);

        // Group by id
        $ids = array_unique(array_map(fn ($item) => $item['id'], $json));
        $grouped = [];
        foreach ($ids as $id) {
            $variants = array_map(
                fn($item) => Arr::only($item, ['variant', 'quantity']),
                array_filter($json, fn ($item) => $item['id'] == $id)
            );
            $grouped[] = [
                'id' => $id,
                'variants' => $variants,
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
            'address' => isset($this->address) ? AddressResource::make($this->address): null,
            'products' => $grouped
        ];
    }
}
