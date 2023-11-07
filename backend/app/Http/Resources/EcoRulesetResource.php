<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EcoRulesetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ecoSystemLoaded = $this->relationLoaded('ecoSystem');
        
        return [
            'id' => $this->id,
            'duty_year' => $this->duty_year,
            'rules' => $this->rules,
            'eco_system' => $this->when($ecoSystemLoaded, new EcoSystemResource($this->ecoSystem)),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
