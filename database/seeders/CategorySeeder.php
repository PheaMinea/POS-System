<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Smart Phone',
                'image' => 'phone.jpg',
            ],
            [
                'name' => 'Laptop',
                'image' => 'laptop.jpg',
            ],
            [
                'name' => 'Tablet',
                'image' => 'tablet.jpg',
            ],
            [
                'name' => 'Smart Watch',
                'image' => 'watch.jpg',
            ],
            [
                'name' => 'Accessories',
                'image' => 'accessories.jpg',
            ],
        ];

        foreach ($categories as $category) {

            Category::updateOrCreate(
                ['name' => $category['name']],
                [
                    'image' => $category['image'],
                ]
            );
        }
    }
}