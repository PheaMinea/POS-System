<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class TelegramReceiptService
{
    public function sendOrderReceipt(Order $order): bool
    {
        if (! config('services.telegram.enabled')) {
            return false;
        }

        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (! $token || ! $chatId) {
            Log::warning('Telegram receipt skipped: missing bot token or chat id', [
                'order_id' => $order->id,
            ]);

            return false;
        }

        $order->loadMissing([
            'customer',
            'user',
            'orderItems.product',
            'payment',
        ]);

        if (! $order->payment || $order->payment->status !== 'paid') {
            return false;
        }

        if (! Schema::hasColumn('payments', 'telegram_sent_at')) {
            Log::warning('Telegram receipt skipped: payments.telegram_sent_at column is missing', [
                'order_id' => $order->id,
            ]);

            return false;
        }

        if ($order->payment->telegram_sent_at) {
            return false;
        }

        try {
            $response = Http::timeout(15)
                ->asForm()
                ->post("https://api.telegram.org/bot{$token}/sendMessage", [
                    'chat_id' => $chatId,
                    'text' => $this->buildReceiptMessage($order),
                    'parse_mode' => 'HTML',
                    'disable_web_page_preview' => true,
                ]);

            if (! $response->successful()) {
                Log::warning('Telegram receipt send failed', [
                    'order_id' => $order->id,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return false;
            }

            $order->payment->update([
                'telegram_sent_at' => now(),
            ]);

            Log::info('Telegram receipt sent', [
                'order_id' => $order->id,
                'chat_id' => $chatId,
            ]);

            return true;
        } catch (\Throwable $e) {
            Log::warning('Telegram receipt send exception', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    private function buildReceiptMessage(Order $order): string
    {
        $payment = $order->payment;
        $customer = $order->customer?->name ?? 'Walk-in Customer';
        $cashier = $order->user?->name ?? 'Cashier';
        $reference = $payment?->reference_no ?? 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT);
        $method = strtoupper($payment?->payment_method ?? 'N/A');
        $createdAt = $order->created_at?->format('M d, Y h:i A') ?? now()->format('M d, Y h:i A');

        $lines = [
            '<b>POS Receipt</b>',
            '',
            '<b>Invoice:</b> ' . e($reference),
            '<b>Order:</b> #' . $order->id,
            '<b>Customer:</b> ' . e($customer),
            '<b>Cashier:</b> ' . e($cashier),
            '<b>Payment:</b> ' . e($method) . ' - PAID',
            '<b>Date:</b> ' . e($createdAt),
            '',
            '<b>Items</b>',
        ];

        foreach ($order->orderItems->take(15) as $item) {
            $productName = $item->product?->name ?? 'Unknown Product';
            $lines[] = '- ' . e($productName)
                . ' x' . $item->quantity
                . ' = $' . number_format((float) $item->subtotal, 2);
        }

        if ($order->orderItems->count() > 15) {
            $lines[] = '...and ' . ($order->orderItems->count() - 15) . ' more item(s)';
        }

        $lines[] = '';
        $lines[] = '<b>Total:</b> $' . number_format((float) $order->total_price, 2);

        return implode("\n", $lines);
    }
}
