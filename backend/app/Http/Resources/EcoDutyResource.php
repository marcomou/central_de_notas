<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EcoDutyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ecoRulesetLoaded = $this->relationLoaded('ecoRuleset');
        $managingOrganizationLoaded = $this->relationLoaded('managingOrganization');

        return [
            'id' => $this->id,
            'managing_code' => $this->managing_code,
            'status' => 'replaced',
            'meta' => $this->meta,
            'metadata' => $this->metadata,
            'eco_ruleset' => $this->when($ecoRulesetLoaded, new EcoRulesetResource($this->ecoRuleset)),
            'managing_organization' => $this->when($managingOrganizationLoaded, new OrganizationResource($this->managingOrganization)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
