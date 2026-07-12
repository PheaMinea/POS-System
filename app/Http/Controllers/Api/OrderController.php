<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseApiController
{
    public function index()
    {
        try {

            $orders = Order::with([
                'customer',
                'user',
                'orderItems.product'
            ])->latest()->get();

            return $this->success(
                $orders,
                'Orders Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function show(Order $order)
    {
        try {

            $order->load([
                'customer',
                'user',
                'orderItems.product',
                'payment'
            ]);

            return $this->success(
                $order,
                'Order Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'total_price' => 'required|numeric|min:0',
                'items' => 'required|array|min:1',

                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity'   => 'required|integer|min:1',
                'items.*.price'      => 'required|numeric|min:0',
            ]);

            $order = Order::create([
                'customer_id' => $request->customer_id,
                'user_id'     => auth()->id(),
                'total_price' => $request->total_price,
            ]);

            foreach ($request->items as $item) {

                $product = Product::findOrFail(
                    $item['product_id']
                );

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        "{$product->name} stock not enough."
                    );
                }

                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $product->id,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'subtotal'   => $item['quantity'] * $item['price'],
                ]);

                $product->decrement(
                    'stock',
                    $item['quantity']
                );
            }

            DB::commit();

            return $this->success(
                $order->load([
                    'customer',
                    'user',
                    'orderItems.product'
                ]),
                'Order Created Successfully',
                201
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {

            $order->load('orderItems');

            foreach ($order->orderItems as $item) {

                Product::where(
                    'id',
                    $item->product_id
                )->increment(
                    'stock',
                    $item->quantity
                );
            }

            $order->orderItems()->delete();

            $order->delete();

            DB::commit();

            return $this->success(
                null,
                'Order Deleted Successfully'
            );

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}