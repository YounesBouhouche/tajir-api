<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /** @use HasFactory<\Database\Factories\ProductFactory> */
    use HasFactory;

    protected $guarded = [];

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

//    public function carts(): BelongsToMany
//    {
//        return $this->belongsToMany()
//    }

    public function isOwnedBy(User $user)
    {
        return $this->user_id === $user->id;
    }

    public function minPrice(): float
    {
        return floatval($this->variants()->min('price'));
    }

    public function maxPrice(): float
    {
        return floatval($this->variants()->max('price'));
    }
}
