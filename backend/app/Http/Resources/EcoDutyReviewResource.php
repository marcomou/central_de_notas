<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EcoDutyReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $reviewerUserLoaded = $this->relationLoaded('reviewer');
        $ecoDutyLoaded = $this->relationLoaded('ecoDuty');

        return [
            'id' => $this->id,
            'sequence_number' => $this->sequence_number,
            'type' => $this->type,
            'reviewer_user' => $this->when($reviewerUserLoaded, new UserResource($this->reviewer)),
            'eco_duty' => $this->when($ecoDutyLoaded, new UserResource($this->ecoDuty)),
            'external_id' => $this->external_id,
            'comments' => $this->comments,
            'metadata' => $this->metadata,
            'reviewed_at' => $this->reviewed_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
        ];
    }
}
