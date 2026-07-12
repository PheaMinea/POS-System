@extends('layouts.app')

@section('title', 'Menu - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- MENU HEADER - Blue Professional -->
<!-- ============================================================ -->
<section class="relative overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 text-white">
    <div class="absolute top-20 right-20 w-64 h-64 bg-white/5 rounded-full blur-2xl"></div>
    <div class="absolute bottom-20 left-20 w-48 h-48 bg-white/5 rounded-full blur-2xl"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20 relative z-10">
        <div class="text-center">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full text-sm font-medium mb-6">
                <i class="fas fa-utensils"></i>
                <span>Our Menu</span>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-extrabold">
                Delicious <span class="text-blue-200">Food</span> Selection
            </h1>
            <p class="mt-4 text-blue-100/90 text-lg max-w-2xl mx-auto">
                Choose your favorite food from our delicious selection of freshly prepared meals
            </p>
            <div class="mt-8 flex flex-wrap justify-center gap-3">
                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10 hover:bg-white/30 transition">
                    <i class="fas fa-fire mr-1.5 text-blue-200"></i>Popular
                </span>
                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10 hover:bg-white/30 transition">
                    <i class="fas fa-leaf mr-1.5 text-blue-200"></i>Fresh
                </span>
                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10 hover:bg-white/30 transition">
                    <i class="fas fa-clock mr-1.5 text-blue-200"></i>Fast Delivery
                </span>
                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10 hover:bg-white/30 transition">
                    <i class="fas fa-heart mr-1.5 text-blue-200"></i>Customer Favorite
                </span>
            </div>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- FILTERS - Clean White -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">
    <div class="bg-white rounded-2xl shadow-xl p-4 md:p-6 border border-slate-100">
        <div class="flex flex-wrap items-center gap-4">
            <div class="flex-1 min-w-[200px] relative">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       id="searchProduct"
                       placeholder="Search food..."
                       class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-slate-50/50 hover:bg-white text-slate-800">
            </div>

            <select id="categoryFilter"
                    class="border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-white text-slate-700 appearance-none cursor-pointer hover:border-blue-300">
                <option value="all">All Categories</option>
                @foreach($categories ?? [] as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            <select id="sortFilter"
                    class="border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-white text-slate-700 appearance-none cursor-pointer hover:border-blue-300">
                <option value="default">Sort by</option>
                <option value="price_low">Price: Low to High</option>
                <option value="price_high">Price: High to Low</option>
                <option value="name_asc">Name: A to Z</option>
                <option value="name_desc">Name: Z to A</option>
            </select>

            <span class="text-sm text-slate-500 bg-slate-50 px-4 py-2 rounded-full border border-slate-200" id="productCount">
                <i class="fas fa-circle text-[6px] text-blue-500 mr-1.5 align-middle"></i>
                {{ $products->count() }} items
            </span>

            <button id="resetFilters"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium transition flex items-center gap-1.5 px-3 py-2 hover:bg-blue-50 rounded-xl">
                <i class="fas fa-rotate-right"></i>
                Reset
            </button>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- PRODUCTS GRID -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <!-- Results Count -->
    <div class="flex justify-between items-center mb-6">
        <p class="text-sm text-slate-500" id="resultsCount">
            Showing <span id="visibleCount" class="text-slate-800 font-semibold">{{ $products->count() }}</span> of {{ $products->count() }} products
        </p>
        <div class="flex items-center gap-2 text-xs text-slate-400">
            <i class="fas fa-circle text-[4px] text-emerald-500"></i>
            <span>Fresh & Delicious</span>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="productGrid">

        @forelse($products as $product)
            <div class="product-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group border border-slate-100"
                 data-id="{{ $product->id }}"
                 data-name="{{ $product->name }}"
                 data-price="{{ $product->price }}"
                 data-category="{{ $product->category_id }}">

                <!-- Image -->
                <div class="relative overflow-hidden bg-slate-100">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}"
                             alt="{{ $product->name }}"
                             class="product-image w-full h-52 object-cover group-hover:scale-105 transition duration-500">
                    @else
                        <div class="w-full h-52 bg-gradient-to-br from-blue-100 to-blue-200 flex flex-col items-center justify-center">
                            <i class="fas fa-image text-5xl text-blue-300/50"></i>
                            <span class="text-sm text-blue-300/70 mt-2">No Image</span>
                        </div>
                    @endif

                    <!-- Badges -->
                    <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                        @if($product->is_featured ?? false)
                            <span class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                <i class="fas fa-star mr-1"></i>Featured
                            </span>
                        @endif
                        @if($product->is_new ?? false)
                            <span class="bg-emerald-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                <i class="fas fa-sparkles mr-1"></i>New
                            </span>
                        @endif
                        @if($product->discount ?? false)
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-lg">
                                <i class="fas fa-tag mr-1"></i>-{{ $product->discount }}%
                            </span>
                        @endif
                    </div>

                    <!-- Category Badge -->
                    <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full">
                        <i class="fas fa-tag mr-1"></i>
                        {{ $product->category?->name ?? 'Uncategorized' }}
                    </div>

                    <!-- Quick Add Button -->
                    <button class="add-to-cart-btn absolute bottom-3 right-3 bg-white rounded-full p-3 shadow-lg hover:bg-blue-600 hover:text-white transition transform hover:scale-110"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>

                <!-- Content -->
                <div class="p-5">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-slate-800 text-lg group-hover:text-blue-600 transition line-clamp-1">
                                {{ $product->name }}
                            </h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fas fa-clock"></i>15-20 min
                                </span>
                                <span class="text-xs text-slate-300">•</span>
                                <span class="text-xs text-slate-400 flex items-center gap-1">
                                    <i class="fas fa-utensils"></i>{{ $product->category?->name ?? 'Uncategorized' }}
                                </span>
                            </div>
                        </div>
                        <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium flex items-center gap-1 flex-shrink-0 ml-2">
                            <i class="fas fa-star text-[10px]"></i> 4.8
                        </span>
                    </div>

                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">
                        <div>
                            <span class="text-xl font-bold text-blue-600">
                                ${{ number_format($product->price, 2) }}
                            </span>
                            @if($product->old_price ?? false)
                                <span class="text-sm text-slate-400 line-through ml-2">
                                    ${{ number_format($product->old_price, 2) }}
                                </span>
                            @endif
                        </div>
                        <button class="add-to-cart-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-1.5 shadow-sm hover:shadow-md"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}"
                                data-product-price="{{ $product->price }}">
                            <i class="fas fa-cart-plus"></i>
                            Add
                        </button>
                    </div>
                </div>

            </div>
        @empty
            <div class="col-span-full bg-white rounded-2xl p-16 text-center shadow-sm border border-slate-100">
                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-box-open text-4xl text-blue-400"></i>
                </div>
                <p class="text-xl font-medium text-slate-600">No Products Found</p>
                <p class="text-sm text-slate-400 mt-2 max-w-md mx-auto">
                    Please add products from the admin dashboard or check back later
                </p>
                <a href="{{ route('customer.home') }}"
                   class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl transition shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Home
                </a>
            </div>
        @endforelse

    </div>

    <!-- Load More -->
    @if($products->count() > 8)
        <div class="text-center mt-10">
            <button id="loadMoreBtn"
                    class="bg-white hover:bg-slate-50 text-slate-700 px-8 py-3.5 rounded-xl font-medium transition border border-slate-200 shadow-sm hover:shadow-md flex items-center gap-2 mx-auto">
                <i class="fas fa-chevron-down"></i>
                Load More Products
            </button>
        </div>
    @endif
