<?php

namespace App\Observers;

use App\Events\LeadOrRulesChanged;
use App\LeadScoreRule;

class LeadScoreRuleObserver
{
    /**
     * Handle the lead score rule "created" event.
     *
     * @param  \App\LeadScoreRule  $leadScoreRule
     * @return void
     */
    public function created(LeadScoreRule $leadScoreRule)
    {
        event(new LeadOrRulesChanged());
    }

    /**
     * Handle the lead score rule "updated" event.
     *
     * @param  \App\LeadScoreRule  $leadScoreRule
     * @return void
     */
    public function updated(LeadScoreRule $leadScoreRule)
    {
        event(new LeadOrRulesChanged());
    }

    /**
     * Handle the lead score rule "deleted" event.
     *
     * @param  \App\LeadScoreRule  $leadScoreRule
     * @return void
     */
    public function deleted(LeadScoreRule $leadScoreRule)
    {
        event(new LeadOrRulesChanged());
    }
}
