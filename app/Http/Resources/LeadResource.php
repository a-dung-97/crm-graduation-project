<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadResource extends JsonResource
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
            'email' => $this->email,
            'score' => $this->score,
            'birthday' => $this->birthday,
            'status' => $this->status ? $this->status->name : null,
            'source' => $this->source ? $this->source->name : null,


            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'facebook' => $this->facebook,

            'owner' => $this->ownerable->name,
            'company' => $this->company,
            'office_address' => $this->office_address,
            'website' => $this->website,
            'tax_code' => $this->tax_code,
            'number_of_workers' => $this->number_of_workers,
            'branch' => $this->branch ? $this->branch->name : null,
            'fax' => $this->fax,
            'revenue' => $this->revenue,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_by ? $this->updated_at : null,
            'created_by' => $this->createdBy->name,
            'updated_by' => $this->updatedBy ? $this->updatedBy->name : null,
        ];
    }
}
