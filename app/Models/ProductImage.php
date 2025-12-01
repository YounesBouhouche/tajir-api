<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 *     title="ProductImage",
 *     description="ProductImage model",
 *     @OA\Property(property="id", type="integer", readOnly=true),
 *     @OA\Property(property="product_id", type="integer"),
 *     @OA\Property(property="url", type="string"),
 *     @OA\Property(property="alt", type="string", nullable=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
 * )
 */
class ProductImage extends Model
{
    /** @use HasFactory<\Database\Factories\ProductImageFactory> */
    use HasFactory;

    protected $guarded = [];
}
