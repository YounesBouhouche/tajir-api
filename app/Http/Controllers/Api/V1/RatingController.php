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

class RatingController extends BaseController
{
    /**
     * Display all resources.
     */
    public function index()
    {
        return $this->sendResponse(RatingWithProductCollection::make(Auth::user()->ratings));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        if (!Rating::where('id', $id)->exists())
            return $this->sendError('Rating not found');
        return $this->sendResponse(RatingResource::make(Rating::find($id)));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
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
