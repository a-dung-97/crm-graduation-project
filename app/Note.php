<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded  = [];
    public function noteable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    // public function getNoteableTypeAttribute($value)
    // {
    //     return convertModelToType($value);
    // }
}
