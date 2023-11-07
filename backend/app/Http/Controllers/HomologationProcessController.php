<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomologationProcessesDocumentTyRequest;
use App\Http\Requests\HomologationProcessRequest;
use App\Http\Resources\HomologationProcessDocumentTypeResource;
use App\Http\Resources\HomologationProcessResource;
use App\Models\DocumentType;
use App\Models\HomologationProcess;
use Illuminate\Http\Request;

class HomologationProcessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $homologationProcesses = HomologationProcess::with([
            'ecoRuleset',
        ])->paginate();

        return HomologationProcessResource::collection($homologationProcesses);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\HomologationProcessRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HomologationProcessRequest $request)
    {
        $homologationProcess = HomologationProcess::create($request->validated());

        return new HomologationProcessResource($homologationProcess);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\HomologationProcess  $homologationProcess
     * @return \Illuminate\Http\Response
     */
    public function show(HomologationProcess $homologationProcess)
    {
        $homologationProcess->load([
            'ecoRuleset',
            'documentTypes'
        ]);

        return new HomologationProcessResource($homologationProcess);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\HomologationProcessRequest  $request
     * @param  \App\Models\HomologationProcess  $homologationProcess
     * @return \Illuminate\Http\Response
     */
    public function update(HomologationProcessRequest $request, HomologationProcess $homologationProcess)
    {
        $homologationProcess->update($request->validated());

        return new HomologationProcessResource($homologationProcess);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\HomologationProcess  $homologationProcess
     * @return \Illuminate\Http\Response
     */
    public function destroy(HomologationProcess $homologationProcess)
    {
        $homologationProcess->delete();

        return response()->noContent();
    }

    public function documentTypes(HomologationProcess $homologationProcess)
    {
        $homologationProcess->load('documentTypes');

        return HomologationProcessDocumentTypeResource::collection($homologationProcess->documentTypes);
    }

    public function attachDocumentType(
        HomologationProcessesDocumentTyRequest $request,
        HomologationProcess $homologationProcess,
        DocumentType $documentType
    ) {
        $homologationProcess->documentTypes()->attach($documentType, ['is_mandatory' => $request->is_mandatory]);

        $homologationProcess->load('documentTypes');

        return HomologationProcessDocumentTypeResource::collection($homologationProcess->documentTypes);
    }

    public function detachDocumentType(
        HomologationProcess $homologationProcess,
        DocumentType $documentType
    ) {
        $homologationProcess->documentTypes()->detach($documentType);

        $homologationProcess->load('documentTypes');

        return HomologationProcessDocumentTypeResource::collection($homologationProcess->documentTypes);
    }

    public function updateDocumentType(
        HomologationProcessesDocumentTyRequest $request,
        HomologationProcess $homologationProcess,
        DocumentType $documentType
    ) {
        $homologationProcess->documentTypes()->updateExistingPivot($documentType, ['is_mandatory' => $request->is_mandatory]);

        $homologationProcess->load('documentTypes');

        return HomologationProcessDocumentTypeResource::collection($homologationProcess->documentTypes);
    }
}
