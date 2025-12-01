<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\PaginationRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;

/**
 * @OA\Tag(
 *     name="Products",
 *     description="API Endpoints for Products"
 * )
 */

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

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     summary="Get list of products",
     *     tags={"Products"},
     *     @OA\Parameter(
     *     name="type",
     *     in="query",
     *     description="Filter by product type ID",
     *     required=false,
     *     @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *     name="category",
     *     in="query",
     *     description="Filter by product category",
     *     required=false,
     *     @OA\Schema(type="string")
     *    ),
     *     @OA\Parameter(
     *     name="min_price",
     *     in="query",
     *     description="Minimum price filter",
     *     required=false,
     *     @OA\Schema(type="number", format="float")
     *    ),
     *     @OA\Parameter(
     *     name="max_price",
     *     in="query",
     *     description="Maximum price filter",
     *     required=false,
     *     @OA\Schema(type="number", format="float")
     *   ),
     *     @OA\Parameter(
     *     name="attributes",
     *     in="query",
     *     description="Filter by attributes (JSON object)",
     *     required=false,
     *     @OA\Schema(type="string", format="json")
     *  ),
     *     @OA\Parameter(
     *     name="page",
     *     in="query",
     *     description="Page number for pagination",
     *     required=false,
     *     @OA\Schema(type="integer", default=1)
     * ),
     *     @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Number of items per page for pagination",
     *     required=false,
     *     @OA\Schema(type="integer", default=15)
     * ),
     *     @OA\Response(
     *      response=200,
     *      description="Successful operation",
     *      @OA\JsonContent(
     *      type="array",
     *      @OA\Items(ref="#/components/schemas/Product")
     *    )
     *  )
     * )
     */
    public function index(PaginationRequest $request)
    {
        $type = $request->input('type');
        $category = $request->input('category');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $attributes = $request->input('attributes', []);
        $query = Product::query()->with('variants');
        if (isset($type)) {
            $query->ofType(intval($type));
        }
        if (isset($category)) {
            $query->ofCategory($category);
        }
        if (isset($minPrice) or isset($maxPrice)) {
            $query->priceInRange(floatval($minPrice) ?? 0, floatval($maxPrice) ?? PHP_FLOAT_MAX);
        }
        if (!empty($attributes)) {
            $query->withAttributes($attributes);
        }
        $products = $query->paginate(perPage: $request->getPerPage(), page: $request->getPage());
        return $this->sendPaginatedResponse(
            $products,
            'Products retrieved successfully',
            collectionTransformer: fn ($collection) => ProductCollection::make($collection)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     summary="Get product details",
     *     tags={"Products"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Product ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *    ),
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Product")
     *   ),
     *     @OA\Response(
     *     response=404,
     *     description="Product not found"
     *  )
     * )
     */
    public function show(Product $product)
    {
        if (isset($product)) {
            return $this->sendResponse(ProductResource::make($product));
        }
        return $this->sendError('Not found');
    }

    /**
     * @throws Throwable
     */
    public function store(StoreProductRequest $request)
    {
        $validator = $request->getValidatorInstance();
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        $data = $validator->validated();
        $product = Auth::user()->products()->create([
            'name' => $data['name'],
            'description' => $data['description'],
            'product_type_id' => $request->input('type_id'),
            'attributes' => $request->input('attributes', []),
        ]);
        DB::transaction(function () use ($data, $product) {
            foreach ($data['variants'] as $variant) {
                $product->variants()->create($variant);
            }
        });
        return $this->sendResponse(ProductResource::make($product), 'Product created successfully', 201);
    }

    /**
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     summary="Update a product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Product ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *     @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *     required={"name","description","variants"},
     *     @OA\Property(property="name", type="string"),
     *     @OA\Property(property="description", type="string"),
     *     @OA\Property(
     *     property="variants",
     *     type="array",
     *     @OA\Items(ref="#/components/schemas/Variant"),
     *     )
     *   )
     * ),
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/Product")
     *  ),
     *     @OA\Response(
     *     response=400,
     *     description="Validation error"
     * ),
     *     @OA\Response(
     *     response=403,
     *     description="Unauthorized"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Product not found"
     * )
     * )
     */
    public function update(int $id, Request $request)
    {
        // Check existence
        $product = Product::find($id);
        if (!isset($product)) {
            return $this->sendError('Not found');
        }

        // Check ownership
        if (!$product->isOwnedBy(Auth::user())) {
            return $this->sendError('Unauthorized', ['You are not allowed to delete this product'], 403);
        }

        // Check request
        $validator = $this->validateRequest($request);
        if ($validator->fails()) {
            return $this->sendError('Validation error', $validator->errors(), 400);
        }

        // Update
        $data = $validator->getData();
        $product->update(Arr::except($data, ['variants']));
        $product->variants()->delete();
        foreach ($data['variants'] as $variant) {
            $product->variants()->create($variant);
        }

        return $this->sendResponse(ProductResource::make($product));
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     summary="Delete a product",
     *     tags={"Products"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="Product ID",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *     @OA\Response(
     *     response=200,
     *     description="Successful operation",
     *     @OA\JsonContent(
     *     type="boolean",
     *     example=true
     *    )
     *  ),
     *     @OA\Response(
     *     response=403,
     *     description="Unauthorized"
     * ),
     *     @OA\Response(
     *     response=404,
     *     description="Product not found"
     * )
     * )
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return $this->sendResponse(true);
    }
}
