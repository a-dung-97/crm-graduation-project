<?php

namespace App\Jobs;

use App\MailingList;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Bogardo\Mailgun\Facades\Mailgun;

class DeleteMailingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $mailingList;
    public function __construct(MailingList $mailingList)
    {
        $this->mailingList = $mailingList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mailgun::api()->delete("lists/{$this->mailingList->address}");
    }
}
