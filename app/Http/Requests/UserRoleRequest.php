<?php

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;

class UserRoleRequest extends FormRequest
{

    public function rules()
    {
        return [
            'slug' => 'sometimes|exists:roles,slug',
            'role_id' => 'sometimes|exists:roles,id',
            'user_id' => 'required|exists:users,id'
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'slug.exists' => '角色不存在',
            'role_id.exists' => '角色不存在',
            'user_id.exists' => '用户不存在',
        ];
    }

}
