<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\ProductCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="History",
 *     description="API Endpoints for User History"
 * )
 */
class HistoryController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/user/history",
     *     summary="Get User Browsing History",
     *     tags={"History"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of user browsing history",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/Product")
     *             ),
     *             @OA\Property(property="message", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     )
     * )
     */
    public function index()
    {
        return $this->sendResponse(ProductCollection::make(Auth::user()->history()));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/history",
     *     summary="Add Product to User Browsing History",
     *     tags={"History"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"product_id"},
     *             @OA\Property(property="product_id", type="integer", description="ID of the product to add to history")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Product added to history successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Product added to history")
     *         )
     *     )
     * )
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);
        Auth::user()->addToHistory($validated['product_id']);
        return $this->sendResponse([], 'Product added to history');
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/history",
     *     summary="Clear User Browsing History",
     *     tags={"History"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="User browsing history cleared successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="User history cleared")
     *         )
     *     )
     * )
     */
    public function destroy()
    {
        Auth::user()->clearHistory();
        return $this->sendResponse([], 'User history cleared');
    }
}
