<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;


/**
 * @OA\Schema(
 *     title="Product",
 *     description="Product model",
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="description", type="string"),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="type_id", type="integer"),
 *     @OA\Property(property="min_price", type="number", format="float", readOnly=true),
 *     @OA\Property(property="max_price", type="number", format="float", readOnly=true),
 *     @OA\Property(property="rating", type="number", format="float", nullable=true, readOnly=true),
 *     @OA\Property(property="product_images", type="array", @OA\Items(ref="#/components/schemas/ProductImage"), readOnly=true),
 *     @OA\Property(property="variants", type="array", @OA\Items(ref="#/components/schemas/Variant"), readOnly=true),
 *     @OA\Property(property="attributes", type="object", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
 * )
 */
class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id',
        'type_id',
        'attributes',
    ];

    public function productImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->oldest('order');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function favoredUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorite_products');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(Variant::class);
    }

    public function quantity(): float
    {
        return array_sum(array_map(fn (Variant $variant) => $variant->quantity(), $this->variants()->get()->all()));
    }

    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function productType(): BelongsTo
    {
        return $this->belongsTo(ProductType::class, 'type_id');
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Get the minimum price from all variants
     */
    public function minPrice(): float
    {
        return $this->variants()->min('price') ?? 0;
    }

    /**
     * Get the maximum price from all variants
     */
    public function maxPrice(): float
    {
        return $this->variants()->max('price') ?? 0;
    }

    /**
     * Get average rating for this product
     */
    public function rating(): float | null
    {
        return $this->ratings()->avg('rate');
    }

    #[Scope]
    protected function priceInRange(Builder $query, float $min, float $max)
    {
        $query->whereHas('variants', function (Builder $variantQuery) use ($min, $max) {
            $variantQuery->whereBetween('price', [$min, $max]);
        });
    }

    #[Scope]
    protected function ofType(Builder $query, int $typeId)
    {
        $query->where('type_id', $typeId);
    }

    #[Scope]
    protected function search(Builder $query, string $term)
    {
        $query->where('name', 'like', '%' . $term . '%')
              ->orWhere('description', 'like', '%' . $term . '%');
    }

    #[Scope]
    protected function withAttributes(Builder $query, array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $query->whereJsonContains('attributes->' . $key, $value);
        }
    }

    #[Scope]
    protected function ofCategory(Builder $query, int $categoryId)
    {
        $query->whereHas('productType', function (Builder $typeQuery) use ($categoryId) {
            $typeQuery->where('category_id', $categoryId);
        });
    }
}
