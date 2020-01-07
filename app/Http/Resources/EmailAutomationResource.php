<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailAutomationResource extends JsonResource
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
            'description' => $this->description,
            'mailing_list' => $this->mailingList->name,
            'mailing_list_id' => $this->mailingList->id,
            'active' => (bool) $this->active,
            'created_at' => $this->created_at
        ];
    }
}
