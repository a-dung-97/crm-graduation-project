<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $guarded  = [];
    public function noteable()
    {
        return $this->morphTo();
    }
    public function user()
    {
        return $this->belongsTo('App\User');
    }
    public function getNoteableTypeAttribute($value)
    {
        switch ($value) {
            case 'App\Product':
                return 'Sản phẩm';
                break;
            case 'App\Customer':
                return 'Khách hàng';
                break;
            case 'App\Lead':
                return 'Tiềm năng';
                break;
            case 'App\Contact':
                return 'Liên hệ';
                break;
            case 'App\Opportunity':
                return 'Cơ hội';
                break;
            default:
                break;
        }
    }
}
