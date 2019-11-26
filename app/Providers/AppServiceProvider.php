<?php

namespace App\Providers;

use App\Lead;
use App\LeadScoreRule;
use App\Observers\LeadObserver;
use App\Observers\LeadScoreRuleObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Lead::observe(LeadObserver::class);
        LeadScoreRule::observe(LeadScoreRuleObserver::class);
    }
}
