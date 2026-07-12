@extends('layouts.app')

@section('title', 'Home - Food Restaurant')
@section('content')


<!-- ============================================================ -->
<!-- HERO SECTION - Blue Professional -->
<!-- ============================================================ -->
<section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white">
    
    <!-- Decorative Elements -->
    <div class="absolute top-20 right-20 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 left-20 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24 relative z-10">

        <div class="grid md:grid-cols-2 gap-12 items-center">

            <!-- Left -->
            <div class="fade-in-up">
                <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <i class="fas fa-circle text-[6px] text-white/70"></i>
                    <span>Fresh & Delicious</span>
                    <i class="fas fa-circle text-[6px] text-white/70"></i>
                </div>

                <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight">
                    Delicious Food
                    <span class="text-blue-200">Delivered Fast</span> 🚀
                </h1>

                <p class="mt-6 text-lg text-blue-100/90 leading-relaxed max-w-lg">
                    Order your favorite meals online and enjoy fresh, hot food delivered right to your door. Taste the difference!
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('customer.menu') }}"
                       class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3.5 rounded-xl font-semibold transition flex items-center gap-2 shadow-lg shadow-blue-500/30 hover:shadow-blue-500/40">
                        <i class="fas fa-utensils"></i>
                        Order Now
                        <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                    <a href="#categories"
                       class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-3.5 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                        <i class="fas fa-chevron-down"></i>
                        Explore Menu
                    </a>
                </div>

                <!-- Stats -->
                <div class="flex gap-8 mt-10 pt-8 border-t border-white/20">
                    <div>
                        <p class="text-3xl font-bold">500+</p>
                        <p class="text-sm text-blue-100/80">Happy Customers</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">100+</p>
                        <p class="text-sm text-blue-100/80">Food Items</p>
                    </div>
                    <div>
                        <p class="text-3xl font-bold">4.9⭐</p>
                        <p class="text-sm text-blue-100/80">Customer Rating</p>
                    </div>
                </div>
            </div>

            <!-- Right -->
            <div class="relative flex justify-center md:justify-end">
                <div class="relative">
                    <img src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop"
                         alt="Delicious Food"
                         class="rounded-3xl shadow-2xl object-cover w-full max-w-md h-80 md:h-96">
                    <div class="absolute -bottom-6 -right-6 bg-white rounded-2xl p-4 shadow-xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-star text-blue-500 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800">4.9/5</p>
                                <p class="text-xs text-slate-400">2.5k+ reviews</p>
                            </div>
                        </div>
                    </div>
                    <div class="absolute -top-4 -left-4 bg-white rounded-2xl p-3 shadow-xl">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-clock text-blue-500"></i>
                            <span class="text-sm font-medium text-slate-700">30-45 min</span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>

<!-- ============================================================ -->
<!-- CATEGORIES SECTION -->
<!-- ============================================================ -->
<section id="categories" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">

    <div class="text-center mb-12">
        <span class="text-sm font-semibold text-red-600 uppercase tracking-wider">Categories</span>
        <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mt-2">
            Explore Our <span class="text-red-600">Food Categories</span>
        </h2>
        <p class="text-slate-400 mt-2 max-w-md mx-auto">Discover delicious meals from our diverse food categories</p>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">

        @forelse($categories as $category)
            <a href="{{ route('customer.menu', ['category' => $category->id]) }}"
               class="category-card bg-white rounded-2xl p-6 text-center shadow-sm hover:shadow-md transition group">
                <div class="w-16 h-16 bg-gradient-to-br from-red-100 to-red-200 rounded-full mx-auto flex items-center justify-center category-icon group-hover:scale-110 transition">
                    <i class="fas fa-utensils text-red-500 text-2xl"></i>
                </div>
                <h3 class="mt-4 font-semibold text-slate-800 group-hover:text-red-600 transition">
                    {{ $category->name }}
                </h3>
                <p class="text-xs text-slate-400 mt-1">{{ $category->products_count ?? 0 }} items</p>
            </a>
        @empty
            <div class="col-span-full text-center py-12 text-slate-400">
                <i class="fas fa-folder-open text-4xl block mb-3 opacity-30"></i>
                <p class="text-lg font-medium text-slate-500">No Categories Found</p>
            </div>
        @endforelse

    </div>

