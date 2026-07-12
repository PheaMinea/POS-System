@extends('layouts.admin')

@section('title', 'Purchase Report')
@section('page_title', 'Purchase Report')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-cart-plus text-indigo-600 mr-3"></i>Purchase Report
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Monitor supplier purchases and inventory expenses
            </p>
        </div>

        <a href="{{ route('admin.reports.purchases.export') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-file-word"></i>
            Export to Word
        </a>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-indigo-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Purchases</p>
                    <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $purchases->count() }}</h2>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cart-plus text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-rose-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Cost</p>
                    <h2 class="text-3xl font-bold text-rose-600 mt-1">
                        ${{ number_format($purchases->sum('total_price'), 2) }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Today Purchases</p>
                    <h2 class="text-3xl font-bold text-purple-600 mt-1">
                        ${{ number_format(
                            $purchases->where('created_at', '>=', now()->startOfDay())->sum('total_price'),
                            2
                        ) }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-day text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-amber-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">This Month</p>
                    <h2 class="text-3xl font-bold text-amber-600 mt-1">
                        ${{ number_format(
                            $purchases->where('created_at', '>=', now()->startOfMonth())->sum('total_price'),
                            2
                        ) }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-calendar-alt text-amber-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       id="searchPurchase"
                       placeholder="Search by supplier or invoice..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            </div>
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-list text-indigo-500 mr-2"></i>Purchase Transactions
            </h3>
            <span class="text-sm text-slate-400">
                <i class="fas fa-clock mr-1"></i>
                {{ now()->format('F d, Y h:i A') }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Supplier</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Cost</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>

                <tbody id="purchaseTable">
                    @forelse($purchases as $index => $purchase)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">{{ $loop->iteration }}</td>

                        <td class="p-4">
                            <span class="font-bold text-slate-800">
                                <i class="fas fa-receipt text-indigo-400 mr-2"></i>#{{ $purchase->id }}
                            </span>
                        </td>

                        <td class="p-4 supplier-name">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-truck text-indigo-400"></i>
                                <span class="font-medium text-slate-700">{{ $purchase->supplier?->name ?? 'N/A' }}</span>
                            </div>
                        </td>

                        <td class="p-4 font-bold text-rose-600">
                            ${{ number_format($purchase->total_price, 2) }}
                        </td>

                        <td class="p-4 text-sm text-slate-500">
                            <i class="fas fa-calendar-alt text-slate-400 mr-2"></i>
                            {{ $purchase->created_at->format('M d, Y') }}
                            <span class="text-xs text-slate-400 block">{{ $purchase->created_at->format('h:i A') }}</span>
                        </td>

                        <td class="p-4">
                            <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Completed
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-16 text-slate-400">
                            <i class="fas fa-cart-plus text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Purchases Found</p>
                            <p class="text-sm mt-1">Purchase transactions will appear here</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($purchases, 'links') && $purchases->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $purchases->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    // Search functionality
    document.getElementById('searchPurchase').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#purchaseTable tr');

        rows.forEach(row => {
            let supplier = row.querySelector('.supplier-name');
            if (!supplier) return;
            let text = supplier.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });
</script>

<style>
    @media print {
        .no-print { display: none !important; }
        body { background: white !important; }
        .bg-white { box-shadow: none !important; border: 1px solid #e2e8f0 !important; }
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