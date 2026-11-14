<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DocumentUploadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permission layer can be added later
    }

    public function rules(): array
    {
        return [
            'document_category_id' => ['required','exists:document_categories,id'],
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string'],

            'file' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                'max:10240' // 10MB
            ],

            'is_public' => ['boolean']
        ];
    }
}