<?php

namespace App\Listeners\Order;

use App\Models\ActionLog;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderDeleted;
use App\Events\Order\OrderUpdated;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class OrderActionListener implements ShouldQueue
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle($event): void
    {
        $actionType = match (get_class($event)) {
            OrderCreated::class => 'Order Created',
            OrderUpdated::class => 'Order Updated',
            OrderDeleted::class => 'Order Deleted',
        };
  
        Log::info('Creating ActionLog Entry', [
            'user_id' =>$event->user_id,
            'action_type' => $actionType,
            'order_id' =>$event->order->id,
            'timestamp' => now(),
        ]);
        ActionLog::create([
            'user_id' => $event->user_id,
            'action_type' => $actionType,
            'order_id' => $event instanceof OrderDeleted ? $event->orderId : $event->order->id,
            'created_at' => now(),
        ]);
    }
}
