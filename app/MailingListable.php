<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MailingListable extends Model
{
    protected $guarded  = [];
    public function listables()
    {
        return $this->morphTo('listable');
    }
}
