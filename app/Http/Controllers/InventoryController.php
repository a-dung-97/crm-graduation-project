<?php

namespace App\Http\Controllers;

use App\Http\Resources\InventoryResource;
use App\Inventory;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 5);
        $warehouse = $request->query('warehouse');
        $search = $request->query('search');
        $query = company()->inventories()->latest('id');
        if ($warehouse) $query = $query->where('warehouse_id', $warehouse);
        if ($search) {
            $products = company()->products()->select('id')
                ->where('name', 'like', '%' . $search . '%')
                ->orWhere('code', 'like', '%' . $search . '%')
                ->get()->pluck('id')->all();
            $query = $query->whereIn('product_id', $products);
        }
        $query = $query->with(['product:id,name,code', 'warehouse:id,name'])->paginate($perPage);
        return InventoryResource::collection($query);
    }
}
