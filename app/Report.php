<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded  = [];
    protected $casts = [
        'column'=>'array',
        'filter'=>'array'
    ];
}
