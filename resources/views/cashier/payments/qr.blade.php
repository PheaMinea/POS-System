@extends('layouts.cashier')

@section('title', 'Bakong Payment')
@section('page_title', 'Bakong Payment')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="grid md:grid-cols-[1fr_0.9fr] gap-6 items-start">

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-2xl mb-4">
                <i class="fas fa-qrcode text-2xl text-blue-600"></i>
            </div>

            <h2 class="text-2xl font-bold text-slate-800">Scan Bakong KHQR</h2>
            <p class="text-sm text-slate-400 mt-1">
                Invoice {{ $order->payment->reference_no ?? 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
            </p>

            <div class="my-6 flex justify-center">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    @if($qr_image)
                        <img src="{{ $qr_image }}" alt="Bakong QR" class="w-64 h-64 object-contain">
                    @elseif($qr_data)
                        {!! QrCode::size(256)->margin(1)->generate($qr_data) !!}
                    @else
                        <div class="w-64 h-64 flex items-center justify-center text-rose-500 bg-rose-50 rounded-xl">
                            QR unavailable
                        </div>
                    @endif
                </div>
            </div>

            @if($api_error)
                <div class="mb-4 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    {{ $api_error }}
                </div>
            @endif

            <div id="paymentStatus"
                 class="rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 text-sm font-medium">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                Waiting for customer payment...
            </div>

            <div class="mt-4 text-sm text-slate-400">
                Auto checking in <span id="countdown" class="font-bold text-slate-700">120</span>s
            </div>

            <!-- Manual Mark as Paid (shown when auto-verify fails) -->
            <div id="manualPaySection" class="mt-4 hidden">
                <div class="rounded-xl bg-amber-50 border border-amber-200 p-4">
                    <p class="text-sm text-amber-700 font-medium mb-3">
                        <i class="fas fa-exclamation-triangle mr-1"></i>
                        Auto-verify is having trouble connecting to Bakong. If customer has paid, click below:
                    </p>
                    <button id="markAsPaidBtn"
                            type="button"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white rounded-xl py-3 font-semibold transition flex items-center justify-center gap-2">
                        <i class="fas fa-check-circle"></i>
                        Mark as Paid (Manual Confirm)
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6">
            <h3 class="font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-receipt text-blue-500"></i>
                Order Summary
            </h3>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                    <span class="text-slate-400">Order</span>
                    <span class="font-semibold text-slate-700">#{{ $order->id }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                    <span class="text-slate-400">Customer</span>
                    <span class="font-semibold text-slate-700">{{ $order->customer?->name ?? 'Walk-in Customer' }}</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                    <span class="text-slate-400">Method</span>
                    <span class="font-semibold text-slate-700">Bakong</span>
                </div>
                <div class="flex justify-between gap-4 border-b border-slate-100 pb-3">
                    <span class="text-slate-400">Status</span>
                    <span class="font-semibold text-amber-600">{{ ucfirst($order->payment->status ?? 'pending') }}</span>
                </div>
                <div class="flex justify-between items-end gap-4 pt-2">
                    <span class="text-slate-500 font-semibold">Total</span>
                    <div class="text-right">
                        <span class="text-3xl font-bold text-blue-600">${{ number_format($order->total_price, 2) }}</span>
                        @if($amount_riel)
                            <p class="text-xs text-slate-400 mt-1">KHR {{ number_format($amount_riel) }}</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($md5)
                <div class="mt-5 rounded-xl bg-slate-50 border border-slate-100 p-3">
                    <p class="text-xs font-semibold text-slate-400 uppercase mb-1">Transaction MD5</p>
                    <p class="text-xs text-slate-600 break-all">{{ $md5 }}</p>
                </div>
            @endif

            <div class="mt-6 grid gap-3">
                <button id="forceVerifyBtn"
                        type="button"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white rounded-xl py-3 font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-check-circle"></i>
                    Verify Now
                </button>

                <a href="{{ route('cashier.pos.index') }}"
                   class="w-full bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl py-3 font-semibold transition flex items-center justify-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to POS
                </a>
            </div>
        </div>

    </div>
</div>

<!-- ===== SUCCESS POPUP ===== -->
<div id="paymentSuccessPopup"
     class="fixed inset-0 z-[2000] hidden items-center justify-center bg-slate-900/50 px-4 backdrop-blur-sm">
    <div class="w-full max-w-sm rounded-2xl bg-white p-8 text-center shadow-2xl animate-bounce-in">
        <div class="mx-auto mb-5 flex h-20 w-20 items-center justify-center rounded-full bg-emerald-100">
            <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-slate-800 mb-2">ទូទាត់ទឹកប្រាក់បានជោគជ័យ</h3>
        <p class="text-sm text-slate-500">កំពុងបើកវិក្កយបត្រ...</p>
        <div class="mt-6 flex justify-center">
            <div class="w-8 h-8 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
        </div>
    </div>
</div>

<style>
    @keyframes bounceIn {
        0% { transform: scale(0.3); opacity: 0; }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.95); }
        100% { transform: scale(1); opacity: 1; }
    }
    .animate-bounce-in {
        animation: bounceIn 0.6s ease-out;
    }
</style>

@endsection

