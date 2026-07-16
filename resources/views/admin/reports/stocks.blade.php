@extends('layouts.admin')

@section('title', 'Stock Report')
@section('page_title', 'Stock Report')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-warehouse text-indigo-600 mr-3"></i>Stock Report
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Monitor product inventory and stock levels
            </p>
        </div>

        <a href="{{ route('admin.reports.stocks.export') }}"
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
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Products</p>
                    <h2 class="text-3xl font-bold text-slate-800 mt-1">{{ $products->count() }}</h2>
                </div>
                <div class="w-12 h-12 bg-indigo-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-boxes text-indigo-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-emerald-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Total Stock</p>
                    <h2 class="text-3xl font-bold text-emerald-600 mt-1">{{ $products->sum('stock') }}</h2>
                </div>
                <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-cubes text-emerald-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-purple-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Inventory Value</p>
                    <h2 class="text-3xl font-bold text-purple-600 mt-1">
                        ${{ number_format($products->sum(fn($p) => $p->stock * $p->price), 2) }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-dollar-sign text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-6 border-l-4 border-rose-500">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-sm text-slate-400 font-medium uppercase tracking-wider">Low Stock Items</p>
                    <h2 class="text-3xl font-bold text-rose-600 mt-1">
                        {{ $products->where('stock', '<=', 5)->count() }}
                    </h2>
                </div>
                <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-rose-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       id="searchStock"
                       placeholder="Search products..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            </div>
            <select id="stockFilter" class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                <option value="all">All Stock</option>
                <option value="low">Low Stock (≤ 5)</option>
                <option value="medium">Medium Stock (6-20)</option>
                <option value="high">High Stock (> 20)</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-200 flex justify-between items-center">
            <h3 class="text-lg font-bold text-slate-800">
                <i class="fas fa-list text-indigo-500 mr-2"></i>Inventory List
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
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Stock</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Stock Value</th>
                        <th class="p-4 text-left text-xs font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>

                <tbody id="stockTable">
                    @forelse($products as $index => $product)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group" data-stock="{{ $product->stock }}">
                        <td class="p-4 text-sm text-slate-400 font-medium">{{ $loop->iteration }}</td>

                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}"
                                         alt="{{ $product->name }}"
                                         class="w-12 h-12 rounded-xl object-cover border-2 border-slate-200 group-hover:border-indigo-300 transition">
                                @else
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                        <i class="fas fa-box text-slate-400 text-lg"></i>
                                    </div>
                                @endif
                                <div>
                                    <h4 class="font-medium text-slate-800">{{ $product->name }}</h4>
                                    <p class="text-xs text-slate-400">SKU: {{ $product->id }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="p-4">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $product->category?->name ?? '-' }}
                            </span>
                        </td>

                        <td class="p-4 font-semibold text-slate-700">
                            ${{ number_format($product->price, 2) }}
                        </td>

                        <td class="p-4 font-bold text-slate-800">
                            {{ $product->stock }}
                        </td>

                        <td class="p-4 font-semibold text-purple-600">
                            ${{ number_format($product->stock * $product->price, 2) }}
                        </td>

                        <td class="p-4">
                            @if($product->stock <= 5)
                                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Low Stock
                                </span>
                            @elseif($product->stock <= 20)
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-minus-circle mr-1"></i>Medium Stock
                                </span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>In Stock
                                </span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <i class="fas fa-warehouse text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Products Found</p>
                            <p class="text-sm mt-1">Products will appear here</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if(method_exists($products, 'links') && $products->hasPages())
            <div class="p-4 border-t border-slate-200">
                {{ $products->links() }}
            </div>
        @endif
    </div>

</div>

<script>
    // Search functionality
    document.getElementById('searchStock').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#stockTable tr');

        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // Filter functionality
    document.getElementById('stockFilter').addEventListener('change', function() {
        let filter = this.value;
        let rows = document.querySelectorAll('#stockTable tr');

        rows.forEach(row => {
            let stock = parseInt(row.dataset.stock);
            if (isNaN(stock)) return;

            if (filter === 'all') {
                row.style.display = '';
            } else if (filter === 'low' && stock <= 5) {
                row.style.display = '';
            } else if (filter === 'medium' && stock > 5 && stock <= 20) {
                row.style.display = '';
            } else if (filter === 'high' && stock > 20) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
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
