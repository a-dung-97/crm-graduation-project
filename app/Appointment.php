<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $guarded = [];
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
    public function leads()
    {
        return $this->morphedByMany('App\Lead', 'appointmentable');
    }
    public function contacts()
    {
        return $this->morphedByMany('App\Contact', 'appointmentable');
    }
    public function users()
    {
        return $this->morphedByMany('App\User', 'appointmentable');
    }
}
