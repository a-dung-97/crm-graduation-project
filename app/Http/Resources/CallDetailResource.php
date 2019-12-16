<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CallDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {

        return array_merge(parent::toArray($request), ['user' => $this->user->name, 'contact' => $this->callable_type == "App\Contact" ? $this->callable->name : null]);
    }
}
