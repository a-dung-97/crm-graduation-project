<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\GroupResource;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $query =  company()->groups()->latest('id');
        if ($search) $query = $query->where('name', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->with(['users' => function ($query) {
            $query->select('user_id as id');
        }])->paginate($perPage) : $query->get();
        return GroupResource::collection($query);
    }
    public function store(GroupRequest $request)
    {
        auth()->user()->company->groups()->create($request->all());
        return response(['message' => "created"], Response::HTTP_CREATED);
    }

    public function update(GroupRequest $request, Group $group)
    {
        $group->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }

    public function destroy(Group $group)
    {
        try {
            $group->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response(['message' => 'Nhóm này chứa người dùng']);
        }
    }
    public function updateUsers(Group $group, Request $request)
    {
        $group->users()->sync($request->all());
        return ['message' => 'updated'];
    }
}
