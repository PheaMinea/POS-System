<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class BakongService
{
    protected string $baseUrl;
    protected string $token;
    protected string $merchantId;
    protected string $merchantAccountId;

    public function __construct()
    {
        $this->baseUrl = config('services.bakong.base_url', 'https://api-bakong.nbc.gov.kh/v1');
        $this->token = config('services.bakong.token', env('BAKONG_TOKEN'));
        $this->merchantId = config('services.bakong.merchant_id')
            ?: env('BAKONG_MERCHANT_ID')
            ?: 'minea_phea1@bkrt';
        $this->merchantAccountId = config('services.bakong.merchant_account_id')
            ?: env('BAKONG_MERCHANT_ACCOUNT_ID')
            ?: 'minea_phea1@bkrt';
    }

    /**
     * Generate KHQR (Bakong QR) for payment
     *
     * @param string $orderId
     * @param float $amount
     * @param string $currency
     * @param string $description
     * @return array
     */
    public function generateQR(string $orderId, float $amount, string $currency = 'KHR', string $description = ''): array
    {
        try {
            $payload = [
                'amount' => $amount,
                'currency' => $currency,
                'description' => $description ?: "Payment for order #{$orderId}",
                'merchantId' => $this->merchantId,
                'merchantAccountId' => $this->merchantAccountId,
                'transactionId' => $orderId,
                'storeLabel' => config('app.name', 'POS System'),
                'phoneNumber' => '',
                'terminalId' => 'POS-001',
            ];

            $response = Http::withOptions([
                'verify' => config('services.bakong.http_verify', true),
                'timeout' => 30,
            ])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->post("{$this->baseUrl}/qr/generate", $payload);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'qr_data' => $data['qrData'] ?? $data['qr'] ?? '',
                    'qr_image' => $data['qrImage'] ?? $data['image'] ?? '',
                    'transaction_id' => $data['transactionId'] ?? $orderId,
                    'raw_response' => $data,
                ];
            }

            Log::warning('Bakong QR Generation Failed, using local fallback', [
                'status' => $response->status(),
                'response' => $response->body(),
                'payload' => $payload,
            ]);

            $fallback = $this->generateLocalQRPayload($orderId, $amount, $currency);

            return [
                'success' => true,
                'fallback' => true,
                'message' => 'Bakong API unavailable. Local QR payload generated instead.',
                'qr_data' => $fallback['qr_data'] ?? '',
                'qr_image' => $this->generateQrImage($fallback['qr_data'] ?? ''),
                'transaction_id' => $orderId,
                'raw_response' => ['fallback' => true],
            ];

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();

            // Handle SSL certificate error specifically
            if (str_contains($errorMsg, 'cURL error 60') || str_contains($errorMsg, 'SSL')) {
                Log::warning('Bakong SSL Error - falling back to local QR', [
                    'order_id' => $orderId,
                    'error' => $errorMsg,
                ]);

                return [
                    'success' => false,
                    'message' => 'SSL certificate verification failed. Using local QR generation.',
                    'ssl_error' => true,
                ];
            }

            Log::warning('Bakong QR Generation Exception, using local fallback', [
                'error' => $errorMsg,
                'order_id' => $orderId,
            ]);

            $fallback = $this->generateLocalQRPayload($orderId, $amount, $currency);

            return [
                'success' => true,
                'fallback' => true,
                'message' => 'Bakong API unavailable. Local QR payload generated instead.',
                'qr_data' => $fallback['qr_data'] ?? '',
                'qr_image' => $this->generateQrImage($fallback['qr_data'] ?? ''),
                'transaction_id' => $orderId,
                'raw_response' => ['fallback' => true],
            ];
        }
    }

    protected function generateQrImage(string $payload): string
    {
        if (empty($payload)) {
            return '';
        }

        try {
            $svg = QrCode::format('svg')
                ->size(300)
                ->margin(2)
                ->errorCorrection('H')
                ->generate($payload);

            return 'data:image/svg+xml;base64,' . base64_encode($svg);
        } catch (\Throwable $e) {
            Log::warning('QR image generation failed, falling back to text payload', [
                'error' => $e->getMessage(),
            ]);

            return '';
        }
    }

    /**
     * Generate a Bakong KHQR-compatible payload for local QR generation
     *
     * @param string $orderId
     * @param float $amount
     * @param string $currency
     * @return array
     */
    public function generateLocalQRPayload(string $orderId, float $amount, string $currency = 'KHR'): array
    {
        $merchantName = config('app.name', 'POS System');
        $merchantId = $this->merchantId ?: 'default_merchant';
        $reference = 'INV-' . str_pad($orderId, 4, '0', STR_PAD_LEFT);

        // Build KHQR payload data
        $payload = [
            'version' => '01',
            'currency' => $currency === 'KHR' ? '1164' : '0840', // ISO currency codes
            'merchant_id' => $merchantId,
            'merchant_name' => $merchantName,
            'merchant_city' => 'Phnom Penh',
            'transaction_id' => $orderId,
            'amount' => number_format($amount, 0, '', ''),
            'reference' => $reference,
            'description' => "Payment for order #{$orderId}",
            'store_label' => $merchantName,
            'terminal_id' => 'POS-001',
            'timestamp' => now()->timestamp,
        ];

        return [
            'success' => true,
            'payload' => $payload,
            'qr_data' => json_encode($payload),
            'reference' => $reference,
        ];
    }

    /**
     * Verify payment status with Bakong
     *
     * @param string $transactionId
     * @return array
     */
    public function verifyPayment(string $transactionId): array
    {
        try {
            $response = Http::withOptions([
                'verify' => config('services.bakong.http_verify', true),
                'timeout' => 30,
            ])
            ->withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->get("{$this->baseUrl}/qr/verify/{$transactionId}");

            if ($response->successful()) {
                $data = $response->json();

                $status = $data['status'] ?? 'pending';
                $isPaid = in_array(strtolower($status), ['completed', 'paid', 'success']);

                return [
                    'success' => true,
                    'is_paid' => $isPaid,
                    'status' => $status,
                    'amount' => $data['amount'] ?? 0,
                    'transaction_id' => $data['transactionId'] ?? $transactionId,
                    'raw_response' => $data,
                ];
            }

            Log::warning('Bakong Verify Payment Failed', [
                'status' => $response->status(),
                'response' => $response->body(),
                'transaction_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'is_paid' => false,
                'status' => 'pending',
                'message' => 'Bakong verification is temporarily unavailable. Please check again later or confirm manually.',
                'fallback' => true,
            ];

        } catch (\Exception $e) {
            $errorMsg = $e->getMessage();

            // Handle SSL error gracefully
            if (str_contains($errorMsg, 'cURL error 60') || str_contains($errorMsg, 'SSL')) {
                Log::warning('Bakong Verify SSL Error', [
                    'transaction_id' => $transactionId,
                ]);

                return [
                    'success' => true,
                    'is_paid' => false,
                    'status' => 'pending',
                    'message' => 'Bakong verification is temporarily unavailable. Please check again later or confirm manually.',
                    'fallback' => true,
                    'ssl_error' => true,
                ];
            }

            Log::error('Bakong Verify Payment Exception', [
                'error' => $errorMsg,
                'transaction_id' => $transactionId,
            ]);

            return [
                'success' => true,
                'is_paid' => false,
                'status' => 'pending',
                'message' => 'Bakong verification is temporarily unavailable. Please check again later or confirm manually.',
                'fallback' => true,
            ];
        }
    }

    /**
     * Check transaction status
     *
     * @param string $transactionId
     * @return array
     */
    public function checkTransactionStatus(string $transactionId): array
    {
        return $this->verifyPayment($transactionId);
    }
}
