<?php

namespace App\Http\Controllers;

use App\Cashbook;
use App\Http\Requests\CashbookRequest;
use App\Http\Resources\CashbooksResource;
use Illuminate\Http\Request;

class CashbookController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = company()->cashbooks()->latest('id');
        $perPage = $request->query('perPage', 10);
        $list = $request->query('list');
        if ($list) return ['data' => $query->select('id', 'name', 'type')->get()];
        $name = $request->query('name');
        $type = $request->query('type');
        $description = $request->query('description');
        if ($name) $query = $query->where('name', 'like', '%' . $name . '%');
        if ($description) $query = $query->where('description', 'like', '%' . $description . '%');
        if ($type) $query = $query->where('type', $type);
        return CashbooksResource::collection($query->paginate($perPage));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CashbookRequest $request)
    {
        company()->cashbooks()->create($request->all());
        return created();
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Cashbook  $cashbook
     * @return \Illuminate\Http\Response
     */
    public function update(CashbookRequest $request, Cashbook $cashbook)
    {
        $cashbook->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Cashbook  $cashbook
     * @return \Illuminate\Http\Response
     */
    public function destroy(Cashbook $cashbook)
    {
        if ($cashbook->balance > 0) return error('Không thể xóa sổ quỹ có số dư lớn hơn 0');
        delete($cashbook);
    }
}
