<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $parentMaterialTypeLoaded = $this->relationLoaded('parentMaterialType');

        return [
            'id' => $this->id,
            'code' => $this->code,
            'name' => $this->name,
            'extra' => $this->extra,
            'parent_material_type' => $this->when($parentMaterialTypeLoaded, new MaterialTypeResource('parentMaterialType')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
