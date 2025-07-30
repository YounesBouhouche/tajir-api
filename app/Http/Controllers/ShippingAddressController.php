<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShippingAddressCollection;
use App\Http\Resources\ShippingAddressResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ShippingAddressController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->sendResponse(ShippingAddressCollection::make(Auth::user()->shippingAddresses));
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->sendResponse(ShippingAddressResource::make(Auth::user()->shippingAddresses()->findOrFail($id)));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Auth::user()->shippingAddresses()->delete($id);
        return $this->sendResponse("");
    }
}
