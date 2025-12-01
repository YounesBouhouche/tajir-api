<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\RatingResource;
use App\Http\Resources\RatingWithProductCollection;
use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Ratings",
 *     description="API Endpoints for Product Ratings"
 * )
 */
class RatingController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/user/ratings",
     *     summary="Get list of ratings for authenticated user",
     *     tags={"Ratings"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of ratings",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Rating"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index()
    {
        return $this->sendResponse(RatingWithProductCollection::make(Auth::user()->ratings));
    }


    /**
     * @OA\Post(
     *     path="/api/v1/user/ratings",
     *     summary="Create a new rating for a product",
     *     tags={"Ratings"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="product", type="integer", example=1),
     *             @OA\Property(property="rate", type="integer", example=5),
     *             @OA\Property(property="note", type="string", example="Great product!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rating created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Rating")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Validation error"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function store(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'product' => 'required|int',
            'rate' => 'required|int|max:5',
            'note' => 'string'
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        if (!Product::where('id', $validator->getValue('product'))->exists())
            return $this->sendError('Product not found');

        $rating = new Rating([
            'product_id' => $validator->getValue('product'),
            'rate' => $validator->getValue('rate'),
            'note' => $validator->getValue('note'),
        ]);
        User::find(Auth::id())->ratings()->save($rating);

        return $this->sendResponse(RatingResource::make($rating));
    }

    /**
     * @OA\Get(
     *     path="/api/v1/user/ratings/{id}",
     *     summary="Get a specific rating by ID",
     *     tags={"Ratings"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Rating ID",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of rating",
     *         @OA\JsonContent(ref="#/components/schemas/Rating")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rating not found"
     *     )
     * )
     */
    public function show(string $id)
    {
        if (!Rating::where('id', $id)->exists())
            return $this->sendError('Rating not found');
        return $this->sendResponse(RatingResource::make(Rating::find($id)));
    }


    public function update(Request $request, string $id)
    {
        if (!Rating::where('id', $id)->exists())
            return $this->sendError('Rating not found');

        $rating = Rating::find($id);
        if (!$rating->isOwnedBy(Auth::id()))
            return $this->sendError('Not allowed', code: 403);
        $validator = Validator::make($request->all(), [
            'rate' => 'required|int|max:5',
            'note' => 'string'
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $rating->update(Arr::only($validator->getData(), ['rate', 'note']));
        return $this->sendResponse(RatingResource::make($rating));
    }


    public function destroy(string $id)
    {
        if (!Rating::where('id', $id)->exists())
            return $this->sendError('Rating not found');
        $rating = Rating::find($id);
        if (!$rating->isOwnedBy(Auth::id()))
            return $this->sendError('Not allowed', code: 403);
        $rating->delete();
        return $this->sendResponse('Deleted');
    }
}
