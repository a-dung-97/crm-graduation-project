<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function company()
    {
        return $this->hasMany('App\Company');
    }
    public function parent()
    {
        return $this->belongsTo('App\Department', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('App\Department', 'parent_id');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
    }
}
