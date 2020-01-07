<?php

namespace App\Jobs;

use App\Email;
use Bogardo\Mailgun\Facades\Mailgun;
use Bogardo\Mailgun\Mail\Message;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendEmailToCustomer implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $info;
    protected $email;
    protected $automation;
    public function __construct($info, Email $email, $automation = null)
    {
        $this->info = $info;
        $this->email = $email;
        $this->automation = $automation;
    }
    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $info = $this->info;
        $response = Mailgun::send('mail.send', ['template' => $info['content']], function (Message $message) use ($info) {
            $message->from($info['from_email'], $info['from_name']);
            $message->subject($info['subject']);
            $message->to($info['email'], $info['name']);
        });
        $messageId = rtrim(ltrim($response->id, '<'), '>');
        if ($this->automation) {
            $this->email->messageId()->create([
                'message_id' => $messageId,
            ]);
        } else
            $this->email->update(['status' => 2, 'message_id' => $messageId]);
    }
    public function fail($exception = null)
    {
        Log::error($exception);
        $this->email->update(['status' => 0]);
    }
}
