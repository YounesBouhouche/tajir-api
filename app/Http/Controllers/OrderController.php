<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderCollection;
use App\Http\Resources\OrderResource;
use App\Mail\OrderPlacement;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class OrderController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(OrderCollection::make(Auth::user()->orders));
    }

    public function show(int $id)
    {
        $order = Order::find($id);
        if (!isset($order))
            return $this->sendError('Not found');
        if (!$order->isOwnedBy(Auth::id()))
            return $this->sendError('Unauthorized', code: 403);
        return $this->sendResponse(OrderResource::make($order));
    }

    public function update(int $id, Request $request)
    {
        $order = Order::find($id);
        if (!isset($order))
            return $this->sendError('Not found');
        if (!$order->isOwnedBy(Auth::id()))
            return $this->sendError('Unauthorized', code: 403);


        $validator = Validator::make($request->all(), [
            'address' => 'required|int',
        ]);
        if ($validator->fails())
            return $this->sendError('Bad request', $validator->errors(), 400);
        $address = Address::find($validator->getData()['address']);
        if (!isset($address))
            return $this->sendError('Not found');
        if (!$order->isOwnedBy(Auth::id()))
            return $this->sendError('Unauthorized, you don\'t own this address', code: 403);

        $order->update(['address_id' => $address->id]);
        return $this->sendResponse(OrderResource::make($order));
    }

    public function place(int $id)
    {
        $order = Order::find($id);
        if (!isset($order))
            return $this->sendError('Not found');
        if (!$order->isOwnedBy(Auth::id()))
            return $this->sendError('Unauthorized', code: 403);
        if ($order->placed != 0)
            return $this->sendError('Already placed', code: 400);

        $order->update(['placed' => 1, 'delivery_eta' => now()->addDays(2)->toDate()]);
        Mail::send((new OrderPlacement($order))->to(Auth::user()->email)->build());
        return $this->sendResponse(OrderResource::make($order));
    }
}
