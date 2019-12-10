<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
            'code' => ['required', new Unique('roles', 'code', $this->id)]
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Bạn chưa nhập tên quyền",
            "code.required" => "Bạn chưa nhập mã quyền",
            "code.unique" => "Mã quyền này đã tồn tại"
        ];
    }
}
