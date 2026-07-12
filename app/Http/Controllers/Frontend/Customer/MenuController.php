<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->latest()
            ->get();

        return view(
            'customer.menu',
            compact(
                'categories',
                'products'
            )
        );
    }
}
