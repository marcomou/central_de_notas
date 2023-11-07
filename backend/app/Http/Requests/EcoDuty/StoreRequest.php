<?php

namespace App\Http\Requests\EcoDuty;

use App\Enums\EcoDutyStatus;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
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
            'eco_ruleset_id' => 'required|exists:eco_rulesets,id,deleted_at,NULL',
            'managing_organization_id' => 'required|exists:organizations,id,deleted_at,NULL',
            'status' => [
                'required',
                new EnumValue(EcoDutyStatus::class)
            ],
            'metadata' => 'nullable',
            'managing_code' => [
                'required',
                Rule::unique('eco_duties')->ignore(request()->eco_duty)->whereNull('deleted_at')
            ]
        ];
    }
}
