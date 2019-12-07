<?php

namespace App;

use App\Scopes\CompanyScope;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded  = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'email_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {

        return [
            'is_setup' => $this->company_id != null,
        ];
    }


    public function role()
    {
        return  $this->belongsTo('App\Role');
    }
    public function position()
    {
        return $this->belongsTo('App\Position');
    }
    public function department()
    {
        return $this->belongsTo('App\Department');
    }
    public function groups()
    {
        return $this->belongsToMany('App\Group');
    }
    public function company()
    {
        return $this->belongsTo('App\Company');
    }
    public function invitations()
    {
        return $this->hasMany('App\Invitation');
    }
    public function leads()
    {
        return $this->morphMany('App\Lead', 'ownerable');
    }
    public function customers()
    {
        return $this->morphMany('App\Customer', 'ownerable');
    }
    public function contacts()
    {
        return $this->morphMany('App\Contacts', 'ownerable');
    }
    public function quotes()
    {
        return $this->morphMany('App\Quotes', 'ownerable');
    }
    public function emailAddresses()
    {
        return $this->hasMany('App\EmailAddress');
    }
    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CompanyScope);
    }
}
