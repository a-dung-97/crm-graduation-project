<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Http\Requests\BillRequest;
use App\Invoice;
use Illuminate\Http\Request;

class BillController extends Controller
{
    public function index(Request $request)
    {
        $invoice = $request->query('invoice');
        if ($invoice)
            return ['data' => company()->bills()->with('cashbook:id,name')->where('invoice_id', $invoice)->latest('id')->get()];
    }

    public function store(BillRequest $request)
    {
        if (!$this->checkBalance($request->payment_amount, Invoice::find($request->invoice_id), 0)) return error('Vượt quá số tiền cần thanh toán');
        company()->bills()->create($request->all());
        return created();
    }

    public function update(BillRequest $request, Bill $bill)
    {
        if (!$this->checkBalance($request->payment_amount, $bill->invoice, $bill->payment_amount)) return error('Vượt quá số tiền cần thanh toán');
        $bill->update($request->all());
        return updated();
    }

    public function destroy(Bill $bill)
    {
        delete($bill);
    }
    private function checkBalance($paymentAmount, $invoice, $oldAmount)
    {
        $currentAmount = $invoice->bills->sum('payment_amount') - $oldAmount;
        $totalAmount = $invoice->payment_amount;
        return $totalAmount > $currentAmount + $paymentAmount;
    }
    public function verify(Bill $bill)
    {
        $bill->update(['status' => 'Đã xác nhận']);
        return updated();
    }
}
