<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Purchase;

class DashboardController extends Controller
{
    public function index()
    {
        $chartStartDate = now()->startOfMonth()->subMonths(5);
        $chartEndDate = now()->endOfMonth();

        $chartOrders = Order::whereBetween(
            'created_at',
            [
                $chartStartDate,
                $chartEndDate,
            ]
        )->get([
            'total_price',
            'created_at',
        ]);

        $monthlySales = collect(range(0, 5))->map(function ($monthOffset) use (
            $chartStartDate,
            $chartOrders
        ) {
            $month = $chartStartDate->copy()->addMonths($monthOffset);

            return [
                'label' => $month->format('M'),
                'total' => (float) $chartOrders
                    ->filter(fn ($order) => $order->created_at->format('Y-m') === $month->format('Y-m'))
                    ->sum('total_price'),
            ];
        });

        $dashboard = [
            'total_products' => Product::count(),
            'total_customers' => Customer::count(),
            'total_orders' => Order::count(),
            'total_sales' => Order::sum('total_price'),
            'total_purchases' => Purchase::sum('total_price'),
        ];

        $lowStockProducts = Product::where('stock', '<=', 5)
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(5)
            ->get();

        $recentOrders = Order::with([
            'customer',
            'payment',
        ])
            ->latest()
            ->limit(5)
            ->get();

        $recentPayments = Payment::with([
            'order.customer',
        ])
            ->latest()
            ->limit(5)
            ->get();

        return view(
            'admin.dashboard.index',
            compact(
                'dashboard',
                'lowStockProducts',
                'recentOrders',
                'recentPayments',
                'monthlySales'
            )
        );
    }
}
