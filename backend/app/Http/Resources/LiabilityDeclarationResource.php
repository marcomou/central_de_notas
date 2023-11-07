<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LiabilityDeclarationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $materialTypeLoaded = $this->relationLoaded('materialType');
        $ecoDutyLoadedLoaded = $this->relationLoaded('ecoDuty');
        $ecoMembershipLoadedd = $this->relationLoaded('ecoMembership');

        return [
            'id' => $this->id,
            'mass_kg' => $this->mass_kg,
            'material_type' => $this->when($materialTypeLoaded, new MaterialTypeResource($this->materialType)),
            'eco_duty' => $this->when($ecoDutyLoadedLoaded, new EcoDutyResource($this->ecoDuty)),
            'eco_membership' => $this->when($ecoMembershipLoadedd, new EcoMembershipResource($this->ecoMembership)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
