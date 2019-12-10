<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'code' => ['required', new Unique('orders', 'code', $this->id)],
            'customer_id' => 'required',
            'ownerable_id' => "required",
            'order_date' => 'required|date',
            'products' => 'required',
            'products.*.product_id' => 'required',
        ];
    }
}
