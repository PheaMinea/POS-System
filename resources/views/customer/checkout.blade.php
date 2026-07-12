@extends('layouts.app')

@section('title', 'Checkout - Food Restaurant')
@section('content')

<!-- ============================================================ -->
<!-- CHECKOUT HEADER - Blue Professional -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="flex flex-wrap justify-between items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">
                    <i class="fas fa-credit-card"></i>
                    Checkout
                </h1>
                <p class="text-blue-100/90 mt-1">
                    <i class="fas fa-circle text-[6px] mr-2 align-middle"></i>
                    Complete your order
                </p>
            </div>
            <a href="{{ route('customer.cart') }}"
               class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20">
                <i class="fas fa-arrow-left"></i>
                Back to Cart
            </a>
        </div>
    </div>
</section>

<!-- ============================================================ -->
<!-- CHECKOUT CONTENT -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="grid lg:grid-cols-3 gap-8">

        <!-- Customer Info Form -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 md:p-8">

                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-slate-800">Customer Information</h2>
                        <p class="text-sm text-slate-400">Fill in your details to complete the order</p>
                    </div>
                </div>

                <form action="{{ route('customer.checkout.store') }}"
                      method="POST"
                      id="checkoutForm">

                    @csrf

                    <input type="hidden" name="items" id="cartItemsInput">

                    <!-- Name -->
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-user text-blue-400 mr-2"></i>Full Name
                        </label>
                        <input type="text"
                               name="name"
                               id="customerName"
                               value="{{ auth('web')->user()->name ?? '' }}"
                               placeholder="Enter your full name"
                               required
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none @error('name') border-rose-400 @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-phone text-blue-400 mr-2"></i>Phone Number
                        </label>
                        <input type="text"
                               name="phone"
                               id="customerPhone"
                               value="{{ auth('web')->user()->phone ?? '' }}"
                               placeholder="Enter your phone number"
                               required
                               class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none @error('phone') border-rose-400 @enderror">
                        @error('phone')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Address -->
                    <div class="mb-5">
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-map-pin text-blue-400 mr-2"></i>Delivery Address
                        </label>
                        <textarea name="address"
                                  id="customerAddress"
                                  rows="3"
                                  placeholder="Enter your delivery address"
                                  required
                                  class="w-full border border-slate-200 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none @error('address') border-rose-400 @enderror">{{ auth('web')->user()->address ?? '' }}</textarea>
                        @error('address')
                            <p class="mt-1 text-sm text-rose-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-6">
                        <label class="block mb-2 text-sm font-semibold text-slate-700">
                            <i class="fas fa-credit-card text-blue-400 mr-2"></i>Payment Method
                        </label>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="payment-option flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="cash" checked class="text-blue-500 focus:ring-blue-400">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-money-bill-wave text-blue-500"></i>
                                    <span class="text-sm font-medium text-slate-700">Cash on Delivery</span>
                                </span>
                            </label>
                            <label class="payment-option flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:border-blue-400 transition has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="payment_method" value="bakong" class="text-blue-500 focus:ring-blue-400">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-qrcode text-blue-500"></i>
                                    <span class="text-sm font-medium text-slate-700">Bakong KHQR</span>
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit -->
                    <button type="submit"
                            id="placeOrderBtn"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white py-3.5 rounded-xl font-semibold transition flex items-center justify-center gap-2 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35">
                        <i class="fas fa-check-circle"></i>
                        Place Order
                    </button>

                </form>

            </div>

        </div>

        <!-- Order Summary -->
        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100/80 p-6 sticky top-24">

                <div class="flex items-center gap-3 mb-4 pb-4 border-b border-slate-100">
                    <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center text-blue-500">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Order Summary</h3>
                        <p class="text-xs text-slate-400" id="itemCount">0 items</p>
                    </div>
                </div>

                <!-- Items -->
                <div id="checkoutItems" class="space-y-3 max-h-60 overflow-y-auto custom-scroll pr-1">
                    <!-- Rendered by JS -->
                </div>

                <!-- Totals -->
                <div class="border-t border-slate-100 mt-4 pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Subtotal</span>
                        <span id="checkoutSubtotal" class="font-medium text-slate-700">$0.00</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Delivery Fee</span>
                        <span id="checkoutDelivery" class="font-medium text-slate-700">$2.50</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Tax (10%)</span>
                        <span id="checkoutTax" class="font-medium text-slate-700">$0.00</span>
                    </div>
                </div>

                <div class="border-t border-slate-200 mt-4 pt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-slate-800">Total</span>
                        <span id="checkoutTotal" class="text-2xl font-bold text-blue-600">$0.00</span>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-slate-100">
                    <div class="flex items-center gap-2 text-xs text-slate-400">
                        <i class="fas fa-lock text-blue-500"></i>
                        <span>Secure checkout • Your information is safe</span>
                    </div>
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

        const cartCount = items.reduce((sum, item) => sum + (item.qty || 0), 0);
        document.querySelectorAll('#cartCount').forEach(el => el.textContent = cartCount);
    }

    function renderCheckoutSummary() {
        const cart = getCart();
        const itemsContainer = document.getElementById('checkoutItems');
        const subtotalEl = document.getElementById('checkoutSubtotal');
        const deliveryEl = document.getElementById('checkoutDelivery');
        const taxEl = document.getElementById('checkoutTax');
        const totalEl = document.getElementById('checkoutTotal');
        const itemCountEl = document.getElementById('itemCount');

        let html = '';
        let subtotal = 0;

        if (cart.length === 0) {
            html = `
                <div class="text-center py-6 text-slate-400">
                    <i class="fas fa-shopping-cart text-3xl block mb-2 opacity-30"></i>
                    <p class="text-sm">Your cart is empty</p>
                    <a href="{{ route('customer.menu') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition">
                        Browse Menu
                    </a>
                </div>
            `;
            itemsContainer.innerHTML = html;
            subtotalEl.textContent = '$0.00';
            deliveryEl.textContent = '$0.00';
            taxEl.textContent = '$0.00';
            totalEl.textContent = '$0.00';
            itemCountEl.textContent = '0 items';
            document.getElementById('cartItemsInput').value = '[]';
            return;
        }

        cart.forEach(item => {
            const itemTotal = (item.qty || 0) * (item.price || 0);
            subtotal += itemTotal;

            html += `
                <div class="flex justify-between items-center py-2 border-b border-slate-100 last:border-0">
                    <div class="flex-1">
                        <p class="font-medium text-slate-700 text-sm">${item.name || 'Product'}</p>
                        <p class="text-xs text-slate-400">Qty: ${item.qty || 0} x $${(item.price || 0).toFixed(2)}</p>
                    </div>
                    <span class="font-bold text-blue-600 text-sm">$${itemTotal.toFixed(2)}</span>
                </div>
            `;
        });

        itemsContainer.innerHTML = html;

        // Calculate totals
        const deliveryFee = subtotal > 0 ? 2.50 : 0;
        const tax = subtotal * 0.10;
        const total = subtotal + deliveryFee + tax;

        subtotalEl.textContent = '$' + subtotal.toFixed(2);
        deliveryEl.textContent = '$' + deliveryFee.toFixed(2);
        taxEl.textContent = '$' + tax.toFixed(2);
        totalEl.textContent = '$' + total.toFixed(2);
        itemCountEl.textContent = cart.length + ' items';

        // Set cart items as JSON in hidden input
        const cartItems = cart.map(item => ({
            id: item.id,
            name: item.name,
            price: item.price,
            qty: item.qty,
        }));
        document.getElementById('cartItemsInput').value = JSON.stringify(cartItems);
    }

    // ===== DOM READY =====
    document.addEventListener('DOMContentLoaded', function() {
        renderCheckoutSummary();


        // Form validation
        document.getElementById('checkoutForm').addEventListener('submit', function(e) {
            const items = JSON.parse(document.getElementById('cartItemsInput').value || '[]');
            if (items.length === 0) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Your cart is empty. Please add items before checking out.',
                    confirmButtonColor: '#2563eb',
                }).then(() => {
                    window.location.href = '{{ route("customer.menu") }}';
                });
                return;
            }

            // Disable button to prevent double submit
            const btn = document.getElementById('placeOrderBtn');
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
        });
    });

    console.log('🧾 Checkout loaded successfully');
