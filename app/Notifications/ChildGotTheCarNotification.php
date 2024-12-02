<?php

namespace App\Notifications;

use App\Models\Child;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class ChildGotTheCarNotification extends Notification
{
    use Queueable;

    protected $child;

    public function __construct(Child $child)
    {
        $this->child = $child;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => "طفلك {$this->child->name} قد وصل السيارة",
            'child_id' => $this->child->id,
            'type' => 'child_got_in_car'
        ];
    }
}
