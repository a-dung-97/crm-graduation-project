<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    public function position()
    {
        return $this->belongsTo('App\Catalog', 'position_id');
    }
    public function department()
    {
        return $this->belongsTo('App\Catalog', 'department_id');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function customer()
    {
        return $this->belongsTo('App\Customer');
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
        return $this->hasMany('App\Task');
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
    public function getPrimaryAttribute($val)
    {
        return (bool) $val;
    }
    public function mailingLists()
    {
        return $this->morphToMany('App\MailingList', 'listable', 'mailing_listables');
    }
    public function emails()
    {
        return $this->morphToMany('App\Email', 'mailable')->as('detail')->withPivot('clicked', 'opened', 'delivered');
    }
}
