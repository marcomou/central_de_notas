<?php

namespace App\Http\Controllers;

use App\Http\Requests\LiabilityDeclaration\StoreRequest as StoreLiabilityDeclarationRequest;
use App\Http\Requests\LiabilityDeclaration\UpdateRequest as UpdateLiabilityDeclarationRequest;
use App\Http\Resources\LiabilityDeclarationResource;
use App\Models\LiabilityDeclaration;
use Illuminate\Http\Request;

class LiabilityDeclarationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $liabilityDeclarations = LiabilityDeclaration::with([
            'ecoMembership',
            'materialType'
        ])->paginate();

        return LiabilityDeclarationResource::collection($liabilityDeclarations);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\LiabilityDeclaration\StoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLiabilityDeclarationRequest $request)
    {
        $liabilityDeclaration = LiabilityDeclaration::create($request->validated());

        return new LiabilityDeclarationResource($liabilityDeclaration);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\LiabilityDeclaration  $liabilityDeclaration
     * @return \Illuminate\Http\Response
     */
    public function show(LiabilityDeclaration $liabilityDeclaration)
    {
        $liabilityDeclaration->load([
            'ecoMembership',
            'materialType'
        ]);

        return new LiabilityDeclarationResource($liabilityDeclaration);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\LiabilityDeclaration\UpdateRequest  $request
     * @param  \App\Models\LiabilityDeclaration  $liabilityDeclaration
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLiabilityDeclarationRequest $request, LiabilityDeclaration $liabilityDeclaration)
    {
        $liabilityDeclaration->update($request->validated());

        return new LiabilityDeclarationResource($liabilityDeclaration);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\LiabilityDeclaration  $liabilityDeclaration
     * @return \Illuminate\Http\Response
     */
    public function destroy(LiabilityDeclaration $liabilityDeclaration)
    {
        $liabilityDeclaration->delete();

        return response()->noContent();
    }
}
