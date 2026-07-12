<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        $products = Product::with('category')
            ->latest()
            ->take(8)
            ->get();

        return view(
            'customer.home',
            compact(
                'categories',
                'products'
            )
        );
    }
}
