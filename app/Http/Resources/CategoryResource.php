<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $products = $this->products();

        // Apply price filtering if min_price or max_price is provided
        if ($request->has('min_price')) {
            $products = $products->whereHas('variants', function($query) use ($request) {
                $query->where('price', '>=', $request->get('min_price'));
            });
        }

        if ($request->has('max_price')) {
            $products = $products->whereHas('variants', function($query) use ($request) {
                $query->where('price', '<=', $request->get('max_price'));
            });
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'products_count' => $products->count(),
            'price_range' => [
                'min_price' => $this->getMinProductPrice(),
                'max_price' => $this->getMaxProductPrice(),
                'currency' => config('app.currency', 'USD')
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    /**
     * Get minimum product price in this category
     */
    private function getMinProductPrice()
    {
        return $this->products()
            ->join('variants', 'products.id', '=', 'variants.product_id')
            ->min('variants.price') ?? 0;
    }

    /**
     * Get maximum product price in this category
     */
    private function getMaxProductPrice()
    {
        return $this->products()
            ->join('variants', 'products.id', '=', 'variants.product_id')
            ->max('variants.price') ?? 0;
    }
}
