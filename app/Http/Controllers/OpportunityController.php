<?php

namespace App\Http\Controllers;

use App\Http\Requests\OpportunityRequest;
use App\Http\Resources\OpportunitiesResource;
use App\Http\Resources\OpportunityListResouce;
use App\Http\Resources\OpportunityResource;
use App\Opportunity;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $query = company()->opportunities();
        $name = $request->query('name');

        if ($request->query('list')) {
            $query = $query->select('id', 'name', 'created_at', 'end_date', 'customer_id')->with('customer:id,name')->where('customer_id', $request->query('customer'));
            if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
            return  OpportunityListResouce::collection($query->paginate($perPage));
        }
        $customer = $request->query('customer');
        $source = $request->query('source');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $createdAt = $request->query('createdAt');
        $endDate = $request->query('endDate');
        if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
        if ($customer) $query = $query->whereHas('customer', function (Builder $query) use ($customer) {
            $query->where('name', 'like', '%' . $customer . '%');
        });
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);
        if ($source) $query = $query->where('source_id', $source);
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        if ($endDate) $query = $query->whereBetween('end_date', $endDate);
        return OpportunitiesResource::collection($query->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OpportunityRequest $request)
    {
        $opportunity = company()->opportunities()->create($request->all());
        return created($opportunity);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Opportunity $opportunity)
    {
        if ($request->query('edit'))
            return ['data' => collect($opportunity)
                ->merge([
                    'customer' => $opportunity->customer->name,
                    'contact' => $opportunity->contact ? $opportunity->contact->name : null
                ])];
        else return new OpportunityResource($opportunity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Opportunity $opportunity)
    {
        $opportunity->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Opportunity  $opportunity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Opportunity $opportunity)
    {
        delete($opportunity);
    }
}
