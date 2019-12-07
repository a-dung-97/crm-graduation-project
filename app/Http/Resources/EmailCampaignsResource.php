<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailCampaignsResource extends JsonResource
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
            'subject' => $this->subject,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'total_count' => $this->email->related->count(),
            'success_count' => $this->email->status == 2 ? $this->email->related->count() : 0,
            'clicked_count' => $this->email->related->where('clicked', 1)->count(),
            'deliveried_count' => $this->email->related->where('deliveried', 1)->count(),
            'opened_count' => $this->email->related->where('opened', 1)->count(),
            'failed_count' => $this->email->related->where('failed', 1)->count(),
        ];
    }
}
