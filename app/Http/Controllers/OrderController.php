<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Order\OrderRequest;
use App\Http\Resources\Order\OrderResource;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class OrderController extends Controller
{

    private OrderRepositoryInterface $OrderRepository;
    private ProductRepositoryInterface $ProductRepository;


    public function __construct( OrderRepositoryInterface $OrderRepository, ProductRepositoryInterface $ProductRepository)
    {
        $this->OrderRepository = $OrderRepository;
        $this->ProductRepository = $ProductRepository;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request  $request)
    {
        $userId = Auth::id(); 
        $perPage = $request->query('per_page', 10); 

        $Orders = $this->OrderRepository->getUserOrders($userId, $perPage);

        if (!$Orders) {
            return response()->json(['error' => 'Orders not found'], 404);
        }

        return response()->json([
            'products' => OrderResource::collection($Orders), 
            'pagination' => [
                'total' => $Orders->total(),
                'count' => $Orders->count(),
                'per_page' => $Orders->perPage(),
                'current_page' => $Orders->currentPage(),
                'total_pages' => $Orders->lastPage(),
            ]
        ], 200);

        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OrderRequest  $request)
    {
        $userId = Auth::id();
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $product = $this->ProductRepository->getById($productId);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        if ($product->stock < $quantity) {
            return response()->json([
                'error' => 'Insufficient stock',
                'available_stock' => $product->stock
            ], 400);
        }

        $total_price=$quantity*$product->price;

        $order = $this->OrderRepository->addToOrder($userId, $productId,$product->price, $quantity, $total_price);

        $product->decrement('stock', $quantity);

        return response()->json([
            'message' => 'Product added to order successfully',
            'order' => new OrderResource($order),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OrderRequest $request, int $id): JsonResponse
    {
        $order = $this->OrderRepository->getById($id);

        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        
        $updatedData = $request->only(['product_id', 'quantity']);
        $updatedOrder = $this->OrderRepository->update($id, $updatedData);

        
        return response()->json([
            'message' => 'Order updated successfully',
            'order' => new OrderResource($updatedOrder),
        ], 200);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $order = $this->OrderRepository->getById($id);

        if ($order->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $deleted = $this->OrderRepository->delete($id);

        if (!$deleted) {
            return response()->json(['error' => 'Cannot delete the Order'], 400);
        }
    
        return response()->json(['message' => 'the Order deleted successfully'], 200);
    }
}
