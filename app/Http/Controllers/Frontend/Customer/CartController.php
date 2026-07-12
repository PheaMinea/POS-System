<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Customer;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        return view('customer.cart');
    }

    public function current(Request $request)
    {
        $user = auth('web')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated.',
            ], 401);
        }

        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'No customer profile found.',
                'order' => null,
            ]);
        }

        // Get the most recent order that is still pending payment for this customer
        $order = Order::with(['orderItems.product'])
            ->where('customer_id', $customer->id)
            ->whereIn('status', ['pending_payment', 'pending'])
            ->latest()
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'No pending order found.',
                'order' => null,
            ]);
        }

        $items = $order->orderItems->map(function ($item) {
            return [
                'id' => $item->product_id,
                'name' => $item->product?->name ?? 'Product #' . $item->product_id,
                'price' => (float) $item->price,
                'qty' => (int) $item->quantity,
            ];
        });

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'total_price' => (float) $order->total_price,
                'items' => $items,
            ],
        ]);
    }
}