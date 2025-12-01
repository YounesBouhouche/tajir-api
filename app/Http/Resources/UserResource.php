<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'cart' => !isset($this->cart) ? null : CartResource::make($this->cart),
            'orders' => OrderCollection::make($this->orders),
            'products' => new ProductCollection($this->products),
            'shipping_addresses' => new ShippingAddressCollection($this->shippingAddresses),
            'image' => Storage::disk('public')->url($this->image),
            'ratings' => $this->ratings,
        ];
    }
}
