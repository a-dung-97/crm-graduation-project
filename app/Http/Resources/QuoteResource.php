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
            'customer' => $this->customer->name,
            'contact' => $this->contact ? $this->contact->name : null,
            'opportunity' => new OpportunitiesResource($this->opportunity),
        ]);
    }
}
