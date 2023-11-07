<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentTypeRequest;
use App\Http\Resources\DocumentTypeResource;
use App\Models\DocumentType;
use Illuminate\Http\Request;

class DocumentTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $documentTypes = DocumentType::query()
            ->when($request->get('search', false), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->search}%");
            })
            ->get();

        return DocumentTypeResource::collection($documentTypes);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\DocumentTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentTypeRequest $request)
    {
        $documentType = DocumentType::create($request->validated());

        return new DocumentTypeResource($documentType);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function show(DocumentType $documentType)
    {
        return new DocumentTypeResource($documentType);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DocumentTypeRequest  $request
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentTypeRequest $request, DocumentType $documentType)
    {
        $documentType->update($request->validated());

        return new DocumentTypeResource($documentType);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DocumentType  $documentType
     * @return \Illuminate\Http\Response
     */
    public function destroy(DocumentType $documentType)
    {
        $documentType->delete();

        return response()->noContent();
    }
}
