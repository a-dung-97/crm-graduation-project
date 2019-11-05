<?php

namespace App\Http\Controllers;

use App\Http\Requests\IssueRequest;
use App\Http\Resources\IssueResource;
use App\Http\Resources\ReceiptAndIssueWithProductResource;
use App\Issue;
use App\Product;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class IssueController extends Controller
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
        $query =  company()->issues()->latest('date');
        if ($search) $query = $query->where('code', 'like', '%' . $search . '%')->orWhere('description', 'like', '%' . $search . '%');
        $query = $perPage ? $query->paginate($perPage) : $query->get();
        return IssueResource::collection($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    private function handleOutOfStock($products)
    {

        $invalidProduct = false;

        collect($products)->each(function ($product) use (&$invalidProduct) {
            $inventory = company()->inventories()->where([['product_id', $product["product_id"]], ['warehouse_id', $product["warehouse_id"]]])->first();
            if (!$inventory || $inventory->quantity - $product['quantity'] < 0) {
                $invalidProduct = [
                    'product' => Product::find($product["product_id"])->name,
                    'warehouse' => Warehouse::find($product['warehouse_id'])->name,
                    'quantity' => $inventory ? $inventory['quantity'] : null,
                    'unit' => $product['unit']
                ];
                return false;
            }
        });
        return $invalidProduct;
    }
    public function store(IssueRequest $request)
    {
        $products = getValidProducts($request->products);
        $check = $this->handleOutOfStock($products);
        if (!$check) {
            $issue = company()->issues()->create(Arr::except($request->all(), ['products']));
            collect($products)->each(function ($product) use ($issue) {
                $issue->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
            });
            if ($issue->confirmed)
                $this->handleInventory($products);
            return created($issue);
        } else
            return response(['message' => 'Đã có lỗi xảy ra', 'data' => $check], 412);
        // return collect($request->products)->keyBy('product_id');

    }

    private function handleInventory($products)
    {
        collect($products)->each(function ($product) {
            $inventory = company()->inventories()->where([['product_id', $product["product_id"]], ['warehouse_id', $product["warehouse_id"]]])->first();
            $inventory->update(['quantity' => $inventory->quantity - $product["quantity"]]);
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function show(Issue $issue)
    {
        return new ReceiptAndIssueWithProductResource($issue);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function update(IssueRequest $request, Issue $issue)
    {
        $products = getValidProducts($request->products);
        $check = $this->handleOutOfStock($products);
        if (!$check) {
            $issue->update(Arr::except($request->all(), ['products']));
            $issue->products()->detach();
            collect($products)->each(function ($product) use ($issue) {
                $issue->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
            });
            if ($issue->confirmed) $this->handleInventory($products);
            return updated();
        } else
            return response(['message' => 'Đã có lỗi xảy ra', 'data' => $check], 412);
    }
    public function confirm(Issue $issue)
    {
        $issue->update(['confirmed' => true]);
        return updated();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\issue  $issue
     * @return \Illuminate\Http\Response
     */
    public function destroy(Issue $issue)
    {
        if ($issue->confirmed) return response(['message' => 'Không thể xóa phiếu xuất đã xác nhận'], 400);
        $issue->products()->detach();
        return delete($issue);
    }
}
