<?php

namespace App\Http\Controllers;

use App\Call;
use App\Http\Resources\CallDetailResource;
use App\Http\Resources\CallResource;
use Illuminate\Http\Request;

class CallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['created_by'] = user()->id;
        $call = company()->calls()->create($data);
        return created($call);
    }

    public function show(Call $call)
    {
        return new CallResource($call);
    }
    public function update(Request $request, Call $call)
    {

        $data = $request->all();
        $data['updated_by'] = user()->id;
        $call->update($data);
        return updated();
    }
    public function destroy(Call $call)
    {
        delete($call);
    }
    public function getCalls(Request $request, $type, $id)
    {
        return CallDetailResource::collection(getModel($type, $id)->calls()->with('user:id,name')->paginate($request->query('perPage', 10)));
    }
    public function addCall(Request $request, $type, $id)
    {
        $request = $request->all();
        $request['company_id'] = company()->id;
        $request['created_by'] = user()->id;
        getModel($type, $id)->calls()->create($request);
        return response(['message' => "added"]);
    }
}
