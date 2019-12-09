<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class CompanyScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    protected $company;
    public function __construct()
    {
        if (user() && company()) {
            $this->company = company()->id;
        }
    }
    public function apply(Builder $builder, Model $model)
    {
        // $company = auth()->user()->company_id;
        if ($this->company)
            $builder->where('company_id', $this->company);
    }
}
