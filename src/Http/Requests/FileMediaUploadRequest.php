<?php

namespace GGPHP\FileMedia\Http\Requests;

class FileMediaUploadRequest extends BaseRequest
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
