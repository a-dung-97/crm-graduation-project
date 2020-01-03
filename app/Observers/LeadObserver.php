<?php

namespace App\Observers;

use App\Events\LeadOrRulesChanged;
use App\Group;
use App\Lead;
use App\Notifications\NewLead;
use App\User;
use Illuminate\Support\Facades\Notification;

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
    public function created(Lead $lead)
    {
        if ($lead->ownerable_type == 'App\User') {
            User::find($lead->ownerable_id)->notify(new NewLead($lead));
        } else {
            Notification::send(Group::find($lead->ownerable_id)->users, new NewLead($lead));
        }
    }
}
