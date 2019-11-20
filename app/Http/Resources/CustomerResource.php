<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomerResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'parent' => $this->parent ? $this->parent->name : null,
            'tax_code' => $this->tax_code,
            'fax' => $this->fax,
            'office_address' => $this->office_address,
            'invoice_address' => $this->invoice_address,
            'delivery_address' => $this->delivery_address,
            'branch' => $this->branch ? $this->branch->name : null,
            'number_of_workers' => $this->number_of_workers,
            'revenue' => $this->revenue,
            'type' => $this->type ? $this->type->name : null,
            'website' => $this->website,
            'birthday' => $this->birthday,
            'evaluate' => $this->evaluate,
            'source' => $this->source ? $this->source->name : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