@push('scripts')
<script>
    const paymentStatus = document.getElementById('paymentStatus');
    const countdown = document.getElementById('countdown');
    const forceVerifyBtn = document.getElementById('forceVerifyBtn');
    const markAsPaidBtn = document.getElementById('markAsPaidBtn');
    const manualPaySection = document.getElementById('manualPaySection');
    const paymentSuccessPopup = document.getElementById('paymentSuccessPopup');
    const checkUrl = @json(route('cashier.auto-payment.check', $order->id));
    const forceVerifyUrl = @json(route('cashier.auto-payment.force-verify', $order->id));
    const csrfToken = @json(csrf_token());

    let secondsLeft = 120;
    let isChecking = false;
    let isCompleted = false;
    let checkInterval = 2; // Check every 2 seconds
    let consecutiveErrors = 0;
    const maxConsecutiveErrors = 3; // Show manual option after 3 errors

    function setStatus(type, message, spinning = false) {
        const classes = {
            waiting: 'rounded-xl bg-amber-50 border border-amber-200 text-amber-700 px-4 py-3 text-sm font-medium',
            success: 'rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 text-sm font-medium',
            error: 'rounded-xl bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 text-sm font-medium',
        };
        const icons = {
            waiting: spinning ? 'fas fa-spinner fa-spin' : 'fas fa-clock',
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
        };

        paymentStatus.className = classes[type] || classes.waiting;
        paymentStatus.innerHTML = `<i class="${icons[type] || icons.waiting} mr-2"></i>${message}`;
    }

    function showSuccessPopup(redirectUrl) {
        isCompleted = true;
        setStatus('success', 'ទូទាត់ទឹកប្រាក់បានជោគជ័យ');

        // Show the popup with animation
        paymentSuccessPopup.classList.remove('hidden');
        paymentSuccessPopup.classList.add('flex');

        // Redirect to receipt after showing popup
        setTimeout(() => {
            const receiptUrl = new URL(redirectUrl, window.location.origin);
            receiptUrl.searchParams.set('payment_success', '1');
            window.location.href = receiptUrl.toString();
        }, 2000);
    }

    function showManualPayOption() {
        manualPaySection.classList.remove('hidden');
        setStatus('error', 'Auto-verify is having trouble. Click "Mark as Paid" if customer has paid.', false);
    }

    async function checkPayment() {
        if (isChecking || isCompleted || secondsLeft <= 0) {
            return;
        }

        isChecking = true;

        try {
            const response = await fetch(checkUrl, {
                headers: {
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache',
                },
            });
            const data = await response.json();

            if (data.success && data.is_paid) {
                // Payment found! Show success popup immediately
                showSuccessPopup(data.redirect_url);
                return;
            }

            if (data.success) {
                // API says waiting for payment - keep polling
                setStatus('waiting', data.message || 'Waiting for customer payment...', true);
                consecutiveErrors = 0;
            } else {
                consecutiveErrors++;
                setStatus('error', 'Auto-verify error: ' + (data.message || 'Unknown'), false);
                if (consecutiveErrors >= maxConsecutiveErrors) {
                    showManualPayOption();
                }
            }
        } catch (error) {
            console.error('Auto payment check failed:', error);
            consecutiveErrors++;
            setStatus('error', 'Connection error. Retrying... (' + consecutiveErrors + '/' + maxConsecutiveErrors + ')', true);
            if (consecutiveErrors >= maxConsecutiveErrors) {
                showManualPayOption();
            }
        } finally {
            isChecking = false;
        }
    }

    async function forceVerify() {
        if (isCompleted) return;

        forceVerifyBtn.disabled = true;
        forceVerifyBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying...';

        try {
            const response = await fetch(forceVerifyUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            const data = await response.json();

            if (data.success) {
                showSuccessPopup(data.redirect_url || '{{ route("cashier.payments.receipt", $order->id) }}');
                return;
            }

            setStatus('error', data.message || 'Verification failed.');
            showManualPayOption();
        } catch (error) {
            console.error('Manual verification failed:', error);
            setStatus('error', 'Verification request failed. Please try "Mark as Paid" below.');
            showManualPayOption();
        } finally {
            forceVerifyBtn.disabled = false;
            forceVerifyBtn.innerHTML = '<i class="fas fa-check-circle"></i> Verify Now';
        }
    }

    async function markAsPaid() {
        if (isCompleted) return;

        markAsPaidBtn.disabled = true;
        markAsPaidBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

        try {
            const response = await fetch(forceVerifyUrl, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });
            const data = await response.json();

            // forceVerify always marks as paid even if API fails
            showSuccessPopup(data.redirect_url || '{{ route("cashier.payments.receipt", $order->id) }}');
        } catch (error) {
            console.error('Mark as paid failed:', error);
            // Last resort - redirect to receipt anyway
            showSuccessPopup('{{ route("cashier.payments.receipt", $order->id) }}');
        }
    }

    // Event listeners
    forceVerifyBtn.addEventListener('click', forceVerify);
    markAsPaidBtn.addEventListener('click', markAsPaid);

    // Initial check after page loads
    setTimeout(() => checkPayment(), 500);

    // Auto-check the payment status every 2 seconds
    const timer = setInterval(() => {
        if (isCompleted) {
            clearInterval(timer);
            return;
        }

        secondsLeft--;
        countdown.textContent = secondsLeft;

        if (secondsLeft <= 0) {
            clearInterval(timer);
            showManualPayOption();
            return;
        }

        // Poll every 2 seconds
        if (secondsLeft % checkInterval === 0) {
            checkPayment();
        }
    }, 1000);

    // Check when user returns to this tab
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden && !isCompleted) {
            checkPayment();
        }
    });
</script>
@endpush