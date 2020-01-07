<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAutomation extends Model
{
    protected $guarded = [];
    public function mailingList()
    {
        return $this->belongsTo('App\MailingList');
    }
    public function emailCampaigns()
    {
        return $this->hasMany('App\EmailCampaign');
    }
}
