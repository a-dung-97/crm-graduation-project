<?php

use App\Contact;
use App\Customer;
use App\Lead;
use App\Opportunity;
use App\Product;
use Carbon\Carbon;

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
        case 'customer':
            $model = Customer::find($id);
            break;
        case 'contact':
            $model = Contact::find($id);
            break;
        case 'opportunity':
            $model = Opportunity::find($id);
            break;
        default:
            break;
    }
    return $model;
}

function convertModelToType($value)
{
    switch ($value) {
        case 'App\Product':
            return 'Sản phẩm';
            break;
        case 'App\Customer':
            return 'Khách hàng';
            break;
        case 'App\Lead':
            return 'Tiềm năng';
            break;
        case 'App\Contact':
            return 'Liên hệ';
            break;
        case 'App\Opportunity':
            return 'Cơ hội';
            break;
        default:
            return "";
            break;
    }
}
function convertTypeToModel($value)
{
    switch ($value) {
        case 'Sản phẩm':
            return 'App\Product';
            break;
        case 'Khách hàng':
            return 'App\Customer';
            break;
        case 'Tiềm năng':
            return 'App\Lead';
            break;
        case 'App\Contact':
            return 'Liên hệ';
            break;
        case 'App\Opportunity':
            return 'Cơ hội';
            break;
        default:
            break;
    }
}

function error($msg)
{
    return response(['message' => $msg], 400);
}
function calculate($products, $shippingFee)
{
    $subtotal = $products->reduce(function ($value, $product) {
        return $value + $product['detail']['price'] * $product['detail']['quantity'];
    });
    $discount = $products->reduce(function ($value, $product) {
        return $value + ($product['detail']['discount'] / 100) * $product['detail']['quantity'] * $product['detail']['price'];
    });
    $tax = $products->reduce(function ($value, $product) {
        return $value + ($product['detail']['tax'] / 100) * $product['detail']['quantity'] * $product['detail']['price'];
    });
    $total = $subtotal - $discount + $tax + $shippingFee;
    return collect(['subtotal' => $subtotal, 'discount' => $discount, 'tax' => $tax, 'total' => $total]);
}


function getDelayTime($time, $mode)
{
    switch ($mode) {
        case 'h':
            return Carbon::now()->addHours($time);
            break;
        case 'd':
            return Carbon::now()->addDays($time);
            break;
        case 'w':
            return Carbon::now()->addWeeks($time);
            break;
        case 'm':
            return Carbon::now()->addMonths($time);
            break;

        default:
            break;
    };
}
