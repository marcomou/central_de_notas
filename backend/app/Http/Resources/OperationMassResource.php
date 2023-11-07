<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OperationMassResource extends JsonResource
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
        $ecoMembershipLoaded = $this->relationLoaded('ecoMembership');

        return [
            'id' => $this->id,
            'operation_mass_type' => $this->operation_mass_type,
            'mass_kg' => $this->mass_kg,
            'work_year' => $this->work_year,
            'material_type' => $this->when($materialTypeLoaded, new MaterialTypeResource($this->materialType)),
            'eco_membership' => $this->when($ecoMembershipLoaded, new EcoMembershipResource($this->ecoMembership)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];

    }
}
