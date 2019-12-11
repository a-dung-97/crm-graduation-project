<?php

namespace App\Http\Controllers;

use App\EmailCampaign;
use App\Http\Requests\EmailCampaignRequest;
use App\Http\Resources\EmailCampaignsResource;
use App\Http\Resources\EmailCampainDetailResource;
use App\Jobs\BatchSending;
use App\Lead;
use App\Mailable;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Http\Request;
use Bogardo\Mailgun\Mail\Message;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;

class EmailCampaignController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $list = $request->query('list');
        $query = company()->emailCampaigns();
        if ($list) return ['data' => $query->select('id', 'name', 'mailing_list_id')->get()];
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('subject', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
        return EmailCampaignsResource::collection($query->with('email.related')->paginate($perPage));
    }
    public function show(EmailCampaign $emailCampaign)
    {
        return  new EmailCampaignsResource($emailCampaign);
    }
    public function getListEmail(Request $request, EmailCampaign $emailCampaign)
    {
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');
        $event = $request->query('event');
        $query = $emailCampaign->email->related()
            ->whereHasMorph('mailable', ['App\Lead', 'App\Customer', 'App\Contact'], function (Builder $query) use ($search) {
                if ($search) $query->where('email', 'like', "%{$search}%")->orWhere('name', 'like', "%{$search}%");
            });
        if ($event) $query->where($event, 1);
        return EmailCampainDetailResource::collection($query->with('mailable:id,name,email')->paginate($perPage));
    }
    public function store(EmailCampaignRequest $request)
    {
        $campaign = company()->emailCampaigns()->create(Arr::except($request->all(), ['from_name', 'from_email', 'content']));
        $content = $request->content;
        $fromName = $request->from_name;
        $fromEmail = $request->from_email;
        $subject = $request->subject;
        $info = ['content' => $content, 'subject' => $subject, 'from_name' => $fromName, 'from_email' => $fromEmail];
        $email = $campaign->email()->create([
            'content' => $content,
            'subject' => $subject,
            'from_name' => $fromName,
            'from_email' => $fromEmail,
        ]);
        if ($request->conditional) {
            $oldCampaign = EmailCampaign::find($request->email_campaign_id);
            $list = $oldCampaign->email->related();
            switch ($request->event) {
                case 'Đã nhận':
                    $list = $list->where('delivered', true);
                    break;
                case 'Đã click':
                    $list = $list->where('clicked', true);
                    break;
                case 'Không click':
                    $list = $list->where('clicked', false);
                    break;
                case 'Đã mở':
                    $list = $list->where('opened', true);
                    break;
                case 'Không mở':
                    $list = $list->where('opened', false);
                    break;
                default:
                    break;
            }
            $list = $list->with('mailable')->get()->map(function ($item) {
                return  collect($item->mailable)->merge(['type' => $item->mailable_type]);
            });
        } else
            $list = $campaign->mailingList->related()->with('listables')->get()->map(function ($item) {
                return  collect($item->listable)->merge(['type' => $item->listable_type]);
            });
        foreach ($list as $item)
            Mailable::create([
                'email_id' => $email->id,
                'mailable_type' => $item['type'],
                'mailable_id' => $item['id'],
            ]);
        BatchSending::dispatch($list, $info, $email);
        return  created();
    }
}
