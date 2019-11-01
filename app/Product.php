<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded  = [];
    public function notes()
    {
        return $this->morphMany('App\Note', 'noteable');
    }
    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
    public function receipts()
    {
        return $this->morphedByMany('App\Receipt', 'productable');
    }
    public function issues()
    {
        return $this->morphedByMany('App\Issue', 'productable');
    }
    public function images()
    {
        return $this->hasMany('App\ProductImage');
    }
}
