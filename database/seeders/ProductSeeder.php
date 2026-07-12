<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [

            [
                'category_id' => 1,
                'name' => 'iPhone 15 Pro Max',
                'price' => 1499,
                'stock' => 20,
                'image' => 'iphone15.jpg',
            ],

            [
                'category_id' => 1,
                'name' => 'Samsung Galaxy S25',
                'price' => 1299,
                'stock' => 15,
                'image' => 'samsung-s25.jpg',
            ],

            [
                'category_id' => 2,
                'name' => 'MacBook Pro M4',
                'price' => 2499,
                'stock' => 10,
                'image' => 'macbook-m4.jpg',
            ],

            [
                'category_id' => 2,
                'name' => 'Dell XPS 15',
                'price' => 1899,
                'stock' => 8,
                'image' => 'dell-xps.jpg',
            ],

            [
                'category_id' => 3,
                'name' => 'iPad Pro',
                'price' => 999,
                'stock' => 12,
                'image' => 'ipad-pro.jpg',
            ],

            [
                'category_id' => 4,
                'name' => 'Apple Watch Ultra',
                'price' => 799,
                'stock' => 18,
                'image' => 'apple-watch.jpg',
            ],

            [
                'category_id' => 5,
                'name' => 'AirPods Pro',
                'price' => 249,
                'stock' => 30,
                'image' => 'airpods.jpg',
            ],
        ];

        foreach ($products as $product) {

            Product::updateOrCreate(
                ['name' => $product['name']],
                $product
            );
        }
    }
}