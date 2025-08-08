<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function getCart(): Cart
    {
        $cart = Cart::firstOrCreate(['user_id' => $this->id]);
        return $this->cart()->save($cart);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function shippingAddresses(): HasMany
    {
        return $this->hasMany(ShippingAddress::class);
    }

    public function hasCart(): bool
    {
        return isset($this->cart);
    }

    public function variantQuantityChanges(): HasMany
    {
        return $this->hasMany(VariantQuantityChange::class);
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'favorite_products');
    }

    public function history(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'history');
    }

    public function addToHistory(int $product)
    {
        $record = $this->history()->find($product);
        if (isset($record))
            $this->history()->updateExistingPivot($product, ['visited_at' => now()]);
        else
            $this->history()->attach($product, ['visited_at' => now()]);
    }

    public function isFavorite(int $id)
    {
        return $this->favorites()->where('product_id', $id)->exists();
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
