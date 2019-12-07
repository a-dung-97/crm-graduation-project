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

class BatchSending implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $list;
    protected $info;
    protected $email;
    public function __construct($list, $info, Email $email)
    {
        $this->list = $list;
        $this->info = $info;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $info = $this->info;
        $list = $this->list->keyBy('email')->map(function ($item) {
            return  collect($item)->keyBy(function ($val, $key) use ($item) {
                switch ($item['type']) {
                    case 'App\Lead':
                        return 'lead_' . $key;
                        break;
                    case 'App\Customer':
                        return 'customer_' . $key;
                        break;
                    case 'App\Contact':
                        return 'contact_' . $key;
                        break;
                    default:
                        break;
                }
            });
        })->toArray();
        $response = Mailgun::send('mail.batch', ['template' => $info['content']], function (Message $message) use ($info, $list) {
            $message->from($info['from_email'], $info['from_name']);
            $message->subject($info['subject']);
            $message->to($list);
        });
        $messageId = rtrim(ltrim($response->id, '<'), '>');
        $this->email->update(['status' => 2, 'message_id' => $messageId]);
    }
    public function fail($exception = null)
    {
        Log::error($exception);
        $this->email->update(['status' => 0]);
    }
}
