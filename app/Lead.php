<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
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
    public function branch()
    {
        return $this->belongsTo('App\Catalog', 'branch_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function notes()
    {
        return $this->morphMany('App\Note', 'noteable');
    }
    public function files()
    {
        return $this->morphMany('App\File', 'fileable');
    }
    public function tags()
    {
        return $this->morphToMany('App\Tag', 'taggable');
    }
    public function tasks()
    {
        return $this->morphMany('App\Task', 'taskable');
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
}
