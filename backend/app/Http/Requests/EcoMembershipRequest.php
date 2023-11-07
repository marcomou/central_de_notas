<?php

namespace App\Http\Requests;

use App\Enums\EcoMembershipRole;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class EcoMembershipRequest extends FormRequest
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
            'member_role' => [
                'required',
                new EnumValue(EcoMembershipRole::class)
            ],
            'eco_duty_id' => 'required|exists:eco_duties,id,deleted_at,NULL',
            'member_organization_id' => 'required|exists:organizations,id,deleted_at,NULL',
            'through_membership_id' => 'nullable|exists:eco_memberships,id,deleted_at,NULL',
            'extra' => 'nullable'
        ];
    }
}
