@extends('layouts.app')

@section('title', 'Order #{{ $order->id }} - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- ORDER DETAIL HEADER -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                    <i class="fas fa-receipt"></i>
                    Order #{{ $order->id }}
                </h1>
                <p class="text-blue-100/90 mt-1">
                    <i class="fas fa-clock mr-2"></i>
                    {{ $order->created_at->format('l, F d, Y - h:i A') }}
                </p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('customer.receipt', $order->id) }}"
                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                    <i class="fas fa-receipt"></i>
                    Receipt
                </a>
                <a href="{{ route('customer.orders') }}"
                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                    <i class="fas fa-arrow-left"></i>
                    Back to Orders
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- ORDER CONTENT -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- Left: Tracking & Items -->
        <div class="lg:col-span-2 space-y-8">

            <!-- Order Tracking Timeline -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 md:p-8">

                <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2 mb-8">
                    <i class="fas fa-route text-blue-500"></i>
                    Order Timeline
                </h2>

                @php
                    // Timeline steps: paid, pending, accepted, preparing, ready, completed
                    $timelineSteps = [
                        'paid' => ['label' => 'Paid', 'icon' => 'fa-credit-card', 'color' => 'emerald'],
                        'pending' => ['label' => 'Pending', 'icon' => 'fa-clock', 'color' => 'amber'],
                        'accepted' => ['label' => 'Accepted', 'icon' => 'fa-handshake', 'color' => 'sky'],
                        'preparing' => ['label' => 'Preparing', 'icon' => 'fa-fire', 'color' => 'orange'],
                        'ready' => ['label' => 'Ready', 'icon' => 'fa-check-circle', 'color' => 'blue'],
                        'completed' => ['label' => 'Completed', 'icon' => 'fa-flag-checkered', 'color' => 'emerald'],
                    ];

                    $currentStatus = $order->status ?? 'pending';
                    $paymentStatus = $order->payment?->status ?? 'pending';

                    // Determine which steps are active
                    $activeSteps = [];
                    $statusOrder = ['paid', 'pending', 'accepted', 'preparing', 'ready', 'completed'];

                    // Paid is active when payment is paid OR order has moved past pending_payment
                    $isPaid = $paymentStatus === 'paid' || $currentStatus !== 'pending_payment';
                    $currentIndex = array_search($currentStatus, $statusOrder);
                    $currentIndex = $currentIndex === false ? 0 : $currentIndex;

                    // Calculate progress percentage
                    $progress = $currentIndex > 0 ? ($currentIndex / (count($statusOrder) - 1)) * 100 : 0;
                    if ($isPaid && $currentIndex === 0) $progress = 0;
                @endphp

                <!-- Progress Bar -->
                <div class="relative mb-10">
                    <div class="w-full h-2.5 bg-slate-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 via-blue-500 to-emerald-500 rounded-full transition-all duration-1000"
                             style="width: {{ $progress }}%;"></div>
                    </div>
                </div>

                <!-- Timeline Steps -->
                <div class="grid grid-cols-6 gap-2">
                    @foreach($timelineSteps as $key => $step)
                        @php
                            $stepIndex = array_search($key, $statusOrder);
                            $isActive = false;

                            if ($key === 'paid') {
                                $isActive = $isPaid;
                            } else {
                                $isActive = $currentIndex >= $stepIndex;
                            }

                            $isCurrent = ($key === 'paid' && $isPaid && $currentIndex === 0) ||
                                        ($key === $currentStatus);

                            $colorClasses = [
                                'emerald' => 'text-emerald-600 bg-emerald-100',
                                'amber' => 'text-amber-600 bg-amber-100',
                                'sky' => 'text-sky-600 bg-sky-100',
                                'orange' => 'text-orange-600 bg-orange-100',
                                'blue' => 'text-blue-600 bg-blue-100',
                            ];
                        @endphp
                        <div class="text-center">
                            <div class="w-12 h-12 mx-auto rounded-full flex items-center justify-center text-xl
                                        {{ $isActive ? $colorClasses[$step['color']] : 'bg-slate-100 text-slate-400' }}
                                        {{ $isCurrent ? 'ring-4 ring-blue-200 scale-110 transition-all duration-500' : '' }}">
                                <i class="fas {{ $step['icon'] }}"></i>
                            </div>
                            <p class="mt-2 text-xs font-semibold {{ $isActive ? 'text-slate-700' : 'text-slate-400' }}">
                                {{ $step['label'] }}
                            </p>
                            @if($isCurrent)
                                <span class="text-[10px] mt-1 inline-block px-2 py-0.5 rounded-full bg-blue-100 text-blue-600 font-bold">
                                    ● Current
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Status Badge -->
                <div class="mt-8 text-center">
                    @php
                        $statusLabel = $timelineSteps[$currentStatus]['label'] ?? ucfirst($currentStatus);
                        if ($currentStatus === 'pending_payment') $statusLabel = 'Pending Payment';
                    @endphp
                    <span class="inline-block px-6 py-2.5 rounded-full text-sm font-semibold
                        @if($currentStatus === 'pending' || $currentStatus === 'pending_payment') bg-amber-100 text-amber-700
                        @elseif($currentStatus === 'accepted') bg-sky-100 text-sky-700
                        @elseif($currentStatus === 'preparing') bg-orange-100 text-orange-700
                        @elseif($currentStatus === 'ready') bg-blue-100 text-blue-700
                        @elseif($currentStatus === 'completed') bg-emerald-100 text-emerald-700
                        @endif">
                        <i class="fas {{ $timelineSteps[$currentStatus]['icon'] ?? 'fa-circle' }} mr-2"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

            </div>

            <!-- Order Items -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">

                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fas fa-utensils text-blue-500"></i>
                        Ordered Items
                        <span class="text-sm font-normal text-slate-400 ml-2">({{ $order->orderItems->count() }} items)</span>
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50">
                            <tr>
                                <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                                <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                                <th class="p-4 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                                <th class="p-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                                <th class="p-4 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $index => $item)
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="p-4 text-sm text-slate-400 font-medium">{{ $loop->iteration }}</td>
                                <td class="p-4 font-medium text-slate-700">
                                    <div class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-blue-400 rounded-full"></span>
                                        {{ $item->product?->name ?? 'Product #' . $item->product_id }}
                                    </div>
                                </td>
                                <td class="p-4 text-center">
                                    <span class="bg-slate-100 px-3 py-1 rounded-full text-sm font-medium text-slate-600">
                                        {{ $item->quantity }}
                                    </span>
                                </td>
                                <td class="p-4 text-right text-slate-600">
                                    ${{ number_format($item->price, 2) }}
                                </td>
                                <td class="p-4 text-right font-bold text-blue-600">
                                    ${{ number_format($item->quantity * $item->price, 2) }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

        <!-- Right: Order Summary -->
        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 sticky top-24">

                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">
                    <i class="fas fa-receipt text-blue-500"></i>
                    Order Summary
                </h3>

                <div class="space-y-3 py-4 border-b border-slate-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Order Number</span>
                        <span class="font-medium text-slate-700">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Customer Name</span>
                        <span class="font-medium text-slate-700">{{ $order->customer?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Phone</span>
                        <span class="font-medium text-slate-700">{{ $order->customer?->phone ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Address</span>
                        <span class="font-medium text-slate-700 text-right max-w-[150px]">{{ $order->customer?->address ?? 'N/A' }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Payment Method</span>
                        <span class="font-medium text-slate-700">
                            @if($order->payment?->payment_method === 'bakong')
                                <i class="fas fa-qrcode text-blue-500 mr-1"></i>Bakong KHQR
                            @else
                                <i class="fas fa-money-bill-wave text-emerald-500 mr-1"></i>Cash on Delivery
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Payment Status</span>
                        <span class="font-medium {{ $order->payment?->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                            <i class="fas {{ $order->payment?->status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                            {{ ucfirst($order->payment?->status ?? 'Pending') }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Order Status</span>
                        <span class="font-medium text-blue-500">
                            <i class="fas {{ $timelineSteps[$currentStatus]['icon'] ?? 'fa-circle' }} mr-1"></i>
                            {{ $timelineSteps[$currentStatus]['label'] ?? ucfirst($currentStatus) }}
                        </span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Items Count</span>
                        <span class="font-medium text-slate-700">{{ $order->orderItems->count() }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Order Date</span>
                        <span class="font-medium text-slate-700">{{ $order->created_at->format('d M Y, h:i A') }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-lg font-bold text-slate-800">Grand Total</span>
                    <span class="text-2xl font-bold text-blue-600">${{ number_format($order->total_price, 2) }}</span>
                </div>

                @if($currentStatus === 'pending_payment' && $order->payment?->payment_method === 'bakong')
                    <a href="{{ route('customer.payment', $order->id) }}"
                       class="block w-full mt-4 bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white py-3 rounded-xl font-semibold text-center transition shadow-lg shadow-blue-500/25">
                        <i class="fas fa-qrcode mr-2"></i>
                        Pay with Bakong
                    </a>
                    <div id="autoPaymentStatus" class="mt-3 text-xs text-slate-400 text-center flex items-center justify-center gap-2">
                        <i class="fas fa-circle text-[6px] text-blue-400"></i>
                        Auto-checking payment...
                    </div>
                @endif

                @if($currentStatus === 'completed')
                    <div class="mt-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-center">
                        <i class="fas fa-check-circle text-emerald-500 text-2xl block mb-1"></i>
                        <p class="text-sm font-medium text-emerald-700">Order Completed</p>
                        <p class="text-xs text-emerald-500">Thank you for your order!</p>
                        <a href="{{ route('customer.receipt', $order->id) }}"
                           class="inline-block mt-3 bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition">
                            <i class="fas fa-receipt mr-1"></i>
                            View Receipt
                        </a>
                    </div>
                @endif

            </div>

        </div>

    </div>

</section>

<!-- ============================================================ -->
<!-- AUTO PAYMENT VERIFICATION (for Bakong pending_payment) -->
<!-- ============================================================ -->
@if($currentStatus === 'pending_payment' && $order->payment?->payment_method === 'bakong')
<script>
    (function() {
        const statusBox = document.getElementById('autoPaymentStatus');
        let checkCount = 0;
        let isChecking = false;

        function verifyPayment() {
            if (isChecking) return;

            isChecking = true;
            checkCount++;

            fetch('{{ route('customer.payment.verify', $order->id) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.is_paid) {
                    statusBox.innerHTML = '<i class="fas fa-check-circle text-emerald-500 mr-1"></i> <span class="text-emerald-600 font-medium">Payment confirmed! Redirecting...</span>';
                    statusBox.className = 'mt-3 text-xs text-emerald-600 text-center';

                    // Auto-redirect after 2 seconds
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                    return;
                }
                if (statusBox) {
                    statusBox.innerHTML = '<i class="fas fa-circle text-[6px] text-blue-400"></i> Auto-checking payment... (Attempt ' + checkCount + ')';
                }
            })
            .catch(error => {
                console.error('Auto payment check failed:', error);
                if (statusBox) {
                    statusBox.innerHTML = '<i class="fas fa-exclamation-circle text-amber-500 mr-1"></i> Retrying...';
                }
            })
            .finally(() => {
                isChecking = false;
            });
        }

        // Start auto-checking every 10 seconds
        if (statusBox) {
            verifyPayment();
            setInterval(verifyPayment, 10000);
        }
    })();
</script>
@endif

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
    .sticky-top {
        position: sticky;
        top: 90px;
    }

    /* Progress bar animation */
    .progress-bar {
        transition: width 1.5s ease-in-out;
    }

    /* Status step animation */
    .status-step {
        transition: all 0.3s ease;
    }

    .status-step.active {
        transform: scale(1.05);
    }

    /* Responsive */
    @media (max-width: 768px) {
        .lg\:col-span-2 {
            order: 2;
        }
        .lg\:col-span-1 {
            order: 1;
        }
        .sticky-top {
            position: relative;
            top: 0;
        }
        .grid-cols-6 {
            grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem;
        }
        .grid-cols-6 .w-12 {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
        }
        .grid-cols-6 .text-xs {
            font-size: 0.6rem;
        }
    }

    /* Scrollbar */
    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }
    .overflow-x-auto::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
</style>

@endsection