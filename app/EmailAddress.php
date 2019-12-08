<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAddress extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
