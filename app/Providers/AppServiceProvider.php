<?php

namespace App\Providers;

use App\Lead;
use App\LeadScoreRule;
use App\Observers\LeadObserver;
use App\Observers\LeadScoreRuleObserver;
use Illuminate\Support\ServiceProvider;
use GuzzleHttp\Client;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('mailgun.client', function () {
            return \Http\Adapter\Guzzle6\Client::createWithConfig([
                // your Guzzle6 configuration
            ]);
        });
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
