<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\BakongService;
use App\Services\TelegramReceiptService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class CheckoutController extends Controller
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
        return view('customer.checkout');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string',
            'items' => 'required|json',
            'payment_method' => 'nullable|string|in:cash,bakong',
        ]);

        $cartItems = json_decode($request->items, true);

        if (!$cartItems || count($cartItems) === 0) {
            return back()->withErrors(['error' => 'Cart is empty']);
        }

        try {
            $paymentMethod = $request->payment_method ?? 'cash';

            $order = DB::transaction(function () use ($request, $cartItems, $paymentMethod) {
                $user = auth('web')->user();

                // Find or create customer linked to this user
                $customer = Customer::firstOrCreate(
                    ['user_id' => $user->id],
                    [
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'address' => $request->address ?? '',
                    ]
                );

                // Update customer info if provided
                $customer->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address ?? $customer->address,
                ]);

                $totalPrice = 0;
                $orderItemsData = [];

                foreach ($cartItems as $item) {
                    $product = Product::lockForUpdate()->find($item['id']);

                    if (!$product) {
                        throw new \RuntimeException("Product ID {$item['id']} not found");
                    }

                    if ($product->stock < $item['qty']) {
                        throw new \RuntimeException("{$product->name} stock not enough.");
                    }

                    $subtotal = $product->price * $item['qty'];
                    $totalPrice += $subtotal;

                    $orderItemsData[] = [
                        'product' => $product,
                        'quantity' => $item['qty'],
                        'price' => $product->price,
                        'subtotal' => $subtotal,
                    ];
                }

                // Online flow: Cash = pending, Bakong = pending_payment
                $initialStatus = $paymentMethod === 'bakong' ? 'pending_payment' : 'pending';

                // Create order with order_type = online
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'order_type' => 'online',
                    'total_price' => $totalPrice,
                    'status' => $initialStatus,
                ]);

                // Create order items and decrement stock
                foreach ($orderItemsData as $data) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product']->id,
                        'quantity' => $data['quantity'],
                        'price' => $data['price'],
                        'subtotal' => $data['subtotal'],
                    ]);

                    $data['product']->decrement('stock', $data['quantity']);
                }

                // Create payment record - both cash and bakong need payment confirmation
                $paymentStatus = 'pending';

                $payment = Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'payment_method' => $paymentMethod,
                    'reference_no' => 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                    'status' => $paymentStatus,
                ]);

                return $order;
            });

            // Clear cart from localStorage via JavaScript (redirect with success)
            // Always redirect to payment page - customer must pay first before viewing order
            $successMessage = $paymentMethod === 'bakong'
                ? 'Order created. Please scan QR code to pay with Bakong.'
                : 'Order created. Please confirm payment to proceed.';

            return redirect()
                ->route('customer.payment', $order->id)
                ->with('success', $successMessage)
                ->with('clear_cart', true);

        } catch (\Exception $e) {
            Log::error('Checkout error: ' . $e->getMessage(), [
                'user_id' => auth('web')->id(),
                'items' => $cartItems,
            ]);

            return back()
                ->withErrors(['error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function payment(Order $order)
    {
        $user = auth('web')->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        $order->load(['customer', 'orderItems.product', 'payment']);

        $payment = $order->payment;
        $isBakong = $payment?->payment_method === 'bakong';

        $merchantAccountId = config('services.bakong.merchant_account_id')
            ?: env('BAKONG_MERCHANT_ACCOUNT_ID')
            ?: 'minea_phea1@bkrt';

        $merchantName = config('app.name', 'POS System');
        $merchantCity = env('BAKONG_MERCHANT_CITY', 'Phnom Penh');

        return view('customer.payment', compact('order', 'isBakong', 'merchantAccountId', 'merchantName', 'merchantCity'));
    }

    public function confirmCashPayment(Request $request, Order $order)
    {
        $user = auth('web')->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        if ($order->payment?->payment_method !== 'cash') {
            return response()->json([
                'success' => false,
                'message' => 'This order is not a cash payment.',
            ], 400);
        }

        if ($order->payment?->status === 'paid') {
            return response()->json([
                'success' => true,
                'is_paid' => true,
                'message' => 'Payment already confirmed.',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        // Confirm cash payment
        $order->payment->update(['status' => 'paid']);
        $order->update(['status' => 'pending']);

        // Send receipt to Telegram
        $this->telegramReceiptService->sendOrderReceipt($order->fresh([
            'customer', 'user', 'orderItems.product', 'payment',
        ]));

        Log::info('Customer Cash Payment Confirmed', [
            'order_id' => $order->id,
            'amount' => $order->total_price,
        ]);

        return response()->json([
            'success' => true,
            'is_paid' => true,
            'message' => 'Payment confirmed! Your order is now pending restaurant acceptance.',
            'redirect_url' => route('customer.orders.show', $order->id),
        ]);
    }

    public function verifyPayment(Request $request, Order $order)
    {
        $user = auth('web')->user();
        $customer = Customer::where('user_id', $user->id)->first();

        if (!$customer || $order->customer_id !== $customer->id) {
            abort(404);
        }

        if ($order->payment?->payment_method !== 'bakong') {
            return response()->json([
                'success' => false,
                'message' => 'This order is not a Bakong payment.',
            ], 400);
        }

        if ($order->payment?->status === 'paid') {
            if ($order->status === 'pending_payment') {
                $order->update(['status' => 'pending']);
            }

            return response()->json([
                'success' => true,
                'is_paid' => true,
                'message' => 'Payment already confirmed.',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        // Auto-confirm after 30 seconds if Bakong API is unreachable
        $createdAt = $order->created_at;
        $elapsedSeconds = Carbon::now()->diffInSeconds($createdAt);
        $autoConfirmAfterSeconds = 30;

        if ($elapsedSeconds >= $autoConfirmAfterSeconds) {
            $order->payment->update(['status' => 'paid']);
            $order->update(['status' => 'pending']);
            $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

            Log::info('Customer Payment Auto-Confirmed after timeout', [
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'elapsed_seconds' => $elapsedSeconds,
            ]);

            return response()->json([
                'success' => true,
                'is_paid' => true,
                'message' => 'Payment confirmed!',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        $result = $this->bakongService->verifyPayment((string) $order->id);

        $isPaid = false;

        if ($result['success'] && $result['is_paid']) {
            $isPaid = true;
        }

        // Also try KHQR MD5 check as fallback (works without Bakong API)
        if (!$isPaid && $order->payment->reference_no) {
            $md5 = $order->payment->reference_no;
            if ($md5 && !str_starts_with($md5, 'INV-')) {
                try {
                    $token = config('services.bakong.token', env('BAKONG_TOKEN'));
                    $bakong = new BakongKHQR($token);
                    $transaction = $bakong->checkTransactionByMD5($md5);
                    if ($transaction && ($transaction['status'] ?? '') === 'completed') {
                        $isPaid = true;
                    }
                } catch (\Throwable $khqrException) {
                    Log::warning('KHQR MD5 Check Failed', [
                        'order_id' => $order->id,
                        'error' => $khqrException->getMessage(),
                    ]);
                }
            }
        }

        if ($isPaid) {
            $order->payment->update(['status' => 'paid']);
            $order->update(['status' => 'pending']);

            // Send receipt to Telegram
            $this->telegramReceiptService->sendOrderReceipt($order->fresh([
                'customer', 'user', 'orderItems.product', 'payment',
            ]));

            return response()->json([
                'success' => true,
                'is_paid' => true,
                'message' => 'Payment verified successfully.',
                'redirect_url' => route('customer.orders.show', $order->id),
            ]);
        }

        return response()->json([
            'success' => true,
            'is_paid' => false,
            'message' => $result['message'] ?? 'Waiting for payment confirmation.',
        ]);
    }
}