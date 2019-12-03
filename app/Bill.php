<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    protected $guarded  = [];
    public function invoice()
    {
        return $this->belongsTo('App\Invoice');
    }
    public function cashbook()
    {
        return $this->belongsTo('App\Cashbook');
    }
}
