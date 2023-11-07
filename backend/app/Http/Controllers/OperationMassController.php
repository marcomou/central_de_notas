<?php

namespace App\Http\Controllers;

use App\Http\Requests\OperationMassRequest;
use App\Http\Resources\OperationMassResource;
use App\Models\OperationMass;
use Illuminate\Http\Request;

class OperationMassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $operationMasses = OperationMass::with([
            'ecoMembership',
            'materialType'
        ])->paginate();

        return OperationMassResource::collection($operationMasses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\OperationMassRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OperationMassRequest $request)
    {
        $operationMass = OperationMass::create($request->validated());

        return new OperationMassResource($operationMass);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\OperationMass  $operationMass
     * @return \Illuminate\Http\Response
     */
    public function show(OperationMass $operationMass)
    {
        $operationMass->load([
            'ecoMembership',
            'materialType'
        ]);

        return new OperationMassResource($operationMass);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\OperationMassRequest  $request
     * @param  \App\Models\OperationMass  $operationMass
     * @return \Illuminate\Http\Response
     */
    public function update(OperationMassRequest $request, OperationMass $operationMass)
    {
        $operationMass->update($request->validated());

        return new OperationMassResource($operationMass);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\OperationMass  $operationMass
     * @return \Illuminate\Http\Response
     */
    public function destroy(OperationMass $operationMass)
    {
        $operationMass->delete();

        return response()->noContent();
    }
}
