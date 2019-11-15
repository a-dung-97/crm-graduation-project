<?php

use App\Lead;
use App\Product;

function company()
{
    return user()->company;
}
function user()
{
    return auth()->user();
}
function created($model = null)
{
    if ($model) return response(['message' => 'created', 'data' => ['id' => $model->id]], 201);
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
        return response(null, 204);
    } catch (\Throwable $th) {
        return response(['message' => 'Xóa thất bại'], 400);
    }
}
function getValidProducts($data)
{
    $products = $data;
    for ($key = 0; $key < count($products); $key++) {
        $products[$key] = Arr::except($products[$key], ['name', "code"]);
    }
    return $products;
}

function getModel($type, $id)
{
    switch ($type) {
        case 'product':
            $model = Product::find($id);
            break;
        case 'lead':
            $model = Lead::find($id);
            break;
        default:
            break;
    }
    return $model;
}
