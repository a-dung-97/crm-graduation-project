<?php

namespace App\Http\Controllers;

use App\Http\Requests\QuoteRequest;
use App\Http\Resources\OrdersResource;
use App\Http\Resources\ProductQuoteResource;
use App\Http\Resources\QuoteListResource;
use App\Http\Resources\QuoteResource;
use App\Http\Resources\QuotesResource;
use App\Mail\QuoteEmail;
use App\Quote;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;

class QuoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->query('perPage', 10);
        $code = $request->query('code');
        $deliveryAddress = $request->query('deliveryAddress');
        $customer = $request->query('customer');
        $qouteDate = $request->query('qouteDate');
        $list = $request->query('list');
        $ownerableType = $request->query('ownerableType');
        $ownerableId = $request->query('ownerableId');
        $query = company()->quotes()->latest('id');
        if ($code) $query = $query->where('code', 'like', '%' . $code . '%');
        if ($list) return  QuoteListResource::collection($query->select('id', 'code', 'customer_id')->with('customer:id,name')->paginate($perPage));
        if ($deliveryAddress) $query = $query->whereBetWeen('delivery_date', $deliveryAddress);
        if ($customer) $query = $query->whereHas('customer', function (Builder $query) use ($customer) {
            $query->where('name', 'like', '%' . $customer . '%');
        });
        if ($qouteDate) $query = $query->whereBetWeen('quote_date', $qouteDate);
        if ($ownerableType && $ownerableId) $query = $query->where([['ownerable_type', $ownerableType], ['ownerable_id', $ownerableId]]);

        return QuotesResource::collection($query->with('customer:id,name', 'ownerable:id,name')->paginate($perPage));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QuoteRequest $request)
    {
        $products = getValidProducts($request->products);
        $quote = company()->quotes()->create(Arr::except($request->all(), 'products'));
        collect($products)->each(function ($product) use ($quote) {
            $quote->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return created($quote);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        return new QuoteResource($quote);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(QuoteRequest $request, Quote $quote)
    {
        $products = getValidProducts($request->products);
        $quote->update(Arr::except($request->all(), ['products']));
        $quote->products()->detach();
        collect($products)->each(function ($product) use ($quote) {
            $quote->products()->attach($product['product_id'], Arr::except($product, ['product_id']));
        });
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        delete($quote);
    }
    public function getOrders(Quote $quote)
    {
        return OrdersResource::collection($quote->orders);
    }
    public function sendQuote(Quote $quote)
    {
        $customer = $quote->customer;
        return ['data' => ['content' => (new QuoteEmail($quote))->render(), 'customer_id' => $customer->id, 'email' => $customer->email]];
    }
}
