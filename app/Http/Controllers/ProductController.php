<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Models\User;
use App\Models\Variant;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductController extends BaseController
{
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
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'variants' => 'required|array|min:1',
            'variants.*.name' => 'required|string',
            'variants.*.quantity' => 'required|integer|min:0',
        ]);
        if ($validator->fails())
            return $this->sendError('Validation error', $validator->errors(), 400);

        $data = $validator->getData();
        $product = new Product([
            'name' => $data['name'],
            'description' => $data['description'],
        ]);
        User::find(Auth::id())->products()->save($product);
        if($data['variants'] ?? false) {
            foreach ($data['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        }

        return $this->sendResponse(ProductResource::make($product), 'Product created successfully', 201);
    }

    public function update(Product $product)
    {
//        $data = request()->validate([
//            'name' => 'required',
//            'description' => 'required',
//            'variants' => 'required|array',
//            'productImages' => 'required|array',
//        ]);
//        $product->update(Arr::except($data, ['variants', 'productImages']));
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
