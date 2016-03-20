<?php 

namespace App\Http\Requests;

use Dingo\Api\Http\FormRequest;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;

class AuthAvatarRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'crop' => 'sometimes',
            'file' => 'required|mimes:jpeg,png,bmp,gif|max:10240' //kb
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return (bool) Sentinel::check();
    }

    public function messages()
    {
        return [
            'file.required' => '请选择文件上传',
            'file.mimes' => '不支持该类型文件',
            'file.max' => '文件大小不能超过10M'
        ];
    }
}