</script>

<!-- ============================================================ -->
<!-- EXTRA STYLES -->
<!-- ============================================================ -->
<style>
    /* Custom scrollbar for order summary */
    .custom-scroll::-webkit-scrollbar {
        width: 3px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }

    /* Sticky summary */
    .sticky-top {
        position: sticky;
        top: 90px;
    }

    /* Radio button styling */
    input[type="radio"] {
        accent-color: #2563eb;
        cursor: pointer;
        width: 18px;
        height: 18px;
    }

    /* Payment method hover */
    .payment-option:hover {
        border-color: #2563eb;
        background: #eff6ff;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .lg\:col-span-2 {
            order: 2;
        }
        .lg\:col-span-1 {
            order: 1;
        }
        .sticky-top {
            position: relative;
            top: 0;
        }
    }

    /* Focus ring */
    input:focus, textarea:focus {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
    }
</style>

@if(session('clear_cart'))
    <script>
        // Clear cart from localStorage
        localStorage.removeItem('shared_cart');
        localStorage.removeItem('cart');
        localStorage.removeItem('receipt');
        document.querySelectorAll('#cartCount').forEach(el => el.textContent = '0');
        console.log('🧹 Cart cleared after successful checkout');
    </script>
@endif

@if(session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Order Placed!',
            text: '{{ session('success') }}',
            confirmButtonColor: '#2563eb',
            confirmButtonText: 'OK',
        });
    </script>
@endif

@if(session('error'))
    <div class="fixed bottom-4 right-4 bg-rose-500 text-white px-6 py-4 rounded-xl shadow-lg z-50 max-w-md animate-slide-in">
        <div class="flex items-center gap-3">
            <i class="fas fa-exclamation-circle text-xl"></i>
            <div>
                <p class="font-semibold">Error</p>
                <p class="text-sm text-rose-100">{{ session('error') }}</p>
            </div>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-auto text-white/70 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }
    </style>
@endif

@endsection