<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class EmailAutomationRequest extends FormRequest
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
            'name' => ['required', new Unique('email_automations', 'name', $this->id)],
            'mailing_list_id' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Hãy nhập tên email tự động',
        ];
    }
}
