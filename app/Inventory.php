<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
    public function warehouse()
    {
        return $this->belongsTo('App\Warehouse');
    }
}
