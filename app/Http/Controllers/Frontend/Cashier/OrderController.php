<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        try {

            $orders = $this->cashierReadyOrders()->get();

            return view(
                'cashier.orders.index',
                compact('orders')
            );

        } catch (\Exception $e) {

            return view(
                'cashier.orders.index',
                ['orders' => []]
            )->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        try {

            $order = $this->cashierReadyOrders()
                ->findOrFail($id);

            return view(
                'cashier.orders.show',
                compact('order')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.orders.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    private function cashierReadyOrders()
    {
        return Order::with([
                'customer',
                'user',
                'orderItems.product',
                'payment',
            ])
            ->where(function ($query) {
                $query->where('status', '!=', 'pending_payment')
                    ->orWhereHas('payment', function ($paymentQuery) {
                        $paymentQuery->where('status', 'paid');
                    });
            })
            ->latest();
    }

    public function data()
    {
        try {
            $orders = $this->cashierReadyOrders()->get();

            return response()->json([
                'success' => true,
                'orders' => $orders,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|string|in:pending,pending_payment,accepted,preparing,ready,completed',
            ]);

            $order = Order::with('payment')->findOrFail($id);

            if ($order->status === 'pending_payment' && $order->payment?->status !== 'paid') {
                return redirect()
                    ->route('cashier.orders.show', $id)
                    ->with('error', 'Customer payment is not completed yet.');
            }

            $allowedTransitions = [
                'pending_payment' => ['accepted'],
                'pending' => ['accepted'],
                'accepted' => ['preparing'],
                'preparing' => ['ready'],
                'ready' => ['completed'],
                'completed' => [],
            ];

            if (! in_array($validated['status'], $allowedTransitions[$order->status] ?? [], true)) {
                return redirect()
                    ->route('cashier.orders.show', $id)
                    ->with('error', 'Invalid order status transition.');
            }

            $order->update([
                'status' => $validated['status'],
            ]);

            Log::info('Order status updated by cashier', [
                'order_id' => $id,
                'status' => $validated['status'],
                'cashier_id' => auth('web')->id(),
            ]);

            return redirect()
                ->route('cashier.orders.show', $id)
                ->with('success', 'Order status updated to ' . $validated['status']);
        } catch (\Exception $e) {
            return redirect()
                ->route('cashier.orders.index')
                ->with('error', 'Failed to update order status: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $order = Order::with(['orderItems', 'payment'])->findOrFail($id);

            DB::transaction(function () use ($order) {
                // Restore stock for each item
                foreach ($order->orderItems as $item) {
                    $item->product->increment('stock', $item->quantity);
                }

                // Delete order items
                OrderItem::where('order_id', $order->id)->delete();

                // Delete payment record
                if ($order->payment) {
                    $order->payment->delete();
                }

                // Delete the order
                $order->delete();
            });

            Log::info('Order deleted by cashier', [
                'order_id' => $id,
                'cashier_id' => auth('web')->id(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Order deleted successfully',
                ]);
            }

            return redirect()
                ->route('cashier.orders.index')
                ->with('success', 'Order deleted successfully');

        } catch (\Exception $e) {
            Log::error('Order delete error', [
                'order_id' => $id,
                'error' => $e->getMessage(),
            ]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to delete order: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()
                ->route('cashier.orders.index')
                ->with('error', 'Failed to delete order: ' . $e->getMessage());
        }
    }

    public function receipt($id)
    {
        try {

            $order = Order::with([
                'customer',
                'user',
                'orderItems.product',
                'payment',
            ])->findOrFail($id);

            return view(
                'cashier.orders.receipt',
                compact('order')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.orders.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}
