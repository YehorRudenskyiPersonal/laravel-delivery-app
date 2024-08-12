<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class DeliveryNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @param array $deliveryData
     * @return void
     */
    public function __construct(
        protected array $deliveryData
    ){}

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail']; // Channels: 'mail' for email, we can use other. Nexmo for sms, for example
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
            ->subject('Delivery Notification')
            ->line('Your delivery has been processed.');
            //Some actions with $this->deliveryData can be provided
    }

    /**
     * Get the SMS representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\NexmoMessage
     */
    public function toNexmo($notifiable)
    {
        //Here we can add logic for SMS if client will ask
        // return (new NexmoMessage)
        //     ->content('');
    }
}
