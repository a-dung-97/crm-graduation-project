<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeadScoreRule extends Model
{
    protected $guarded  = [];
    public function getActionAttribute($val)
    {
        return (bool) $val;
    }
}
