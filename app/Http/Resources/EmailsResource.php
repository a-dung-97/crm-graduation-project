<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EmailsResource extends JsonResource
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
            'subject' => $this->subject,
            'from_name' => $this->from_name,
            'from_email' => $this->from_email,
            'content' => $this->content,
            'created_at' => $this->created_at,
            'campaign' => $this->mailable,
            'status' => $this->status == 1 ? 'Đang gửi' : ($this->status == 0 ? 'Thất bại' : 'Thành công'),
            'opened' => $this->detail->opened,
            'clicked' => $this->detail->clicked,
            'delivered' => $this->detail->delivered,
        ];
    }
}
