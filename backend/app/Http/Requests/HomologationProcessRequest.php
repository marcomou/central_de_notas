<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HomologationProcessRequest extends FormRequest
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
            'description' => 'nullable'
        ];
    }
}
