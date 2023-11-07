<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Validator;

class DocumentRequest extends FormRequest
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
        $request = $this;

        return [
            'uploader_user_id' => 'required|exists:users,id,deleted_at,NULL',
            'document_type_id' => 'required|exists:document_types,id,deleted_at,NULL',
            'eco_membership_id' => 'required|exists:eco_memberships,id,deleted_at,NULL',
            // 'name' => 'required|min:3|max:255',
            // 'file_name' => 'required|string|min:3|max:255',
            'file_path' => [
                'required',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->method() === 'POST' || ($request->method() === 'PUT' && $request->file_path instanceof UploadedFile)) {
                        $request->validate([
                            $attribute => [
                                'file',
                                'mimetypes:image/jpeg,image/png,application/pdf'
                            ]
                        ]);
                    }
                }
            ],
            'annotation' => 'nullable',
            'metadata' => 'nullable',
        ];
    }
}
