<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @OA\Schema(
 *     title="Order",
 *     description="Order model",
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="user_id", type="integer"),
 *     @OA\Property(property="shipping_address_id", type="integer"),
 *     @OA\Property(property="total_amount", type="number", format="float"),
 *     @OA\Property(property="status", type="string"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true)
 * )
 */
class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function variants(): BelongsToMany
    {
        return $this->belongsToMany(Variant::class)->withPivot(['quantity']);
    }

    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    public function isOwnedBy(int $user_id): bool
    {
        return $this->user_id == $user_id;
    }
}
