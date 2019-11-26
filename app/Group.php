<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
    public function customers()
    {
        return $this->morphMany('App\Customer', 'ownerable');
    }
    public function contacts()
    {
        return $this->morphMany('App\Contacts', 'ownerable');
    }
    public function leads()
    {
        return $this->morphMany('App\Lead', 'ownerable');
    }
    public function quotes()
    {
        return $this->morphMany('App\Quotes', 'ownerable');
    }
}
