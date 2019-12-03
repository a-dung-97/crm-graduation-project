<?php

namespace App\Jobs;

use App\MailingList;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateMailingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
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
        Mailgun::api()->post("lists", [
            'address'      => $this->mailingList->address,
            'name'         => $this->mailingList->name,
            'description'  => $this->mailingList->description,
            'access_level' => 'readonly'
        ]);
    }
}
