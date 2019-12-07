<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $guarded  = [];
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
