<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskUpdatedNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Task $task, private readonly string $event) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'event' => $this->event,
            'project_id' => $this->task->project_id,
        ];
    }
}
