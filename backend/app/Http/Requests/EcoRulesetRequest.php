<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EcoRulesetRequest extends FormRequest
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
            'eco_system_id' => 'required|exists:eco_systems,id,deleted_at,NULL',
            'duty_year' => 'required|integer|min:1900|max:' . now()->addYear()->format('Y'),
            'rules' => 'required|array',
        ];
    }
}
