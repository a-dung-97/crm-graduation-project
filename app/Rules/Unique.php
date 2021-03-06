<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class Unique implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    protected $table;
    protected $id;
    protected $company;
    protected $column;
    public function __construct($table, $column, $id = "")
    {
        $this->table = $table;
        $this->id = $id;
        $this->column = $column;
        $this->company = company()->id;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (!$value) return true;
        $query = DB::table($this->table)->where($this->column, $value)->where('company_id', $this->company);
        if ($this->id) $query = $query->where('id', '<>', $this->id);
        $query = $query->first();
        if ($query) return false;
        else return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        $name = '';
        switch ($this->column) {
            case 'name':
                $name = "Tên";
                break;
            case 'code':
                $name = "Mã";
                break;
            case 'phone_number':
                $name = "Số điện thoại";
                break;
            case 'mobile_number':
                $name = "Số di động";
                break;
            case 'email':
                $name = "Email";
                break;
            default:
                break;
        }
        return $name . ' đã tồn tại';
    }
}
