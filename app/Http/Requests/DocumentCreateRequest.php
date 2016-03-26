<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class DocumentCreateRequest extends FormRequest
{

    public function rules()
    {
        return [
            'title' => 'required'
        ];
    }
    
    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
        ];
    }

}
