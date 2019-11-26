<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Opportunity extends Model
{
    protected $guarded  = [];
    public function status()
    {
        return $this->belongsTo('App\Catalog', 'status_id');
    }
    public function source()
    {
        return $this->belongsTo('App\Catalog', 'source_id');
    }
    public function type()
    {
        return $this->belongsTo('App\Catalog', 'type_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer');
    }
    public function contact()
    {
        return $this->belongsTo('App\Contact');
    }
    public function notes()
    {
        return $this->morphMany('App\Note', 'noteable');
    }
    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
    public function ownerable()
    {
        return $this->morphTo();
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = user()->id;
        });
        static::updating(function ($model) {
            $model->updated_by = user()->id;
        });
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function quotes()
    {
        return $this->hasMany('App\Quotes');
    }
}
