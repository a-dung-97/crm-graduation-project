<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class IssueRequest extends FormRequest
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
            'code' => ['required', new Unique('issues', 'code', $this->id)],
            'date' => 'required|date',
            'products' => 'required',
            'products.*.product_id' => 'required',
            'products.*.warehouse_id' => 'required',
            'phone_number' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'code.unique' => 'Mã phiếu nhập đã tồn tại',
            'products.*.warehouse_id.required' => 'Bạn chưa chọn kho hàng'
        ];
    }
}