</section>

<!-- ============================================================ -->
<!-- POPULAR FOODS SECTION -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16 md:pb-20">

    <div class="flex flex-wrap justify-between items-center mb-12">
        <div>
            <span class="text-sm font-semibold text-red-600 uppercase tracking-wider">Popular</span>
            <h2 class="text-3xl md:text-4xl font-bold text-slate-800 mt-2">
                Our <span class="text-red-600">Popular Foods</span>
            </h2>
        </div>
        <a href="{{ route('customer.menu') }}"
           class="text-red-600 hover:text-red-700 font-medium transition flex items-center gap-1">
            View All <i class="fas fa-arrow-right text-sm"></i>
        </a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">

        @forelse($products as $product)
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group">

                <!-- Image -->
                <div class="relative overflow-hidden bg-slate-100">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="product-image w-full h-52 object-cover">
                    @else
                        <div class="w-full h-52 bg-gradient-to-br from-red-100 to-red-200 flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-red-300/50"></i>
                        </div>
                    @endif

                    <!-- Badge -->
                    <div class="absolute top-3 left-3 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full">
                        <i class="fas fa-fire mr-1"></i> Popular
                    </div>

                    <!-- Quick Add -->
                    <button class="add-to-cart absolute bottom-3 right-3 bg-white rounded-full p-3 shadow-lg hover:bg-red-600 hover:text-white transition">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-semibold text-slate-800 text-lg group-hover:text-red-600 transition">
                                {{ $product->name }}
                            </h3>
                            <p class="text-xs text-slate-400 mt-1">
                                <i class="fas fa-tag mr-1"></i>
                                {{ $product->category?->name ?? 'Uncategorized' }}
                            </p>
                        </div>
                        <span class="text-xs bg-red-50 text-red-600 px-2 py-1 rounded-full font-medium">
                            ★ 4.8
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <span class="text-xl font-bold text-red-600">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        <button class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-1.5">
                            <i class="fas fa-cart-plus"></i>
                            Add
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-12 text-center">
                <i class="fas fa-box-open text-5xl block mb-4 text-slate-300"></i>
                <p class="text-lg font-medium text-slate-600">No Products Found</p>
                <p class="text-sm text-slate-400 mt-1">Please add products from the admin dashboard</p>
            </div>
        @endforelse

    </div>

</section>

<!-- ============================================================ -->
<!-- CTA SECTION - Blue Professional -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-3xl p-8 md:p-12 text-white relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
            <div>
                <h3 class="text-2xl md:text-3xl font-bold">Hungry? Order Now!</h3>
                <p class="text-blue-100/80 mt-2">Get your favorite meals delivered to your door</p>
            </div>
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('customer.menu') }}"
                   class="bg-white text-blue-600 hover:bg-blue-50 px-8 py-3.5 rounded-xl font-semibold transition shadow-lg shadow-blue-500/30 flex items-center gap-2">
                    <i class="fas fa-utensils"></i>
                    Order Now
                </a>
                <a href="#"
                   class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-8 py-3.5 rounded-xl font-medium transition border border-white/20 flex items-center gap-2">
                    <i class="fas fa-phone"></i>
                    Call Us
                </a>
            </div>
        </div>

    </div>
</section>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Fade In Animation */
    .fade-in-up {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Category Card */
    .category-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .category-card:hover {
        transform: translateY(-6px);
        border-color: #dc2626;
    }

    /* Product Card */
    .product-card {
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .product-card:hover {
        transform: translateY(-8px);
        border-color: #dc2626;
    }

    .product-card .product-image {
        transition: transform 0.5s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-card .add-to-cart {
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(10px);
    }

    .product-card:hover .add-to-cart {
        opacity: 1;
        transform: translateY(0);
    }

    .product-card .add-to-cart:hover {
        background: #dc2626 !important;
        color: white !important;
    }
</style>

@endsection