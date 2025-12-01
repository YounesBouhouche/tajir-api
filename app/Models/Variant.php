<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @OA\Schema(
 *     title="Variant",
 *     description="Variant model",
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="price", type="number", format="float"),
 *     @OA\Property(property="promotional_price", type="number", format="float", nullable=true),
 *     @OA\Property(property="promotional_price_start", type="date-time", nullable=true),
 *     @OA\Property(property="promotional_price_end", type="date-time", nullable=true),
 *     @OA\Property(property="attributes", type="object", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 *     )
 */
class Variant extends Model
{
    /** @use HasFactory<\Database\Factories\VariantFactory> */
    use HasFactory;

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function carts(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class)->withPivot('quantity');
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class)->withPivot('quantity');
    }

    public function variantQuantityChanges(): HasMany
    {
        return $this->hasMany(VariantQuantityChange::class);
    }

    public function quantity(): int
    {
        return $this->variantQuantityChanges()->sum('amount');
    }
}
