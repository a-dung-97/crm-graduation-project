<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        Carbon::setLocale('vi');
        return [
            'id' => $this->id,
            'type' => $this->convertType($this->type),
            'link' => $this->convertLink($this->type) . $this->data['id'],
            'data' => $this->data['name'],
            'created_at' => $this->created_at->diffForHumans()
        ];
    }
    protected function convertType($type)
    {
        switch ($type) {
            case 'App\Notifications\TaskReminder':
                return 'Nhắc nhở công việc';
                break;
            case 'App\Notifications\NewLead':
                return 'Tiềm năng mới';
                break;
            case 'App\Notifications\NewTask':
                return 'Công việc mới';
                break;
            default:
                return;
                break;
        }
    }
    protected function convertLink($type)
    {
        switch ($type) {
            case 'App\Notifications\TaskReminder':
                return '/business/task/show/';
                break;
            case 'App\Notifications\NewLead':
                return '/customer/lead/show/';
                break;
            case 'App\Notifications\NewTask':
                return '/business/task/show/';
                break;
            default:
                return;
                break;
        }
    }
}
