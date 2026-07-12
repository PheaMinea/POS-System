@extends('layouts.admin')

@section('title', 'Products Management')
@section('page_title', 'Products')

@section('content')

<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-wrap justify-between items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                <i class="fas fa-box-open text-indigo-600 mr-3"></i>Products
            </h1>
            <p class="text-slate-500 mt-1">
                <i class="fas fa-info-circle mr-2"></i>Manage your products inventory
            </p>
        </div>

        <a href="{{ route('admin.products.create') }}"
           class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-3 rounded-xl font-medium transition flex items-center gap-2 shadow-sm hover:shadow-md">
            <i class="fas fa-plus-circle"></i>
            Add Product
        </a>
    </div>

    <!-- Stats Summary -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-indigo-600">{{ $products->count() }}</p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Total Products</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-emerald-600">
                {{ $products->where('stock', '>', 10)->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">In Stock</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-amber-600">
                {{ $products->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Low Stock</p>
        </div>
        <div class="bg-white rounded-xl p-4 shadow-sm border border-slate-200">
            <p class="text-2xl font-bold text-rose-600">
                {{ $products->where('stock', 0)->count() }}
            </p>
            <p class="text-xs text-slate-500 font-medium uppercase tracking-wider">Out of Stock</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <div class="flex flex-wrap gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       placeholder="Search products..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
            </div>
            <select class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                <option value="">All Categories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
            <select class="border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition outline-none">
                <option value="">All Status</option>
                <option value="in_stock">In Stock</option>
                <option value="low_stock">Low Stock</option>
                <option value="out_of_stock">Out of Stock</option>
            </select>
        </div>
    </div>

    <!-- Product Table -->
    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">#</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Image</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Name</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Category</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Price</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Stock</th>
                        <th class="text-left p-4 text-xs font-semibold text-slate-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($products as $index => $product)
                    <tr class="border-b border-slate-100 hover:bg-slate-50 transition group">
                        <td class="p-4 text-sm text-slate-400 font-medium">
                            {{ $loop->iteration }}
                        </td>

                        <td class="p-4">
                            @if($product->image)
                                <img src="/storage/{{ $product->image }}"
                                     alt="{{ $product->name }}"
                                     class="w-14 h-14 rounded-xl object-cover border-2 border-slate-200 group-hover:border-indigo-300 transition">
                            @else
                                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-slate-200 to-slate-300 flex items-center justify-center">
                                    <i class="fas fa-image text-slate-400 text-2xl opacity-50"></i>
                                </div>
                            @endif
                        </td>

                        <td class="p-4 font-medium text-slate-700">
                            {{ $product->name }}
                        </td>

                        <td class="p-4">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1 rounded-full text-sm font-medium">
                                {{ $product->category?->name ?? '-' }}
                            </span>
                        </td>

                        <td class="p-4 font-bold text-emerald-600">
                            ${{ number_format($product->price, 2) }}
                        </td>

                        <td class="p-4">
                            @if($product->stock <= 0)
                                <span class="bg-rose-100 text-rose-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-times-circle mr-1"></i>Out of Stock
                                </span>
                            @elseif($product->stock <= 10)
                                <span class="bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-exclamation-triangle mr-1"></i>{{ $product->stock }} Left
                                </span>
                            @else
                                <span class="bg-emerald-100 text-emerald-700 px-3 py-1 rounded-full text-sm font-medium">
                                    <i class="fas fa-check-circle mr-1"></i>{{ $product->stock }} in Stock
                                </span>
                            @endif
                        </td>

                        <td class="p-4">
                            <div class="flex items-center gap-2">
                                <a href="{{ route('admin.products.show', $product) }}"
                                   class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-eye"></i>
                                </a>

                                <a href="{{ route('admin.products.edit', $product) }}"
                                   class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('Are you sure you want to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-rose-500 hover:bg-rose-600 text-white px-3 py-2 rounded-lg transition text-sm font-medium flex items-center gap-1.5">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-16 text-slate-400">
                            <i class="fas fa-box-open text-5xl block mb-4 opacity-20"></i>
                            <p class="text-lg font-medium text-slate-600">No Products Found</p>
                            <p class="text-sm mt-1">Start by adding your first product</p>
                            <a href="{{ route('admin.products.create') }}"
                               class="inline-block mt-4 bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-xl transition">
                                <i class="fas fa-plus-circle mr-2"></i>Add Product
                            </a>
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