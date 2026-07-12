<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $user = auth('web')->user();

        // Get the customer linked to this user
        $customer = Customer::where('user_id', $user->id)->first();

        $orders = collect();

        if ($customer) {
            $orders = Order::with([
                'customer',
                'payment',
                'orderItems.product'
            ])
                ->where('customer_id', $customer->id)
                ->latest()
                ->get();
        }

        return view(
            'customer.orders',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        $user = auth('web')->user();
        $customer = Customer::where('user_id', $user->id)->first();

        // Ensure the customer can only see their own orders
        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        $order->load([
            'customer',
            'user',
            'orderItems.product',
            'payment'
        ]);

        return view(
            'customer.order-show',
            compact('order')
        );
    }

    public function receipt(Order $order)
    {
        $user = auth('web')->user();
        $customer = Customer::where('user_id', $user->id)->first();

        // Ensure the customer can only see their own orders
        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        $order->load([
            'customer',
            'user',
            'orderItems.product',
            'payment'
        ]);

        return view(
            'customer.receipt',
            compact('order')
        );
    }
}
