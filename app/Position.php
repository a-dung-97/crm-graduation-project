<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function parent()
    {
        return $this->belongsTo('App\Position', 'parent_id');
    }
}
