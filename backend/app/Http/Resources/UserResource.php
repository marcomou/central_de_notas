<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'federal_registration' => $this->federal_registration,
            'organizations' => OrganizationResource::collection($this->whenLoaded('organizations')),
            'created_at' => $this->created_at,
            'updated_at' => $this->created_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
