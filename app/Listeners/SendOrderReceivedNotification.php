<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\OrderReceived;
use App\Notifications\NewOrderReceived;

class SendOrderReceivedNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param OrderReceived $event
     * @return void
     */
    public function handle(OrderReceived $event)
    {
        $vendor = $event->order->shop->owner;
        $vendor->notify(new NewOrderReceived($event->order));
    }
}
