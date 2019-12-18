<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function menus()
    {
        return $this->belongsToMany('App\Menu');
    }
}
