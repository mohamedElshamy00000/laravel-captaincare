<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

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
        return ['mail', 'database', 'broadcast']; // يمكنك إضافة قنوات أخرى مثل 'database' أو 'broadcast'
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The trip for your child ' . $this->child->name . ' has started.')
                    ->action('View Trip', url('/trips/' . $this->child->id))
                    ->line('Thank you for using our application!');
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
            'message' => 'The trip for your child ' . $this->child->name . ' has started.',
            'child_id' => $this->child->id,
        ];
    }
}
