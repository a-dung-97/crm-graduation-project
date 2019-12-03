<?php

namespace App\Jobs;

use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class AddMembersToMailingList implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $mailingListAddress;
    protected $members;
    public function __construct($mailingListAddress, $members)
    {
        $this->mailingListAddress = $mailingListAddress;
        $this->members = $members;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mailgun::api()->post("lists/{$this->mailingListAddress}/members.json", [
            'members' => json_encode($this->members),
        ]);
    }
}
