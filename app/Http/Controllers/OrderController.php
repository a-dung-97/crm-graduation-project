<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Http\Resources\InvoicesResource;
use App\Http\Resources\OderResource;
use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\ProductQuoteResource;
use App\Http\Resources\QuotesResource;
use App\Mail\OrderEmail;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);

        $code = $request->query('code');
        $customer = $request->query('customer');
        $status = $request->query('status');
        $orderDate = $request->query('orderDate');
        $list = $request->query('list');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $deliveryDate = $request->query('deliveryDate');


        $query = company()->orders()->latest('id');
        if ($code) $query = $query->where('code', 'like', '%' . $code . '%');
        if ($list) return  OrderListResource::collection($query->select('id', 'code', 'customer_id')->with('customer:id,name')->paginate($perPage));
        if ($status) $query = $query->where('status_id',  $code);
        if ($customer) $query = $query->whereHas('customer', function (Builder $query) use ($customer) {
            $query->where('name', 'like', '%' . $customer . '%');
        });
        if ($orderDate) $query = $query->whereBetWeen('order_date', $orderDate);
        if ($deliveryDate) $query = $query->whereBetWeen('delivery_date', $deliveryDate);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);

        return OrdersResource::collection($query->with('customer:id,name', 'ownerable:id,name')->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrderRequest $request)
    {
        $products = getValidProducts($request->products);
        $order = company()->orders()->create(Arr::except($request->all(), 'products'));
        collect($products)->each(function ($product) use ($order) {
            $order->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return created($order);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return new OrderResource($order);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(OrderRequest $request, Order $order)
    {
        $products = getValidProducts($request->products);
        $order->update(Arr::except($request->all(), ['products']));
        $order->products()->detach();
        collect($products)->each(function ($product) use ($order) {
            $order->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        delete($order);
    }

    public function getInvoices(Order $order)
    {
        return InvoicesResource::collection($order->invoices);
    }
    public function sendOrder(Order $order)
    {
        return ['data' => ['content' => (new OrderEmail($order))->render()]];
    }
}
