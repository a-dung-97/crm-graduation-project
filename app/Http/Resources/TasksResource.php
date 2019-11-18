<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'type' => $this->taskable_type,
            'status' => $this->status,
            'user' => $this->user->name,
            'taskable' => $this->taskable,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
            'expiration_date' => $this->expiration_date
        ];
    }
}
