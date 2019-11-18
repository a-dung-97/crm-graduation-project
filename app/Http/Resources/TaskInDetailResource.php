<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskInDetailResource extends JsonResource
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
            'title' => $this->title,
            'user' => $this->user->name,
            'status' => $this->status,
            'start_date' => $this->start_date,
            'description' => $this->description,
            'expiration_date' => $this->expiration_date
        ];
    }
}
