<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
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
            'last_name' => 'required',
            'customer_id' => 'required',
            'ownerable_id' => "required",
            'email' => 'unique:contacts,email,' . $this->id . ',id',
            'phone_number' => $this->phone_number ? 'unique:customers,phone_number,' . $this->id . ',id' : '',
            'mobile_number' => $this->mobile_number ? 'unique:customers,mobile_number,' . $this->id . ',id' : ''

        ];
    }
    public function messages()
    {
        return [

            'email.unique' => 'Địa chỉ email đã tồn tại',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            'mobile_number.unique' => 'Số di động đã tồn tại'
        ];
    }
}
