<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'customer' => new CustomersResource($this->customer),
            'contact' => new ContactsResource($this->contact),
            'opportunity' => new OpportunitiesResource($this->opportunity),
            'quote' => new OrdersResource($this->quote),
        ]);
    }
}
