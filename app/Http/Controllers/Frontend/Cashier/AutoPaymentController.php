<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\BakongService;
use App\Services\TelegramReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;

class AutoPaymentController extends Controller
{
    protected BakongService $bakongService;
    protected TelegramReceiptService $telegramReceiptService;

    public function __construct(
        BakongService $bakongService,
        TelegramReceiptService $telegramReceiptService
    )
    {
        $this->bakongService = $bakongService;
        $this->telegramReceiptService = $telegramReceiptService;
    }

    /**
     * AJAX endpoint to check payment status
     * Used by the QR page for auto-verification
     */
    public function check($orderId)
    {
        try {
            $order = Order::with('payment')->findOrFail($orderId);

            if ($order->payment->payment_method !== 'bakong') {
                return response()->json([
                    'success' => false,
                    'message' => 'Not a Bakong payment',
                ], 400);
            }

            // If already paid in database, return success immediately
            if ($order->payment->status === 'paid') {
                if ($order->status === 'pending_payment') {
                    $order->update(['status' => 'pending']);
                }

                $this->telegramReceiptService->sendOrderReceipt($order);

                return response()->json([
                    'success' => true,
                    'is_paid' => true,
                    'message' => 'Payment already verified',
                    'redirect_url' => route('cashier.payments.receipt', $order->id),
                ]);
            }

            $md5 = $order->payment->reference_no;

            // Try KHQR MD5 check first (most reliable)
            if ($md5 && ! str_starts_with($md5, 'INV-')) {
                $token = config('services.bakong.token');

                if ($token) {
                    try {
                        $bakong = new BakongKHQR($token);
                        $transaction = $bakong->checkTransactionByMD5($md5);

                        if (($transaction->responseCode ?? null) == 0) {
                            $order->payment->update(['status' => 'paid']);
                            $order->update(['status' => 'pending']);
                            $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

                            Log::info('Auto Payment Verified by KHQR MD5', [
                                'order_id' => $order->id,
                                'amount' => $order->total_price,
                                'md5' => $md5,
                            ]);

                            return response()->json([
                                'success' => true,
                                'is_paid' => true,
                                'message' => 'Payment verified!',
                                'redirect_url' => route('cashier.payments.receipt', $order->id),
                            ]);
                        }
                    } catch (\Throwable $khqrException) {
                        Log::warning('KHQR MD5 Auto Check Failed', [
                            'order_id' => $order->id,
                            'md5' => $md5,
                            'error' => $khqrException->getMessage(),
                        ]);
                    }
                }
            }

            // Try Bakong API verification as fallback
            try {
                $result = $this->bakongService->verifyPayment((string) $order->id);

                if ($result['success'] && $result['is_paid']) {
                    $order->payment->update(['status' => 'paid']);
                    $order->update(['status' => 'pending']);
                    $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

                    Log::info('Auto Payment Verified via Bakong API', [
                        'order_id' => $order->id,
                        'amount' => $order->total_price,
                    ]);

                    return response()->json([
                        'success' => true,
                        'is_paid' => true,
                        'message' => 'Payment verified!',
                        'redirect_url' => route('cashier.payments.receipt', $order->id),
                    ]);
                }

                // If API responded but payment not found
                if ($result['success'] && !$result['is_paid']) {
                    return response()->json([
                        'success' => true,
                        'is_paid' => false,
                        'message' => 'Waiting for payment...',
                        'status' => $result['status'] ?? 'pending',
                    ]);
                }

                // API error (but not exception) - return waiting
                return response()->json([
                    'success' => true,
                    'is_paid' => false,
                    'message' => 'Waiting for payment...',
                    'status' => 'pending',
                ]);
            } catch (\Throwable $apiException) {
                Log::warning('Bakong API Verify Failed (non-critical)', [
                    'order_id' => $order->id,
                    'error' => $apiException->getMessage(),
                ]);

                // API failed - still return waiting so UI keeps polling
                return response()->json([
                    'success' => true,
                    'is_paid' => false,
                    'message' => 'Checking payment...',
                    'status' => 'pending',
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Auto Payment Check Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            // Return waiting instead of error to keep the UI polling
            return response()->json([
                'success' => true,
                'is_paid' => false,
                'message' => 'Checking payment status...',
                'status' => 'pending',
            ]);
        }
    }

    /**
     * Webhook endpoint for Bakong payment notifications.
     * This can be configured in Bakong merchant dashboard.
     */
    public function webhook(Request $request)
    {
        try {
            $payload = $request->all();

            Log::info('Bakong Webhook Received', ['payload' => $payload]);

            // Validate webhook signature (implement based on Bakong docs)
            $transactionId = $payload['transactionId'] ?? $payload['order_id'] ?? null;
            $status = $payload['status'] ?? '';

            if (!$transactionId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Missing transaction ID',
                ], 400);
            }

            $isPaid = in_array(strtolower($status), ['completed', 'paid', 'success', '0000']);

            if ($isPaid) {
                $order = Order::with('payment')->find($transactionId);

                if ($order && $order->payment) {
                    $order->payment->update(['status' => 'paid']);
                    $order->update(['status' => 'pending']);
                    $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

                    Log::info('Bakong Webhook - Payment Updated', [
                        'order_id' => $order->id,
                        'transaction' => $transactionId,
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment confirmed via webhook',
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook received but no action taken',
            ]);

        } catch (\Exception $e) {
            Log::error('Bakong Webhook Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Webhook processing failed',
            ], 500);
        }
    }

    /**
     * Force verify a payment manually (cashier override)
     * Always marks as paid - this is an override for when Bakong API is unreachable
     */
    public function forceVerify($orderId)
    {
        try {
            $order = Order::with('payment')->findOrFail($orderId);

            if ($order->payment->payment_method !== 'bakong') {
                return response()->json([
                    'success' => false,
                    'message' => 'Not a Bakong payment',
                ], 400);
            }

            if ($order->payment->status === 'paid') {
                if ($order->status === 'pending_payment') {
                    $order->update(['status' => 'pending']);
                }

                $this->telegramReceiptService->sendOrderReceipt($order);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment already verified',
                    'redirect_url' => route('cashier.payments.receipt', $order->id),
                ]);
            }

            // Try API verification first (best effort)
            try {
                $result = $this->bakongService->verifyPayment((string) $order->id);

                if ($result['success'] && $result['is_paid']) {
                    $order->payment->update(['status' => 'paid']);
                    $order->update(['status' => 'pending']);
                    $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

                    return response()->json([
                        'success' => true,
                        'message' => 'Payment verified with Bakong',
                        'redirect_url' => route('cashier.payments.receipt', $order->id),
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('Force verify - Bakong API check failed, proceeding with manual confirmation', [
                    'order_id' => $orderId,
                    'error' => $e->getMessage(),
                ]);
            }

            // Mark as paid (cashier confirms customer has paid)
            $order->payment->update([
                'status' => 'paid',
            ]);
            $order->update(['status' => 'pending']);
            $this->telegramReceiptService->sendOrderReceipt($order->fresh(['customer', 'user', 'orderItems.product', 'payment']));

            Log::info('Payment Force Verified by Cashier', [
                'order_id' => $order->id,
                'cashier_id' => auth('web')->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment confirmed successfully',
                'redirect_url' => route('cashier.payments.receipt', $order->id),
            ]);

        } catch (\Exception $e) {
            Log::error('Force Verify Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Force verify failed.',
            ], 500);
        }
    }
}
