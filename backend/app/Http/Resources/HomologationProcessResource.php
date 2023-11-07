<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomologationProcessResource extends JsonResource
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

        return [
            'id' => $this->id,
            'title' => $this->title,
            'configs' => $this->configs,
            'process_code' => $this->process_code,
            'description' => $this->description,
            'eco_ruleset' => $this->when($ecoRulesetLoaded, new EcoRulesetResource($this->ecoRuleset)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
