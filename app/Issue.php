<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $guarded  = [];
    public function products()
    {
        return $this->morphToMany('App\Product', 'productable');
    }
}
