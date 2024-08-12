<?php

namespace App\Listeners;

use App\Events\DeliveryProvidedEvent;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DeliveryNotification;

class WriteDeliveryListener
{
    /**
     * Handle the event.
     *
     * @param DeliveryProvidedEvent $event
     * @return void
     */
    public function handle(DeliveryProvidedEvent $event)
    {
        // Now it's only log that job was done, but in the future it can be usefull to extend the functionality
        $deliveryData = $event->deliveryData;
        Log::info('Delivery request saved.', $deliveryData);

        // We can do notification after we did request and saved data. Now it's only the placeholder, but for the future
        // Notification::route('mail', $deliveryData['user_email'])
            //         ->notify(new DeliveryNotification($response));
    }
}
