<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
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
            'status' => $this->status,
            'user' => $this->user->name,
            'start_date' => $this->start_date,
            'expiration_date' => $this->expiration_date,
            'finish_date' => $this->finish_date,
            'priority' => $this->priority,
            'description' => $this->description,
            'taskable' => [
                'type' => $this->taskable_type,
                'detail' => $this->taskable()->select('id', 'first_name', 'last_name')->first()
            ],
            'reminder' => [
                'date' => $this->reminder_date,
                'type' => $this->reminder_type,
            ],
            'timeline' => [
                'created_at' => $this->created_at->toDateTimeString(),
                'updated_at' => $this->updated_at->toDateTimeString(),
                'created_by' => $this->createdBy->name,
                'updated_by' => $this->updated_by ? $this->updatedBy->name : null,
            ]
        ];
    }
}
