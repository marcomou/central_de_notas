<?php

namespace App\Http\Controllers;

use App\Http\Requests\EcoSystemRequest;
use App\Http\Resources\EcoSystemResource;
use App\Models\EcoSystem;
use Illuminate\Http\Request;

class EcoSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $ecoSystems = EcoSystem::with('supervisingOrganization')->paginate();

        return EcoSystemResource::collection($ecoSystems);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\EcoSystemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EcoSystemRequest $request)
    {
        $ecoSystem = EcoSystem::create($request->validated());

        return new EcoSystemResource($ecoSystem);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcoSystem  $ecoSystem
     * @return \Illuminate\Http\Response
     */
    public function show(EcoSystem $ecoSystem)
    {
        $ecoSystem->load('supervisingOrganization');
        
        return new EcoSystemResource($ecoSystem);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\EcoSystemRequest  $request
     * @param  \App\Models\EcoSystem  $ecoSystem
     * @return \Illuminate\Http\Response
     */
    public function update(EcoSystemRequest $request, EcoSystem $ecoSystem)
    {
        $ecoSystem->update($request->validated());

        return new EcoSystemResource($ecoSystem);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcoSystem  $ecoSystem
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcoSystem $ecoSystem)
    {
        $ecoSystem->delete();

        return response()->noContent();
    }
}
