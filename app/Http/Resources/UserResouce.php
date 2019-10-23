<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResouce extends JsonResource
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
            'position' => $this->position_id ? $this->position->name : null,
            'department' => $this->department_id ? $this->department->name : null,
            'role' => $this->role->name,
            'postion_id' => $this->postion_id,
            'department_id' => $this->department_id,
            'role_id' => $this->role_id,
        ];
    }
}
