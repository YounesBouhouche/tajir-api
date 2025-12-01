<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="User",
 *     description="API Endpoints for User"
 * )
 */
class UserController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/user",
     *     summary="Get authenticated user details",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function index()
    {
        return $this->sendResponse(UserResource::make(Auth::user()));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user",
     *     summary="Update authenticated user details",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *             @OA\Property(property="image", type="string", format="url", example="https://example.com/image.jpg")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/User")
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
    public function update(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'email' => 'email|unique:users',
            'image' => 'url|nullable'
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        // Update
        Auth::user()->update($validator->getData());
        return $this->sendResponse(UserResource::make(Auth::user()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/products",
     *     summary="Get products of authenticated user",
     *     tags={"User"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated"
     *     )
     * )
     */
    public function products()
    {
        return $this->sendResponse(ProductCollection::make(Auth::user()->products));
    }
}
