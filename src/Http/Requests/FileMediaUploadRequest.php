<?php

namespace GGPHP\FileMedia\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FileMediaUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'file' => 'required',
            'file.*' => 'file|mimes:jpeg,png,pdf,doc,docx',
            'name' => 'nullable',
            'name.*' => 'string',
        ];
    }
}
