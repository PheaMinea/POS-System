@extends('layouts.cashier')

@section('title', 'POS Receipt')
@section('page_title', 'Receipt')

@section('content')

<div class="max-w-2xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm p-8 receipt-container">

        <!-- ============================================================ -->
        <!-- RECEIPT HEADER -->
        <!-- ============================================================ -->
        <div class="text-center mb-8">

            <!-- Success Icon -->
            <div class="inline-flex items-center justify-center w-16 h-16 bg-emerald-100 rounded-full mb-4">
                <i class="fas fa-check text-emerald-600 text-2xl"></i>
            </div>

            <h1 class="text-3xl font-bold text-slate-800 flex items-center justify-center gap-2">
                <i class="fas fa-receipt text-emerald-500"></i>
                POS RECEIPT
            </h1>

            <p class="text-slate-400 mt-1">
                <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                Thank you for your purchase
            </p>

            <!-- Store Info -->
            <div class="mt-4 text-sm text-slate-400 space-y-1">
                <p class="font-medium text-slate-600">{{ auth()->user()->name ?? 'Cashier' }}</p>
                <p><i class="fas fa-calendar-alt mr-1.5"></i>{{ $order->created_at->format('F d, Y') }}</p>
                <p><i class="fas fa-clock mr-1.5"></i>{{ $order->created_at->format('h:i A') }}</p>
                <div class="flex justify-center items-center gap-4 mt-2 text-xs text-slate-300">
                    <span><i class="fas fa-receipt mr-1"></i>{{ $order->payment->reference_no ?? 'INV-'.$order->id }}</span>
                    <span><i class="fas fa-user mr-1"></i>{{ $order->customer->name ?? 'Walk-in' }}</span>
                    <span><i class="fas fa-credit-card mr-1"></i>{{ ucfirst($order->payment->payment_method ?? 'N/A') }}</span>
                </div>
            </div>

        </div>

        <!-- ============================================================ -->
        <!-- DIVIDER -->
        <!-- ============================================================ -->
        <div class="border-t-2 border-dashed border-slate-200 mb-6"></div>

        <!-- ============================================================ -->
        <!-- ITEMS TABLE -->
        <!-- ============================================================ -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="text-left py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                        <th class="text-center py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                        <th class="text-right py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="text-right py-3 text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($order->orderItems as $item)
                        <tr class="border-b border-slate-100 transition">
                            <td class="py-3 font-medium text-slate-700">
                                <span class="flex items-center gap-2">
                                    <span class="w-2 h-2 bg-emerald-400 rounded-full"></span>
                                    {{ $item->product->name }}
                                </span>
                            </td>
                            <td class="text-center font-medium text-slate-600">
                                <span class="bg-slate-100 px-3 py-0.5 rounded-full text-sm">
                                    {{ $item->quantity }}
                                </span>
                            </td>
                            <td class="text-right text-slate-600">
                                ${{ number_format($item->price, 2) }}
                            </td>
                            <td class="text-right font-bold text-emerald-600">
                                ${{ number_format($item->subtotal, 2) }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-8 text-slate-400">
                                <i class="fas fa-shopping-cart text-3xl block mb-2 opacity-30"></i>
                                <p class="font-medium text-slate-500">No items in this order</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- ============================================================ -->
        <!-- DIVIDER -->
        <!-- ============================================================ -->
        <div class="border-t-2 border-dashed border-slate-200 mt-6 pt-4"></div>

        <!-- ============================================================ -->
        <!-- TOTAL -->
        <!-- ============================================================ -->
        <div class="flex justify-between items-center text-2xl font-bold">
            <span class="text-slate-700">Total</span>
            <div class="text-right">
                <span class="text-emerald-600">${{ number_format($order->total_price, 2) }}</span>
                <p class="text-xs font-normal text-slate-400 mt-0.5">Thank you for shopping with us!</p>
            </div>
        </div>

        <!-- ============================================================ -->
        <!-- DIVIDER -->
        <!-- ============================================================ -->
        <div class="border-t-2 border-dashed border-slate-200 mt-4 pt-4"></div>

        <!-- ============================================================ -->
        <!-- FOOTER -->
        <!-- ============================================================ -->
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

        <!-- ============================================================ -->
        <!-- BUTTONS -->
        <!-- ============================================================ -->
        <div class="flex flex-wrap gap-3 mt-6 no-print">
            <button type="button" onclick="window.print()"
                    class="flex-1 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white py-3.5 rounded-xl font-medium transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35">
                <i class="fas fa-print"></i>
                Print Receipt
            </button>

            <a href="{{ route('cashier.pos.index') }}"
               class="flex-1 bg-slate-100 hover:bg-slate-200 text-slate-700 py-3.5 rounded-xl font-medium transition flex items-center justify-center gap-2">
                <i class="fas fa-plus-circle"></i>
                New Sale
            </a>
        </div>

    </div>

</div>

@if(request()->boolean('payment_success'))
    <div id="autoPaymentSuccessPopup"
         class="fixed inset-0 z-[2000] flex items-center justify-center bg-slate-900/50 px-4">
        <div class="w-full max-w-sm rounded-2xl bg-white p-6 text-center shadow-2xl">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-emerald-100">
                <i class="fas fa-check text-3xl text-emerald-600"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800">ទូទាត់ទឹកប្រាក់បានជោគជ័យ</h3>
            <p class="mt-2 text-sm text-slate-500">វិក្កយបត្ររួចរាល់</p>
        </div>
    </div>
@endif

<script>
    window.addEventListener('load', function () {
        setTimeout(function () {
            window.print();
        }, 300);
    });
</script>

<!-- ============================================================ -->
<!-- STYLES -->
<!-- ============================================================ -->
<style>
    /* Print styles */
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

        .rounded-2xl {
            border-radius: 0 !important;
        }

        .bg-white {
            background: white !important;
        }

        .text-emerald-600 {
            color: #059669 !important;
        }
    }

    /* Receipt container animation */
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

    /* Table row hover */
    #receiptBody tr:hover {
        background-color: #f8fafc;
    }

    /* Custom scrollbar */
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

@if(request()->boolean('payment_success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const popup = document.getElementById('autoPaymentSuccessPopup');

            setTimeout(() => {
                popup.classList.add('opacity-0');
                popup.style.transition = 'opacity 0.35s ease';
                setTimeout(() => popup.remove(), 350);
            }, 1800);
        });
    </script>
@endif

@endsection
