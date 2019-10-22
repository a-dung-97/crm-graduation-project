<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'full_name' => 'required',
            'phone_number' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'email.email' => 'Hãy nhập một địa chỉ email',
            'email.required' => 'Địa chỉ email không được bỏ trống',
            'email.unique' => 'Địa chỉ email đã tồn tại',
            'password.required' => 'Mật khẩu không được bỏ trống',
            'full_name.required' => 'Hãy nhập họ tên của bạn'
        ];
    }
}
