<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Webform extends Model
{
    protected $guarded = [];
    protected $casts = [
        'field' => 'array',
    ];
    public function ownerable()
    {
        return $this->morphTo();
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
}
