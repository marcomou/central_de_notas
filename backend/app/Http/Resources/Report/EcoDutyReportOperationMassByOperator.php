<?php

namespace App\Http\Resources\Report;

use App\Enums\OperationMassType;
use Illuminate\Http\Resources\Json\JsonResource;

class EcoDutyReportOperationMassByOperator extends JsonResource
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
            'federal_registration' => $this->memberOrganization->federal_registration,
            'legal_name' => $this->memberOrganization->legal_name,
            'front_name' => $this->memberOrganization->front_name,
            'operation_masses' => $this->operationMasses,
            // OperationMassType::VALIDATED_INCOMING_WEIGHT => $this->getMassKgByType(OperationMassType::VALIDATED_INCOMING_WEIGHT),
            // OperationMassType::VALIDATED_OUTGOING_WEIGHT => $this->getMassKgByType(OperationMassType::VALIDATED_OUTGOING_WEIGHT),
        ];
    }

    private function getMassKgByType(string $type)
    {
        if ($this->operationMasses->count()) {
            foreach ($this->operationMasses as $operationMass) {
                // dd($operationMass, $type);
                return $operationMass->$type;
            }
        }

        return null;
    }
}
