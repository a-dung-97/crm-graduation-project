<?php

namespace App\Http\Controllers;

use App\Http\Requests\LeadRequest;
use App\Lead;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $perPage = $request->query('perPage');
        // $search = $request->query('search');
        // $source = $request->query('source');
        // $company = $request->query('company');
        // $branch = $request->query('branch');
        // $type = $request->query('type');
        // $start



        // $query =  company()->products()->with('images')->latest();
        // if ($type) $query = $query->where('type', $type);
        // if ($search) $query = $query->where(function ($query) use ($search) {
        //     $query->where('name', 'like', '%' . $search . '%')
        //         ->orWhere('code', 'like', '%' . $search . '%')
        //         ->orWhere('barcode', 'like', '%' . $search . '%')
        //         ->orWhere('brand', 'like', '%' . $search . '%')
        //         ->orWhere('manufacturer', 'like', '%' . $search . '%');
        // });
        // if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
        // $query = $perPage ? $query->paginate($perPage) : $query->get();
        // return ProductsResource::collection($query);
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
        $data = Arr::add($data, 'company_id', company()->id);
        $lead = user()->leads()->create($data);
        return created($lead);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Lead  $lead
     * @return \Illuminate\Http\Response
     */
    public function show(Lead $lead)
    {
        return ['data' => $lead];
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
        $lead->update($request->all());
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
