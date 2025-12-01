<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteProductsController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/user/favorites",
     *     summary="Get list of favorite products for authenticated user",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *    ),
     *    @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *    )
     * )
     */
    public function index()
    {
        return $this->sendResponse(ProductCollection::make(Auth::user()->favorites));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/favorites",
     *     summary="Add a product to authenticated user's favorites",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         @OA\Property(property="product_id", type="integer", example=1),
     *     )
     *   ),
     *     @OA\Response(
     *     response=200,
     *     description="Product added to favorites successfully",
     *   ),
     *    @OA\Response(
     *     response=400,
     *     description="Validation error",
     *    ),
     *    @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *    )
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }
        $productId = $validator->getValue('product_id');
        Auth::user()->favorites()->syncWithoutDetaching([$productId]);
        return $this->sendResponse(null, 'Product added to favorites successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/favorites",
     *     summary="Remove a product from authenticated user's favorites",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *         @OA\Property(property="product_id", type="integer", example=1),
     *     )
     *   ),
     *     @OA\Response(
     *     response=200,
     *     description="Product removed from favorites successfully",
     *   ),
     *    @OA\Response(
     *     response=400,
     *     description="Validation error",
     *    ),
     *    @OA\Response(
     *     response=401,
     *     description="Unauthenticated",
     *    )
     * )
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|integer|exists:products,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }
        $productId = $validator->getValue('product_id');
        Auth::user()->favorites()->detach($productId);
        return $this->sendResponse(null, 'Product removed from favorites successfully');
    }
}
