<?php

namespace App\Http\Requests\LiabilityDeclaration;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            // 'material_type_id' => 'required|exists:material_types,id,deleted_at,NULL',
            'mass_kg' => 'required|integer|gte:1'
        ];
    }
}
