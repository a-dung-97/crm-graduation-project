<?php

namespace App\Notifications;

use App\Mail\TaskReminder as AppTaskReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $task;
    public function __construct($task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        switch ($this->task->reminder_type) {
            case '1':
                return ['mail'];
                break;
            case '2':
                return ['database', "broadcast"];
                break;
            case '3':
                return ['mail', 'database', "broadcast"];
                break;
        }
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)->view(
            'mail.task',
            ['task' => $this->task]
        );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'id' => $this->task->id,
            'name' => $this->task->title,
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'link' => '/business/task/show/' . $this->task->id,
            'name' => $this->task->title,
            'obj_type' => 'Nhắc nhở công việc'
        ]);
    }
}
