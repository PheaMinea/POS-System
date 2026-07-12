<?php

namespace App\Http\Controllers\Frontend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\BakongService;
use App\Services\TelegramReceiptService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;

class PaymentController extends Controller
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

    public function index()
    {
        try {

            $payments = Payment::with('order.customer')->latest()->get();

            return view(
                'cashier.payments.index',
                compact('payments')
            );

        } catch (\Exception $e) {

            return view(
                'cashier.payments.index',
                ['payments' => []]
            )->with(
                'error',
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        try {

            $payment = Payment::with('order')->findOrFail($id);

            return view(
                'cashier.payments.show',
                compact('payment')
            );

        } catch (\Exception $e) {

            return redirect()
                ->route('cashier.payments.index')
                ->with(
                    'error',
                    $e->getMessage()
                );
        }
    }

    /**
     * Generate Bakong QR code for payment
     */
    public function generateQR($orderId)
    {
        try {
            $order = Order::with(['customer', 'payment'])->findOrFail($orderId);

            // Verify this is a Bakong payment
            if ($order->payment->payment_method !== 'bakong') {
                return redirect()
                    ->route('cashier.receipt', $order->id)
                    ->with('error', 'This order is not a Bakong payment');
            }

            // Check if already paid
            if ($order->payment->status === 'paid') {
                if ($order->status === 'pending_payment') {
                    $order->update(['status' => 'pending']);
                }

                return redirect()
                    ->route('cashier.receipt', $order->id)
                    ->with('success', 'Payment already completed');
            }

            $payment = $order->payment;
            $merchantAccountId = config('services.bakong.merchant_account_id')
                ?: env('BAKONG_MERCHANT_ACCOUNT_ID')
                ?: 'minea_phea1@bkrt';

            try {
                $merchant = new IndividualInfo(
                    bakongAccountID: $merchantAccountId,
                    merchantName: config('app.name', 'POS System'),
                    merchantCity: env('BAKONG_MERCHANT_CITY', 'Phnom Penh'),
                    currency: KHQRData::CURRENCY_USD,
                    amount: (float) $order->total_price
                );

                $qrResponse = BakongKHQR::generateIndividual($merchant);
                $qrData = $qrResponse->data['qr'] ?? null;
                $md5 = $qrResponse->data['md5'] ?? null;

                if ($qrData) {
                    if ($md5 && $payment->reference_no !== $md5) {
                        $payment->update([
                            'reference_no' => $md5,
                        ]);
                    }

                    return view('cashier.payments.qr', [
                        'order' => $order->fresh(['customer', 'payment']),
                        'qr_data' => $qrData,
                        'qr_image' => null,
                        'md5' => $md5,
                        'api_error' => null,
                        'amount_riel' => null,
                    ]);
                }
            } catch (\Throwable $khqrException) {
                Log::warning('KHQR Local Generation Failed', [
                    'order_id' => $order->id,
                    'error' => $khqrException->getMessage(),
                ]);
            }

            // Generate QR code via Bakong API fallback
            $currency = 'KHR';
            $amountInRiel = $order->total_price * 4000;

            $result = $this->bakongService->generateQR(
                (string) $order->id,
                $amountInRiel,
                $currency,
                "Payment for order #{$order->id}"
            );

            if (!$result['success']) {
                // If API fails, generate a local QR code as fallback
                return view('cashier.payments.qr', [
                    'order' => $order,
                    'qr_data' => $this->generateLocalQRData($order),
                    'qr_image' => null,
                    'md5' => $order->payment->reference_no,
                    'api_error' => $result['message'] ?? null,
                    'amount_riel' => $amountInRiel,
                ]);
            }

            return view('cashier.payments.qr', [
                'order' => $order,
                'qr_data' => $result['qr_data'],
                'qr_image' => $result['qr_image'],
                'md5' => $order->payment->reference_no,
                'api_error' => null,
                'amount_riel' => $amountInRiel,
            ]);

        } catch (\Throwable $e) {
            Log::error('QR Generation Error', [
                'order_id' => $orderId,
                'error' => $e->getMessage(),
            ]);

            return redirect()
                ->route('cashier.pos.index')
                ->with('error', 'Failed to generate QR: ' . $e->getMessage());
        }
    }

    /**
     * Verify Bakong payment status
     */
    public function verify(Request $request)
    {
        try {
            $validated = $request->validate([
                'order_id' => 'required|exists:orders,id',
            ]);

            $order = Order::with('payment')->findOrFail($validated['order_id']);

            // Verify payment with Bakong API
            $result = $this->bakongService->verifyPayment((string) $order->id);

            if ($result['success'] && $result['is_paid']) {
                // Update payment status to paid
                $order->payment->update([
                    'status' => 'paid',
                ]);
                $order->update(['status' => 'pending']);
                $this->telegramReceiptService->sendOrderReceipt($order->fresh([
                    'customer',
                    'user',
                    'orderItems.product',
                    'payment',
                ]));

                return response()->json([
                    'success' => true,
                    'is_paid' => true,
                    'message' => 'Payment verified successfully',
                    'redirect_url' => route('cashier.payments.receipt', $order->id),
                ]);
            }

            return response()->json([
                'success' => true,
                'is_paid' => false,
                'message' => $result['message'] ?? 'Payment not yet completed',
            ]);

        } catch (\Exception $e) {
            Log::error('Payment Verification Error', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'is_paid' => false,
                'message' => 'Verification error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show payment receipt after successful Bakong payment
     */
    public function receipt($orderId)
    {
        try {
            $order = Order::with([
                'customer',
                'user',
                'orderItems.product',
                'payment',
            ])->findOrFail($orderId);

            // If payment is still pending, redirect to QR page
            if ($order->payment->status === 'pending') {
                return redirect()
                    ->route('cashier.payments.generate-qr', $order->id)
                    ->with('warning', 'Payment not yet completed');
            }

            if ($order->payment->status === 'paid') {
                $this->telegramReceiptService->sendOrderReceipt($order);
            }

            return view('cashier.pos.receipt', compact('order'));

        } catch (\Exception $e) {
            return redirect()
                ->route('cashier.pos.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Generate local QR data as fallback when Bakong API is unavailable
     */
    private function generateLocalQRData(Order $order): string
    {
        // Generate a KHQR-compatible string format
        // This creates a basic QR payload that can be scanned
        $merchantName = config('app.name', 'POS System');
        $merchantId = config('services.bakong.merchant_id', env('BAKONG_MERCHANT_ID', ''));
        $amount = number_format($order->total_price * 4000, 0, '', ''); // Amount in Riel
        $reference = 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);

        // KHQR payload format (simplified)
        $qrPayload = json_encode([
            'merchant_name' => $merchantName,
            'merchant_id' => $merchantId,
            'amount' => $amount,
            'currency' => 'KHR',
            'reference' => $reference,
            'description' => "Payment for order #{$order->id}",
            'store' => $merchantName,
            'terminal' => 'POS-001',
        ]);

        return $qrPayload;
    }
}
