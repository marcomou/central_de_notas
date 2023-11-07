<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomologationDiagnosticResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $homologationProcessLoaded = $this->relationLoaded('homologationProcess');
        $ecoMembershipLoaded = $this->relationLoaded('ecoMembership');
        $authorLoaded = $this->relationLoaded('author');

        return [
            'id' => $this->id,
            'status' => $this->status,
            'annotation' => $this->annotation,
            'homologation_process' => $this->when($homologationProcessLoaded, new HomologationProcessResource($this->homologationProcess)),
            'eco_membership' => $this->when($ecoMembershipLoaded, new HomologationProcessResource($this->ecoMembership)),
            'author' => $this->when($authorLoaded, new HomologationProcessResource($this->author)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
