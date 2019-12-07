<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EmailCampaignRequest extends FormRequest
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
            'name' => 'required',
            'mailing_list_id' => 'required',
            'subject' => 'required',
            'conditional' => 'required',
            'email_campaign_id' => $this->conditional ? 'required' : '',
            'event' => $this->conditional ? 'required' : '',
            'from_name' => 'required',
            'from_email' => 'required',
            'content' => 'required',
        ];
    }
}
