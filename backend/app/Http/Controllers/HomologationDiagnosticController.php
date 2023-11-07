<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomologationDiagnosticRequest;
use App\Http\Resources\HomologationDiagnosticResource;
use App\Http\Resources\HomologationProcessResource;
use App\Models\HomologationDiagnostic;
use Illuminate\Http\Request;

class HomologationDiagnosticController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homologationDiagnostics = HomologationDiagnostic::with([
            'author',
            'ecoMembership',
            'homologationProcess'
        ])->paginate();

        return HomologationProcessResource::collection($homologationDiagnostics);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HomologationDiagnosticRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HomologationDiagnosticRequest $request)
    {
        $homologationDiagnostic = HomologationDiagnostic::create($request->validated());

        return new HomologationDiagnosticResource($homologationDiagnostic);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomologationDiagnostic  $homologationDiagnostic
     * @return \Illuminate\Http\Response
     */
    public function show(HomologationDiagnostic $homologationDiagnostic)
    {
        $homologationDiagnostic->load([
            'author',
            'ecoMembership',
            'homologationProcess'
        ]);

        return new HomologationDiagnosticResource($homologationDiagnostic);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HomologationDiagnosticRequest  $request
     * @param  \App\Models\HomologationDiagnostic  $homologationDiagnostic
     * @return \Illuminate\Http\Response
     */
    public function update(HomologationDiagnosticRequest $request, HomologationDiagnostic $homologationDiagnostic)
    {
        $homologationDiagnostic->update($request->validated());

        return new HomologationDiagnosticResource($homologationDiagnostic);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomologationDiagnostic  $homologationDiagnostic
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomologationDiagnostic $homologationDiagnostic)
    {
        $homologationDiagnostic->delete();

        return response()->noContent();
    }
}
