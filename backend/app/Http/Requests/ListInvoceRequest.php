<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListInvoceRequest extends FormRequest
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
            'skip' => 'nullable|integer',
            'top' => 'nullable|integer',
            'operation' => 'nullable|in:0,1',
            'file_status' => 'nullable|in:0,1,2,3,4',
            'invoice_status' => 'nullable',
            'eco_duties' => 'nullable',
            'sent_by' => 'nullable|exists:organizations,id,deleted_at,NULL',
            'material' => 'nullable|exists:material_types,code,deleted_at,NULL',
            'getherers' => 'nullable',
            'getherer' => 'nullable',
            'state' => 'nullable',
            'search' => 'nullable',
            'issuer_taxid' => 'nullable',
            'issuer_name'  => 'nullable',
            'recipient_taxid' => 'nullable',
            'q'  => 'nullable',
        ];
    }
}
