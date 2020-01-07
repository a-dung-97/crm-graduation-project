<?php

namespace App\Listeners;

use App\Events\AddMemberToMailingList;
use App\Jobs\SendEmailToCustomer;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendFirstEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AddMemberToMailingList  $event
     * @return void
     */
    public function handle(AddMemberToMailingList $event)
    {
        $mailingList = $event->mailingList;
        $automations = $mailingList->automations;
        $automations->each(function ($automation) use ($mailingList) {
            if (!$automation->active) return;
            $firstEmail = $automation->emailCampaigns()->where('order', 1)->first();
            if ($firstEmail) {
                $email = $firstEmail->email;
                $mailingList->customers->each(function ($customer) use ($firstEmail, $email) {
                    if ($customer->emails()->where('emails.mailable_id', $firstEmail->id)->count() == 0) {
                        $info = [
                            'content' => $email->content,
                            'subject' => $email->subject,
                            'from_name' => $email->from_name,
                            'from_email' => $email->from_email,
                            'name' => $customer->name,
                            'email' => $customer->email,
                        ];
                        $customer->emails()->attach($email->id);
                        SendEmailToCustomer::dispatch($info, $email, true)->delay(getDelayTime($firstEmail->after, $firstEmail->time_mode));
                    }
                });
                $mailingList->contacts->each(function ($contact) use ($firstEmail, $email) {
                    if ($contact->emails()->where('emails.mailable_id', $firstEmail->id)->count() == 0) {
                        $info = [
                            'content' => $email->content,
                            'subject' => $email->subject,
                            'from_name' => $email->from_name,
                            'from_email' => $email->from_email,
                            'name' => $contact->name,
                            'email' => $contact->email,
                        ];
                        $contact->emails()->attach($email->id);

                        SendEmailToCustomer::dispatch($info, $email, true)->delay(getDelayTime($firstEmail->after, $firstEmail->time_mode));
                    }
                });
                $mailingList->leads->each(function ($lead) use ($firstEmail, $email) {
                    if ($lead->emails()->where('emails.mailable_id', $firstEmail->id)->count() == 0) {
                        $info = [
                            'content' => $email->content,
                            'subject' => $email->subject,
                            'from_name' => $email->from_name,
                            'from_email' => $email->from_email,
                            'name' => $lead->name,
                            'email' => $lead->email,
                        ];
                        $lead->emails()->attach($email->id);

                        SendEmailToCustomer::dispatch($info, $email, true)->delay(getDelayTime($firstEmail->after, $firstEmail->time_mode));
                    }
                });
            }
        });
    }
}
