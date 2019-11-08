<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $guarded  = [];
    public function status()
    {
        return $this->belongsTo('App\Catalog', 'status_id');
    }
    public function source()
    {
        return $this->belongsTo('App\Catalog', 'source_id');
    }
    public function branch()
    {
        return $this->belongsTo('App\Catalog', 'branch_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function ownerable()
    {
        return $this->morphTo();
    }
}
