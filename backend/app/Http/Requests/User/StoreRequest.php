<?php

namespace App\Http\Requests\User;

use App\Rules\CpfRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
            'name' => 'required|min:1|max:255',
            'phone' => 'nullable|string|min:10|max:11',
            'federal_registration' => [
                'required',
                Rule::unique('users')->ignore(request()->user),
                new CpfRule,
            ],
            'email' => [
                'required',
                'email:rfc,dns',
                'max:255',
                Rule::unique('users')->ignore(request()->user)
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->letters()
                    ->numbers()
                    ->mixedCase()
                    ->symbols()

            ]
        ];
    }
}
