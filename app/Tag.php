<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'taggable');
    }
}
