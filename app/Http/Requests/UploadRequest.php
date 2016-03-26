<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UploadRequest extends FormRequest
{

    public function rules()
    {
        return [
            'file' => 'required'
        ];
    }
    
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'file.required' => '请选择文件上传'
        ];
    }

}
