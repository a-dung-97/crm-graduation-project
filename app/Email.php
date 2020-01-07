<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $guarded  = [];
    public function mailable()
    {
        return $this->morphTo();
    }
    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'mailable');
    }
    public function customer()
    {
        return $this->morphedByMany('App\Customer', 'mailable');
    }
    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'mailable');
    }
    public function related()
    {
        return $this->hasMany('App\Mailable');
    }
    public function messageId()
    {
        return $this->hasMany('App\MessageId');
    }
}
