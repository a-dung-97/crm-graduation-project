<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $guarded  = [];
    public $timestamps = false;
    public function users()
    {
        return $this->hasMany('App\User');
    }
    public function departments()
    {
        return $this->hasMany('App\Department');
    }
    public function positions()
    {
        return $this->hasMany('App\Position');
    }
    public function groups()
    {
        return $this->hasMany('App\Group');
    }
    public function roles()
    {
        return $this->hasMany('App\Role');
    }
    public function products()
    {
        return $this->hasMany('App\Product');
    }
    public function warehouses()
    {
        return $this->hasMany('App\Warehouse');
    }
    public function receipts()
    {
        return $this->hasMany('App\Receipt');
    }
    public function issues()
    {
        return $this->hasMany('App\Issue');
    }
    public function notes()
    {
        return $this->hasMany('App\Note');
    }
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }
    public function files()
    {
        return $this->hasMany('App\File');
    }
    public function inventories()
    {
        return $this->hasMany('App\Inventory');
    }
    public function catalogs()
    {
        return $this->hasMany('App\Catalog');
    }
    public function tasks()
    {
        return $this->hasMany('App\Task');
    }
    public function leads()
    {
        return $this->hasMany('App\Lead');
    }
    public function customers()
    {
        return $this->hasMany('App\Customer');
    }
    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
    public function quotes()
    {
        return $this->hasMany('App\Quote');
    }
    public function orders()
    {
        return $this->hasMany('App\Order');
    }
    public function bills()
    {
        return $this->hasMany('App\Bill');
    }
    public function cashbooks()
    {
        return $this->hasMany('App\Cashbook');
    }
    public function invoices()
    {
        return $this->hasMany('App\Invoice');
    }
    public function opportunities()
    {
        return $this->hasMany('App\Opportunity');
    }
    public function leadScoreRules()
    {
        return $this->hasMany('App\LeadScoreRule');
    }
    public function mailingLists()
    {
        return $this->hasMany('App\MailingList');
    }
    public function emailTemplates()
    {
        return $this->hasMany('App\EmailTemplate');
    }
    public function emailCampaigns()
    {
        return $this->hasMany('App\EmailCampaign');
    }
    public function emailAutomations()
    {
        return $this->hasMany('App\EmailAutomation');
    }
    public function webforms()
    {
        return $this->hasMany('App\Webform');
    }
    public function reports()
    {
        return $this->hasMany('App\Report');
    }
    public function calls()
    {
        return $this->hasMany('App\Call');
    }
    public function appointments()
    {
        return $this->hasMany('App\Appointment');
    }
}
