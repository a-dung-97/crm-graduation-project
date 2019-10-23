<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompanyRequest extends FormRequest
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
            'name' => 'required',
            'address' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Bạn chưa nhập tên công ty',
            'address.required' => 'Bạn chưa nhập địa chỉ công ty'
        ];
    }
}
