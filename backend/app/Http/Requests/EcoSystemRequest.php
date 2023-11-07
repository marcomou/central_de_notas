<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EcoSystemRequest extends FormRequest
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
        // dd($this, request()->eco_system);
        return [
            'supervising_organization_id' => [
                'required',
                Rule::exists('organizations', 'id')
                    ->whereNull('deleted_at'),
            ],
            'name' => [
                'required',
                'min:3',
                'max:255',
                Rule::unique('eco_systems')
                    ->ignore(request()->eco_system)
                    ->whereNull('deleted_at'),
            ],
            'location_id' => [
                'required',
                Rule::exists('locations', 'id')
                    ->whereNull('deleted_at'),
                Rule::unique('eco_systems')
                    ->ignore(request()->eco_system)
                    ->whereNull('deleted_at'),
            ],
        ];
    }
}
