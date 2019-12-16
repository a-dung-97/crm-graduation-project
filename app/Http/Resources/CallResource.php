<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CallResource extends JsonResource
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
            'time' => $this->time,
            'type' => $this->type,
            'target' => $this->target,
            'duration' => $this->duration,
            'result' => $this->result,
            'description' => $this->description,
            'callable' => $this->callable_type && $this->callable_id ? [
                'type' => $this->callable_type,
                'detail' => $this->callable()->select('id', 'name', 'email', 'phone_number', 'mobile_number')->first()
            ] : null,
            'customer' => $this->callable_type == "App\Contact" ? $this->callable->customer()->select('id', 'name', 'email', 'phone_number', 'mobile_number')->first() : null,
            'timeline' => [
                'created_at' => $this->created_at->toDateTimeString(),
                'updated_at' => $this->updated_at->toDateTimeString(),
                'created_by' => $this->createdBy->name,
                'updated_by' => $this->updated_by ? $this->updatedBy->name : null,
            ]
        ];
    }
}
