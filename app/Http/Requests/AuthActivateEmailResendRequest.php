<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AuthActivateEmailResendRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|exists:users,email',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'email' => '邮箱地址未注册，请您先<a href="/register">注册</a>',
            'email.email' => '邮箱地址格式不正确',
            'email.required' => '请输入邮箱地址'
        ];
    }

}
