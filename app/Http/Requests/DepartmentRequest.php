<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            'name' => 'required|unique:departments,name,' . $this->id . ',id'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Hãy nhập tên phòng ban',
            'name.unique' => 'Phòng ban này đã tồn tại'
        ];
    }
}
