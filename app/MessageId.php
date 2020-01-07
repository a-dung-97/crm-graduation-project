<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MessageId extends Model
{
    protected $guarded = [];
    public function email()
    {
        return $this->belongsTo('App\Email');
    }
}
