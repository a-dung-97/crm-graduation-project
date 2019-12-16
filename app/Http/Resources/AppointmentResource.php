<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $users = $this->users()->select('id', 'name', 'email')->get()->each(function ($item) {
            $item['type'] = 'Người dùng';
        });
        $contacts = $this->contacts()->select('id', 'name', 'email')->get()->each(function ($item) {
            $item['type'] = 'Liên hệ';
        });
        $leads = $this->leads()->select('id', 'name', 'email')->get()->each(function ($item) {
            $item['type'] = 'Tiềm năng';
        });

        $appointmentable = $users->merge($contacts)->merge($leads);
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user' => $this->user->name,
            'time' => $this->time,
            'place' => $this->place,
            'status' => $this->status,
            'description' => $this->description,
            'appointmentable' => $appointmentable,
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
