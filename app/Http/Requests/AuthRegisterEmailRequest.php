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
            'email' => 'required|email|unique:users,email',
        ];
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
        $email = $this->input('email');
        $credentials = [
            'login' => $email,
        ];
        $user = Sentinel::findByCredentials($credentials);
        if ($user && Activation::completed($user)) {
            $unique_error = '该邮箱已激活，您可以<a href="/login">登录</a> 或 <a href="/reset?email='.$email.'">重置密码</a>';
        } else {
            $unique_error = '该邮箱未激活，您可以<a href="/activate/email/resend?email='.$email.'">重发验证邮件</a>';
        }
        return [
            'email.unique' => $unique_error,
            'email.email' => '邮箱地址格式不正确',
            'email.required' => '请输入邮箱地址'
        ];
    }

}
