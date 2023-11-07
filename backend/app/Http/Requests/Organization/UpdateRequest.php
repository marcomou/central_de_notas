<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'front_name' => [
                'required',
                'min:1',
                'max:255',
                Rule::unique('organizations')
                    ->ignore($this->organization)
                    ->whereNull('deleted_at')
            ],
            'legal_name' => [
                'required',
                'min:1',
                'max:255',
                Rule::unique('organizations')
                    ->ignore($this->organization)
                    ->whereNull('deleted_at')
            ],
        ];
    }
}
