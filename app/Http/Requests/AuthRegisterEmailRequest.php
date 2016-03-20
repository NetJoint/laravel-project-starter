<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use Cartalyst\Sentinel\Laravel\Facades\Activation;

class AuthRegisterEmailRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'email.email' => '邮箱地址格式不正确',
            'email.required' => '请输入邮箱地址'
        ];
    }
}