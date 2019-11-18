<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TasksResource extends JsonResource
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
            'type' => convertTypeToModel($this->taskable_type),
            'status' => $this->convertStatus($this->status),
            'user' => $this->user->name,
            'taskable' => $this->taskable,
            'start_date' => $this->start_date,
            'finish_date' => $this->finish_date,
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
