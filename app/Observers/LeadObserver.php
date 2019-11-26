<?php

namespace App\Observers;

use App\Events\LeadOrRulesChanged;
use App\Lead;

class LeadObserver
{
    /**
     * Handle the lead "created" event.
     *
     * @param  \App\Lead  $lead
     * @return void
     */
    public function updating(Lead $lead)
    {
        event(new LeadOrRulesChanged($lead));
    }

    /**
     * Handle the lead "updated" event.
     *
     * @param  \App\Lead  $lead
     * @return void
     */
    public function creating(Lead $lead)
    {
        event(new LeadOrRulesChanged($lead));
    }
}
