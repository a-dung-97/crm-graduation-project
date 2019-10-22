<?php

namespace App\Http\Controllers;

use App\Department;
use App\Http\Requests\DepartmentRequest;
use App\Http\Resources\DepartmentResource;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DepartmentController extends Controller
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
        $query =  Department::query()->with('parent');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return DepartmentResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DepartmentRequest $request)
    {
        auth()->user()->company->departments()->create($request->all());
        return response(['message' => 'created'], 201);
    }
    public function update(DepartmentRequest $request, Department $department)
    {
        $department->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        $department->delete();
        return response(null, Response::HTTP_NO_CONTENT);
    }
}
