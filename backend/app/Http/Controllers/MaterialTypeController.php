<?php

namespace App\Http\Controllers;

use App\Http\Requests\MaterialTypeRequest;
use App\Http\Resources\MaterialTypeResource;
use App\Models\MaterialType;
use Illuminate\Http\Request;

class MaterialTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $materialTypes = MaterialType::all();

        return MaterialTypeResource::collection($materialTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\MaterialTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MaterialTypeRequest $request)
    {
        $materialType = MaterialType::create($request->validated());

        return new MaterialTypeResource($materialType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\MaterialType  $materialType
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialType $materialType)
    {
        $materialType->load([
            'materialTypeParent',
            'subMaterialTypes'
        ]);

        return new MaterialTypeResource($materialType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\MaterialTypeRequest  $request
     * @param  \App\Models\MaterialType  $materialType
     * @return \Illuminate\Http\Response
     */
    public function update(MaterialTypeRequest $request, MaterialType $materialType)
    {
        $materialType->update($request->validated());

        return new MaterialTypeResource($materialType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\MaterialType  $materialType
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialType $materialType)
    {
        $materialType->delete();

        return response()->noContent();
    }
}