</section>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script>
    const CART_STORAGE_KEY = 'shared_cart';

    function getCart() {
        try {
            const data = localStorage.getItem(CART_STORAGE_KEY) || localStorage.getItem('cart');
            return data ? JSON.parse(data) : [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(cart) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        localStorage.setItem('cart', JSON.stringify(cart));
        localStorage.setItem('receipt', JSON.stringify(cart));

        // Update cart count
        const cartCount = cart.reduce((sum, item) => sum + (item.qty || 0), 0);
        const cartBadges = document.querySelectorAll('#cartCount');
        cartBadges.forEach(badge => {
            badge.textContent = cartCount;
        });
    }

    function addToCart(id, name, price) {
        let cart = getCart();
        let item = cart.find(i => Number(i.id) === Number(id));

        if (item) {
            item.qty += 1;
        } else {
            cart.push({
                id: Number(id),
                name: name,
                price: Number(price),
                qty: 1
            });
        }

        saveCart(cart);

        // Show success toast
        showToast('✅ ' + name + ' added to cart!');

        // Update button state
        document.querySelectorAll(`[data-product-id="${id}"]`).forEach(btn => {
            btn.innerHTML = '<i class="fas fa-check mr-1"></i> Added';
            btn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            btn.classList.add('bg-emerald-500', 'hover:bg-emerald-600');
        });
    }

    function showToast(message) {
        let existing = document.getElementById('cart-toast');
        if (existing) existing.remove();

        let toast = document.createElement('div');
        toast.id = 'cart-toast';
        toast.innerHTML = `
            <div class="fixed top-20 right-4 bg-emerald-500 text-white px-6 py-3 rounded-xl shadow-2xl z-[9999] flex items-center gap-3 animate-[slideIn_0.5s_ease-out]">
                <i class="fas fa-check-circle text-xl"></i>
                <span class="font-medium">${message}</span>
                <button onclick="this.parentElement.remove()" class="text-white/70 hover:text-white transition">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        document.body.appendChild(toast);

        setTimeout(function() {
            let el = document.getElementById('cart-toast');
            if (el) {
                el.style.transition = 'opacity 0.3s, transform 0.3s';
                el.style.opacity = '0';
                el.style.transform = 'translateX(20px)';
                setTimeout(() => el.remove(), 300);
            }
        }, 3000);
    }

    // ===== DOM READY =====
    document.addEventListener('DOMContentLoaded', function() {
        // Add to Cart
        document.querySelectorAll('.add-to-cart-btn').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.stopPropagation();
                addToCart(
                    button.dataset.productId,
                    button.dataset.productName,
                    button.dataset.productPrice
                );
            });
        });

        // Search
        document.getElementById('searchProduct')?.addEventListener('keyup', function() {
            const keyword = this.value.toLowerCase();
            const cards = document.querySelectorAll('.product-card');
            let visible = 0;

            cards.forEach(card => {
                const name = card.dataset.name.toLowerCase();
                if (name.includes(keyword)) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('productCount').innerHTML = `
                <i class="fas fa-circle text-[6px] text-blue-500 mr-1.5 align-middle"></i>
                ${visible} items
            `;
            document.getElementById('visibleCount').textContent = visible;
            document.getElementById('resultsCount').innerHTML = `
                Showing <span id="visibleCount">${visible}</span> of ${document.querySelectorAll('.product-card').length} products
            `;
        });

        // Category Filter
        document.getElementById('categoryFilter')?.addEventListener('change', function() {
            const categoryId = this.value;
            const cards = document.querySelectorAll('.product-card');
            let visible = 0;

            cards.forEach(card => {
                if (categoryId === 'all' || card.dataset.category === categoryId) {
                    card.style.display = '';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });

            document.getElementById('productCount').innerHTML = `
                <i class="fas fa-circle text-[6px] text-blue-500 mr-1.5 align-middle"></i>
                ${visible} items
            `;
            document.getElementById('visibleCount').textContent = visible;
        });

        // Sort
        document.getElementById('sortFilter')?.addEventListener('change', function() {
            const sortValue = this.value;
            const grid = document.getElementById('productGrid');
            const cards = Array.from(grid.querySelectorAll('.product-card'));

            cards.sort((a, b) => {
                const nameA = a.dataset.name.toLowerCase();
                const nameB = b.dataset.name.toLowerCase();
                const priceA = parseFloat(a.dataset.price);
                const priceB = parseFloat(b.dataset.price);

                switch(sortValue) {
                    case 'price_low': return priceA - priceB;
                    case 'price_high': return priceB - priceA;
                    case 'name_asc': return nameA.localeCompare(nameB);
                    case 'name_desc': return nameB.localeCompare(nameA);
                    default: return 0;
                }
            });

            cards.forEach(card => grid.appendChild(card));
        });

        // Load More
        document.getElementById('loadMoreBtn')?.addEventListener('click', function() {
            const hidden = document.querySelectorAll('.product-card[style*="display: none"]');
            if (hidden.length > 0) {
                hidden.forEach((card, index) => {
                    if (index < 4) card.style.display = '';
                });
            }
            if (document.querySelectorAll('.product-card[style*="display: none"]').length === 0) {
                this.style.display = 'none';
            }
        });

        // Reset Filters
        document.getElementById('resetFilters')?.addEventListener('click', function() {
            document.getElementById('searchProduct').value = '';
            document.getElementById('categoryFilter').value = 'all';
            document.getElementById('sortFilter').value = 'default';

            document.querySelectorAll('.product-card').forEach(card => {
                card.style.display = '';
            });

            document.getElementById('productCount').innerHTML = `
                <i class="fas fa-circle text-[6px] text-blue-500 mr-1.5 align-middle"></i>
                ${document.querySelectorAll('.product-card').length} items
            `;
            document.getElementById('visibleCount').textContent = document.querySelectorAll('.product-card').length;
        });
    });

    // Add animation style
    const style = document.createElement('style');
    style.textContent = `
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .product-card {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .product-card:nth-child(1) { animation-delay: 0.05s; }
        .product-card:nth-child(2) { animation-delay: 0.1s; }
        .product-card:nth-child(3) { animation-delay: 0.15s; }
        .product-card:nth-child(4) { animation-delay: 0.2s; }
        .product-card:nth-child(5) { animation-delay: 0.25s; }
        .product-card:nth-child(6) { animation-delay: 0.3s; }
        .product-card:nth-child(7) { animation-delay: 0.35s; }
        .product-card:nth-child(8) { animation-delay: 0.4s; }
    `;
    document.head.appendChild(style);
</script>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Product Card */
    .product-card {
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid rgba(226, 232, 240, 0.6);
    }

    .product-card:hover {
        transform: translateY(-8px);
        border-color: #2563eb;
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.1);
    }

    .product-card .product-image {
        transition: transform 0.5s ease;
    }

    /* Quick Add Button */
    .add-to-cart-btn {
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .add-to-cart-btn:active {
        transform: scale(0.95);
    }

    /* Line clamp */
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Select dropdown styling */
    select {
        appearance: none;
        -webkit-appearance: none;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 8L1 3h10z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        cursor: pointer;
    }

    /* Scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .product-card .product-image {
            height: 180px;
        }
        .product-card .p-5 {
            padding: 1rem;
        }
    }

    /* Toast close button */
    #cart-toast .fa-times {
        cursor: pointer;
        padding: 4px;
    }
    #cart-toast .fa-times:hover {
        opacity: 0.7;
    }
</style>

@endsection