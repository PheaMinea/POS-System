@extends('layouts.admin')

@section('title', 'Purchases Management')
@section('page_title', 'Purchases')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-cart-plus text-indigo-600 mr-3"></i>Purchases
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Manage stock purchases
            </p>
        </div>

        <a href="{{ route('admin.purchases.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-plus-circle"></i>
            New Purchase
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-indigo-600">{{ $purchases->count() }}</p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Purchases</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600">
                ${{ number_format($purchases->sum('total_price'), 0) }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Spent</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-amber-600">
                {{ $purchases->where('created_at', '>=', now()->startOfMonth())->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">This Month</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-rose-600">
                {{ $purchases->where('created_at', '>=', now()->startOfWeek())->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">This Week</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       placeholder="Search purchases..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            </div>
            <select class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                <option value="">All Suppliers</option>
                @foreach($suppliers ?? [] as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            <input type="date"
                   class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Invoice</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Supplier</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Total</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Items</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Date</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($purchases as $index => $purchase)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-4">
                            <span class="font-bold text-slate-800">
                                <i class="fas fa-receipt text-indigo-400 mr-2"></i>#{{ $purchase->id }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-truck text-indigo-400"></i>
                                <span class="font-medium text-slate-700">{{ $purchase->supplier?->name ?? 'N/A' }}</span>
                            </div>
                        </td>

                        <td class="p-4 font-bold text-emerald-600">
                            ${{ number_format($purchase->total_price, 2) }}
                        </td>

                        <td class="p-4">
                            <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $purchase->purchaseItems->count() }} items
                            </span>
                        </td>

                        <td class="p-4 text-sm text-slate-500">
                            <i class="fas fa-calendar-alt text-slate-400 mr-2"></i>
                            {{ $purchase->created_at->format('M d, Y') }}
                            <span class="text-xs text-slate-400 block">
                                {{ $purchase->created_at->format('h:i A') }}
                            </span>
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.purchases.show', $purchase) }}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                    <span class="hidden sm:inline">View</span>
                                </a>

                                <a href="{{ route('admin.purchases.invoice', $purchase) }}"
                                   class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-file-invoice"></i>
                                    <span class="hidden sm:inline">Invoice</span>
                                </a>

                                <form action="{{ route('admin.purchases.destroy', $purchase) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this purchase?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                        <i class="fas fa-trash"></i>
                                        <span class="hidden sm:inline">Delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <i class="fas fa-cart-plus text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Purchases Found</p>
                            <p class="text-sm mt-1">Start by creating your first purchase</p>
                            <a href="{{ route('admin.purchases.create') }}"
                               class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl transition">
                                <i class="fas fa-plus-circle mr-2"></i>New Purchase
                            </a>
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