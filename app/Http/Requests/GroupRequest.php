<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class GroupRequest extends FormRequest
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
            'name' => ['required', new Unique('groups', 'name', $this->id)]
        ];
    }
    public function messages()
    {
        return [
            "name.required" => "Bạn chưa nhập tên nhóm",
            "name.unique" => "Tên nhóm này đã tồn tại"
        ];
    }
}
