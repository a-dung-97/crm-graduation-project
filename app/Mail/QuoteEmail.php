<?php

namespace App\Mail;

use App\Company;
use App\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuoteEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $quote;
    protected $company;
    protected $customer;
    protected $calculation;
    protected $products;
    public function __construct(Quote $quote)
    {
        $this->quote = $quote;
        $this->company = company();
        $this->customer = $quote->customer;
        $this->products = $quote->products;
        $this->calculation = $this->calculate($quote->products);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.quote')->with([
            'quote' => $this->quote,
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
        $total = $subtotal - $discount + $tax + $this->quote->shipping_fee;
        return collect(['subtotal' => $subtotal, 'discount' => $discount, 'tax' => $tax, 'total' => $total]);
    }
}
