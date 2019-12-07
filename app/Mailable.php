<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mailable extends Model
{
    protected $guarded = [];
    public $timestamps = false;
    public function mailables()
    {
        return $this->morphTo('mailable');
    }
}
