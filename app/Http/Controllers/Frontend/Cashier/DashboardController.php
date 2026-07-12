<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;

class DashboardController extends Controller
{
    public function index()
    {
        $cashierOrders = Order::where('user_id', auth('web')->id());

        $dashboard = [
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_orders' => (clone $cashierOrders)->count(),
            'total_sales' => (clone $cashierOrders)->sum('total_price'),
            'total_purchases' => Purchase::sum('total_price'),
        ];

        $recentSales = Order::with([
            'customer',
            'payment',
        ])
            ->where('user_id', auth('web')->id())
            ->latest()
            ->limit(5)
            ->get();

        return view(
            'cashier.dashboard.index',
            compact(
                'dashboard',
                'recentSales'
            )
        );
    }
}
