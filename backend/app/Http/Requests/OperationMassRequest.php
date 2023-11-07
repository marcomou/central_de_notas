<?php

namespace App\Http\Requests;

use App\Enums\OperationMassType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class OperationMassRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'eco_membership_id' => 'required|exists:eco_memberships,id,deleted_at,NULL',
            'material_type_id'  => 'required|exists:material_types,id,deleted_at,NULL',
            'mass' => 'required|integer|min:0|max:1000000',
            'work_year' => 'required|digits:4|integer|min:1900|max:' . (date('Y')),
            'operation_mass_type' => [
                'required',
                new EnumValue(OperationMassType::class)
            ]
        ];
    }
}
