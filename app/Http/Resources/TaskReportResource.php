<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskReportResource extends JsonResource
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
            'status' => $this->convertStatus($this->status),
            'contact' => $this->contact ? ['name' => $this->contact->name, 'id' => $this->contact->id] : null,
            'opportunity' => $this->opportunity ? ['name' => $this->opportunity->name, 'id' => $this->opportunity->id] : null,
            'taskable' => $this->taskable,
            'start_date' => $this->start_date,
            'priority' => $this->priority,
            'expiration_date' => $this->expiration_date
        ];
    }
    private function convertStatus($value)
    {
        switch ($value) {
            case '1':
                return 'Chưa thực hiện';
                break;
            case '2':
                return 'Đang thực hiện';
                break;
            case '3':
                return 'Đã giải quyết';
                break;
            case '4':
                return 'Đã hoàn thành';
                break;
            default:
                return null;
                break;
        }
    }
}
