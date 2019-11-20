<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class LeadsResource extends JsonResource
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
            'phone_number' => $this->phone_number,
            'user' => $this->user->name,
            'company' => $this->company,
            'office_address' => $this->office_address,
            'created_at' => $this->created_at,
        ];
    }
}
