<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductQuoteResource extends JsonResource
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
            'product_id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'price' => $this->detail->price,
            'discount' => $this->detail->discount,
            'quantity' => $this->detail->quantity,
            'tax' => $this->detail->tax,
        ];
    }
}
