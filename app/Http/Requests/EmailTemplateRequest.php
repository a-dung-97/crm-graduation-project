<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailTemplateRequest extends FormRequest
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
            'name' => 'required|unique:email_templates,name,' . $this->id . ',id'
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => 'Tên mẫu này đã tồn tại'
        ];
    }
}