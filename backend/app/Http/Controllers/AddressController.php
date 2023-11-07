<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddressRequest;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::with('organization')->paginate();

        return AddressResource::collection($addresses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AddressRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddressRequest $request)
    {
        $address = Address::create($request->validated());

        return new AddressResource($address);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function show(Address $address)
    {
        $address->load("organization");

        return new AddressResource($address);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AddressRequest  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(AddressRequest $request, Address $address)
    {
        $address->update($request->validated());

        return new AddressResource($address);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy(Address $address)
    {
        $address->delete();

        return response()->noContent();
    }
}
