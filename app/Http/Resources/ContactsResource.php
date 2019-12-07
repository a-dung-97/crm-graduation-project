<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ContactsResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'owner' => $this->ownerable->name,
            'office_address' => $this->office_address,
            'birthday' => $this->birthday,
            'created_at' => $this->created_at,
            'customer' => $this->customer->name,
            'position' => $this->position ? $this->position->name : null,
            'gender' =>  $this->gender == 1 ? 'Nam' : ($this->gender == null ? '' : 'Nữ'),
        ];
    }
}
