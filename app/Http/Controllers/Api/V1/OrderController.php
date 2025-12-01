<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\OrderPlaced;
use App\Http\Requests\PaginationRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\Models\ShippingAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

/**
 * @OA\Tag(
 *     name="Orders",
 *     description="API Endpoints for Orders",
 * )
 */
class OrderController extends BaseController
{
    /**
     * @OA\Get(
     *     path="/api/v1/orders",
     *     summary="Get list of orders for authenticated user",
     *     tags={"Orders"},
     *     security={{"sanctum": {}}},
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
     *         description="Number of items per page",
     *         required=false,
     *         @OA\Schema(type="integer", default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of orders",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Order"))
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     )
     * )
     */
    public function index(PaginationRequest $request)
    {
        $orders = Auth::user()->orders()
            ->latest()
            ->eagerLoadRelations(['variants', 'shippingAddress'])
            ->paginate(page: $request->getPage(), perPage: $request->getPerPage());

        return $this->sendPaginatedResponse(
            $orders,
            message: 'Orders retrieved successfully',
            collectionTransformer: fn ($collection) => OrderResource::collection($collection)
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v1/orders/{id}",
     *     summary="Get order details by ID",
     *     tags={"Orders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful retrieval of order",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
     */
    public function show(int $id)
    {
        Validator::make(['id' => $id], [
            'id' => 'required|int|exists:orders,id'
        ])->validate();
        return $this->sendResponse(OrderResource::make(Order::query()->findOrFail($id)));
    }

    /**
     * @OA\Put(
     *     path="/api/v1/orders/{id}",
     *     summary="Update shipping address for an order",
     *     tags={"Orders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="address", type="integer", description="Shipping Address ID")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful update of order",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order or Address not found"
     *     )
     * )
    */
    public function update(int $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|int|exists:orders,id',
            'address' => 'required|int|exists:shipping_addresses,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Bad request', $validator->errors(), 400);
        }
        $order = Order::get($id)->load('variants');
        $address = ShippingAddress::find($validator->getData()['address']);
        if (!isset($address)) {
            return $this->sendError('Not found');
        }

        if (!$order->isOwnedBy(Auth::id())) {
            return $this->sendError('Unauthorized, you don\'t own this address', code: 403);
        }

        $order->update(['shipping_address_id' => $address->id]);
        return $this->sendResponse(OrderResource::make($order));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/orders/{id}/place",
     *     summary="Place an order",
     *     tags={"Orders"},
     *     security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Order ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful placement of order",
     *         @OA\JsonContent(ref="#/components/schemas/Order")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Order not found"
     *     )
     * )
    */
    public function place(int $id)
    {
        $order = Order::find($id)->load('variants');
        if (!isset($order)) {
            return $this->sendError('Not found');
        }
        if (!$order->isOwnedBy(Auth::id())) {
            return $this->sendError('Unauthorized', code: 403);
        }
        if ($order->placed != 0) {
            return $this->sendError('Already placed', code: 400);
        }

        foreach ($order->variants as $variant) {
            $variant->variantQuantityChanges()->create([
                'amount' => 0 - $variant->pivot->quantity,
                'user_id' => $order->user->id
            ]);
        }
        $order->update(['status' => 'PENDING', 'delivery_eta' => now()->addDays(2)->toDate()]);
        OrderPlaced::dispatch($order);
        return $this->sendResponse(OrderResource::make($order));
    }
}
