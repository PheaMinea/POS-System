@extends('layouts.admin')

@section('title', 'Purchase Detail')
@section('page_title', 'Purchase Detail')

@section('content')

<div class="max-w-5xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
            <div class="flex items-center gap-3">
                <i class="fas fa-receipt text-3xl opacity-50"></i>
                <div>
                    <h1 class="text-3xl font-bold">Purchase Detail</h1>
                    <p class="text-indigo-100 mt-1">
                        <i class="fas fa-info-circle mr-2"></i>View purchase information
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-8">

            <!-- Summary -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <i class="fas fa-receipt mr-1"></i>Invoice
                    </p>
                    <p class="text-xl font-bold text-slate-800 mt-1">#{{ $purchase->id }}</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <i class="fas fa-truck mr-1"></i>Supplier
                    </p>
                    <p class="text-xl font-bold text-slate-800 mt-1">{{ $purchase->supplier?->name ?? 'N/A' }}</p>
                </div>

                <div class="bg-slate-50 rounded-xl p-4">
                    <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">
                        <i class="fas fa-calendar-alt mr-1"></i>Date
                    </p>
                    <p class="text-xl font-bold text-slate-800 mt-1">
                        {{ $purchase->created_at->format('M d, Y') }}
                    </p>
                    <p class="text-xs text-slate-400">{{ $purchase->created_at->format('h:i A') }}</p>
                </div>

                <div class="bg-emerald-50 rounded-xl p-4 border-2 border-emerald-200">
                    <p class="text-xs text-emerald-600 uppercase tracking-wider font-semibold">
                        <i class="fas fa-dollar-sign mr-1"></i>Total
                    </p>
                    <p class="text-2xl font-bold text-emerald-600 mt-1">
                        ${{ number_format($purchase->total_price, 2) }}
                    </p>
                </div>
            </div>

            <!-- Items Table -->
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-list text-indigo-500 mr-2"></i>Purchase Items
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Quantity</th>
                            <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($purchase->purchaseItems as $index => $item)
                        <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                            <td class="p-4 text-sm text-slate-400">{{ $loop->iteration }}</td>

                            <td class="p-4">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-box text-indigo-400"></i>
                                    <span class="font-medium text-slate-700">{{ $item->product?->name ?? 'N/A' }}</span>
                                </div>
                            </td>

                            <td class="p-4 font-medium text-slate-600">
                                ${{ number_format($item->price, 2) }}
                            </td>

                            <td class="p-4">
                                <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                    {{ $item->quantity }}
                                </span>
                            </td>

                            <td class="p-4 font-bold text-emerald-600">
                                ${{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr class="border-t-2 border-slate-200">
                            <th colspan="4" class="text-right p-4 text-sm font-semibold text-slate-600">
                                <i class="fas fa-calculator text-indigo-500 mr-2"></i>Grand Total
                            </th>
                            <th class="p-4 text-xl font-bold text-emerald-600">
                                ${{ number_format($purchase->total_price, 2) }}
                            </th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Buttons -->
            <div class="flex flex-wrap gap-3 mt-8 pt-6 border-t border-slate-200">
                <a href="{{ route('admin.purchases.invoice', $purchase) }}"
                   class="bg-emerald-500 hover:bg-emerald-600 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
                    <i class="fas fa-file-invoice"></i>
                    Print Invoice
                </a>

                <a href="{{ route('admin.purchases.index') }}"
                   class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2">
                    <i class="fas fa-arrow-left"></i>
                    Back to List
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
    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>

@endsection