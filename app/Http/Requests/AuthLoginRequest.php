<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AuthLoginRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {               
        $rules = [
            'password' => 'required|min:3'
        ];

        //登录名为邮箱
        if (str_contains($this->input('login'), '@')) {
            $rules['login'] = 'required|email';
        } else { //登录名为手机号码
            $rules['login'] = 'required|size:11';
        }

        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
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
