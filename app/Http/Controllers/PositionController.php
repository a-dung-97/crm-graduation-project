<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Http\Resources\PositionResource;
use App\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PositionController extends Controller
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
        $query =  Position::query()->with('parent');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return PositionResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
        auth()->user()->company->positions()->create($request->all());
        return response(['message' => 'created'], 201);
    }
    public function update(PositionRequest $request, Position $position)
    {
        $position->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }


    public function destroy(Position $position)
    {
        $position->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
