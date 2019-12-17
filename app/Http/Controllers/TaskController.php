<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Http\Resources\TaskInDetailResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\TasksResource;
use App\Task;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $status = $request->query('status');
        $type = $request->query('type');
        $title = $request->query('title');
        $user = $request->query('user');
        $startDate = $request->query('startDate');
        $finishDate = $request->query('finishDate');
        $query = company()->tasks()->latest();
        $query = $query->where(function ($query) use ($status, $title, $user, $startDate, $finishDate, $type) {
            if ($title) $query = $query->where('title', 'like', '%' . $title . '%');
            if ($status) $query = $query->where('status', $status);
            if ($type) $query = $query->where('taskable_type', $type);
            if ($user) $query = $query->where('user_id', $user);
            if ($startDate) $query = $query->whereBetween('start_date', $startDate);
            if ($finishDate) $query = $query->whereBetween('finish_date', $finishDate);
        });
        return TasksResource::collection($query->with('taskable:id,name', 'user:id,name')->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TaskRequest $request)
    {
        $data = $request->all();
        $data['created_by'] = user()->id;
        $data['taskable_type'] = $request->taskable_type;
        $data['taskable_id'] = $request->taskable_id;
        $task = company()->tasks()->create($data);
        return created($task);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Task $task)
    {
        if ($request->query('edit')) {
            $task = collect($task)->merge(['taskable' => $task->taskable ? $task->taskable->name : null, 'contact' => $task->contact ? $task->contact->name : null,]);
            return ['data' => $task];
        }
        return new TaskResource($task);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(TaskRequest $request, Task $task)
    {
        if ($task->status == 4) {
            return response(['message' => 'Công việc này đã hoàn thành'], 400);
        };
        $data = $request->all();
        $data['updated_by'] = user()->id;
        if ($request->status == 4) $data['finish_date'] = Carbon::now()->toDateString();
        $task->update($data);
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy(Task $task)
    {
        delete($task);
    }

    public function addTask(TaskRequest $request, $type, $id)
    {
        $request = $request->all();
        $request['company_id'] = company()->id;
        $request['created_by'] = user()->id;
        getModel($type, $id)->tasks()->create($request);
        return response(['message' => "added"]);
    }
    public function getTasks(Request $request, $type, $id)
    {
        return TaskInDetailResource::collection(getModel($type, $id)->tasks()->with('user:id,name')->paginate($request->query('perPage', 10)));
    }

    public function finishTask(Task $task)
    {
        $task->update(['status' => 4, 'updated_by' => user()->id, 'finish_date' => Carbon::now()->toDateString()]);
        return updated();
    }
}
