@extends('layouts.app')

@section('title', 'Shopping Cart - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- CART HEADER - Blue Professional -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                    <i class="fas fa-shopping-cart"></i>
                    Shopping Cart
                </h1>
                <p class="text-blue-100/90 mt-1">
                    <i class="fas fa-circle text-[6px] mr-2 align-middle"></i>
                    Review your order before checkout
                </p>
            </div>
            <a href="{{ route('customer.menu') }}"
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                <i class="fas fa-arrow-left"></i>
                Continue Shopping
            </a>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CART CONTENT -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- Cart Items -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 overflow-hidden">

                <!-- Table Header -->
                <div class="hidden md:grid grid-cols-12 gap-4 bg-slate-50 px-6 py-4 border-b border-slate-200">
                    <div class="col-span-5 text-xs font-semibold text-slate-400 uppercase tracking-wider">Product</div>
                    <div class="col-span-3 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">Quantity</div>
                    <div class="col-span-2 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Price</div>
                    <div class="col-span-2 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">Total</div>
                </div>

                <!-- Cart Items List -->
                <div id="cartItems" class="divide-y divide-slate-100">

                    <!-- Empty State -->
                    <div id="emptyCart" class="text-center py-16 px-6">
                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-shopping-cart text-4xl text-blue-400"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-700">Your Cart is Empty</h3>
                        <p class="text-slate-400 mt-2 text-sm">Looks like you haven't added any items yet</p>
                        <a href="{{ route('customer.menu') }}"
                           class="inline-block mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-medium transition shadow-lg shadow-blue-500/25">
                            <i class="fas fa-utensils mr-2"></i>
                            Browse Menu
                        </a>
                    </div>

                    <!-- Cart Items -->
                    <div id="cartItemsList" class="hidden"></div>

                </div>

            </div>

        </div>

        <!-- Cart Summary -->
        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 sticky top-24">

                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">
                    <i class="fas fa-receipt text-blue-500"></i>
                    Order Summary
                </h3>

                <div class="space-y-3 py-4 border-b border-slate-100">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span id="subtotal" class="font-medium text-slate-700">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Delivery Fee</span>
                        <span id="deliveryFee" class="font-medium text-slate-700">$2.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tax (10%)</span>
                        <span id="taxAmount" class="font-medium text-slate-700">$0.00</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4">
                    <span class="text-lg font-bold text-slate-800">Grand Total</span>
                    <span id="grandTotal" class="text-2xl font-bold text-blue-600">$0.00</span>
                </div>

                <div class="mt-6 space-y-3">
                    <a href="{{ route('customer.checkout') }}"
                       class="block w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white py-3.5 rounded-xl font-semibold text-center transition shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35">
                        <i class="fas fa-credit-card mr-2"></i>
                        Proceed to Checkout
                    </a>

                    <button onclick="clearCart()"
                            class="block w-full bg-slate-100 hover:bg-slate-200 text-slate-600 py-2.5 rounded-xl text-sm font-medium transition">
                        <i class="fas fa-trash-alt mr-2"></i>
                        Clear Cart
                    </button>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <p class="text-xs text-slate-400 text-center">
                        <i class="fas fa-lock mr-1.5"></i>
                        Secure checkout • Free delivery on orders over $50
                    </p>
                </div>

            </div>

        </div>

    </div>

</section>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script>
    const CART_STORAGE_KEY = 'shared_cart';

    let cart = [];

    function getCart() {
        try {
            const data = localStorage.getItem(CART_STORAGE_KEY) || localStorage.getItem('cart');
            return data ? JSON.parse(data) : [];
        } catch (e) {
            return [];
        }
    }

    function saveCart(items) {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(items));
        localStorage.setItem('cart', JSON.stringify(items));
        localStorage.setItem('receipt', JSON.stringify(items));

        // Update cart count
        const cartCount = items.reduce((sum, item) => sum + (item.qty || 0), 0);
        document.querySelectorAll('#cartCount').forEach(el => el.textContent = cartCount);
    }

    function renderCart() {
        cart = getCart();

        const emptyCart = document.getElementById('emptyCart');
        const cartItemsList = document.getElementById('cartItemsList');
        const subtotalEl = document.getElementById('subtotal');
        const taxEl = document.getElementById('taxAmount');
        const grandTotalEl = document.getElementById('grandTotal');

        if (cart.length === 0) {
            emptyCart.classList.remove('hidden');
            cartItemsList.classList.add('hidden');
            subtotalEl.textContent = '$0.00';
            taxEl.textContent = '$0.00';
            grandTotalEl.textContent = '$0.00';
            document.getElementById('deliveryFee').textContent = '$0.00';
            return;
        }

        emptyCart.classList.add('hidden');
        cartItemsList.classList.remove('hidden');

        let html = '';
        let subtotal = 0;

        cart.forEach((item, index) => {
            const itemTotal = (item.qty || 0) * (item.price || 0);
            subtotal += itemTotal;

            html += `
                <div class="flex flex-wrap items-center gap-4 p-4 hover:bg-slate-50 transition group">
                    <!-- Product Info -->
                    <div class="flex-1 min-w-[120px]">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <div>
                                <p class="font-medium text-slate-800 text-sm">${item.name || 'Product'}</p>
                                <p class="text-xs text-slate-400">Item #${item.id || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="flex items-center gap-2">
                        <button onclick="updateQuantity(${index}, -1)"
                                class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 font-bold transition">
                            −
                        </button>
                        <span class="w-8 text-center font-bold text-slate-700 text-sm">${item.qty || 0}</span>
                        <button onclick="updateQuantity(${index}, 1)"
                                class="w-8 h-8 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 font-bold transition">
                            +
                        </button>
                    </div>

                    <!-- Price -->
                    <div class="w-20 text-right">
                        <span class="font-medium text-slate-700 text-sm">$${(item.price || 0).toFixed(2)}</span>
                    </div>

                    <!-- Total -->
                    <div class="w-24 text-right">
                        <span class="font-bold text-blue-600 text-sm">$${itemTotal.toFixed(2)}</span>
                    </div>

                    <!-- Remove -->
                    <div class="w-10 text-right">
                        <button onclick="removeItem(${index})"
                                class="text-rose-400 hover:text-rose-600 transition text-sm">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;
        });

        cartItemsList.innerHTML = html;

        // Update summary
        const tax = subtotal * 0.10;
        const deliveryFee = subtotal > 0 ? 2.50 : 0;
        const total = subtotal + tax + deliveryFee;

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        taxEl.textContent = '$' + tax.toFixed(2);
        document.getElementById('deliveryFee').textContent = '$' + deliveryFee.toFixed(2);
        grandTotalEl.textContent = '$' + total.toFixed(2);
    }

    function updateQuantity(index, delta) {
        cart = getCart();
        if (!cart[index]) return;

        const newQty = (cart[index].qty || 0) + delta;
        if (newQty < 1) {
            removeItem(index);
            return;
        }

        cart[index].qty = newQty;
        saveCart(cart);
        renderCart();
    }

    function removeItem(index) {
        if (confirm('Remove this item from your cart?')) {
            cart = getCart();
            cart.splice(index, 1);
            saveCart(cart);
            renderCart();
        }
    }

    function clearCart() {
        if (cart.length === 0) return;
        if (confirm('Are you sure you want to clear your cart?')) {
            saveCart([]);
            renderCart();
        }
    }

    // ===== DOM READY =====
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();
    });

    console.log('🛒 Shopping Cart loaded successfully!');
    console.log('📦 Items:', cart.length);
</script>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Cart item hover */
    .cart-item {
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        background-color: #f8fafc;
    }

    /* Quantity buttons */
    .qty-btn {
        transition: all 0.2s ease;
        user-select: none;
    }

    .qty-btn:hover {
        background-color: #e2e8f0;
    }

    .qty-btn:active {
        transform: scale(0.95);
    }

    /* Sticky summary */
    .sticky-top {
        position: sticky;
        top: 90px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-item {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .cart-item .product-info {
            flex: 1 1 100%;
        }
        .cart-item .qty-control {
            flex: 1 1 auto;
        }

    // ===== DOM READY =====
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });

    console.log('🛒 Shopping Cart loaded successfully!');
</script>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Cart item hover */
    .cart-item {
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        background-color: #f8fafc;
    }

    /* Quantity buttons */
    .qty-btn {
        transition: all 0.2s ease;
        user-select: none;
    }

    .qty-btn:hover {
        background-color: #e2e8f0;
    }

    .qty-btn:active {
        transform: scale(0.95);
    }

    /* Sticky summary */
    .sticky-top {
        position: sticky;
        top: 90px;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .cart-item {
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .cart-item .product-info {
            flex: 1 1 100%;
        }
        .cart-item .qty-control {
            flex: 1 1 auto;
        }
        .cart-item .price-display {
            flex: 1 1 auto;
        }
        .cart-item .total-display {
            flex: 1 1 auto;
        }
        .cart-item .remove-btn {
            flex: 0 0 auto;
        }
    }

    /* Animation for empty state */
    #emptyCart .w-24 {
        animation: bounce 2s ease-in-out infinite;
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
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