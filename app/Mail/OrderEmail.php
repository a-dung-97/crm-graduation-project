<?php

namespace App\Mail;

use App\Company;
use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderEmail extends Mailable
{
    use Queueable, SerializesModels;
    protected $order;
    protected $company;
    protected $customer;
    protected $calculation;
    protected $products;
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->company = company();
        $this->customer = $order->customer;
        $this->products = $order->products;
        $this->calculation = $this->calculate($order->products);
    }
    public function build()
    {
        return $this->view('mail.order')->with([
            'order' => $this->order,
            'company' => $this->company,
            'customer' => $this->customer,
            'products' => $this->products,
            'calculation' => $this->calculation
        ]);
    }
    private function calculate($products)
    {
        $subtotal = $products->reduce(function ($value, $product) {
            return $value + $product->detail->price * $product->detail->quantity;
        });
        $discount = $products->reduce(function ($value, $product) {
            return $value + ($product->detail->discount / 100) * $product->detail->quantity * $product->detail->price;
        });
        $tax = $products->reduce(function ($value, $product) {
            return $value + ($product->detail->tax / 100)  * $product->detail->quantity * $product->detail->price;
        });
        $total = $subtotal - $discount + $tax + $this->order->shipping_fee;
        return collect(['subtotal' => $subtotal, 'discount' => $discount, 'tax' => $tax, 'total' => $total]);
    }
}
