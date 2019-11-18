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
            'status' => $this->convertStatus($this->status),
            'user' => $this->user->name,
            'start_date' => $this->start_date,
            'expiration_date' => $this->expiration_date,
            'finish_date' => $this->finish_date,
            'priority' => $this->convertPriority($this->priority),
            'description' => $this->description,
            'taskable' => [
                'type' => $this->taskable_type,
                'detail' => $this->taskable()->select('id', 'full_name', 'email', 'phone_number')->first()
            ],
            'reminder' => [
                'time' => $this->reminder_time,
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
    private function convertStatus($val)
    {
        switch ($val) {
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
    private function convertPriority($val)
    {
        switch ($val) {
            case '1':
                return 'Thấp nhất';
                break;
            case '2':
                return 'Thấp';
                break;
            case '3':
                return 'Bình thường';
                break;
            case '4':
                return 'Cao';
                break;
            case '5':
                return 'Cao nhất';
                break;
            default:
                return null;
                break;
        }
    }
}
