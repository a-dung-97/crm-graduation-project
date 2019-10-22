<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvitationRequest extends FormRequest
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
            'email' => 'required',
            'position_id' => 'required',
            'role_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'email.required' => 'Bạn chưa nhập email người được mời',
            'position_id.required' => 'Bạn chưa nhập chức vụ',
            'role_id.required' => 'Bạn chưa nhập quyền hạn'
        ];
    }
}
