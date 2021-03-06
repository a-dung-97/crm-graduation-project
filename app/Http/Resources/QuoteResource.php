<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class QuoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'owner' => $this->ownerable->name,
            'products' => ProductQuoteResource::collection($this->products),
            'status' => $this->status ? $this->status->name : null,
            'customer' => $this->customer()->select('id', 'name', 'delivery_address', 'invoice_address')->first(),
            'contact' => $this->contact()->select('id', 'name')->first(),
            'opportunity' => new OpportunitiesResource($this->opportunity),
        ]);
    }
}
