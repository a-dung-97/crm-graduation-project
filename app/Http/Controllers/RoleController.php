<?php

namespace App\Http\Controllers;

use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage');
        $search = $request->query('search');
        $query =  company()->roles()->latest('id');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return RoleResource::collection($query);
    }
    public function store(RoleRequest $request)
    {
        auth()->user()->company->roles()->create($request->all());
        return response(['message' => "created"], Response::HTTP_CREATED);
    }

    public function update(Request $request, Role $role)
    {
        $role->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }

    public function destroy(Role $role)
    {
        try {
            $role->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response(['message' => 'Quyền này chứa người dùng']);
        }
    }
}
