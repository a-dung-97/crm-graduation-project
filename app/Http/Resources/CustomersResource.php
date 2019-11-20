<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CustomersResource extends JsonResource
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
            "code" => $this->code,
            'name' => $this->name,
            'birthday' => $this->birthday,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'mobile_number' => $this->mobile_number,
            'office_address' => $this->office_address,
            'created_at' => $this->created_at,
            'owner' => $this->ownerable->name,
        ];
    }
}
