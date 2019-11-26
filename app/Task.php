<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $guarded  = [];
    public function taskable()
    {
        return $this->morphTo();
    }
    // public function getTaskableTypeAttribute($value)
    // {
    //     return convertModelToType($value);
    // }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function contact()
    {
        return $this->belongsTo('App\Contact');;
    }
}
