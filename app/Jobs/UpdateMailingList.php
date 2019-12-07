<?php

namespace App\Jobs;

use App\MailingList;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateMailingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $mailingList;
    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }
    public function handle()
    {
        Mailgun::api()->put("lists/{$this->mailingList->address}", [
            'name'         => $this->mailingList->name,
            'description'  => $this->mailingList->description,
        ]);
    }
}
