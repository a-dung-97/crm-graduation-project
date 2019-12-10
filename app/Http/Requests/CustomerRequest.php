<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            'ownerable_id' => 'required',
            'name' => 'required',
            'code' => ['required', new Unique('customers', 'code', $this->id)],
            'email' =>  new Unique('customers', 'email', $this->id),
            'phone_number' =>  new Unique('customers', 'phone_number', $this->id),
            'mobile_number' =>  new Unique('customers', 'mobile_number', $this->id)
        ];
    }
    public function messages()
    {
        return [
            'code.unique' => 'Mã khách hàng đã tồn tại',
            'email.unique' => 'Địa chỉ email đã tồn tại',
            'phone_number.unique' => 'Số điện thoại đã tồn tại',
            'mobile_number.unique' => 'Số di động đã tồn tại'
        ];
    }
}
