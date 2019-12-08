<?php

namespace App\Http\Controllers;

use App\Email;
use App\EmailAddress;
use App\Http\Middleware\MailgunWebhook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MailgunWebhookController extends Controller
{
    public function __construct()
    {
        $this->middleware(MailgunWebhook::class);
    }

    public function handle(Request $request)
    {
        $data = $request['event-data'];
        $messageId = $data['message']['headers']['message-id'];
        $recipient = $data['recipient'];
        $event = $data['event'];
        $email = Email::whereMessageId($messageId)->first();
        return EmailAddress::whereEmail($email['from_email'])->first()->user;
        if ($email) {
            $email->related()
                ->whereHasMorph('mailable', ['App\Lead', 'App\Customer', 'App\Contact'], function (Builder $query) use ($recipient) {
                    $query->where('email', $recipient);
                })->get()->first()->update([$event => 1]);
            if ($email->mailable_type == null && ($event == 'clicked' || $event == 'opened')) {
                //do something
            }
            return ['message' => 'Thanks Mailgun!'];
        }
    }
}
