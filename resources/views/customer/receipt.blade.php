@extends('layouts.app')

@section('title', 'Receipt #'.$order->id.' - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- RECEIPT HEADER -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white print:hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                    <i class="fas fa-receipt"></i>
                    Receipt #{{ $order->id }}
                </h1>
                <p class="text-blue-100/90 mt-1">
                    <i class="fas fa-circle text-[6px] mr-2 align-middle"></i>
                    Invoice #{{ $order->payment?->reference_no ?? 'INV-'.str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                </p>
            </div>
            <div class="flex gap-2">
                <button onclick="window.print()"
                        class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                    <i class="fas fa-print"></i>
                    Print
                </button>
                <a href="{{ route('customer.orders.show', $order->id) }}"
                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                    <i class="fas fa-arrow-left"></i>
                    Back to Order
                </a>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- RECEIPT CONTENT -->
<!-- ============================================================ -->
<section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden" id="receipt-content">

        <!-- Receipt Header -->
        <div class="p-8 border-b border-slate-200 text-center">
            <div class="flex items-center justify-center gap-3 mb-4">
                <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <i class="fas fa-utensils text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-slate-800">
                    Food<span class="text-blue-600">Restaurant</span>
                </span>
            </div>
            <h2 class="text-lg font-semibold text-slate-600">Payment Receipt</h2>
            <p class="text-sm text-slate-400 mt-1">
                <i class="fas fa-calendar-alt mr-1"></i>
                {{ $order->created_at->format('l, F d, Y - h:i A') }}
            </p>
        </div>

        <!-- Invoice Info -->
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Invoice Number</p>
                    <p class="text-lg font-bold text-slate-800 mt-1">
                        {{ $order->payment?->reference_no ?? 'INV-'.str_pad($order->id, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Customer Name</p>
                    <p class="text-lg font-bold text-slate-800 mt-1">{{ $order->customer?->name ?? 'N/A' }}</p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Payment Method</p>
                    <p class="text-base font-medium text-slate-700 mt-1">
                        @if($order->payment?->payment_method === 'bakong')
                            <i class="fas fa-qrcode text-blue-500 mr-1"></i>Bakong KHQR
                        @else
                            <i class="fas fa-money-bill-wave text-emerald-500 mr-1"></i>Cash on Delivery
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Payment Status</p>
                    <p class="text-base font-medium mt-1 {{ $order->payment?->status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                        <i class="fas {{ $order->payment?->status === 'paid' ? 'fa-check-circle' : 'fa-clock' }} mr-1"></i>
                        {{ ucfirst($order->payment?->status ?? 'Pending') }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6">
            <h3 class="text-sm font-semibold text-slate-400 uppercase tracking-wider mb-4">Order Items</h3>

            <table class="w-full">
                <thead>
                    <tr class="border-b border-slate-200">
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="pb-3 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Item</th>
                        <th class="pb-3 text-center text-xs font-semibold text-slate-400 uppercase tracking-wider">Qty</th>
                        <th class="pb-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="pb-3 text-right text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr class="border-b border-slate-100">
                        <td class="py-3 text-sm text-slate-400">{{ $loop->iteration }}</td>
                        <td class="py-3 font-medium text-slate-700">
                            {{ $item->product?->name ?? 'Product #'.$item->product_id }}
                        </td>
                        <td class="py-3 text-center text-slate-600">{{ $item->quantity }}</td>
                        <td class="py-3 text-right text-slate-600">${{ number_format($item->price, 2) }}</td>
                        <td class="py-3 text-right font-bold text-blue-600">${{ number_format($item->quantity * $item->price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="p-6 border-t border-slate-200 bg-slate-50/50">
            <div class="flex justify-end">
                <div class="w-64 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span class="font-medium text-slate-700">${{ number_format($order->total_price, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Delivery Fee</span>
                        <span class="font-medium text-slate-700">$2.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tax (10%)</span>
                        <span class="font-medium text-slate-700">${{ number_format($order->total_price * 0.10, 2) }}</span>
                    </div>
                    <div class="border-t border-slate-200 pt-2 flex justify-between">
                        <span class="text-lg font-bold text-slate-800">Total Amount</span>
                        <span class="text-lg font-bold text-blue-600">${{ number_format($order->total_price + 2.50 + ($order->total_price * 0.10), 2) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="p-6 text-center border-t border-slate-200">
            <div class="flex items-center justify-center gap-2 text-sm text-slate-400 mb-2">
                <i class="fas fa-check-circle text-emerald-500"></i>
                <span>Order #{{ $order->id }} - {{ $order->status === 'completed' ? 'Completed' : ucfirst($order->status) }}</span>
            </div>
            <p class="text-xs text-slate-400">
                <i class="fas fa-utensils mr-1"></i>
                Thank you for dining with FoodRestaurant!
            </p>
            <div class="mt-3 flex items-center justify-center gap-4 text-xs text-slate-400">
                <span><i class="fas fa-phone mr-1"></i>+855 12 345 678</span>
                <span><i class="fas fa-envelope mr-1"></i>info@foodrestaurant.com</span>
            </div>
        </div>

    </div>

    <!-- Download Button -->
    <div class="text-center mt-6 print:hidden">
        <button onclick="downloadReceipt()"
                class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3.5 rounded-xl font-semibold transition inline-flex items-center gap-2 shadow-lg shadow-blue-500/25">
            <i class="fas fa-download"></i>
            Download Receipt
        </button>
    </div>
</section>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script>
    function downloadReceipt() {
        const content = document.getElementById('receipt-content');
        const clone = content.cloneNode(true);
        
        // Create a new window for printing
        const win = window.open('', '_blank');
        win.document.write(`
            <!DOCTYPE html>
            <html>
            <head>
                <title>Receipt #{{ $order->id }}</title>
                <script src="https://cdn.tailwindcss.com">
                <\/script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
                <style>
                    body { 
                        font-family: 'Inter', sans-serif;
                        padding: 2rem;
                        max-width: 800px;
                        margin: 0 auto;
                    }
                    @media print {
                        body { padding: 0; }
                    }
                </style>
            </head>
            <body>
                ${clone.outerHTML}
                <script>
                    window.onload = function() {
                        window.print();
                        setTimeout(function() { window.close(); }, 1000);
                    }
                <\/script>
            </body>
            </html>
        `);
        win.document.close();
    }

    console.log('🧾 Receipt page loaded');
</script>

<!-- ============================================================ -->
<!-- PRINT STYLES -->
<!-- ============================================================ -->
<style>
    @media print {
        body {
            background: white !important;
        }
        .print\:hidden {
            display: none !important;
        }
        #receipt-content {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }
    }

    /* Receipt hover */
    #receipt-content {
        transition: box-shadow 0.3s ease;
    }
    #receipt-content:hover {
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 4px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
</style>

@endsection