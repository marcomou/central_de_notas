<?php

namespace App\Http\Requests\Organization;

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
            'federal_registration' => 'required|min:11|max:14|unique:organizations',
            'front_name' => 'required|min:1|max:255',
            'legal_name' => 'required|min:1|max:255',
            'legal_type_id' => 'required|exists:legal_types,id,deleted_at,NULL'
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'federal_registration' => preg_replace('/[^0-9]/', '', $this->federal_registration),
        ]);
    }
}
