<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $guarded  = [];
    public function order()
    {
        return $this->belongsTo('App\Order');
    }
    public function status()
    {
        return $this->belongsTo('App\Catalog', 'status_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
    public function ownerable()
    {
        return $this->morphTo();
    }
}
