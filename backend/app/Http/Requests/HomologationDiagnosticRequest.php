<?php

namespace App\Http\Requests;

use App\Enums\HomologationDiagnosticStatus;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class HomologationDiagnosticRequest extends FormRequest
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
            'author_id' => 'required|exists:users,id,deleted_at,NULL',
            'eco_membership_id' => 'required|exists:eco_memberships,id,deleted_at,NULL',
            'homologation_process_id' => 'required|exists:homologation_processes,id,deleted_at,NULL',
            'annotation' => 'nullable',
            'status' => [
                'required',
                new EnumValue(HomologationDiagnosticStatus::class)
            ]
        ];
    }
}
