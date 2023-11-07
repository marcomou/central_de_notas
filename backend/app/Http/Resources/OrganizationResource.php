<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'federal_registration' => $this->federal_registration,
            'legal_name' => $this->legal_name,
            'front_name' => $this->front_name,
            'legal_type' => $this->whenLoaded('legalType', new LegalTypeResource($this->legalType)),
            'getherer_id' => $this->getherer_id,
            'eco_duties' => EcoDutyResource::collection($this->whenLoaded('ecoDuties')),
            'eco_systems' => EcoSystemResource::collection($this->whenLoaded('ecoSystems')),
            'is_supervising_organization' => $this->is_supervising_organization,
            'is_managing_organization' => $this->is_managing_organization,
            'is_federal_organization' => $this->is_federal_organization,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
