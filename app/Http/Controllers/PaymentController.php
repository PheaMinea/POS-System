<?php

namespace App\Http\Controllers;
use App\Models\Order;
use App\Models\Payment;
use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    //
     public function generateQR($orderId)
    {
        try {

            $order = Order::findOrFail($orderId);

            $merchant = new IndividualInfo(
                bakongAccountID: 'chanchav_som@bkrt',
                merchantName: 'POS SYSTEM',
                merchantCity: 'Phnom Penh',
                currency: KHQRData::CURRENCY_USD,
                amount: $order->total_price
            );

            $qrResponse =
                BakongKHQR::generateIndividual(
                    $merchant
                );

            Payment::create([
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'payment_method' => 'bakong',
                'reference_no' =>
                $qrResponse->data['md5'] ?? null,
                'status' => 'pending'
            ]);

            return $this->successResponse([
                'order_id' => $order->id,
                'amount' => $order->total_price,
                'qr' => $qrResponse->data['qr'] ?? null,
                'md5' => $qrResponse->data['md5'] ?? null,
            ], 'KHQR Generated Successfully');
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage()
            );
        }
    }

    public function verifyTransaction(
        Request $request
    ) {
        try {

            $request->validate([
                'md5' => 'required'
            ]);

            $token =
                config(
                    'services.bakong.token'
                );

            $bakong =
                new BakongKHQR(
                    $token
                );

            $result =
                $bakong->checkTransactionByMD5(
                    $request->md5
                );

            if (
                isset(
                    $result->responseCode
                )
                &&
                $result->responseCode == 0
            ) {

                Payment::where(
                    'reference_no',
                    $request->md5
                )->update([
                    'status' => 'paid'
                ]);

                return $this->successResponse(
                    $result,
                    'Payment Successful'
                );
            }

            return $this->errorResponse(
                'Payment Not Found'
            );
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage()
            );
        }
    }

    public function index()
    {
        try {

            $payments = Payment::with(
                'order'
            )
                ->latest()
                ->get();

            return $this->successResponse(
                $payments,
                'Payments Retrieved Successfully'
            );
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage()
            );
        }
    }

    public function show($id)
    {
        try {

            $payment =
                Payment::with('order')
                ->findOrFail($id);

            return $this->successResponse(
                $payment
            );
        } catch (\Exception $e) {

            return $this->errorResponse(
                $e->getMessage()
            );
        }
    }
}