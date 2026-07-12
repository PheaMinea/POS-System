@extends('layouts.cashier')

@section('title', 'Order Detail')
@section('page_title', 'Order Detail')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-emerald-600 to-green-600 p-8 text-white">
            <div class="flex flex-wrap justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <i class="fas fa-receipt text-3xl opacity-50"></i>
                    <div>
                        <h1 class="text-3xl font-bold">Order Detail</h1>
                        <p class="text-emerald-100 mt-1">
                            <i class="fas fa-info-circle mr-2"></i>View order information
                        </p>
                    </div>
                </div>

                <a href="{{ route('cashier.orders.receipt', $order->id) }}" id="receiptBtn"
                   class="bg-white/20 hover:bg-white/30 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 backdrop-blur-sm">
                    <i class="fas fa-print"></i>
                    Print Receipt
                </a>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">

            @php
                $payment = $order->payment;
                $status = $order->status ?? 'pending';
                $paymentStatus = $payment ? $payment->status : 'pending';
                $statusOrder = ['pending_payment', 'pending', 'accepted', 'preparing', 'ready', 'completed'];
                $currentIndex = array_search($status, $statusOrder);
                $currentIndex = $currentIndex === false ? 0 : $currentIndex;
                $total = 0;
            @endphp

            <!-- Order Info -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                        <i class="fas fa-receipt mr-1"></i>Order ID
                    </p>
                    <p class="text-lg font-bold text-slate-800 mt-1">#{{ $order->id }}</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                        <i class="fas fa-user mr-1"></i>Customer
                    </p>
                    <p class="text-lg font-bold text-slate-800 mt-1">{{ $order->customer->name ?? 'Walk-in Customer' }}</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                        <i class="fas fa-calendar-alt mr-1"></i>Date
                    </p>
                    <p class="text-lg font-bold text-slate-800 mt-1">
                        {{ $order->created_at ? $order->created_at->format('M d, Y') : '-' }}
                    </p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                        <i class="fas fa-tag mr-1"></i>Order Status
                    </p>
                    <span class="inline-block mt-1 px-3 py-1 rounded-full text-sm font-medium
                        @if($status === 'pending') bg-amber-100 text-amber-700
                        @elseif($status === 'pending_payment') bg-violet-100 text-violet-700
                        @elseif($status === 'accepted') bg-sky-100 text-sky-700
                        @elseif($status === 'preparing') bg-orange-100 text-orange-700
                        @elseif($status === 'ready') bg-blue-100 text-blue-700
                        @elseif($status === 'completed') bg-emerald-100 text-emerald-700
                        @endif">
                        <i class="fas
                            @if($status === 'pending') fa-clock
                            @elseif($status === 'pending_payment') fa-wallet
                            @elseif($status === 'accepted') fa-handshake
                            @elseif($status === 'preparing') fa-fire
                            @elseif($status === 'ready') fa-check-circle
                            @elseif($status === 'completed') fa-flag-checkered
                            @endif mr-1"></i>
                        @if($status === 'pending_payment' && $paymentStatus !== 'paid')
                            Pending Payment
                        @elseif($status === 'pending_payment' || $status === 'pending')
                            Waiting Accept
                        @else
                            {{ ucfirst($status) }}
                        @endif
                    </span>
                </div>
            </div>

            <!-- Status Tracking Progress -->
            <div class="bg-slate-50 rounded-xl p-6 mb-6">
                <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach(['pending_payment' => 'fa-wallet', 'pending' => 'fa-clock', 'accepted' => 'fa-handshake', 'preparing' => 'fa-fire', 'ready' => 'fa-check-circle', 'completed' => 'fa-flag-checkered'] as $state => $icon)
                    <div class="text-center">
                        <div class="w-14 h-14 mx-auto rounded-full flex items-center justify-center
                            @if(array_search($state, $statusOrder) <= $currentIndex) bg-emerald-100 text-emerald-600
                            @else bg-slate-200 text-slate-400 @endif">
                            <i class="fas {{ $icon }} text-xl"></i>
                        </div>
                        <p class="mt-2 text-sm font-semibold
                            @if(array_search($state, $statusOrder) <= $currentIndex) text-emerald-600
                            @else text-slate-400 @endif">
                            @if($state === 'pending_payment')
                                Payment
                            @elseif($state === 'pending')
                                Waiting
                            @else
                                {{ ucfirst($state) }}
                            @endif
                        </p>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Update Status Buttons -->
            <div class="bg-slate-50 rounded-xl p-4 mb-6">
                <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider mb-3">
                    <i class="fas fa-edit mr-1"></i>Order Processing Flow
                </p>
                <div class="flex flex-wrap gap-2">
                    <form action="{{ route('cashier.orders.update-status', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="accepted">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition bg-amber-600 text-white hover:bg-amber-700"
                            {{ in_array($status, ['pending', 'pending_payment']) && $paymentStatus === 'paid' ? '' : 'disabled' }}>
                            <i class="fas fa-handshake mr-1"></i>
                            Accept
                        </button>
                    </form>

                    <form action="{{ route('cashier.orders.update-status', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="preparing">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition bg-orange-600 text-white hover:bg-orange-700"
                            {{ $status === 'accepted' ? '' : 'disabled' }}>
                            <i class="fas fa-fire mr-1"></i>
                            Preparing
                        </button>
                    </form>

                    <form action="{{ route('cashier.orders.update-status', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="ready">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition bg-blue-600 text-white hover:bg-blue-700"
                            {{ $status === 'preparing' ? '' : 'disabled' }}>
                            <i class="fas fa-utensils mr-1"></i>
                            Ready
                        </button>
                    </form>

                    <form action="{{ route('cashier.orders.update-status', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="completed">
                        <button type="submit"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition bg-emerald-600 text-white hover:bg-emerald-700"
                            {{ $status === 'ready' ? '' : 'disabled' }}>
                            <i class="fas fa-flag-checkered mr-1"></i>
                            Complete
                        </button>
                    </form>
                </div>
            </div>

            <!-- Order Summary Box -->
            <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-6">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <p class="text-sm font-semibold text-emerald-700">Order #{{ $order->id }}</p>
                        <p class="text-xs text-emerald-600">Customer: {{ $order->customer->name ?? 'Walk-in Customer' }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-emerald-700">Total: ${{ number_format($total, 2) }}</p>
                        <p class="text-xs text-emerald-600">Payment: {{ strtoupper($payment?->payment_method ?? 'N/A') }} • Status: {{ ucfirst($payment?->status ?? 'Pending') }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Info -->
            @if($payment)
            <div class="bg-slate-50 rounded-xl p-4 mb-6">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                            <i class="fas fa-credit-card mr-1"></i>Payment Method
                        </p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ strtoupper($payment->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-1"></i>Reference No
                        </p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ $payment->reference_no ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">
                            <i class="fas fa-user mr-1"></i>Cashier
                        </p>
                        <p class="text-lg font-bold text-slate-800 mt-1">{{ $order->user->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Items Table -->
            <h3 class="text-lg font-bold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-list text-emerald-500"></i>
                Order Items
                <span class="text-sm font-normal text-slate-400">
                    ({{ $order->orderItems->count() }} items)
                </span>
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                            <th class="p-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                            <th class="p-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                            <th class="p-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                            <th class="p-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($order->orderItems as $index => $item)
                            @php $subtotal = $item->quantity * $item->price; $total += $subtotal; @endphp
                            <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                                <td class="py-3">
                                    <span class="text-sm font-medium text-slate-500">{{ $index + 1 }}</span>
                                </td>
                                <td class="py-3 font-medium text-slate-700">
                                    <span class="flex items-center gap-2">
                                        <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                                        {{ $item->product->name ?? 'Product #'.$item->product_id }}
                                    </span>
                                </td>
                                <td class="py-3 text-center font-medium text-slate-600">
                                    <span class="bg-slate-100 px-3 py-0.5 rounded-full text-sm">{{ $item->quantity }}</span>
                                </td>
                                <td class="py-3 text-right text-slate-600">${{ number_format($item->price, 2) }}</td>
                                <td class="py-3 text-right font-bold text-emerald-600">${{ number_format($subtotal, 2) }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-8 text-slate-400">
                                    <i class="fas fa-box-open text-3xl block mb-2 opacity-50"></i>
                                    No items found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <th colspan="4" class="text-right p-3 text-sm font-semibold text-slate-600">
                                <i class="fas fa-calculator text-emerald-500 mr-2"></i>Grand Total
                            </th>
                            <th class="p-3 text-right text-xl font-bold text-emerald-600">
                                ${{ number_format($total, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Actions -->
            <div class="flex flex-wrap gap-3 mt-6 pt-6 border-t border-slate-200">
                <a href="{{ route('cashier.orders.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-5 py-3 rounded-xl transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to Orders
                </a>
            </div>
        </div>

    </div>

</div>

<style>
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
