<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\AllProductsResource;
use App\Repositories\Interfaces\ProductRepositoryInterface;

class ProductController extends Controller
{

    private ProductRepositoryInterface $ProductRepository;
    /**
     * Display a listing of the resource.
     */

     public function __construct( ProductRepositoryInterface $ProductRepository)
    {
        $this->ProductRepository = $ProductRepository;
    }

    public function index(Request $request)
    {

        $perPage = $request->query('per_page', 10);
        $products = $this->ProductRepository->getAll($perPage);

        return response()->json([
            'products' => AllProductsResource::collection($products), 
            'pagination' => [
                'total' => $products->total(),
                'count' => $products->count(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'total_pages' => $products->lastPage(),
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        $product = $this->ProductRepository->getBySlug($slug);
        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        return response()->json(new ProductResource($product), 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
    }
}
