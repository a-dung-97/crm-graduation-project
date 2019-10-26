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
            'name' => $this->name,
            'email' => $this->email,
            'phone_number' => $this->phone_number,
            'active' => (bool) $this->active,
            'position_id' => $this->position_id,
            'position' => $this->position_id ? $this->position->name : null,
            'department_id' => $this->department_id,
            'department' => $this->department ? $this->department->name : null,
            'role_id' => $this->role_id,
            'role' => $this->role_id ? $this->role->name : null,
        ];
    }
}
