<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class CashbookRequest extends FormRequest
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
            'name' => ['required', new Unique('cashbooks', 'name', $this->id)]
        ];
    }
    public function messages()
    {
        return [
            'name.unique' => 'Tên sổ qũy đã tồn tại',
        ];
    }
}
