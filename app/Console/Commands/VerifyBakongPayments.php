<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\BakongService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class VerifyBakongPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bakong:verify 
        {--timeout=60 : Maximum seconds to keep checking}
        {--once : Only run once, do not loop}
        {--order= : Verify a specific order ID only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto-verify pending Bakong payments and update status';

    /**
     * Execute the console command.
     */
    public function handle(BakongService $bakongService)
    {
        $startTime = time();
        $timeout = (int) $this->option('timeout');
        $orderId = $this->option('order');
        $once = $this->option('once');

        $this->info('🔍 Bakong Payment Auto-Verification Started');
        $this->newLine();
        $this->info("Timeout: {$timeout}s | Once: " . ($once ? 'Yes' : 'No') . " | Order: " . ($orderId ?? 'All'));
        $this->newLine();

        $verified = 0;
        $failed = 0;
        $cycle = 0;

        do {
            $cycle++;
            $this->line("───────────────────────────────────────────");
            $this->line("📋 Cycle #{$cycle} - " . now()->format('H:i:s'));

            // Query pending Bakong payments
            $query = Payment::where('payment_method', 'bakong')
                ->where('status', 'pending')
                ->with('order');

            if ($orderId) {
                $query->where('order_id', $orderId);
            }

            $payments = $query->get();

            if ($payments->isEmpty()) {
                $this->info('✅ No pending Bakong payments found');

                if ($once) {
                    break;
                }
            } else {
                $this->info("📦 Found {$payments->count()} pending payment(s)");

                foreach ($payments as $payment) {
                    $this->line("   ⏳ Checking Order #{$payment->order_id}...");

                    $result = $bakongService->verifyPayment((string) $payment->order_id);

                    if ($result['success'] && $result['is_paid']) {
                        $payment->update(['status' => 'paid']);
                        $payment->order?->update(['status' => 'pending']);

                        $this->info("   ✅ Order #{$payment->order_id} - PAYMENT VERIFIED!");
                        $this->info("      Amount: \${$payment->amount} | Ref: {$payment->reference_no}");

                        // Log successful verification
                        Log::info('Bakong Auto-Verify Success', [
                            'order_id' => $payment->order_id,
                            'amount' => $payment->amount,
                            'reference' => $payment->reference_no,
                        ]);

                        $verified++;
                    } else {
                        $status = $result['status'] ?? 'pending';
                        $message = $result['message'] ?? 'No payment detected';

                        $this->warn("   ⏳ Order #{$payment->order_id} - Status: {$status}");
                        $this->line("      {$message}");

                        $failed++;
                    }
                }
            }

            // Check elapsed time
            $elapsed = time() - $startTime;
            $remaining = $timeout - $elapsed;

            if ($remaining <= 0) {
                $this->newLine();
                $this->warn("⏰ Timeout reached ({$timeout}s)");
                break;
            }

            if (!$once && $payments->isNotEmpty()) {
                $this->newLine();
                $this->info("💤 Waiting 10 seconds before next check... (timeout in {$remaining}s)");
                sleep(min(10, $remaining));
            }

        } while (!$once && (time() - $startTime) < $timeout);

        $this->newLine();
        $this->line("═══════════════════════════════════════════");
        $this->info("📊 Summary: {$verified} verified | {$failed} pending | {$cycle} cycle(s)");
        $this->info('🏁 Bakong Payment Auto-Verification Completed');

        return Command::SUCCESS;
    }
}
