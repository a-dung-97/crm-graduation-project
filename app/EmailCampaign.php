<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    protected $guarded  = [];
    public function mailingList()
    {
        return $this->belongsTo('App\MailingList');
    }
    public function email()
    {
        return $this->morphOne('App\Email', 'mailable');
    }
    public function automation()
    {
        return $this->belongsTo('App\EmailAutomation', 'email_automation_id');
    }
}
