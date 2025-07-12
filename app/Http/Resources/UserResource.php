<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

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
            'addresses' => new AddressCollection($this->addresses),
        ];
    }
}
