<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\BaseController;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\ProductTypeResource;
use App\Models\ProductType;

/**
 * @OA\Tag(
 *     name="Product Types",
 *     description="API Endpoints for Product Types"
 * )
 */
class ProductTypeController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/product-types",
     *     summary="Get list of product types",
     *     tags={"Product Types"},
     *     @OA\Parameter(
     *         name="category",
     *         in="query",
     *         description="Filter by product category",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Page number for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=1)
     *     ),
     *     @OA\Parameter(
     *         name="per_page",
     *         in="query",
     *         description="Number of items per page for pagination",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/ProductType")),
     *             @OA\Property(property="meta", type="object")
     *         )
     *     )
     * )
     */
    public function index(PaginationRequest $request)
    {
        $query = ProductType::query();
        $category = $request->input('category');
        if ($category) {
            $query->ofCategory($category);
        }
        $result = $query->paginate(perPage: $request->getPerPage(), page: $request->getPage());
        return $this->sendPaginatedResponse(
            $result,
            message: 'Product types retrieved successfully',
            collectionTransformer: fn ($items) => ProductTypeResource::collection($items)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/product-types/{id}",
     *     summary="Get product type by ID",
     *     tags={"Product Types"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Product Type ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(ref="#/components/schemas/ProductType")
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Product Type not found"
     *     )
     * )
     */
    public function show(ProductType $productType)
    {
        return $this->sendResponse(ProductTypeResource::make($productType));
    }
}
