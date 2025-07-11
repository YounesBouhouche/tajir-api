<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
    private function validateRequest(Request $request)
    {
        return Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);
    }

    public function index()
    {
        $products = Product::latest()->get();
        return $this->sendResponse(ProductCollection::make($products));
    }
    public function show(int $id)
    {
        $product = Product::find($id);
        if (isset($product))
            return $this->sendResponse(ProductResource::make($product));
        return $this->sendError('Not found');
    }

    public function store(Request $request)
    {
        $validator = $this->validateRequest($request);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $data = $validator->getData();
        $product = new Product([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        User::find(Auth::id())->products()->save($product);
        foreach ($data['variants'] as $variant) {
            $product->variants()->create($variant);
        }

        return $this->sendResponse(ProductResource::make($product), 'Product created successfully', 201);
    }

    public function update(int $id, Request $request)
    {
        // Check existence
        $product = Product::find($id);
        if (!isset($product))
            return $this->sendError('Not found');

        // Check ownership
        if (!$product->isOwnedBy(Auth::user()))
            return $this->sendError('Unauthorized', ['You are not allowed to delete this product'], 403);

        // Check request
        $validator = $this->validateRequest($request);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        // Update
        $data = $validator->getData();
        $product->update(Arr::except($data, ['variants']));
        $product->variants()->delete();
        foreach ($data['variants'] as $variant) {
            $product->variants()->create($variant);
        }

        return $this->sendResponse(ProductResource::make($product));
    }

    public function destroy(int $id)
    {
        // Check existence
        $product = Product::find($id);
        if (!isset($product))
            return $this->sendError('Not found');

        // Check ownership
        if (!$product->isOwnedBy(Auth::user()))
            return $this->sendError('Unauthorized', ['You are not allowed to delete this product'], 403);

        // Delete
        $product->delete();
        return $this->sendResponse(true);
    }
}
