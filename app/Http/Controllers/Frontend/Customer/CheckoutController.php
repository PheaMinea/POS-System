<?php

namespace App\Http\Controllers\Frontend\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Services\TelegramReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class CheckoutController extends Controller
{
    protected TelegramReceiptService $telegramReceiptService;

    public function __construct(
        TelegramReceiptService $telegramReceiptService
    ) {
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

        $cartItems = json_decode(
            $request->items,
            true
        );

        if (
            !is_array($cartItems) ||
            count($cartItems) === 0
        ) {
            return $this->checkoutError(
                $request,
                'Cart is empty',
                422
            );
        }

        try {
            $paymentMethod = $request->payment_method
                ?? 'cash';

            $order = DB::transaction(function () use (
                $request,
                $cartItems,
                $paymentMethod
            ) {
                $user = auth('web')->user();

                if (!$user) {
                    throw new \RuntimeException(
                        'Please login before checkout.'
                    );
                }

                $customer = Customer::firstOrCreate(
                    [
                        'user_id' => $user->id,
                    ],
                    [
                        'name' => $request->name,
                        'phone' => $request->phone,
                        'address' => $request->address ?? '',
                    ]
                );

                $customer->update([
                    'name' => $request->name,
                    'phone' => $request->phone,
                    'address' => $request->address
                        ?? $customer->address,
                ]);

                $totalPrice = 0;
                $orderItemsData = [];

                foreach ($cartItems as $item) {
                    $productId = $item['id'] ?? null;
                    $quantity = (int) (
                        $item['qty'] ?? 0
                    );

                    if (!$productId || $quantity < 1) {
                        throw new \RuntimeException(
                            'Invalid cart item.'
                        );
                    }

                    $product = Product::query()
                        ->lockForUpdate()
                        ->find($productId);

                    if (!$product) {
                        throw new \RuntimeException(
                            "Product ID {$productId} not found."
                        );
                    }

                    if ($product->stock < $quantity) {
                        throw new \RuntimeException(
                            "{$product->name} stock not enough."
                        );
                    }

                    $price = (float) $product->price;
                    $subtotal = $price * $quantity;

                    $totalPrice += $subtotal;

                    $orderItemsData[] = [
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'subtotal' => $subtotal,
                    ];
                }

                $order = Order::create([
                    'customer_id' => $customer->id,
                    'user_id' => $user->id,
                    'order_type' => 'online',
                    'total_price' => $totalPrice,
                    'status' => $paymentMethod === 'bakong'
                        ? 'pending_payment'
                        : 'pending',
                ]);

                foreach ($orderItemsData as $data) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $data['product_id'],
                        'quantity' => $data['quantity'],
                        'price' => $data['price'],
                        'subtotal' => $data['subtotal'],
                    ]);
                }

                Payment::create([
                    'order_id' => $order->id,
                    'amount' => $totalPrice,
                    'payment_method' => $paymentMethod,
                    'reference_no' => null,
                    'status' => 'pending',
                ]);

                return $order;
            });

            $order->load([
                'customer',
                'orderItems.product',
                'payment',
            ]);

            if ($paymentMethod === 'bakong') {
                $qr = $this->generateBakongQrForOrder(
                    $order
                );

                if (empty($qr['qr_data'])) {
                    throw new \RuntimeException(
                        'Unable to generate Bakong KHQR.'
                    );
                }

                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'clear_cart' => true,
                        'message' => 'Please scan KHQR to pay.',
                        'payment' => [
                            'order_id' => $order->id,
                            'payment_id' => $order->payment->id,
                            'amount' => (float) $order->total_price,
                            'reference_no' => $qr['md5'],
                            'qr_data' => $qr['qr_data'],
                            'qr_image' => '',
                            'verify_url' => route(
                                'customer.payment.verify',
                                $order->id
                            ),
                            'order_url' => route(
                                'customer.orders.show',
                                $order->id
                            ),
                        ],
                    ]);
                }
            }

            return redirect()
                ->route(
                    'customer.payment',
                    $order->id
                )
                ->with(
                    'success',
                    $paymentMethod === 'bakong'
                        ? 'Please scan KHQR to pay.'
                        : 'Please confirm payment.'
                )
                ->with('clear_cart', true);

        } catch (\Throwable $e) {
            Log::error('Customer Checkout Error', [
                'user_id' => auth('web')->id(),
                'message' => $e->getMessage(),
            ]);

            return $this->checkoutError(
                $request,
                $e->getMessage(),
                422
            );
        }
    }

    public function payment(Order $order)
    {
        $this->authorizeCustomerOrder($order);

        $order->load([
            'customer',
            'orderItems.product',
            'payment',
        ]);

        $payment = $order->payment;

        $isBakong = $payment?->payment_method
            === 'bakong';

        $merchantAccountId = config(
            'services.bakong.merchant_account_id'
        )
            ?: env('BAKONG_MERCHANT_ACCOUNT_ID')
            ?: 'minea_phea1@bkrt';

        $merchantName = config(
            'app.name',
            'POS System'
        );

        $merchantCity = env(
            'BAKONG_MERCHANT_CITY',
            'Phnom Penh'
        );

        $qr = null;

        if (
            $isBakong &&
            $payment?->status !== 'paid'
        ) {
            $qr = $this->generateBakongQrForOrder(
                $order
            );
        }

        return view(
            'customer.payment',
            compact(
                'order',
                'isBakong',
                'merchantAccountId',
                'merchantName',
                'merchantCity',
                'qr'
            )
        );
    }

    public function verifyPayment(
        Request $request,
        Order $order
    ) {
        try {
            $this->authorizeCustomerOrder($order);

            $order->load([
                'payment',
                'orderItems.product',
            ]);

            $payment = $order->payment;

            if (!$payment) {
                return response()->json([
                    'success' => false,
                    'is_paid' => false,
                    'message' => 'Payment not found.',
                ], 404);
            }

            if (
                strtolower($payment->payment_method)
                !== 'bakong'
            ) {
                return response()->json([
                    'success' => false,
                    'is_paid' => false,
                    'message' => 'This is not a Bakong payment.',
                ], 400);
            }

            if ($payment->status === 'paid') {
                return response()->json([
                    'success' => true,
                    'is_paid' => true,
                    'status' => 'paid',
                    'message' => 'ទឹកប្រាក់ទូទាត់បានជោគជ័យ',
                    'redirect_url' => route(
                        'customer.orders.show',
                        $order->id
                    ),
                ]);
            }

            $md5 = $payment->reference_no;

            if (
                empty($md5) ||
                str_starts_with($md5, 'INV-')
            ) {
                return response()->json([
                    'success' => true,
                    'is_paid' => false,
                    'status' => 'pending',
                    'message' => 'Waiting for KHQR payment...',
                ]);
            }

            $token = config(
                'services.bakong.token'
            ) ?: env('BAKONG_TOKEN');

            if (empty($token)) {
                throw new \RuntimeException(
                    'BAKONG_TOKEN is not configured.'
                );
            }

            $bakong = new BakongKHQR($token);

            $result = $bakong->checkTransactionByMD5(
                $md5
            );

            Log::info('Customer Bakong Verify', [
                'order_id' => $order->id,
                'payment_id' => $payment->id,
                'md5' => $md5,
                'response' => json_decode(
                    json_encode($result),
                    true
                ),
            ]);

            $responseCode = data_get(
                $result,
                'responseCode'
            );

            if ((int) $responseCode !== 0) {
                return response()->json([
                    'success' => true,
                    'is_paid' => false,
                    'status' => 'pending',
                    'message' => 'កំពុងរង់ចាំការទូទាត់...',
                ]);
            }

            DB::transaction(function () use (
                $order,
                $payment
            ) {
                $lockedPayment = Payment::query()
                    ->lockForUpdate()
                    ->findOrFail($payment->id);

                if ($lockedPayment->status === 'paid') {
                    return;
                }

                $lockedOrder = Order::query()
                    ->with('orderItems')
                    ->lockForUpdate()
                    ->findOrFail($order->id);

                foreach (
                    $lockedOrder->orderItems as $item
                ) {
                    $product = Product::query()
                        ->lockForUpdate()
                        ->findOrFail($item->product_id);

                    if (
                        $product->stock
                        < $item->quantity
                    ) {
                        throw new \RuntimeException(
                            "{$product->name} stock not enough."
                        );
                    }
                }

                foreach (
                    $lockedOrder->orderItems as $item
                ) {
                    $product = Product::query()
                        ->lockForUpdate()
                        ->findOrFail($item->product_id);

                    $product->decrement(
                        'stock',
                        $item->quantity
                    );
                }

                $lockedPayment->update([
                    'status' => 'paid',
                ]);

                $lockedOrder->update([
                    'status' => 'pending',
                ]);
            });

            $freshOrder = $order->fresh([
                'customer',
                'user',
                'orderItems.product',
                'payment',
            ]);

            try {
                $this->telegramReceiptService
                    ->sendOrderReceipt($freshOrder);
            } catch (\Throwable $telegramException) {
                Log::warning(
                    'Telegram receipt failed after payment',
                    [
                        'order_id' => $order->id,
                        'error' => $telegramException->getMessage(),
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'is_paid' => true,
                'status' => 'paid',
                'message' => 'ទឹកប្រាក់ទូទាត់បានជោគជ័យ',
                'redirect_url' => route(
                    'customer.orders.show',
                    $order->id
                ),
            ]);

        } catch (\Throwable $e) {
            Log::error('Customer Bakong Verify Error', [
                'order_id' => $order->id,
                'message' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'is_paid' => false,
                'status' => 'pending',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function confirmCashPayment(
        Request $request,
        Order $order
    ) {
        try {
            $this->authorizeCustomerOrder($order);

            $order->load([
                'payment',
                'orderItems',
            ]);

            $payment = $order->payment;

            if (
                !$payment ||
                $payment->payment_method !== 'cash'
            ) {
                return response()->json([
                    'success' => false,
                    'is_paid' => false,
                    'message' => 'This order is not cash payment.',
                ], 400);
            }

            if ($payment->status !== 'paid') {
                DB::transaction(function () use (
                    $order,
                    $payment
                ) {
                    $lockedPayment = Payment::query()
                        ->lockForUpdate()
                        ->findOrFail($payment->id);

                    if (
                        $lockedPayment->status
                        === 'paid'
                    ) {
                        return;
                    }

                    $lockedOrder = Order::query()
                        ->with('orderItems')
                        ->lockForUpdate()
                        ->findOrFail($order->id);

                    foreach (
                        $lockedOrder->orderItems as $item
                    ) {
                        $product = Product::query()
                            ->lockForUpdate()
                            ->findOrFail(
                                $item->product_id
                            );

                        if (
                            $product->stock
                            < $item->quantity
                        ) {
                            throw new \RuntimeException(
                                "{$product->name} stock not enough."
                            );
                        }
                    }

                    foreach (
                        $lockedOrder->orderItems as $item
                    ) {
                        Product::where(
                            'id',
                            $item->product_id
                        )->decrement(
                            'stock',
                            $item->quantity
                        );
                    }

                    $lockedPayment->update([
                        'status' => 'paid',
                    ]);

                    $lockedOrder->update([
                        'status' => 'pending',
                    ]);
                });
            }

            return response()->json([
                'success' => true,
                'is_paid' => true,
                'status' => 'paid',
                'message' => 'ទឹកប្រាក់ទូទាត់បានជោគជ័យ',
                'redirect_url' => route(
                    'customer.orders.show',
                    $order->id
                ),
            ]);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'is_paid' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    private function generateBakongQrForOrder(
        Order $order
    ): array {
        $order->loadMissing('payment');

        $payment = $order->payment;

        if (!$payment) {
            throw new \RuntimeException(
                'Payment record not found.'
            );
        }

        $merchant = new IndividualInfo(
            bakongAccountID: config(
                'services.bakong.merchant_account_id'
            )
                ?: env('BAKONG_MERCHANT_ACCOUNT_ID')
                ?: 'minea_phea1@bkrt',

            merchantName: config(
                'services.bakong.merchant_name'
            )
                ?: config('app.name', 'POS System'),

            merchantCity: config(
                'services.bakong.merchant_city'
            )
                ?: env(
                    'BAKONG_MERCHANT_CITY',
                    'Phnom Penh'
                ),

            currency: KHQRData::CURRENCY_USD,

            amount: (float) $order->total_price
        );

        $qrResponse = BakongKHQR::generateIndividual(
            $merchant
        );

        $qrData = data_get(
            $qrResponse,
            'data.qr'
        );

        $md5 = data_get(
            $qrResponse,
            'data.md5'
        );

        if (empty($qrData) || empty($md5)) {
            throw new \RuntimeException(
                'Bakong KHQR generation failed.'
            );
        }

        $payment->update([
            'reference_no' => $md5,
        ]);

        return [
            'qr_data' => $qrData,
            'qr_image' => '',
            'md5' => $md5,
        ];
    }

    private function authorizeCustomerOrder(
        Order $order
    ): void {
        $user = auth('web')->user();

        if (!$user) {
            abort(401);
        }

        $customer = Customer::where(
            'user_id',
            $user->id
        )->first();

        if (
            !$customer ||
            (int) $order->customer_id
            !== (int) $customer->id
        ) {
            abort(404);
        }
    }

    private function checkoutError(
        Request $request,
        string $message,
        int $status = 422
    ) {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $message,
            ], $status);
        }

        return back()
            ->withErrors([
                'error' => $message,
            ])
            ->withInput();
    }
}