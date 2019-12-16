<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $guarded  = [];
    public function callable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
}
