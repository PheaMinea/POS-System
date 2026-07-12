<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerMenuTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_menu_shows_products_and_add_to_cart_button(): void
    {
        Category::factory()->create(['name' => 'Food']);
        Product::create([
            'category_id' => Category::first()->id,
            'name' => 'Burger',
            'price' => 5.50,
            'stock' => 10,
        ]);

        $response = $this->get(route('customer.menu'));

        $response->assertStatus(200);
        $response->assertSee('Burger');
        $response->assertSee('Add To Cart');
        $response->assertSee('class="add-to-cart-btn', false);
    }
}
