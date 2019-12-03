<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'owner' => $this->ownerable->name,
            'code' => $this->code,
            'order' => $this->order->code,
            'customer' => $this->customer,
            'tax_code' => $this->tax_code,
            'user' => $this->user->name,
            'payment_method' => $this->payment_method,
            'payment_amount' => $this->payment_amount,
            'created_at' => $this->created_at,
            'expiration_date' => $this->expiration_date,
            'status' => $this->status ? $this->status->name : null,
            'note' => $this->note,
        ];
    }
}
