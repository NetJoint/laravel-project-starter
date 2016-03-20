<?php 

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class AuthActivateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:users,id',
            'code' => 'required',
            'name' => 'required',
            'password' => 'required|min:6',
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
        return [
            'password.min' => '密码至少6位'
        ];
    }
}