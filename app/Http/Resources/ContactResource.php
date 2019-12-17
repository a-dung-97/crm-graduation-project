<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
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
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'customer' => $this->customer->name,
            'customer_id' => $this->customer->id,
            'skype' => $this->skype,
            'facebook' => $this->facebook,
            'office_address' => $this->office_address,
            'position' => $this->position ? $this->position->name : null,
            'name' => $this->name,
            'birthday' => $this->birthday,
            'primary' => $this->primary,
            'fax' => $this->fax,
            'department' => $this->department ? $this->department->name : null,
            'gender' => $this->gender,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_by ? $this->updated_at : null,
            'created_by' => $this->createdBy->name,
            'updated_by' => $this->updatedBy ? $this->updatedBy->name : null,
        ];
    }
}
