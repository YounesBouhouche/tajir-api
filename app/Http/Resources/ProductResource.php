<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ProductResource extends JsonResource
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
            'description' => $this->description,
            'min_price' => $this->minPrice(),
            'max_price' => $this->maxPrice(),
            'quantity' => $this->quantity(),
            'variants' => new VariantCollection($this->variants),
            'category' => $this->category,
            'product_images' => ProductImageCollection::make($this->productImages),
            'is_favorite' => Auth::user() !== null ? Auth::user()->isFavorite($this->id) : false
        ];
    }
}
