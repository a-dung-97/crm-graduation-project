<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OdersResource extends JsonResource
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
            'customer' => $this->customer->name,
            'owner' => $this->customer->owner,
            'status' => $this->status ? $this->status->name : null,
            'contact' => $this->contact ? $this->contact->name : null,
            'order_date' => $this->order_date,
        ];
    }
}
