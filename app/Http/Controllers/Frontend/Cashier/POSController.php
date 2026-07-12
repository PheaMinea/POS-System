<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Payment;
use App\Services\BakongService;
use App\Services\TelegramReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    protected BakongService $bakongService;
    protected TelegramReceiptService $telegramReceiptService;

    public function __construct(
        BakongService $bakongService,
        TelegramReceiptService $telegramReceiptService
    ) {
        $this->bakongService = $bakongService;
        $this->telegramReceiptService = $telegramReceiptService;
    }

    public function index()
    {
        try {

            $products = Product::with('category')->latest()->get();
            $customers = Customer::latest()->get();

            return view(
                'cashier.pos.index',
                compact(
                    'products',
                    'customers'
                )
            );

        } catch (\Exception $e) {

            return view(
                'cashier.pos.index',
                [
                    'products' => [],
                    'customers' => []
                ]
            )->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function checkout(Request $request)
    {
        try {

            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'payment_method' => 'required|string|in:cash,aba,acleda,wing,bakong',
                'items' => 'required|array|min:1',
                'items.*.product_id' => 'required|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $order = DB::transaction(function () use ($validated) {
                $items = collect($validated['items'])->map(function ($item) {
                    $product = Product::lockForUpdate()->findOrFail(
                        $item['product_id']
                    );

                    if ($product->stock < $item['quantity']) {
                        throw new \RuntimeException(
                            "{$product->name} stock not enough."
                        );
                    }

                    return [
                        'product' => $product,
                        'quantity' => $item['quantity'],
                        'subtotal' => $product->price * $item['quantity'],
                    ];
                });

                $totalPrice = $items->sum('subtotal');

                $order = Order::create([
                    'customer_id' => $validated['customer_id'],
                    'user_id' => auth('web')->id(),
                    'total_price' => $totalPrice,
                ]);

                foreach ($items as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item['product']->id,
                        'quantity' => $item['quantity'],
                        'price' => $item['product']->price,
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['quantity']);
                }

                    // Create payment record
                $paymentStatus = $validated['payment_method'] === 'bakong' ? 'pending' : 'paid';

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'payment_method' => $validated['payment_method'],
                    'reference_no' => 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'status' => $paymentStatus,
                ]);

                return $order;
            });

            // Load relationships for receipt
            $order->load(['customer', 'user', 'orderItems.product', 'payment']);

            // For Bakong, redirect to QR generation page
            if ($validated['payment_method'] === 'bakong') {
                if ($request->wantsJson()) {
                    return response()->json([
                        'success' => true,
                        'order_id' => $order->id,
                        'redirect_url' => route('cashier.payments.generate-qr', $order->id),
                    ]);
                }

                return redirect()
                    ->route('cashier.payments.generate-qr', $order->id)
                    ->with('success', 'Please scan QR code to complete payment');
            }

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'order_id' => $order->id,
                    'redirect_url' => route('cashier.receipt', $order->id),
                ]);
            }

            return redirect()
                ->route('cashier.receipt', $order->id)
                ->with('success', 'Order Created Successfully');

        } catch (\Exception $e) {

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->withErrors([
                'error' => $e->getMessage()
            ]);
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
                'cashier.pos.receipt',
                compact('order')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.pos.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }
}