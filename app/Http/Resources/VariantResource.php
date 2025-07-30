<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
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
            'quantity' => $this->quantity(),
            'history' => VariantQuantityChangeCollection::make($this->variantQuantityChanges),
            'price' => $this->price,
            'promoted_price' => $this->promoted_price,
            'promoted_price_end' => $this->promoted_price_end,
        ];
    }
}
