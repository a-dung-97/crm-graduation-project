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
        $result = collect([]);
        $position->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            $this->getAllChildren($item, $result);
        });
        if ($result->contains($request->parent_id)) return response(['message' => "Không thể chọn vì chức vụ là cấp dưới"], 400);
        if ($request->parent_id == $position->id) return response(['message' => 'Không thể chọn chức vụ hiện tại là cấp trên'], 400);
        $position->update($request->all());
        return response(['message' => 'updated'], Response::HTTP_ACCEPTED);
    }
    public function getAllChildren($position, &$result)
    {

        $position->childrenRecursive->each(function ($item) use (&$result) {
            $result->push($item->id);
            if ($item->childrenRecursive->count() > 0) $this->getAllChildren($item, $result);
        });
    }

    public function getChildrenRecursive()
    {
        return ['data' => Position::whereNull('parent_id')->with('childrenRecursive')->get()];
    }

    public function destroy(Position $position)
    {
        if ($position->children()->count() > 0) return response(['message' => 'Không thể xóa cáp trên'], 400);
        try {
            $position->delete();
            return response(null, Response::HTTP_NO_CONTENT);
        } catch (\Throwable $th) {
            return response(['message' => 'Chức vụ này chứa người dùng']);
        }
    }
}
