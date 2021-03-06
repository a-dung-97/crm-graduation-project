<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Customer extends Model
{

    protected $guarded  = [];
    public function source()
    {
        return $this->belongsTo('App\Catalog', 'source_id');
    }
    public function type()
    {
        return $this->belongsTo('App\Catalog', 'type_id');
    }
    public function parent()
    {
        return $this->belongsTo('App\Customer', 'parent_id');
    }
    public function children()
    {
        return $this->hasMany('App\Customer', 'parent_id');
    }
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
    public function childrenRecursive()
    {
        return $this->children()->with('childrenRecursive');
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
    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
    public function opportunities()
    {
        return $this->hasMany('App\Opportunity');
    }
    public function mailingLists()
    {
        return $this->morphToMany('App\MailingList', 'listable', 'mailing_listables');
    }
    public function emails()
    {
        return $this->morphToMany('App\Email', 'mailable')->as('detail')->withPivot('clicked', 'opened', 'delivered');
    }
    public function calls()
    {
        return $this->hasManyThrough('App\Call', 'App\Contact', 'customer_id', 'callable_id');
    }
    public function appointments()
    {
        $contacts = $this->contacts->pluck('id')->all();
        $appointments = DB::table('appointmentables')
            ->where('appointmentable_type', 'App\Contact')
            ->whereIn('appointmentable_id', $contacts)->get()->pluck('appointment_id')->unique()->all();
        return Appointment::whereIn('id', $appointments)->with(['contacts' => function ($query) use ($contacts) {
            $query->whereIn('id', $contacts)->select('id', 'name');
        }]);
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
