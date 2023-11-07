<?php

namespace App\Http\Requests;

use App\Enums\EcoDutyReviewType;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Foundation\Http\FormRequest;

class EcoDutyReviewRequest extends FormRequest
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
            'eco_duty_id' => 'required|exists:eco_duties,id,deleted_at,NULL',
            'reviewer_user_id' => 'required|exists:users,id,deleted_at,NULL',
            'type' => [
                'required',
                new EnumValue(EcoDutyReviewType::class)
            ],
            'reviewed_at' => 'nullable|before_or_equal:now',
            'external_id' => 'nullable|uuid',
            'comments' => 'nullable',
            'metadata' => 'nullable',
        ];
    }
}
