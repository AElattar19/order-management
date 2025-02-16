<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    
        Product::query()->truncate();
        $Products = [
            ['slug' => 'product_title_1','title' => 'Product Title 1','description' => 'description Administrators', 'price' => '20.5', 'stock' => '1'],
            ['slug' => 'product_title_2','title' => 'Product Title 2','description' => 'description Administrators', 'price' => '22.5', 'stock' => '10'],
            ['slug' => 'product_title_3','title' => 'Product Title 3','description' => 'description Administrators', 'price' => '20.5', 'stock' => '3'],
            ['slug' => 'product_title_4','title' => 'Product Title 4','description' => 'description Administrators', 'price' => '30.5', 'stock' => '1'],
            ['slug' => 'product_title_5','title' => 'Product Title 5','description' => 'description Administrators', 'price' => '20.5', 'stock' => '5'],
            ['slug' => 'product_title_6','title' => 'Product Title 6','description' => 'description Administrators', 'price' => '20.5', 'stock' => '0'],


        ];
        foreach ($Products as $key => $value) {
            Product::create($value);
        }
    }
}
