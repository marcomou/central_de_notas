<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'organization_id' => 'required|exists:organizations,id,deleted_at,NULL',
            'postal_code' => 'required|string|size:8',
            'number' => 'required|string|min:1',
            'street' => 'required|string|min:1',
            'city' => 'required|string|min:1',
            'state' => 'required|string|size:2',
            'country' => 'required|string|min:1',
            'primary' => 'required|boolean',
            'fiscal' => 'sometimes|boolean',
            'additional_info' => 'nullable|string',
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
            'postal_code' => preg_replace('/[^0-9]/', '', $this->postal_code),
        ]);
    }
}
