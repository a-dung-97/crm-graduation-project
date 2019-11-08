<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Catalog extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function catalogs()
    {
        return $this->hasMany('App\Catalog', 'parent_id');
    }
    public function childrens()
    {
        return $this->catalogs()->with('childrens');
    }
    public function scopeRoot($query)
    {
        return $query->whereNull('parent_id');
    }
}
