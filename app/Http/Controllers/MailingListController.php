<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMembersToMailingListRequest;
use App\Http\Requests\MailingListRequest;
use App\Http\Resources\MailingListsResource;
use App\Jobs\AddMembersToMailingList;
use App\Jobs\CreateMailingList;
use App\Jobs\DeleteMailingList;
use App\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MailingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $list = $request->query('list');
        $query = company()->mailingLists();
        if ($list) return ['data' => $query->select('id', 'name')->get()];
        $perPage = $request->query('perPage', 10);
        $name = $request->query('name');
        if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
        return MailingListsResource::collection($query->withCount('customers', 'contacts', 'leads')->paginate($perPage));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MailingListRequest $request)
    {
        $request = $request->all();
        $request['address'] = strtolower(Str::random(10)) . company()->id . '@' . env('MAILGUN_DOMAIN');

        $mailingList = company()->mailingLists()->create($request);
        CreateMailingList::dispatch($mailingList);
        return created();
    }

    public function update(MailingListRequest $request, MailingList $mailingList)
    {
        $mailingList->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function addMembers(AddMembersToMailingListRequest $request, MailingList $mailingList)
    {

        //return $request;
        $memberIds = collect($request->members)->pluck('id');
        $members = $request->members;
        foreach ($members as &$member) {
            $member['address'] = $member['email'];
            unset($member['id']);
            unset($member['email']);
        }
        switch ($request->type) {
            case 'customer':
                $mailingList->customers()->syncWithoutDetaching($memberIds);
                break;
            case 'contact':
                $mailingList->contacts()->syncWithoutDetaching($memberIds);
                break;
            case 'lead':
                $mailingList->leads()->syncWithoutDetaching($memberIds);
                break;
            default:
                break;
        }
        AddMembersToMailingList::dispatch($mailingList->address, $members);
        return created();
    }
    public function destroy(MailingList $mailingList)
    {
        DeleteMailingList::dispatch($mailingList);
        delete($mailingList);
    }
    public function deleteMembers()
    { }
}
