<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $guarded  = [];
    public function fileable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
