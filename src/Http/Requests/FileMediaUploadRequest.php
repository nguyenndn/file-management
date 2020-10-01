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
            'absent_type_id' => 'required|exists:absent_types,id',
            'absent_reason_id' => request('absent_type_id'),
            'store_id' => 'required|exists:stores,id',
        ];
    }
}
