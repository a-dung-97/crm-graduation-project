<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function products()
    {
        return $this->morphToMany('App\Product', 'productable')->as('detail')->withPivot('product_id', 'price', 'discount', 'tax', 'quantity');
    }
    public function ownerable()
    {
        return $this->morphTo();
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function status()
    {
        return $this->belongsTo('App\Catalog', 'status_id');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
    public function opportunity()
    {
        return $this->belongsTo('App\Opportunity');
    }
    public function quote()
    {
        return $this->belongsTo('App\Quote');
    }
    public function invoices()
    {
        return $this->hasOne('App\Invoice');
    }
}
