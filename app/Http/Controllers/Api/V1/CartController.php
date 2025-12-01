<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CartResource;
use App\Http\Resources\OrderResource;
use App\Models\Cart;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Cart",
 *     description="API Endpoints for Shopping Cart"
 * )
 */
class CartController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/cart",
     *     summary="Get the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     )
     * )
     */
    public function index()
    {
        $cart = Auth::user()->cart;
        if (!isset($cart)) {
            return $this->sendError('Not found');
        }
        return $this->sendResponse(CartResource::make($cart));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/cart",
     *     summary="Add items to the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="variant", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Items added to cart successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*.variant' => 'required|int',
            'items.*.quantity' => 'required|int',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $items = $validator->getData()['items'];
        $nonExistingItems = array_filter($items, fn ($item) => !Variant::exists($item['variant']));
        if (!empty($nonExistingItems)) {
            return $this->sendError(
                'Some variants does not exist',
                [
                    'ids' => implode(',', array_map(fn ($item) => $item['variant'], $nonExistingItems))
                ]
            );
        }

        $cart = Auth::user()->getCart();
        foreach ($items as $item) {
            $cart->variants()->attach($item['variant'], Arr::only($item, ['quantity']));
        }

        return $this->sendResponse(CartResource::make($cart), code: 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart",
     *     summary="Delete items from the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="items",
     *                 type="array",
     *                 @OA\Items(type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Items deleted from cart successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     )
     * )
     */
    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'items' => 'required|array|min:1',
            'items.*' => 'int',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $cart = Auth::user()->cart;

        if (!isset($cart)) {
            return $this->sendError('Not found');
        }

        foreach ($validator->getData()['items'] as $variant) {
            $cart->variants()->detach($variant);
        }
        return $this->sendResponse(CartResource::make($cart));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/cart/items/{item}",
     *     summary="Edit the quantity of a variant in the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="item",
     *         in="path",
     *         description="Variant ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="quantity", type="integer", example=2)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Variant quantity updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Cart")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Cart not found"
     *     )
     * )
     */
    public function edit(Variant $variant, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|int',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $cart = Auth::user()->cart;

        if (!isset($cart)) {
            return $this->sendError('Not found');
        }

        $cart->variants()->updateExistingPivot($variant->id, [
            'quantity' => $validator->getValue('quantity'),
        ]);
        return $this->sendResponse(CartResource::make($cart));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/cart/",
     *     summary="Clear the authenticated user's cart",
     *     tags={"Cart"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Cart cleared successfully"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function destroy()
    {
        Auth::user()->cart->variants()->detach();
        Auth::user()->cart->delete();
        return $this->sendResponse('Done');
    }

}
