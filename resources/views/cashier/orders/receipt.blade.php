@extends('layouts.cashier')

@section('title', 'Order Receipt')
@section('page_title', 'Receipt')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8 receipt-container">

        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                <i class="fas fa-check text-emerald-600 text-2xl"></i>
            </div>

            <h1 class="text-3xl font-bold text-slate-800 flex items-center justify-center gap-2">
                <i class="fas fa-receipt text-emerald-500"></i>
                POS RECEIPT
            </h1>

            <p class="text-slate-400 mt-1">
                <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                Thank You For Shopping
            </p>
        </div>

        <!-- Divider -->
        <div class="border-t-2 border-dashed border-slate-200 mb-6"></div>

        <!-- Receipt Content -->
        <div id="receiptContent">
            @php
                $paymentReceipt = $order->payment;
                $statusReceipt = $paymentReceipt ? $paymentReceipt->status : 'paid';
                $totalReceipt = 0;
            @endphp

            <!-- Order Info -->
            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Invoice</p>
                    <p class="text-lg font-bold text-slate-800 mt-1">#{{ $order->id }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Customer</p>
                    <p class="text-lg font-bold text-slate-800 mt-1">{{ $order->customer->name ?? 'Walk-in Customer' }}</p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Date</p>
                    <p class="text-sm font-medium text-slate-700 mt-1">
                        {{ $order->created_at ? $order->created_at->format('M d, Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-400 font-semibold uppercase tracking-wider">Status</p>
                    <span class="inline-block mt-1 bg-emerald-100 text-emerald-700 px-3 py-0.5 rounded-full text-sm font-medium">
                        <i class="fas fa-check-circle mr-1"></i>
                        {{ ucfirst($statusReceipt) }}
                    </span>
                </div>
            </div>

            <div class="border-t border-slate-200 mb-4"></div>

            <!-- Items -->
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-2.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                        <th class="text-center py-2.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                        <th class="text-right py-2.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="text-right py-2.5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $item)
                        @php $subtotalReceipt = $item->quantity * $item->price; $totalReceipt += $subtotalReceipt; @endphp
                        <tr class="border-b border-slate-100">
                            <td class="py-2.5 font-medium text-slate-700">
                                <span class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                    {{ $item->product->name ?? 'Product #'.$item->product_id }}
                                </span>
                            </td>
                            <td class="py-2.5 text-center font-medium text-slate-600">
                                <span class="bg-slate-100 px-3 py-0.5 rounded-full text-sm">{{ $item->quantity }}</span>
                            </td>
                            <td class="py-2.5 text-right text-slate-600">${{ number_format($item->price, 2) }}</td>
                            <td class="py-2.5 text-right font-bold text-emerald-600">${{ number_format($subtotalReceipt, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-400">
                                <i class="fas fa-box-open text-3xl block mb-2 opacity-50"></i>
                                No items
                            </td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="border-t-2 border-slate-200">
                        <th colspan="3" class="text-right py-3 text-sm font-semibold text-slate-600">
                            Total
                        </th>
                        <th class="text-right py-3 text-xl font-bold text-emerald-600">
                            ${{ number_format($totalReceipt, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Divider -->
        <div class="border-t-2 border-dashed border-slate-200 mt-6"></div>

        <!-- Footer -->
        <div class="text-center text-xs text-slate-400 mt-4">
            <p>
                <i class="fas fa-store mr-1"></i>
                POS System v2.0
            </p>
            <p class="mt-1">
                <i class="fas fa-print mr-1"></i>
                Printed on {{ now()->format('F d, Y h:i A') }}
            </p>
        </div>

        <!-- Buttons -->
        <div class="flex flex-wrap gap-3 mt-6 no-print">
            <button onclick="window.print()"
                    class="flex-1 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white py-3.5 rounded-xl font-medium transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25">
                <i class="fas fa-print"></i>
                Print Receipt
            </button>

            <a href="{{ route('cashier.orders.index') }}"
               class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3.5 rounded-xl font-medium transition flex items-center justify-center gap-2">
                <i class="fas fa-arrow-left"></i>
                Back to Orders
            </a>
        </div>

    </div>

</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }

        .receipt-container, .receipt-container * {
            visibility: visible;
        }

        .receipt-container {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            max-width: 100%;
            padding: 20px;
            box-shadow: none !important;
            border-radius: 0 !important;
            background: white !important;
            margin: 0 !important;
        }

        .no-print {
            display: none !important;
        }

        .border-dashed {
            border-color: #e2e8f0 !important;
        }

        .shadow-sm {
            box-shadow: none !important;
        }

        .bg-white {
            background: white !important;
        }
    }

    .receipt-container {
        animation: slideUp 0.5s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

@endsection