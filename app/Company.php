<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function departments()
    {
        return $this->hasMany('App\Department');
    }
    public function positions()
    {
        return $this->hasMany('App\Position');
    }
    public function groups()
    {
        return $this->hasMany('App\Group');
    }
    public function roles()
    {
        return $this->hasMany('App\Role');
    }
}
