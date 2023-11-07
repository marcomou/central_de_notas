<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HomologationProcessDocumentTypeResource extends JsonResource
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
            'code' => $this->code,
            'description' => $this->description,
            'allow_digital' => $this->allow_digital,
            'is_mandatory' => (bool) $this->pivot->is_mandatory,
            'created_at' => $this->pivot->created_at,
            'updated_at' => $this->pivot->updated_at,
        ];
    }
}
