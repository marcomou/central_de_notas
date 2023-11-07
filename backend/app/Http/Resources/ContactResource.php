<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ecoMembershipLoaded = $this->relationLoaded('ecoMembership');

        return [
            'id' => $this->id,
            'role' => $this->role,
            'name' => $this->name,
            'eco_membership' => $this->when(
                $ecoMembershipLoaded,
                new EcoMembershipResource($this->ecoMembership)
            ),
            'document' => $this->document,
            'email' => $this->email,
            'phone' => $this->phone,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
