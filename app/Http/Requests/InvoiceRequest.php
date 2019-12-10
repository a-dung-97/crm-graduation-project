<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class InvoiceRequest extends FormRequest
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
            'code' => ['required', new Unique('invoices', 'code', $this->id)],
            'order_id' => 'required',
            'customer' => 'required',
            'payment_method' => 'required',
            'payment_amount' => 'required',
            'user_id' => 'required',
        ];
    }
    public function messages()
    {
        return [
            'code.unique' => 'Mã hóa đơn đã tồn tại'
        ];
    }
}
