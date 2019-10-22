<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'active' => $this->active,
            'position_id' => $this->position_id,
            'position' => $this->position_id ? $this->position->name : null,
            'department_id' => $this->position_id,
            'department' => $this->position_id ? $this->position->name : null,
            'role_id' => $this->position_id,
            'role' => $this->position_id ? $this->position->name : null,
        ];
    }
}
