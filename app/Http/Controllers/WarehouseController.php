<?php

namespace App\Http\Controllers;

use App\Http\Requests\WarehouseRequest;
use App\Http\Resources\WarehouseResource;
use App\Warehouse;
use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $query =  company()->warehouses()->latest('id');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->select('id', 'name')->get();
        return WarehouseResource::collection($query);
    }


    public function store(WareHouseRequest $request)
    {
        company()->warehouses()->create($request->all());
        return created();
    }

    public function update(WarehouseRequest $request, Warehouse $warehouse)
    {

        $warehouse->update($request->all());
        return updated();
    }

    public function destroy(Warehouse $warehouse)
    {
        return delete($warehouse);
    }
}
