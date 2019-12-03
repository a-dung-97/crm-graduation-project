<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cashbook extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
    public function getBalanceAttribute()
    {
        return $this->bills()->where('status', 'Đã xác nhận')->sum('payment_amount');
    }
}
