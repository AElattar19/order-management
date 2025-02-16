<?php

namespace Tests\Feature;
use App\Models\Order;
use App\Models\User;
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderDeleted;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_order_creation(): void
    {
        Event::fake();
        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);
        $this->assertTrue(true);
        Event::assertDispatched(OrderCreated::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });

        $this->assertDatabaseHas('orders', ['id' => $order->id]);
    }

    public function test_order_update()
    {
        Event::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $order->update(['quantity' => 5]); 

        Event::assertDispatched(OrderUpdated::class, function ($event) use ($order) {
            return $event->order->id === $order->id;
        });

        $this->assertDatabaseHas('orders', ['id' => $order->id, 'quantity' => 5]);
    }

    public function test_order_deletion()
    {
        Event::fake();

        $user = User::factory()->create();
        $order = Order::factory()->create(['user_id' => $user->id]);

        $orderId = $order->id;
        $order->delete();

        Event::assertDispatched(OrderDeleted::class, function ($event) use ($orderId) {
            return $event->orderId === $orderId;
        });

        $this->assertDatabaseMissing('orders', ['id' => $orderId]); 
    }
}
