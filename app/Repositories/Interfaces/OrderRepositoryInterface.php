<?php

namespace App\Repositories\Interfaces;

use App\Models\Order;
use Illuminate\Pagination\LengthAwarePaginator;

interface OrderRepositoryInterface
{
    public function getAll();
    public function getById($id);
    public function create(array $attributes);
    public function update($id, array $attributes);
    public function delete($id);
    public function addToOrder(int $userId, int $productId, int $product_price, int $quantity, int $total_price): Order;
    public function getUserOrders(int $userId, int $perPage = 10): LengthAwarePaginator;


}
