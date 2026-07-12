@extends('layouts.admin')

@section('title', 'Purchase Invoice')
@section('page_title', 'Purchase Invoice')

@section('content')

<div class="max-w-4xl mx-auto">

    <!-- Invoice -->
    <div id="invoiceArea" class="bg-white rounded-2xl shadow-sm p-10">

        <!-- Header -->
        <div class="flex flex-wrap justify-between items-start mb-8">
            <div>
                <h1 class="text-3xl font-bold text-slate-800">
                    <i class="fas fa-receipt text-indigo-600 mr-3"></i>PURCHASE INVOICE
                </h1>
                <p class="text-slate-500 mt-1">
                    <i class="fas fa-hashtag mr-1"></i>Invoice #{{ $purchase->id }}
                </p>
            </div>

            <div class="text-right">
                <p class="text-sm font-medium text-slate-600">
                    <i class="fas fa-calendar-alt text-slate-400 mr-1"></i>
                    {{ $purchase->created_at->format('F d, Y') }}
                </p>
                <p class="text-sm text-slate-500">
                    <i class="fas fa-clock text-slate-400 mr-1"></i>
                    {{ $purchase->created_at->format('h:i A') }}
                </p>
                <p class="text-sm font-medium text-slate-700 mt-2">
                    <i class="fas fa-truck text-indigo-400 mr-1"></i>
                    {{ $purchase->supplier?->name ?? 'N/A' }}
                </p>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t-2 border-dashed border-slate-200 mb-6"></div>

        <!-- Items Table -->
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($purchase->purchaseItems as $index => $item)
                    <tr class="border-b border-slate-100">
                        <td class="p-4 text-sm text-slate-400">{{ $loop->iteration }}</td>
                        <td class="p-4 font-medium text-slate-700">{{ $item->product?->name ?? 'N/A' }}</td>
                        <td class="p-4 text-slate-600">${{ number_format($item->price, 2) }}</td>
                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $item->quantity }}
                            </span>
                        </td>
                        <td class="p-4 font-bold text-emerald-600">${{ number_format($item->subtotal, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>

                <tfoot>
                    <tr class="border-t-2 border-slate-200">
                        <th colspan="4" class="text-right p-4 text-sm font-semibold text-slate-600">
                            <i class="fas fa-calculator text-indigo-500 mr-2"></i>Grand Total
                        </th>
                        <th class="p-4 text-2xl font-bold text-emerald-600">
                            ${{ number_format($purchase->total_price, 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t-2 border-dashed border-slate-200 text-center text-sm text-slate-400">
            <p>
                <i class="fas fa-print mr-1"></i>
                Generated on {{ now()->format('F d, Y h:i A') }}
            </p>
            <p class="mt-1">
                <i class="fas fa-check-circle text-emerald-500 mr-1"></i>
                Thank you for your purchase!
            </p>
        </div>

    </div>

    <!-- Buttons -->
    <div class="flex flex-wrap gap-3 mt-6">
        <button onclick="window.print()"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-print"></i>
            Print Invoice
        </button>

        <a href="{{ route('admin.purchases.index') }}"
           class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2">
            <i class="fas fa-arrow-left"></i>
            Back to List
        </a>
    </div>

</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #invoiceArea, #invoiceArea * {
            visibility: visible;
        }
        #invoiceArea {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            padding: 20px;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        .no-print {
            display: none !important;
        }
        #invoiceArea .border-dashed {
            border-color: #e2e8f0 !important;
        }
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
</style>

@endsection