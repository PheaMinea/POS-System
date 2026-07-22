@extends('layouts.cashier')

@section('title', 'Cashier Dashboard')
@section('page_title', 'Cashier Dashboard')

@section('content')

<div class="space-y-6">

    <!-- ============================================================ -->
    <!-- HEADER -->
    <!-- ============================================================ -->
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
        <div class="min-w-0">
            <h1 class="text-2xl font-bold text-slate-800 tracking-tight sm:text-3xl">
                <i class="fas fa-gauge-high text-blue-500 mr-3"></i>Cashier Dashboard
            </h1>
            <p class="mt-1 truncate text-slate-400">
                <i class="fas fa-circle text-[6px] text-blue-400 mr-2 align-middle"></i>
                Welcome back, {{ auth()->user()->name ?? 'Cashier' }}
            </p>
        </div>

        <a href="{{ route('cashier.pos.index') }}"
           class="flex w-full items-center justify-center gap-2.5 rounded-xl bg-gradient-to-r from-blue-500 to-blue-700 px-6 py-3 font-medium text-white shadow-lg shadow-blue-500/25 transition hover:from-blue-600 hover:to-blue-800 hover:shadow-blue-500/35 sm:w-auto">
            <i class="fas fa-cash-register text-lg"></i>
            Open POS
            <i class="fas fa-arrow-right text-sm opacity-70"></i>
        </a>
    </div>

    <!-- ============================================================ -->
    <!-- STATISTICS CARDS -->
    <!-- ============================================================ -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-5">

        <!-- Products -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-4 md:p-5">
            <div class="flex justify-between items-start">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <i class="fas fa-circle text-[6px] text-blue-400 mr-1.5 align-middle"></i>
                        Products
                    </p>
                    <h2 id="totalProducts"
                        class="mt-1.5 break-words text-2xl font-bold text-blue-600 md:text-3xl">
                        {{ $dashboard['total_products'] ?? 0 }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Available inventory</p>
                </div>
                <div class="icon-wrapper flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <i class="fas fa-box text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-4 md:p-5">
            <div class="flex justify-between items-start">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <i class="fas fa-circle text-[6px] text-blue-400 mr-1.5 align-middle"></i>
                        Customers
                    </p>
                    <h2 id="totalCustomers"
                        class="mt-1.5 break-words text-2xl font-bold text-blue-600 md:text-3xl">
                        {{ $dashboard['total_customers'] ?? 0 }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Customer records</p>
                </div>
                <div class="icon-wrapper flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-blue-50">
                    <i class="fas fa-users text-blue-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-4 md:p-5">
            <div class="flex justify-between items-start">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <i class="fas fa-circle text-[6px] text-amber-400 mr-1.5 align-middle"></i>
                        Orders
                    </p>
                    <h2 id="totalOrders"
                        class="mt-1.5 break-words text-2xl font-bold text-amber-600 md:text-3xl">
                        {{ $dashboard['total_orders'] ?? 0 }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Your cashier orders</p>
                </div>
                <div class="icon-wrapper flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-amber-50">
                    <i class="fas fa-cart-shopping text-amber-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Sales -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-4 md:p-5">
            <div class="flex justify-between items-start">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <i class="fas fa-circle text-[6px] text-rose-400 mr-1.5 align-middle"></i>
                        Sales
                    </p>
                    <h2 id="totalSales"
                        class="mt-1.5 break-words text-2xl font-bold text-rose-600 md:text-3xl">
                        ${{ number_format($dashboard['total_sales'] ?? 0, 2) }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Your cashier sales</p>
                </div>
                <div class="icon-wrapper flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-rose-50">
                    <i class="fas fa-dollar-sign text-rose-500 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Purchases -->
        <div class="stat-card bg-white rounded-2xl shadow-sm p-4 md:p-5">
            <div class="flex justify-between items-start">
                <div class="min-w-0">
                    <p class="text-xs font-medium text-slate-400 uppercase tracking-wider">
                        <i class="fas fa-circle text-[6px] text-purple-400 mr-1.5 align-middle"></i>
                        Purchases
                    </p>
                    <h2 id="totalPurchases"
                        class="mt-1.5 break-words text-2xl font-bold text-purple-600 md:text-3xl">
                        ${{ number_format($dashboard['total_purchases'] ?? 0, 2) }}
                    </h2>
                    <p class="text-xs text-slate-400 mt-1">Total shop purchases</p>
                </div>
                <div class="icon-wrapper flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-purple-50">
                    <i class="fas fa-truck text-purple-500 text-xl"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- ============================================================ -->
    <!-- QUICK ACTIONS & RECENT ACTIVITY -->
    <!-- ============================================================ -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">
            <h3 class="font-semibold text-slate-800 mb-4 flex items-center gap-2">
                <i class="fas fa-bolt text-blue-500"></i>
                Quick Actions
            </h3>
            <div class="space-y-3">
                <a href="{{ route('cashier.pos.index') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-cash-register"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-700 transition group-hover:text-blue-600">New Sale</p>
                        <p class="truncate text-xs text-slate-400">Start a new transaction</p>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transition"></i>
                </a>

                <a href="{{ route('cashier.customers.index') }}"
                   class="flex items-center gap-3 p-3 bg-blue-50 hover:bg-blue-100 rounded-xl transition group">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-700 transition group-hover:text-blue-600">Add Customer</p>
                        <p class="truncate text-xs text-slate-400">Create new customer profile</p>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-blue-500 transition"></i>
                </a>

                <a href="{{ route('cashier.orders.index') }}"
                   class="flex items-center gap-3 p-3 bg-amber-50 hover:bg-amber-100 rounded-xl transition group">
                    <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-medium text-slate-700 transition group-hover:text-amber-600">View Orders</p>
                        <p class="truncate text-xs text-slate-400">Check recent orders</p>
                    </div>
                    <i class="fas fa-arrow-right ml-auto text-slate-300 group-hover:text-amber-500 transition"></i>
                </a>
            </div>
        </div>

        <!-- Recent Sales -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm p-4 md:p-6">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <h3 class="font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-clock-rotate-left text-blue-500"></i>
                    Recent Sales
                </h3>
                <a href="{{ route('cashier.orders.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium transition">
                    View All <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>

            <div class="space-y-3">
                @forelse($recentSales as $order)
                    @php
                        $customerName = $order->customer?->name ?? 'Walk-in Customer';
                        $status = $order->payment?->status ?? 'pending';
                        $statusClass = match ($status) {
                            'paid' => 'bg-emerald-100 text-emerald-700',
                            'failed' => 'bg-rose-100 text-rose-700',
                            default => 'bg-amber-100 text-amber-700',
                        };
                        $amountClass = match ($status) {
                            'paid' => 'text-emerald-600',
                            'failed' => 'text-rose-600',
                            default => 'text-amber-600',
                        };
                    @endphp
                    <div class="flex flex-col gap-3 rounded-xl bg-slate-50 p-3 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($customerName, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-slate-700">
                                    {{ $order->payment?->reference_no ?? 'INV-' . str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </p>
                                <p class="text-xs text-slate-400 truncate">
                                    {{ $customerName }} &middot; {{ $order->created_at->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                        <div class="flex-shrink-0 sm:text-right">
                            <p class="font-bold {{ $amountClass }}">${{ number_format($order->total_price, 2) }}</p>
                            <span class="text-xs {{ $statusClass }} px-2 py-0.5 rounded-full">{{ ucfirst($status) }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8 text-slate-400">
                        <i class="fas fa-receipt text-2xl block mb-2 opacity-30"></i>
                        No recent sales found.
                    </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const totalProducts = document.getElementById('totalProducts');
        const totalCustomers = document.getElementById('totalCustomers');
        const totalOrders = document.getElementById('totalOrders');

        if (totalProducts) {
            animateNumber('totalProducts', Number(totalProducts.textContent.replace(/,/g, '')));
        }
        if (totalCustomers) {
            animateNumber('totalCustomers', Number(totalCustomers.textContent.replace(/,/g, '')));
        }
        if (totalOrders) {
            animateNumber('totalOrders', Number(totalOrders.textContent.replace(/,/g, '')));
        }
    });

    function animateNumber(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element) return;

        const duration = 800;
        const startTime = performance.now();

        function updateNumber(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            const currentValue = Math.floor(progress * targetValue);

            element.textContent = currentValue.toLocaleString();

            if (progress < 1) {
                requestAnimationFrame(updateNumber);
            } else {
                element.textContent = targetValue.toLocaleString();
            }
        }

        requestAnimationFrame(updateNumber);
    }
</script>

<style>
    /* Stat card hover effect */
    .stat-card {
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
        border-color: #2563eb;
    }

    .stat-card .icon-wrapper {
        transition: all 0.3s ease;
    }

    .stat-card:hover .icon-wrapper {
        transform: scale(1.05) rotate(-3deg);
    }

    /* Quick action hover */
    .quick-action {
        transition: all 0.2s ease;
    }

    .quick-action:hover {
        transform: translateX(4px);
    }
</style>

@endsection
