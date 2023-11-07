<?php

namespace App\Http\Requests;

use App\Rules\CnpjRule;
use App\Rules\CpfRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'federal_registration' => [
                'required',
                Rule::unique('organizations', 'federal_registration'),
                new CnpjRule
            ],
            'legal_name' => 'required|min:3|max:255',
            'front_name' => 'required|min:3|max:255',
            'legal_type_id' => 'required|exists:legal_types,id,deleted_at,NULL',
            'user' => 'required|array',
            'user.name' => 'required|min:3|max:255',
            'user.federal_registration' => [
                'required',
                // Rule::unique('users', 'federal_registration'),
                new CpfRule
            ],
            'user.email' => [
                'required',
                'email:rfc,dns',
                // Rule::unique('users', 'email'),
            ],
            'user.password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->symbols(),
            ],
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'federal_registration' => preg_replace('/[^0-9]/', '', (string) $this->federal_registration)
        ]);
    }
}
