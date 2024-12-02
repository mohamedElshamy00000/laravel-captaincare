<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
class TripStartedNotification extends Notification
{
    use Queueable;

    protected $child;

    /**
     * Create a new notification instance.
     *
     * @param $child
     */
    public function __construct($child)
    {
        $this->child = $child;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'message' => 'رحلة الطفل ' . $this->child->name . ' قد بدأت',
            'child_id' => $this->child->id,
        ];
    }
}
