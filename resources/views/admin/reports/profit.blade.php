@extends('layouts.admin')

@section('title', 'Profit Report')
@section('page_title', 'Profit Report')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">
            <i class="fas fa-sack-dollar text-indigo-600 mr-3"></i>Profit Report
        </h1>
        <p class="text-slate-500 mt-1">
            <i class="fas fa-info-circle mr-2"></i>Sales, Purchases and Net Profit Overview
        </p>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">

        <!-- Sales -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Sales</p>
                    <h2 class="text-3xl font-bold text-emerald-600 mt-1">
                        ${{ number_format($totalSales, 2) }}
                    </h2>
                </div>
                <div class="w-14 h-14 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-emerald-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-slate-400">
                <i class="fas fa-chart-line mr-1"></i>
                {{ $orderCount }} orders
            </div>
        </div>

        <!-- Purchases -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-rose-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Purchases</p>
                    <h2 class="text-3xl font-bold text-rose-600 mt-1">
                        ${{ number_format($totalPurchases, 2) }}
                    </h2>
                </div>
                <div class="w-14 h-14 bg-rose-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cart-shopping text-rose-600 text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 text-sm text-slate-400">
                <i class="fas fa-receipt mr-1"></i>
                {{ $purchaseCount }} purchases
            </div>
        </div>

        <!-- Profit -->
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 {{ $profit >= 0 ? 'border-emerald-500' : 'border-rose-500' }}">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Net Profit</p>
                    <h2 class="text-3xl font-bold mt-1 {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
                        ${{ number_format($profit, 2) }}
                    </h2>
                </div>
                <div class="w-14 h-14 {{ $profit >= 0 ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-xl flex items-center justify-center">
                    <i class="fas fa-chart-line {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }} text-2xl"></i>
                </div>
            </div>
            <div class="mt-2 text-sm {{ $profit >= 0 ? 'text-emerald-500' : 'text-rose-500' }}">
                <i class="fas {{ $profit >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }} mr-1"></i>
                {{ $profit >= 0 ? 'Profitable' : 'At Loss' }}
            </div>
        </div>

    </div>

    <!-- Profit Status -->
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center">
        @if($profit >= 0)
            <div class="inline-flex items-center gap-3 bg-emerald-100 text-emerald-700 px-6 py-3 rounded-full font-medium">
                <i class="fas fa-arrow-trend-up text-xl"></i>
                <span class="text-lg">Business is Profitable</span>
            </div>
        @else
            <div class="inline-flex items-center gap-3 bg-rose-100 text-rose-700 px-6 py-3 rounded-full font-medium">
                <i class="fas fa-arrow-trend-down text-xl"></i>
                <span class="text-lg">Business is Running at Loss</span>
            </div>
        @endif

        <h2 class="text-6xl font-bold mt-8 {{ $profit >= 0 ? 'text-emerald-600' : 'text-rose-600' }}">
            ${{ number_format($profit, 2) }}
        </h2>

        <p class="text-slate-400 mt-3 text-sm">
            <i class="fas fa-info-circle mr-1"></i>
            Current Net Profit (Sales - Purchases)
        </p>

        <!-- Additional Info -->
        <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 gap-4 max-w-2xl mx-auto">
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-sm text-slate-400">Profit Margin</p>
                <p class="text-xl font-bold text-slate-800">
                    {{ $totalSales > 0 ? number_format(($profit / $totalSales) * 100, 1) : '0' }}%
                </p>
            </div>
            <div class="bg-slate-50 rounded-xl p-4">
                <p class="text-sm text-slate-400">Expense Ratio</p>
                <p class="text-xl font-bold text-slate-800">
                    {{ $totalSales > 0 ? number_format(($totalPurchases / $totalSales) * 100, 1) : '0' }}%
                </p>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap items-center gap-4">
            <span class="font-medium text-slate-700">
                <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>Date Range:
            </span>
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            <span class="text-slate-400">to</span>
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            <button class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl font-medium transition">
                Apply Filter
            </button>
        </div>
    </div>

</div>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .bg-white { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
    }
</style>

@endsection