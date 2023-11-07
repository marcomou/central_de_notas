<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $uploaderUserLoaded = $this->relationLoaded('uploaderUser');
        $documentTypeLoaded = $this->relationLoaded('documentType');
        $ecoMembershipLoaded = $this->relationLoaded('ecoMembership');

        return [
            'id' => $this->id,
            'url' => $this->url,
            'file_name' => $this->file_name,
            'file_path' => $this->file_path,
            'annotation' => $this->annotation,
            'metadata' => $this->metadata,
            'uploader_user' => $this->when($uploaderUserLoaded, new UserResource($this->uploaderUser)),
            'document_type' => $this->when($documentTypeLoaded, new DocumentTypeResource($this->documentType)),
            'eco_membership' => $this->when($ecoMembershipLoaded, new EcoMembershipResource($this->ecoMembership)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
