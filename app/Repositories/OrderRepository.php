<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Support\Facades\Log;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Models\Order; // Assumption: You have a Model with the same name
use App\Events\Order\OrderCreated;
use App\Events\Order\OrderUpdated;
use App\Events\Order\OrderDeleted;
class OrderRepository implements OrderRepositoryInterface
{
    public function getAll()
    {
        return Order::all();
    }

    public function getUserOrders(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return Order::where('user_id', $userId)->paginate($perPage);
    }


    public function addToOrder(int $userId, int $productId, int $product_price, int $quantity, int $total_price): Order
    {
        return Order::create([
            'user_id' => $userId,
            'product_id' => $productId,
            'product_price' => $product_price,
            'quantity' => $quantity,
            'total_price' => $total_price,
            'status' => 'pending',
        ]);

        event(new OrderCreated($order, $userId));
    }

    public function getById($id)
    {
        return Order::find($id);
    }

    public function create(array $attributes)
    {
        return Order::create($attributes);
    }

    public function update($id, array $attributes)
    {

        $order = Order::find($id);
        if (!$order) {
            return null;
        }
        $product = Product::find($order->product_id);
        if (!$product) {
            return null;
        }

     
      
        if ($order) {
            $order->update($attributes);
            event(new OrderUpdated($order, $order->user_id));
            return $order;
        }
        return null;
    }

 
    public function delete($id)
    {

        $record = Order::find($id);
        if ($record) {
            event(new OrderDeleted($record, $record->user_id));
            return $record->delete();
        }
        return false;
    }
}
