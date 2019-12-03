<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Http\Requests\BillRequest;
use App\Http\Requests\InvoiceRequest;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\InvoicesResource;
use App\Invoice;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;


class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = company()->invoices()->latest('id');
        $perPage = $request->query('perPage', 10);
        // $list = $request->query('list');
        // if ($list) return ['data' => $query->select('id', 'name', 'type')->get()];
        $code = $request->query('code');
        $customer = $request->query('customer');
        $order = $request->query('order');
        $user = $request->query('user');
        $status = $request->query('status');
        $paymentMethod = $request->query('paymentMethod');
        $createdAt = $request->query('createdAt');
        if ($code) $query = $query->where('code', 'like', '%' . $code . '%');
        if ($customer) $query = $query->where('customer', 'like', '%' . $customer . '%');
        if ($order) $query = $query->whereHas('order', function (Builder $query) use ($order) {
            $query->where('code', 'like', '%' . $order . '%');
        });
        if ($user) $query = $query->where('user_id', $user);
        if ($status) $query = $query->where('status_id', $status);
        if ($paymentMethod) $query = $query->where('payment_method', $paymentMethod);
        if ($createdAt) $query = $query->whereBetween(DB::raw('DATE(created_at)'), $createdAt);
        return InvoicesResource::collection($query->with('order', 'user', 'status')->paginate($perPage));
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(InvoiceRequest $request)
    {
        $invoice = company()->invoices()->create($request->all());
        return created($invoice);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Invoice $invoice)
    {
        if ($request->query('edit'))
            return ['data' => collect($invoice)
                ->merge([
                    'order' => $invoice->order->code,
                ])];
        else return new InvoiceResource($invoice);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(InvoiceRequest $request, Invoice $invoice)
    {
        $invoice->update($request->all());
        return updated();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->bills()->where('status', 'Đã xác nhận')->count() > 0) return error('Không thể xóa hóa đơn đã thanh toán');
        delete($invoice);
    }
}
