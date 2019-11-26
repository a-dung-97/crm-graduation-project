<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunitiesResource extends JsonResource
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
            'name' => $this->name,
            'owner' => $this->ownerable->name,
            'source' => $this->source ? $this->source->name : null,
            'customer' => $this->customer->name,
            'created_at' => $this->created_at,
            'end_date' => $this->end_date,
        ];
    }
}
