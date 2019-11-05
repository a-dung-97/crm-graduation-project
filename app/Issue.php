<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function products()
    {
        return $this->morphToMany('App\Product', 'productable')->as('detail')->withPivot('product_id', 'warehouse_id', 'quantity', 'tax', 'unit', 'price');
    }
    public function getConfirmedAttribute($val)
    {
        return (bool) $val;
    }
}
