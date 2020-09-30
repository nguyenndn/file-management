<?php

namespace GGPHP\FileManagement\Http\Requests;

use GGPHP\Absent\Models\AbsentType;
use Illuminate\Foundation\Http\FormRequest;

class FileManagementUploadRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $type = AbsentType::where('type', AbsentType::ANNUAL_LEAVE)->first();
        $awolType = AbsentType::where('type', AbsentType::AWOL)->first();

        return [
            'absent_type_id' => 'required|exists:absent_types,id',
            'absent_reason_id' => request('absent_type_id') == $awolType->id ? '' : 'required|exists:absent_reasons,id',
            'user_id' => 'required|exists:users,id',
            'store_id' => 'required|exists:stores,id',
        ];
    }
}
