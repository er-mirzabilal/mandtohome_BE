<?php


namespace App\Events;

use App\Models\Order;

class OrderReceived
{
    public $order;

    /**
     * Create a new event instance.
     *
     * @param Order $order
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }
}
