<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadInvoiceRequest extends FormRequest
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
            'invoices' => 'required|array|min:1',
            'invoices.*' => 'required',
            'eco_duty' => 'nullable|exists:eco_duties,id,deleted_at,NULL',
            'sent_by' => 'nullable|exists:eco_memberships,id,deleted_at,NULL',
            'getherer' => 'required|exists:organizations,getherer_id,deleted_at,NULL',
        ];
    }
}
