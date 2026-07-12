@extends('layouts.admin')

@section('title', 'Product Detail')
@section('page_title', 'Product Detail')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-2xl shadow-sm overflow-hidden">

        <!-- Header -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white">
            <div class="flex items-center gap-3">
                <i class="fas fa-box-open text-3xl opacity-50"></i>
                <div>
                    <h1 class="text-3xl font-bold">Product Detail</h1>
                    <p class="text-indigo-100 mt-1">
                        <i class="fas fa-info-circle mr-2"></i>View product information
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div id="productDetail" class="p-8">
            <div class="flex flex-col items-center">
                @if($product->image)
                    <div class="relative">
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-48 h-48 rounded-2xl object-cover shadow-lg border-4 border-white">
                        <div class="absolute -bottom-2 -right-2 bg-emerald-500 text-white p-2 rounded-full shadow-lg">
                            <i class="fas fa-check text-sm"></i>
                        </div>
                    </div>
                @else
                    <div class="w-48 h-48 rounded-2xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center shadow-lg border-4 border-white">
                        <i class="fas fa-box-open text-indigo-400 text-6xl opacity-50"></i>
                    </div>
                @endif

                <h2 class="text-3xl font-bold text-slate-800 mt-6">
                    <i class="fas fa-tag text-indigo-500 mr-2"></i>{{ $product->name }}
                </h2>

                <div class="flex flex-wrap items-center gap-3 mt-3">
                    <span class="bg-indigo-50 text-indigo-700 px-4 py-1.5 rounded-full text-sm font-medium">
                        <i class="fas fa-folder-open mr-1"></i>{{ $product->category?->name ?? 'No Category' }}
                    </span>
                    @if($product->stock <= 0)
                        <span class="bg-rose-100 text-rose-700 px-4 py-1.5 rounded-full text-sm font-medium">
                            <i class="fas fa-times-circle mr-1"></i>Out of Stock
                        </span>
                    @elseif($product->stock <= 10)
                        <span class="bg-amber-100 text-amber-700 px-4 py-1.5 rounded-full text-sm font-medium">
                            <i class="fas fa-exclamation-triangle mr-1"></i>Low Stock ({{ $product->stock }})
                        </span>
                    @else
                        <span class="bg-emerald-100 text-emerald-700 px-4 py-1.5 rounded-full text-sm font-medium">
                            <i class="fas fa-check-circle mr-1"></i>In Stock ({{ $product->stock }})
                        </span>
                    @endif
                </div>

                <div class="flex items-center gap-6 mt-4">
                    <div class="text-center">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">Price</p>
                        <p class="text-2xl font-bold text-emerald-600">${{ number_format($product->price, 2) }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">Stock</p>
                        <p class="text-2xl font-bold text-slate-800">{{ $product->stock }}</p>
                    </div>
                    <div class="text-center">
                        <p class="text-slate-400 text-xs uppercase tracking-wider">ID</p>
                        <p class="text-2xl font-bold text-slate-800">#{{ $product->id }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3 mt-8">
                    <a href="{{ route('admin.products.edit', $product) }}"
                       class="bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl transition flex items-center gap-2 shadow-sm hover:shadow-md">
                        <i class="fas fa-edit"></i>
                        Edit Product
                    </a>

                    <a href="{{ route('admin.products.index') }}"
                       class="bg-slate-500 hover:bg-slate-600 text-white px-6 py-3 rounded-xl transition flex items-center gap-2 shadow-sm hover:shadow-md">
                        <i class="fas fa-arrow-left"></i>
                        Back to List
                    </a>
                </div>

                <div class="mt-8 pt-6 border-t border-slate-200 w-full">
                    <div class="flex flex-wrap justify-center gap-6 md:gap-8 text-sm">
                        <div class="text-center">
                            <p class="text-slate-400 text-xs uppercase tracking-wider">
                                <i class="fas fa-calendar-plus mr-1"></i>Created At
                            </p>
                            <p class="font-medium text-slate-700 mt-1">
                                {{ $product->created_at->format('M d, Y') }}
                                <span class="text-slate-400 font-normal text-xs block">
                                    {{ $product->created_at->format('h:i A') }}
                                </span>
                            </p>
                        </div>
                        <div class="text-center">
                            <p class="text-slate-400 text-xs uppercase tracking-wider">
                                <i class="fas fa-calendar-edit mr-1"></i>Last Updated
                            </p>
                            <p class="font-medium text-slate-700 mt-1">
                                {{ $product->updated_at->format('M d, Y') }}
                                <span class="text-slate-400 font-normal text-xs block">
                                    {{ $product->updated_at->format('h:i A') }}
                                </span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

<style>
    .product-image {
        transition: transform 0.3s ease;
    }
    .product-image:hover {
        transform: scale(1.02);
    }

    @media (max-width: 640px) {
        .w-48 {
            width: 120px;
            height: 120px;
        }
        .text-3xl {
            font-size: 1.5rem;
        }
    }
</style>

@endsection