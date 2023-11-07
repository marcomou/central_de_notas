<?php

namespace App\Http\Requests\LiabilityDeclaration;

use App\Rules\UniqueLiabilityDeclaration;
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
            'material_type_id' => [
                'bail',
                'required',
                Rule::exists('material_types', 'id')
                    ->whereNull('deleted_at'),
            ],
            'eco_membership_id' => [
                'nullable',
                Rule::exists('eco_memberships', 'id')
                    ->where('eco_duty_id', $this->eco_duty_id)
                    ->whereNull('deleted_at'),
                'bail',
                new UniqueLiabilityDeclaration(
                    $this->material_type_id,
                    $this->eco_duty_id,
                    $this->eco_membership_id,
                ),
            ],
            'eco_duty_id' => [
                'bail',
                'required',
                Rule::exists('eco_duties', 'id')
                    ->whereNull('deleted_at'),
                new UniqueLiabilityDeclaration(
                    $this->material_type_id,
                    $this->eco_duty_id,
                    $this->eco_membership_id
                ),
            ],

            'mass_kg' => 'required|integer|gte:1'
        ];
    }
}
