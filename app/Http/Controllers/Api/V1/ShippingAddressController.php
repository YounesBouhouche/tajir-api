<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\ShippingAddressCollection;
use App\Http\Resources\ShippingAddressResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Shipping Addresses",
 *     description="API Endpoints for Managing User Shipping Addresses"
 * )
 */
class ShippingAddressController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/user/shipping-addresses",
     *     summary="Get All Shipping Addresses",
     *     tags={"Shipping Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of shipping addresses",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(
     *                 property="data",
     *                 type="array",
     *                 @OA\Items(ref="#/components/schemas/ShippingAddress")
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
        return $this->sendResponse(ShippingAddressCollection::make(Auth::user()->shippingAddresses));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/user/shipping-addresses",
     *     summary="Create a New Shipping Address",
     *     tags={"Shipping Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"street_address", "city", "state", "country", "zip_code"},
     *             @OA\Property(property="street_address", type="string", example="123 Main St"),
     *             @OA\Property(property="city", type="string", example="New York"),
     *             @OA\Property(property="state", type="string", example="NY"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="zip_code", type="string", example="10001")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shipping address created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/User"),
     *             @OA\Property(property="message", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="data", type="object")
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
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'street_address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);
        Auth::user()->shippingAddresses()->create(
            Arr::only(
                $validator->getData(),
                [
                    'street_address',
                    'city',
                    'state',
                    'country',
                    'zip_code',
                ]
            )
        );
        return $this->sendResponse(UserResource::make(Auth::user()));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/shipping-addresses/{id}",
     *     summary="Get a Specific Shipping Address",
     *     tags={"Shipping Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shipping address ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of shipping address",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ShippingAddress"),
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shipping address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        return $this->sendResponse(ShippingAddressResource::make(Auth::user()->shippingAddresses()->findOrFail($id)));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/user/shipping-addresses/{id}",
     *     summary="Update a Shipping Address",
     *     tags={"Shipping Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shipping address ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="street_address", type="string", example="456 Oak Ave"),
     *             @OA\Property(property="city", type="string", example="Los Angeles"),
     *             @OA\Property(property="state", type="string", example="CA"),
     *             @OA\Property(property="country", type="string", example="USA"),
     *             @OA\Property(property="zip_code", type="string", example="90001")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shipping address updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="data", ref="#/components/schemas/ShippingAddress"),
     *             @OA\Property(property="message", type="string", example="Success")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shipping address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'street_address' => 'sometimes|string',
            'city' => 'sometimes|string',
            'state' => 'sometimes|string',
            'country' => 'sometimes|string',
            'zip_code' => 'sometimes|string',
        ]);

        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $address = Auth::user()->shippingAddresses()->findOrFail($id);
        $address->update($validator->validated());

        return $this->sendResponse(ShippingAddressResource::make($address));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/user/shipping-addresses/{id}",
     *     summary="Delete a Shipping Address",
     *     tags={"Shipping Addresses"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Shipping address ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Shipping address deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
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
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Shipping address not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Not found")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $address = Auth::user()->shippingAddresses()->findOrFail($id);
        $address->delete();
        return $this->sendResponse("");
    }
}
