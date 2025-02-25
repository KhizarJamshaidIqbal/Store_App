<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'This is a test product',
            'price' => 99.99,
            'category_id' => 1, // Make sure you have a category with ID 1
            'stock' => 100,
            'status' => 'active'
        ]);
    }
}
