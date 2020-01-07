<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailAutomationDetailResource extends JsonResource
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
            'order' => $this->order,
            'event' => strtolower($this->event),
            'after' => $this->after,
            'time_mode' => $this->time_mode,
            'time_mode_name' => $this->convert($this->time_mode),
            'from_email' => $this->email->from_email,
            'from_name' => $this->email->from_name,
            'total_count' => $this->email->related->count(),
            'clicked_count' => $this->email->related->where('clicked', 1)->count(),
            'delivered_count' => $this->email->related->where('delivered', 1)->count(),
            'opened_count' => $this->email->related->where('opened', 1)->count(),
            'content' => $this->email->content,
            'subject' => $this->subject,
        ];
    }
    private function convert($timeMode)
    {
        switch ($timeMode) {
            case 'h':
                return "giờ";
                break;
            case 'd':
                return "ngày";
                break;
            case 'w':
                return "tuần";
                break;
            case 'm':
                return "tháng";
                break;
        }
    }
}
