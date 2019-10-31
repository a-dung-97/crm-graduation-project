<?php
function company()
{
    return auth()->user()->company;
}
function created()
{
    return response(['message' => 'created'], 201);
}
function updated()
{
    return response(['message' => 'updated'], 202);
}
// function deleted()
// {
//     return response(['message' => 'deleted'], 204);
// }
function delete($model)
{
    try {
        $model->delete();
        return response(['message' => 'deleted'], 204);
    } catch (\Throwable $th) {
        return response(['message' => 'Xóa thất bại'], 400);
    }
}
