<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];
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
}
