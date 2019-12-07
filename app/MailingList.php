<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailingList extends Model
{
    protected $guarded  = [];
    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'listable', 'mailing_listables');
    }
    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'listable', 'mailing_listables');
    }
    public function customers()
    {
        return $this->morphedByMany('App\Customer', 'listable', 'mailing_listables');
    }
    public function related()
    {
        return $this->hasMany('App\MailingListable');
    }
    public function campaigns()
    {
        return $this->hasMany('App\EmailCampaign');
    }
}
