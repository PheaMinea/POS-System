@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')

<!-- ============================================================ -->
<!-- WELCOME SECTION -->
<!-- ============================================================ -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-8">

    <div class="min-w-0">
        <h2 class="text-2xl md:text-3xl font-bold text-slate-800 tracking-tight">
            <i class="fas fa-chart-pie text-indigo-600 mr-3"></i>Dashboard Overview
        </h2>
        <p class="text-slate-500 mt-1 flex items-center gap-2">
            <i class="fas fa-user-circle text-indigo-400"></i>
            Welcome back, <span class="font-medium text-slate-700">{{ auth()->user()->name ?? 'Admin' }}</span>
        </p>
    </div>

    <div class="w-full sm:w-auto bg-gradient-to-br from-indigo-600 to-indigo-700 text-white px-5 md:px-6 py-4 rounded-2xl shadow-lg shadow-indigo-500/25 flex items-center justify-between gap-4 hover:shadow-indigo-500/35 transition">
        <div class="min-w-0">
            <p class="text-xs font-medium uppercase tracking-wider opacity-80 flex items-center gap-1.5">
                <i class="fas fa-calendar-day"></i>
                Total Sales
            </p>
            <h2 class="mt-1 break-words text-2xl font-bold leading-tight md:text-3xl">
                ${{ number_format($dashboard['total_sales'], 2) }}
            </h2>
        </div>
        <div class="bg-white/20 p-3 rounded-xl flex-shrink-0">
            <i class="fas fa-arrow-trend-up text-2xl"></i>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- STATISTICS CARDS -->
