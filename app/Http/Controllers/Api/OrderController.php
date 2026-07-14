<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends BaseApiController
{
    /**
     * Display all orders.
     */
    public function index()
    {
        try {

            $orders = Order::with([
                'customer',
                'user',
                'orderItems.product',
                'payment',
            ])
                ->latest()
                ->get();

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

    /**
     * Display single order.
     */
    public function show(Order $order)
    {
        try {

            $order->load([
                'customer',
                'user',
                'orderItems.product',
                'payment',
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

    /**
     * Create POS order.
     *
     * This method is for POS/Admin orders.
     * Customer Bakong checkout should use CheckoutController.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {

            $validated = $request->validate([
                'customer_id' => [
                    'required',
                    'exists:customers,id',
                ],

                'items' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'items.*.product_id' => [
                    'required',
                    'exists:products,id',
                ],

                'items.*.quantity' => [
                    'required',
                    'integer',
                    'min:1',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Calculate total from database
            |--------------------------------------------------------------------------
            |
            | Do not trust total_price or product price from frontend.
            |
            */

            $totalPrice = 0;

            $products = [];

            foreach ($validated['items'] as $item) {

                $product = Product::findOrFail(
                    $item['product_id']
                );

                if ($product->stock < $item['quantity']) {

                    throw new \Exception(
                        "{$product->name} stock not enough."
                    );
                }

                $subtotal = (float) $product->price
                    * (int) $item['quantity'];

                $totalPrice += $subtotal;

                $products[] = [
                    'product' => $product,
                    'quantity' => (int) $item['quantity'],
                    'price' => (float) $product->price,
                    'subtotal' => $subtotal,
                ];
            }

            /*
            |--------------------------------------------------------------------------
            | Create POS order
            |--------------------------------------------------------------------------
            */

            $order = Order::create([
                'customer_id' => $validated['customer_id'],
                'user_id' => auth()->id(),
                'order_type' => 'pos',
                'total_price' => $totalPrice,
                'status' => 'paid',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Create order items
            |--------------------------------------------------------------------------
            */

            foreach ($products as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product']->id,
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['subtotal'],
                ]);

                /*
                |--------------------------------------------------------------------------
                | POS order can deduct stock immediately
                |--------------------------------------------------------------------------
                */

                $item['product']->decrement(
                    'stock',
                    $item['quantity']
                );
            }

            DB::commit();

            return $this->success(
                $order->load([
                    'customer',
                    'user',
                    'orderItems.product',
                    'payment',
                ]),
                'Order Created Successfully',
                201
            );

        } catch (\Illuminate\Validation\ValidationException $e) {

            DB::rollBack();

            throw $e;

        } catch (\Exception $e) {

            DB::rollBack();

            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Delete order.
     */
    public function destroy(Order $order)
    {
        DB::beginTransaction();

        try {

            $order->load([
                'orderItems',
                'payment',
            ]);

            /*
            |--------------------------------------------------------------------------
            | Restore stock only when stock was deducted
            |--------------------------------------------------------------------------
            */

            if ($order->status === 'paid') {

                foreach ($order->orderItems as $item) {

                    Product::where(
                        'id',
                        $item->product_id
                    )->increment(
                        'stock',
                        $item->quantity
                    );
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Delete payment
            |--------------------------------------------------------------------------
            */

            if ($order->payment) {
                $order->payment->delete();
            }

            /*
            |--------------------------------------------------------------------------
            | Delete order items
            |--------------------------------------------------------------------------
            */

            $order->orderItems()->delete();

            /*
            |--------------------------------------------------------------------------
            | Delete order
            |--------------------------------------------------------------------------
            */

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