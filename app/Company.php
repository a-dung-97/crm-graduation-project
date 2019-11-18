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
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    public function warehouses()
    {
        return $this->hasMany('App\Warehouse');
    }
    public function receipts()
    {
        return $this->hasMany('App\Receipt');
    }
    public function issues()
    {
        return $this->hasMany('App\Issue');
    }
    public function notes()
    {
        return $this->hasMany('App\Note');
    }
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
    public function files()
    {
        return $this->hasMany('App\File');
    }
    public function inventories()
    {
        return $this->hasMany('App\Inventory');
    }
    public function catalogs()
    {
        return $this->hasMany('App\Catalog');
    }
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
}
