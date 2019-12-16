<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge(parent::toArray($request), [
            'user' => $this->user->name,
            'participants' => [
                'users' => $this->users->pluck('id'),
                'contacts' => $this->contacts ? $this->contacts->pluck('id') : [],
                'leads' => $this->leads ? $this->leads->pluck('id') : [],
            ]
        ]);
    }
}
