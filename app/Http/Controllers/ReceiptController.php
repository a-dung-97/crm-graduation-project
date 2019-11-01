<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReceiptRequest;
use App\Http\Resources\ReceiptResource;
use App\Http\Resources\ReceiptAndIssueWithProductResource;
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
        $perPage = $request->query('per_page');
        $search = $request->query('search');
        $query =  Receipt::latest('date');
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
        // return collect($request->products)->keyBy('product_id');
        $receipt = company()->receipts()->create(Arr::except($request->all(), ['products']));
        $receipt->products()->attach(collect($request->products)->keyBy('product_id')->all());
        return created();
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
        $receipt->update(Arr::except($request->all(), ['products']));
        $receipt->products()->sync(collect($request->products)->keyBy('product_id')->all());
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
        if ($receipt->confirmed) return response(['message' => 'Không thể xóa phiếu nhập đã xác nhận']);
        $receipt->products()->detach();
        return delete($receipt);
    }
}
