@extends('layouts.app')

@section('title', 'My Orders - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- ORDERS HEADER -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-orange-500 to-red-500 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                    <i class="fas fa-receipt"></i>
                    My Orders
                </h1>
                <p class="text-orange-100/90 mt-1">
                    <i class="fas fa-circle text-[6px] mr-2 align-middle"></i>
                    Track your food orders
                </p>
            </div>
            <a href="{{ route('customer.menu') }}"
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                <i class="fas fa-utensils"></i>
                Order More Food
            </a>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- ORDERS LIST -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">

        <!-- Table Header -->
        <div class="hidden md:grid grid-cols-7 gap-4 bg-slate-50 px-6 py-4 border-b border-slate-200">
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Order ID</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Items</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Total</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Payment</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Status</div>
            <div class="col-span-1 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Action</div>
        </div>

        <!-- Orders List -->
        <div class="divide-y divide-slate-100">

            @forelse($orders as $order)
                <div class="p-4 md:p-6 hover:bg-slate-50 transition">
                    <div class="grid md:grid-cols-7 gap-4 items-center">

                        <!-- Order ID -->
                        <div class="md:col-span-1">
                            <span class="font-bold text-slate-800 text-sm">
                                <i class="fas fa-hashtag text-orange-400 text-xs mr-1"></i>
                                #{{ $order->id }}
                            </span>
                        </div>

                        <!-- Date -->
                        <div class="md:col-span-1 text-sm text-slate-500">
                            <i class="fas fa-calendar-alt text-slate-400 mr-1.5"></i>
                            {{ $order->created_at->format('d M Y') }}
                            <span class="text-xs text-slate-400 block md:hidden">
                                {{ $order->created_at->format('H:i') }}
                            </span>
                        </div>

                        <!-- Items -->
                        <div class="md:col-span-1 text-center">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $order->orderItems->count() }} item(s)
                            </span>
                        </div>

                        <!-- Total -->
                        <div class="md:col-span-1 text-right font-bold text-orange-500">
                            ${{ number_format($order->total_price, 2) }}
                        </div>

                        <!-- Payment -->
                        <div class="md:col-span-1 text-center">
                            @php
                                $paymentStatus = $order->payment?->status ?? 'pending';
                                $paymentClass = $paymentStatus === 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $paymentClass }}">
                                <i class="fas {{ $paymentStatus === 'paid' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                                {{ ucfirst($paymentStatus) }}
                            </span>
                        </div>

                        <!-- Status -->
                        <div class="md:col-span-1 text-center">
                            @php
                                $status = $order->status ?? 'pending';
                                $statusClasses = [
                                    'pending' => 'bg-amber-100 text-amber-700',
                                    'pending_payment' => 'bg-violet-100 text-violet-700',
                                    'accepted' => 'bg-sky-100 text-sky-700',
                                    'preparing' => 'bg-orange-100 text-orange-700',
                                    'ready' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-emerald-100 text-emerald-700',
                                    'cancelled' => 'bg-rose-100 text-rose-700',
                                ];
                                $statusLabels = [
                                    'pending' => 'Pending',
                                    'pending_payment' => 'Pending Payment',
                                    'accepted' => 'Accepted',
                                    'preparing' => 'Preparing',
                                    'ready' => 'Ready',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ];
                                $statusIcons = [
                                    'pending' => 'fa-clock',
                                    'pending_payment' => 'fa-credit-card',
                                    'accepted' => 'fa-handshake',
                                    'preparing' => 'fa-fire',
                                    'ready' => 'fa-check-circle',
                                    'completed' => 'fa-flag-checkered',
                                    'cancelled' => 'fa-times-circle',
                                ];
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $statusClasses[$status] ?? 'bg-slate-100 text-slate-600' }}">
                                <i class="fas {{ $statusIcons[$status] ?? 'fa-circle' }} mr-1"></i>
                                {{ $statusLabels[$status] ?? ucfirst($status) }}
                            </span>
                        </div>

                        <!-- Action -->
                        <div class="md:col-span-1 text-center">
                            <a href="{{ route('customer.orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1.5 bg-sky-500 hover:bg-sky-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
                                <i class="fas fa-eye"></i>
                                View
                            </a>
                        </div>

                    </div>
                </div>
            @empty
                <div class="text-center py-16 px-6">
                    <div class="w-24 h-24 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-receipt text-4xl text-orange-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-700">No Orders Found</h3>
                    <p class="text-slate-400 mt-2 text-sm">You haven't placed any orders yet</p>
                    <a href="{{ route('customer.menu') }}"
                       class="inline-block mt-6 bg-orange-500 hover:bg-orange-600 text-white px-8 py-3 rounded-xl font-medium transition">
                        <i class="fas fa-utensils mr-2"></i>
                        Browse Menu
                    </a>
                </div>
            @endforelse

        </div>

    </div>

</section>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
    /* Responsive */
    @media (max-width: 768px) {
        .grid-cols-7 {
            grid-template-columns: 1fr;
            gap: 0.5rem;
        }
        .grid-cols-7 > div {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.25rem 0;
        }
        .grid-cols-7 > div:not(:last-child) {
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.5rem;
        }
        .grid-cols-7 .text-center {
            text-align: left !important;
        }
        .grid-cols-7 .text-right {
            text-align: left !important;
        }
        .grid-cols-7 .text-center .inline-flex {
            margin-left: 0 !important;
        }
    }
</style>

@endsection
