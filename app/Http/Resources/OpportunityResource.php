<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OpportunityResource extends JsonResource
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
            'customer' => $this->customer->name,
            'owner' => $this->ownerable->name,
            'contact' => $this->contact ? $this->contact->name : null,
            'source' => $this->source ? $this->source->name : null,
            'type' => $this->type ? $this->type->name : null,
            'next_step' => $this->next_step,
            'end_date' => $this->end_date,
            'status' => $this->status ? $this->status->name : null,
            'probability_of_success' => $this->probability_of_success,
            'value' => $this->value,
            'expected_sales' => $this->expected_sales,
            'created_at' => $this->created_at,
            'description' => $this->description,
            'updated_at' => $this->updated_at,
        ];
    }
}
