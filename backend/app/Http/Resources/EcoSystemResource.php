<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EcoSystemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $locationLoaded = $this->relationLoaded('location');
        $supervisingOrganizationLoaded = $this->relationLoaded('supervisingOrganization');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'location' => $this->when(
                $locationLoaded,
                new LocationResource($this->location)
            ),
            'supervising_organization' => $this->when(
                $supervisingOrganizationLoaded,
                new OrganizationResource($this->supervisingOrganization)
            ),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
