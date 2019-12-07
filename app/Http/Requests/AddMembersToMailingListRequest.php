<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddMembersToMailingListRequest extends FormRequest
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
            'type' => 'required',
            'mailing_lists' => 'required|min:1',
            'members' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'mailing_lists.required' => 'Hãy chọn ít nhất một danh sách email'
        ];
    }
}
