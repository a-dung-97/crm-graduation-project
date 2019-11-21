<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Http\Resources\CustomerListResource;
use App\Http\Resources\CustomerResource;
use App\Http\Resources\LeadResource;
use App\Http\Resources\LeadsResource;
use App\Http\Resources\ListLeadResource;
use App\Lead;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->leads();
        if ($request->query('list')) {
            $name = $request->query('name');
            $query = $query->select('id', 'name', 'email', 'phone_number', 'mobile_number');
            if ($request->query('name')) $query = $query->where('name', 'like', '%' . $name . '%');
            return  CustomerListResource::collection($query->paginate($perPage));
        }

        $search = $request->query('search');
        $interactive = $request->query('interactive');
        $source = $request->query('source');
        $company = $request->query('company');
        $branch = $request->query('branch');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $tags = $request->query('tags');
        $createdAt = $request->query('createdAt');
        $scoreFrom = $request->query('scoreFrom');
        $scoreTo = $request->query('scoreTo');
        $birthday = $request->query('birthday');
        if ($search) $query = $query->where(function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone_number', 'like', '%' . $search . '%');
        });
        if ($source) $query = $query->where('source_id', $source);
        if ($company) $query = $query->where('company', $company);
        if ($branch) $query = $query->where('branch_id', $branch);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);
        if ($birthday) $query = $query->whereBetWeen('birthday', $birthday);
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        if ($scoreFrom) $query = $query->where('score', '>=', $scoreFrom);
        if ($scoreTo) $query = $query->where('score', '<=', $scoreTo);
        if ($tags) $query = $query->whereHas('tags', function (Builder $query) use ($tags) {
            $query->whereIn('name', $tags);
        });
        if ($interactive) {
            if ($interactive == "task") $query = $query->doesntHave('tasks');
            if ($interactive == "note") $query = $query->doesntHave('notes');
        }
        return LeadsResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(LeadRequest $request)
    {
        $data = $request->all();
        $data = array_merge($data, ['company_id' => company()->id, 'name' => $request->first_name . ' ' . $request->last_name]);
        $lead = company()->leads()->create($data);
        return created($lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead, Request $request)
    {
        if ($request->query('edit'))
            return ['data' => $lead];
        else return new LeadResource($lead);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function update(LeadRequest $request, Lead $lead)
    {
        $request = $request->all();
        $request['name'] = $request['first_name'] . ' ' . $request['last_name'];
        $lead->update($request);
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lead $lead)
    {
        //
    }
}
