<?php

namespace App\Http\Controllers;

use App\Events\AddMemberToMailingList;
use App\Http\Requests\AddMembersToMailingListRequest;
use App\Http\Requests\MailingListRequest;
use App\Http\Resources\MailingListResource;
use App\Http\Resources\MailingListsResource;
use App\Jobs\AddMembersToMailingList;
use App\Jobs\CreateMailingList;
use App\Jobs\DeleteMailingList;
use App\Jobs\UpdateMailingList;
use App\MailingList;
use App\MailingListable;
use Bogardo\Mailgun\Facades\Mailgun;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use MailingListables;

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
        if ($list) return ['data' => $query->select('id', 'name', 'description')->get()];
        $perPage = $request->query('perPage', 10);
        $search = $request->query('search');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
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
        company()->mailingLists()->create($request->all());
        return created();
    }

    public function update(MailingListRequest $request, MailingList $mailingList)
    {
        $mailingList->update($request->all());
        return updated();
    }

    public function show(MailingList $mailingList)
    {
        return MailingListResource::collection($mailingList->related()->with('listables')->get());
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MailingList  $mailingList
     * @return \Illuminate\Http\Response
     */
    public function addMembers(AddMembersToMailingListRequest $request)
    {
        $members = $request->members;
        $lists = $request->mailing_lists;
        $type = $request->type;
        $errors = [];
        foreach ($lists as $list) {
            $list = MailingList::find($list['id']);
            $emails = $list->related()->with('listables')->get()->map(function ($item) {
                return $item->listable;
            })->pluck('email');
            foreach ($members as $member) {
                if ($emails->contains($member['email'])) array_push($errors, ['email' => $member['email'], 'name' => $member['name'], 'list' => $list['name']]);
                else {
                    if ($type == "contact") $list->contacts()->attach($member['id']);
                    if ($type == "customer") $list->customers()->attach($member['id']);
                    if ($type == "lead") $list->leads()->attach($member['id']);
                    event(new AddMemberToMailingList($list));
                }
            }
        }
        return ['errors' => $errors];
    }
    public function destroy(MailingList $mailingList)
    {
        delete($mailingList);
    }
    public function deleteMembers(Request $request, $id)
    {
        $members = $request->all();
        foreach ($members as $member) {
            DB::delete('delete from mailing_listables where mailing_list_id = ? and listable_type = ? and listable_id = ? ', [$id, $member['type'], $member['id']]);
        }
        return response(null, 204);
    }
}
