<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'name' => 'Premium Wireless Headphones',
                'description' => 'High-quality wireless headphones with noise cancellation',
                'price' => 25000,
                'stock' => 50,
            ],
            [
                'name' => 'Smart Watch Pro',
                'description' => 'Feature-rich smartwatch with health tracking',
                'price' => 45000,
                'stock' => 30,
            ],
            [
                'name' => 'Ultra HD Smart TV 55"',
                'description' => '4K Smart TV with streaming apps built-in',
                'price' => 250000,
                'stock' => 10,
            ],
            [
                'name' => 'Gaming Laptop',
                'description' => 'High-performance gaming laptop with RTX graphics',
                'price' => 550000,
                'stock' => 15,
            ],
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Compact earbuds with long battery life',
                'price' => 15000,
                'stock' => 100,
            ],
            [
                'name' => 'Fitness Tracker',
                'description' => 'Track your steps, heart rate, and sleep',
                'price' => 12000,
                'stock' => 75,
            ],
            [
                'name' => 'Portable Power Bank',
                'description' => '20000mAh fast charging power bank',
                'price' => 8000,
                'stock' => 200,
            ],
            [
                'name' => 'Mechanical Keyboard',
                'description' => 'RGB mechanical gaming keyboard',
                'price' => 18000,
                'stock' => 40,
            ],
        ];

        foreach ($products as $product) {
            Product::create([
                'id' => (string) Str::uuid(),
                'name' => $product['name'],
                'description' => $product['description'],
                'price' => $product['price'],
                'stock' => $product['stock'],
            ]);
        }
    }
}