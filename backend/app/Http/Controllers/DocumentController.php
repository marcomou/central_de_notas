<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentRequest;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $documents = Document::with([
            'uploaderUser',
            'documentType',
            'ecoMembership'
        ])->paginate();

        return DocumentResource::collection($documents);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\DocumentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentRequest $request)
    {
        $document = Document::create($request->validated());

        return new DocumentResource($document);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function show(Document $document)
    {
        $document->load([
            'uploaderUser',
            'documentType',
            'ecoMembership'
        ]);

        return new DocumentResource($document);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\DocumentRequest  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(DocumentRequest $request, Document $document)
    {
        $document->update($request->validated());

        return new DocumentResource($document);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function destroy(Document $document)
    {
        $document->delete();

        return response()->noContent();
    }
}
