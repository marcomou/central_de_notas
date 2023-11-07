<?php

namespace App\Http\Requests;

use App\Enums\ContactType;
use App\Rules\CpfRule;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'eco_membership_id' => 'required|exists:eco_memberships,id,deleted_at,NULL',
            'name' => 'nullable|min:3|max:255',
            'email' => 'nullable|email:rfc,dns',
            'document' => [
                'nullable',
                new CpfRule,
            ],
            'role' => [
                'required',
                new EnumValue(ContactType::class)
            ],
            'phone' => 'nullable|min:10|max:11',
        ];
    }
}
