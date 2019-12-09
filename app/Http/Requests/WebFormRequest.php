<?php

namespace App\Http\Requests;

use App\Rules\Unique;
use Illuminate\Foundation\Http\FormRequest;

class WebFormRequest extends FormRequest
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
            'name' => ['required', new Unique('webforms', 'name', $this->id)],
            'language' => 'required',
            'url' => 'required|url',
            'redirect_url' => $this->redirect_url ? 'url' : '',
            'field' => 'required',
            'width' => 'required|min:0',
            'height' => 'required|min:0',
            'ownerable_type' => 'required',
            'ownerable_id' => 'required'
        ];
    }
}
