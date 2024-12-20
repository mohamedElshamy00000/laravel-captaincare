<?php

namespace App\Notifications;

use App\Models\Child;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TripEndedNotification extends Notification
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
            'message' => "رحلة الطفل " . $this->child->name . " بامان قد انتهت",
            'child_id' => $this->child->id,
            'type' => 'trip_ended'
        ];
    }
}
