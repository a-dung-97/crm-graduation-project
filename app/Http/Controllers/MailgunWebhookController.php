<?php

namespace App\Http\Controllers;

use App\Email;
use App\EmailAddress;
use App\Http\Middleware\MailgunWebhook;
use App\Jobs\SendEmailToCustomer;
use App\MessageId;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class MailgunWebhookController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(MailgunWebhook::class);
    // }

    public function handle(Request $request)
    {

        $data = $request['event-data'];
        $messageId = $data['message']['headers']['message-id'];
        $recipient = $data['recipient'];
        $event = $data['event'];
        $email = Email::where('message_id', $messageId)->first();
        //return EmailAddress::whereEmail($email['from_email'])->first()->user;
        if (!$email) {
            $existMessageId = MessageId::whereMessageId($messageId)->first();
            if ($existMessageId) $email = $existMessageId->email;
        }
        if ($email) {
            $mailable = $email->related()
                ->whereHasMorph('mailable', ['App\Lead', 'App\Customer', 'App\Contact'], function (Builder $query) use ($recipient) {
                    $query->where('email', $recipient);
                })->get()->first();
            $mailable->update([$event => 1]);
            if (!$email->message_id) {
                $campaign = $email->mailable;
                $automation = $campaign->automation;
                if (!$automation->active) return;
                $nextCampaign = $automation->emailCampaigns()->where('order', $campaign->order + 1)->first();
                if ($nextCampaign) {
                    $nextEmail = $nextCampaign->email;
                    if ($nextCampaign->event == $this->convert($event)) {
                        $info = [
                            'content' => $nextEmail->content,
                            'subject' => $nextEmail->subject,
                            'from_name' => $nextEmail->from_name,
                            'from_email' => $nextEmail->from_email,
                            'name' => $mailable->mailable->name,
                            'email' => $mailable->mailable->email,
                        ];
                        $mailable->mailable->emails()->attach($nextEmail->id);
                        SendEmailToCustomer::dispatch($info, $nextEmail, true)->delay(getDelayTime($nextCampaign->after, $nextCampaign->time_mode));
                    }
                }
            }
        }
    }
    private function convert($event)
    {
        switch ($event) {
            case 'delivered':
                return 'Đã nhận';
                break;
            case 'clicked':
                return 'Đã click';
                break;
            case 'opened':
                return 'Đã mở';
                break;
            default:
                return null;
                break;
        }
    }
}
