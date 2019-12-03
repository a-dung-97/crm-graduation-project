<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class InvoicesResource extends JsonResource
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
            'code' => $this->code,
            'owner' => $this->ownerable->name,
            'order' => $this->order->code,
            'customer' => $this->customer,
            'user' => $this->user->name,
            'status' => $this->status ? $this->status->name : null,
            'created_at' => $this->created_at,
            'payment_amount' => $this->payment_amount,
            'payment_method' => $this->payment_method
        ];
    }
}
