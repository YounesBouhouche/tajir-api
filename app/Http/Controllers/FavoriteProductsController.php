<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FavoriteProductsController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(ProductCollection::make(Auth::user()->favorites()->get()));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int',
            'favorite' => 'required|bool'
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);
        $product = Product::find($validator->getValue('id'));
        if (!isset($product))
            return $this->sendError('Not found');

        if (
            $validator->getValue('favorite') and
            (!Auth::user()->favorites()->where('product_id', $product->id)->exists())
        ) {
            Auth::user()->favorites()->attach($product);
        } else if (!$validator->getValue('favorite')) {
            Auth::user()->favorites()->detach($product->id);
        }

        return $this->sendResponse(ProductResource::make($product));
    }
}
