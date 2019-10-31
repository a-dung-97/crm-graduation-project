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
        $query =  company()->departments()->latest('id')->with('parent');
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
        $result = collect([]);
        $department->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            $this->getAllChildren($item, $result);
        });
        if ($result->contains($request->parent_id)) return response(['message' => "Không thể chọn vì phòng ban là cấp dưới"], 400);
        if ($request->parent_id == $department->id) return response(['message' => 'Không thể chọn phòng bạn hiện tại là phòng ban cha'], 400);
        $department->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }

    public function getAllChildren($department, &$result)
    {

        $department->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            if ($item->childrenRecursive->count() > 0) $this->getAllChildren($item, $result);
        });
    }

    public function getChildrenRecursive()
    {
        return ['data' => Department::whereNull('parent_id')->with('childrenRecursive')->get()];
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Department  $department
     * @return \Illuminate\Http\Response
     */
    public function destroy(Department $department)
    {
        if ($department->children()->count() > 0) return response(['message' => 'Không thể xóa phòng ban cha'], 400);
        try {
            $department->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response(['message' => 'Phòng ban này chứa người dùng']);
        }
    }
}
