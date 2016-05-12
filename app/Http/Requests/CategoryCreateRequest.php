<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{

    public function rules()
    {
        return [
            'title' => 'required',
            'list_template' => 'required',
            'detail_template' => 'required'
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
