<?php

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;

class PaymentController extends BaseApiController
{
    /**
     * Get all payments
     */
    public function index()
    {
        try {

            $payments = Payment::with('order')
                ->latest()
                ->get();

            return $this->success(
                $payments,
                'Payments Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Payment creation is handled by CheckoutController
     */
    public function store(Request $request)
    {
        return $this->error(
            'Payment is created during checkout.',
            501
        );
    }

    /**
     * Get single payment
     */
    public function show($id)
    {
        try {

            $payment = Payment::with('order')
                ->findOrFail($id);

            return $this->success(
                $payment,
                'Payment Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );
        }
    }

    /**
     * Verify Bakong KHQR payment
     */
    public function verify($id)
    {
        try {

            /*
            |--------------------------------------------------------------------------
            | Find Payment
            |--------------------------------------------------------------------------
            */

            $payment = Payment::with([
                'order.orderItems.product'
            ])->findOrFail($id);

            /*
            |--------------------------------------------------------------------------
            | Already Paid
            |--------------------------------------------------------------------------
            */

            if ($payment->status === 'paid') {

                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'ទឹកប្រាក់ទូទាត់បានជោគជ័យ',
                    'data' => [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'amount' => $payment->amount,
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Payment Method
            |--------------------------------------------------------------------------
            */

            if (
                strtolower($payment->payment_method) !== 'bakong'
            ) {

                return response()->json([
                    'success' => false,
                    'status' => $payment->status,
                    'message' => 'This payment is not Bakong KHQR.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Check Reference Number / MD5
            |--------------------------------------------------------------------------
            */

            if (empty($payment->reference_no)) {

                return response()->json([
                    'success' => false,
                    'status' => 'pending',
                    'message' => 'Bakong MD5 reference not found.',
                ], 422);
            }

            /*
            |--------------------------------------------------------------------------
            | Bakong Token
            |--------------------------------------------------------------------------
            */

            $bakongToken = config('services.bakong.token')
                ?? env('BAKONG_TOKEN');

            if (empty($bakongToken)) {

                return response()->json([
                    'success' => false,
                    'status' => 'pending',
                    'message' => 'BAKONG_TOKEN is not configured.',
                ], 500);
            }

            /*
            |--------------------------------------------------------------------------
            | Verify Transaction With Bakong
            |--------------------------------------------------------------------------
            */

            $bakong = new BakongKHQR($bakongToken);

            $response = $bakong->checkTransactionByMD5(
                $payment->reference_no
            );

            Log::info('Bakong Verify Response', [
                'payment_id' => $payment->id,
                'order_id' => $payment->order_id,
                'md5' => $payment->reference_no,
                'response' => json_decode(
                    json_encode($response),
                    true
                ),
            ]);

            /*
            |--------------------------------------------------------------------------
            | Read Bakong Response Code
            |--------------------------------------------------------------------------
            */

            $responseCode = data_get(
                $response,
                'responseCode'
            );

            /*
            |--------------------------------------------------------------------------
            | Payment Success
            |--------------------------------------------------------------------------
            */

            if ((int) $responseCode === 0) {

                DB::transaction(function () use ($payment) {

                    /*
                    |--------------------------------------------------------------------------
                    | Lock Payment
                    |--------------------------------------------------------------------------
                    */

                    $lockedPayment = Payment::where(
                        'id',
                        $payment->id
                    )
                        ->lockForUpdate()
                        ->firstOrFail();

                    /*
                    |--------------------------------------------------------------------------
                    | Prevent Duplicate Stock Deduction
                    |--------------------------------------------------------------------------
                    */

                    if ($lockedPayment->status === 'paid') {
                        return;
                    }

                    $order = Order::with(
                        'orderItems.product'
                    )
                        ->lockForUpdate()
                        ->findOrFail(
                            $lockedPayment->order_id
                        );

                    /*
                    |--------------------------------------------------------------------------
                    | Check Stock
                    |--------------------------------------------------------------------------
                    */

                    foreach ($order->orderItems as $item) {

                        $product = Product::where(
                            'id',
                            $item->product_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                        if (
                            $product->stock
                            < $item->quantity
                        ) {

                            throw new \Exception(
                                "{$product->name} stock not enough."
                            );
                        }
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Deduct Stock After Payment Success
                    |--------------------------------------------------------------------------
                    */

                    foreach ($order->orderItems as $item) {

                        $product = Product::where(
                            'id',
                            $item->product_id
                        )
                            ->lockForUpdate()
                            ->firstOrFail();

                        $product->decrement(
                            'stock',
                            $item->quantity
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Update Payment
                    |--------------------------------------------------------------------------
                    */

                    $lockedPayment->update([
                        'status' => 'paid',
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Update Order
                    |--------------------------------------------------------------------------
                    */

                    $order->update([
                        'status' => 'paid',
                    ]);
                });

                $payment->refresh();

                return response()->json([
                    'success' => true,
                    'status' => 'paid',
                    'message' => 'ទឹកប្រាក់ទូទាត់បានជោគជ័យ',
                    'data' => [
                        'payment_id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                    ],
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | Payment Still Pending
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'កំពុងរង់ចាំការទូទាត់...',
                'data' => [
                    'payment_id' => $payment->id,
                    'order_id' => $payment->order_id,
                ],
            ]);

        } catch (\Exception $e) {

            Log::error('Bakong Payment Verify Error', [
                'payment_id' => $id,
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'status' => 'pending',
                'message' => 'Unable to verify payment right now.',
            ], 500);
        }
    }

    /**
     * Update payment
     */
    public function update(Request $request, $id)
    {
        return $this->error(
            'Not implemented',
            501
        );
    }

    /**
     * Delete payment
     */
    public function destroy($id)
    {
        return $this->error(
            'Not implemented',
            501
        );
    }
}
