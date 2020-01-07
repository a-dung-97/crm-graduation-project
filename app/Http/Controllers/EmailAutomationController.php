<?php

namespace App\Http\Controllers;

use App\EmailAutomation;
use App\EmailCampaign;
use App\Http\Requests\EmailAutomationRequest;
use App\Http\Resources\EmailAutomationDetailResource;
use App\Http\Resources\EmailAutomationResource;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class EmailAutomationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->emailAutomations();
        $search = $request->query('search');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')
            ->orWhere('name', 'like', '%' . $search . '%')
            ->orWhere('description', 'like', '%' . $search . '%');
        return EmailAutomationResource::collection($query->with('mailingList')->paginate($perPage));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function addEmail(Request $request, EmailAutomation $emailAutomation)
    {
        $campaign = $emailAutomation->emailCampaigns()->create(Arr::add(Arr::add(Arr::except($request->all(), ['from_name', 'from_email', 'content']), 'order', $emailAutomation->emailCampaigns()->count() + 1), 'company_id', company()->id));
        $campaign->email()->create([
            'content' => $request->content,
            'subject' => $request->subject,
            'from_name' => $request->from_name,
            'from_email' => $request->from_email,
        ]);
        return created();
    }
    public function updateEmail(Request $request, $id)
    {
        $campaign = EmailCampaign::find($id);
        $campaign->update(Arr::except($request->all(), ['from_name', 'from_email', 'content']));
        $campaign->email()->update([
            'content' => $request->content,
            'subject' => $request->subject,
            'from_name' => $request->from_name,
            'from_email' => $request->from_email,
        ]);
        return updated();
    }
    public function getEmails(EmailAutomation $emailAutomation)
    {
        return EmailAutomationDetailResource::collection($emailAutomation->emailCampaigns);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmailAutomationRequest $request)
    {
        company()->emailAutomations()->create($request->all());
        return created();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\EmailAutomation  $emailAutomation
     * @return \Illuminate\Http\Response
     */
    public function show(EmailAutomation $emailAutomation)
    {
        return new EmailAutomationResource($emailAutomation);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\EmailAutomation  $emailAutomation
     * @return \Illuminate\Http\Response
     */
    public function update(EmailAutomationRequest $request, EmailAutomation $emailAutomation)
    {
        $emailAutomation->update($request->all());
        return updated();
    }
    public function changeActive(EmailAutomation $emailAutomation)
    {
        $emailAutomation->update(['active' => !$emailAutomation->active]);
        return updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\EmailAutomation  $emailAutomation
     * @return \Illuminate\Http\Response
     */
    public function destroy(EmailAutomation $emailAutomation)
    {
        $emailAutomation->emailCampaigns()->delete();
        delete($emailAutomation);
    }
}
