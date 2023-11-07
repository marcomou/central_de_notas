<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EcoMembershipResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $ecoDutyLoaded = $this->relationLoaded('ecoDuty');
        $throughMembershipLoaded = $this->relationLoaded('throughMembership');
        $memberOrganizationLoaded = $this->relationLoaded('memberOrganization');

        return [
            'id' => $this->id,
            'member_role' => $this->member_role,
            'homologated' => (bool) $this->homologated,
            'eco_duty' => $this->when(
                $ecoDutyLoaded,
                new EcoDutyResource($this->ecoDuty)
            ),
            'member_organization' => $this->when(
                $memberOrganizationLoaded,
                new OrganizationResource($this->memberOrganization)
            ),
            'through_membership' => $this->when(
                $throughMembershipLoaded,
                new OrganizationResource($this->throughMembership)
            ),
            'contacts' => ContactResource::collection($this->whenLoaded('contacts')),
            'extra' => $this->extra,
            'created_at' => $this->deleted_at,
            'updated_at' => $this->deleted_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
