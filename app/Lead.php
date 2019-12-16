<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
    public function calls()
    {
        return $this->morphMany('App\Call', 'callable');
    }
    public function ownerable()
    {
        return $this->morphTo();
    }
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (user())
                $model->created_by = user()->id;
        });
        static::updating(function ($model) {
            $model->updated_by = user()->id;
        });
        // static::addGlobalScope('converted', function (Builder $builder) {
        //     $builder->where('converted', 0);
        // });
    }
    public function scopeConverted($query)
    {
        return $query->where('converted', '<>', 0);
    }
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function mailingLists()
    {
        return $this->morphToMany('App\MailingList', 'listable', 'mailing_listables');
    }
    public function emails()
    {
        return $this->morphToMany('App\Email', 'mailable')->as('detail')->withPivot('clicked', 'opened', 'delivered');
    }
    public function appointments()
    {
        return $this->morphToMany('App\Appointment', 'appointmentable');
    }
}
