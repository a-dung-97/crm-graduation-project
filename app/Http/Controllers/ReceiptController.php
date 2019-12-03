<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRequest;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\ReceiptAndIssueWithProductResource;
use App\Inventory;
use App\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ReceiptController extends Controller
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
        $query =  company()->receipts()->latest('date');
        if ($search) $query = $query->where('code', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return ReceiptResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReceiptRequest $request)
    {
        $products = getValidProducts($request->products);
        if ($request->confirmed) {
            $this->handleInventory($products);
        }
        // return collect($products)->keyBy('product_id');
        $receipt = company()->receipts()->create(Arr::except($request->all(), ['products']));
        collect($products)->each(function ($product) use ($receipt) {
            $receipt->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return created($receipt);
    }

    public function handleInventory($products)
    {
        collect($products)->each(function ($product) {
            $inventory = company()->inventories()->where([['product_id', $product["product_id"]], ['warehouse_id', $product["warehouse_id"]]])->first();
            if ($inventory)
                $inventory->update(['quantity' => $inventory->quantity + $product["quantity"]]);
            else
                company()->inventories()->create([
                    'product_id' => $product["product_id"],
                    'warehouse_id' => $product["warehouse_id"],
                    'quantity' => $product["quantity"],
                ]);
        });
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function show(Receipt $receipt)
    {
        return new ReceiptAndIssueWithProductResource($receipt);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function update(ReceiptRequest $request, Receipt $receipt)
    {
        $products = getValidProducts($request->products);
        if (!$receipt->confirmed && $request->confirmed) {
            $this->handleInventory($products);
        }
        $receipt->update(Arr::except($request->all(), ['products']));
        $receipt->products()->detach();
        collect($products)->each(function ($product) use ($receipt) {
            $receipt->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return updated();
    }

    public function confirm(Receipt $receipt)
    {
        $receipt->update(['confirmed' => true]);
        return updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Receipt  $receipt
     * @return \Illuminate\Http\Response
     */
    public function destroy(Receipt $receipt)
    {
        if ($receipt->confirmed) return response(['message' => 'Không thể xóa phiếu nhập đã xác nhận'], 400);
        $receipt->products()->detach();
        return delete($receipt);
    }
}
