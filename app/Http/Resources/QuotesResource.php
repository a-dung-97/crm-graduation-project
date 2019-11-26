<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuotesResource extends JsonResource
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
            'quote_date' => $this->quote_date,
            'delivery_date' => $this->delivery_date,
            'customer' => $this->customer->name,
            'owner' => $this->ownerable->name,
        ];
    }
}