<!-- ============================================================ -->
<div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-5 mb-8">

    <!-- Total Products -->
    <div class="bg-gradient-to-br from-blue-500 to-blue-700 rounded-2xl p-6 text-white shadow-lg shadow-blue-500/20 card-hover relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="flex justify-between items-start relative z-10">
            <div class="min-w-0">
                <p class="text-sm font-medium opacity-80">Total Products</p>
                <h2 class="mt-1 break-words text-3xl font-bold tracking-tight md:text-4xl">{{ $dashboard['total_products'] }}</h2>
                <p class="text-xs opacity-70 mt-2 flex items-center gap-1">
                    <i class="fas fa-box"></i>
                    Inventory items
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl flex-shrink-0">
                <i class="fas fa-box text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-white/10 relative z-10">
            <span class="text-xs text-white/70">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
                Updated: {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    <!-- Total Customers -->
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-white shadow-lg shadow-emerald-500/20 card-hover relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="flex justify-between items-start relative z-10">
            <div class="min-w-0">
                <p class="text-sm font-medium opacity-80">Total Customers</p>
                <h2 class="mt-1 break-words text-3xl font-bold tracking-tight md:text-4xl">{{ $dashboard['total_customers'] }}</h2>
                <p class="text-xs opacity-70 mt-2 flex items-center gap-1">
                    <i class="fas fa-users"></i>
                    Active customers
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl flex-shrink-0">
                <i class="fas fa-users text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-white/10 relative z-10">
            <span class="text-xs text-white/70">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
                Updated: {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    <!-- Total Orders -->
    <div class="bg-gradient-to-br from-amber-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg shadow-amber-500/20 card-hover relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="flex justify-between items-start relative z-10">
            <div class="min-w-0">
                <p class="text-sm font-medium opacity-80">Total Orders</p>
                <h2 class="mt-1 break-words text-3xl font-bold tracking-tight md:text-4xl">{{ $dashboard['total_orders'] }}</h2>
                <p class="text-xs opacity-70 mt-2 flex items-center gap-1">
                    <i class="fas fa-shopping-cart"></i>
                    Completed orders
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl flex-shrink-0">
                <i class="fas fa-shopping-cart text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-white/10 relative z-10">
            <span class="text-xs text-white/70">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
                Updated: {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

    <!-- Total Sales -->
    <div class="bg-gradient-to-br from-rose-500 to-pink-600 rounded-2xl p-6 text-white shadow-lg shadow-rose-500/20 card-hover relative overflow-hidden">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/5 rounded-full"></div>
        <div class="flex justify-between items-start relative z-10">
            <div class="min-w-0">
                <p class="text-sm font-medium opacity-80">Total Sales</p>
                <h2 class="mt-1 break-words text-3xl font-bold tracking-tight md:text-4xl">${{ number_format($dashboard['total_sales'], 2) }}</h2>
                <p class="text-xs opacity-70 mt-2 flex items-center gap-1">
                    <i class="fas fa-dollar-sign"></i>
                    Total revenue
                </p>
            </div>
            <div class="bg-white/20 backdrop-blur-sm p-3 rounded-xl flex-shrink-0">
                <i class="fas fa-dollar-sign text-2xl"></i>
            </div>
        </div>
        <div class="mt-3 pt-3 border-t border-white/10 relative z-10">
            <span class="text-xs text-white/70">
                <i class="fas fa-circle text-[6px] text-emerald-400 mr-1.5 align-middle"></i>
                Updated: {{ now()->format('M d, Y') }}
            </span>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- CHART SECTION -->
<!-- ============================================================ -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-4 md:p-6 mb-8">
    <div class="flex flex-wrap justify-between items-center gap-4 mb-6">
        <h3 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
            <i class="fas fa-chart-line text-indigo-600"></i>
            Sales Overview
        </h3>
        <span class="text-xs text-slate-400 bg-slate-50 px-4 py-2 rounded-full border border-slate-100">
            <i class="fas fa-calendar-alt mr-2"></i>Last 6 Months
        </span>
    </div>

    @php
        $maxMonthlySales = max(0, $monthlySales->max('total') ?? 0);
    @endphp

    <div class="h-56 md:h-64 flex items-end gap-2 overflow-x-auto pb-1">
        @foreach($monthlySales as $month)
            @php
                $height = $maxMonthlySales > 0
                    ? max(10, ($month['total'] / $maxMonthlySales) * 100)
                    : 0;
            @endphp
            <div class="min-w-10 flex-1 h-full flex items-end group relative">
                <div class="w-full rounded-lg transition-all duration-500 ease-out hover:scale-y-105"
                     style="height: {{ $height }}%; background: linear-gradient(180deg, #818cf8 0%, #6366f1 100%);">
                </div>
                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200 bg-slate-800 text-white text-xs px-2 py-1 rounded-lg whitespace-nowrap">
                    ${{ number_format($month['total'], 2) }}
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex justify-between gap-2 mt-3 text-xs text-slate-400 font-medium overflow-x-auto pb-1">
        @foreach($monthlySales as $month)
            <span class="min-w-10 text-center">{{ $month['label'] }}</span>
        @endforeach
    </div>
</div>

<!-- ============================================================ -->
<!-- TWO COLUMNS: Low Stock + Recent Orders -->
<!-- ============================================================ -->
<div class="grid lg:grid-cols-3 gap-6 mb-8">

    <!-- Low Stock Alert -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-4 md:p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-lg text-slate-800 flex items-center gap-2">
                <i class="fas fa-exclamation-triangle text-amber-500"></i>
                Low Stock Alert
            </h3>
            <span class="text-xs bg-slate-100 text-slate-500 px-2.5 py-1 rounded-full font-medium">
                {{ $lowStockProducts->count() }}
            </span>
        </div>

        <div id="lowStockProducts" class="space-y-3 max-h-64 overflow-y-auto custom-scroll pr-1">
            @forelse($lowStockProducts as $product)
                <div class="flex items-center justify-between gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                    <div class="min-w-0">
                        <p class="font-medium text-slate-700 text-sm">{{ $product->name }}</p>
                        <p class="text-xs text-slate-400">SKU: {{ $product->id }}</p>
                    </div>
                    <span class="{{ $product->stock <= 3 ? 'bg-rose-100 text-rose-600' : 'bg-amber-100 text-amber-600' }} px-3 py-1 rounded-full text-xs font-bold flex flex-shrink-0 items-center gap-1">
                        <i class="fas fa-circle text-[6px]"></i>
                        {{ $product->stock }} Left
                    </span>
                </div>
            @empty
                <div class="text-center py-8">
                    <div class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                        <i class="fas fa-check-circle text-2xl text-emerald-500"></i>
                    </div>
                    <p class="font-medium text-slate-600">All Products Well Stocked</p>
                    <p class="text-sm text-slate-400">No low stock items found</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-100/80 p-4 md:p-6">
        <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
            <h3 class="font-semibold text-lg text-slate-800 flex items-center gap-2">
                <i class="fas fa-receipt text-indigo-600"></i>
                Recent Orders
            </h3>
            <a href="{{ route('admin.reports.sales') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium transition flex items-center gap-1">
                View All
                <i class="fas fa-chevron-right text-xs"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[560px]">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Amount</th>
                        <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody id="recentOrders">
                    @forelse($recentOrders as $order)
                        @php
                            $status = $order->payment?->status ?? 'pending';
                            $statusClass = match ($status) {
                                'paid' => 'bg-emerald-100 text-emerald-600',
                                'failed' => 'bg-rose-100 text-rose-600',
                                default => 'bg-amber-100 text-amber-600',
                            };
                            $statusIcon = match ($status) {
                                'paid' => 'fa-check-circle',
                                'failed' => 'fa-times-circle',
                                default => 'fa-clock',
                            };
                            $customerName = $order->customer?->name ?? 'Walk-in Customer';
                        @endphp
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition last:border-b-0">
                            <td class="py-3 text-sm font-medium text-slate-700">
                                <span class="bg-indigo-50 text-indigo-600 px-2 py-0.5 rounded text-xs font-mono">
                                    #INV-{{ str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-semibold text-xs">
                                        {{ strtoupper(substr($customerName, 0, 1)) }}
                                    </div>
                                    <span class="text-sm text-slate-700">{{ $customerName }}</span>
                                </div>
                            </td>
                            <td class="py-3 text-sm font-semibold text-slate-700">${{ number_format($order->total_price, 2) }}</td>
                            <td class="py-3">
                                <span class="{{ $statusClass }} px-3 py-1 rounded-full text-xs font-medium flex items-center gap-1.5 w-fit">
                                    <i class="fas {{ $statusIcon }}"></i>{{ ucfirst($status) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-8 text-center text-slate-400">
                                <i class="fas fa-inbox text-2xl block mb-2 opacity-30"></i>
                                No recent orders found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

<!-- ============================================================ -->
<!-- RECENT PAYMENTS -->
<!-- ============================================================ -->
<div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-4 md:p-6">
    <div class="flex flex-wrap justify-between items-center gap-3 mb-4">
        <h3 class="font-semibold text-lg text-slate-800 flex items-center gap-2">
            <i class="fas fa-credit-card text-indigo-600"></i>
            Recent Payments
        </h3>
        <span class="text-xs text-slate-400 bg-slate-50 px-3 py-1 rounded-full border border-slate-100">
            <i class="fas fa-clock mr-1.5"></i>{{ now()->format('M d, Y') }}
        </span>
    </div>

    <div id="recentPayments" class="space-y-2">
        @forelse($recentPayments as $payment)
            @php
                $paymentClass = match ($payment->status) {
                    'paid' => 'text-emerald-600',
                    'failed' => 'text-rose-600',
                    default => 'text-amber-600',
                };
                $paymentIcon = match ($payment->payment_method) {
                    'card' => 'fa-credit-card',
                    'bank_transfer' => 'fa-university',
                    'mobile_money' => 'fa-mobile-alt',
                    default => 'fa-money-bill-wave',
                };
            @endphp
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-white rounded-lg flex items-center justify-center text-indigo-500 shadow-sm">
                        <i class="fas {{ $paymentIcon }}"></i>
                    </div>
                    <div>
                        <p class="font-medium text-slate-700 text-sm">
                            {{ $payment->reference_no ?? 'INV-' . str_pad($payment->order_id, 4, '0', STR_PAD_LEFT) }}
                        </p>
                        <p class="text-xs text-slate-400">
                            {{ $payment->order?->customer?->name ?? 'Walk-in Customer' }}
                            <span class="mx-1.5 text-slate-300">&middot;</span>
                            {{ ucfirst($payment->payment_method) }}
                        </p>
                    </div>
                </div>
                <div class="sm:text-right">
                    <span class="font-bold {{ $paymentClass }}">${{ number_format($payment->amount, 2) }}</span>
                    <p class="text-xs text-slate-400">{{ ucfirst($payment->status) }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-8 text-slate-400">
                <i class="fas fa-credit-card text-2xl block mb-2 opacity-30"></i>
                No recent payments found.
            </div>
        @endforelse
    </div>
</div>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Card hover effect */
    .card-hover {
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .card-hover:hover {
        transform: translateY(-8px);
        box-shadow: 0 25px 40px -15px rgba(0, 0, 0, 0.2) !important;
    }

    /* Chart bar animation */
    .chart-bar {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .chart-bar:hover {
        transform: scaleY(1.05);
    }

    /* Custom scrollbar */
    .custom-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

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
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Status badges */
    .badge-completed {
        background-color: #d1fae5;
        color: #065f46;
    }
    .badge-pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    .badge-cancelled {
        background-color: #fee2e2;
        color: #991b1b;
    }

    /* Decorative elements */
    .bg-gradient-to-br {
        background-attachment: fixed;
    }
</style>

@endsection
