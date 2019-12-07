<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class DepartmentRequest extends FormRequest
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
            // php artisan make:rule Uppercase
            'name' => ['required', new Unique('departments', 'name', $this->id)]
        ];
    }
    public function messages()
    {
        return [
            'name.required' => 'Hãy nhập tên phòng ban',
        ];
    }
}
