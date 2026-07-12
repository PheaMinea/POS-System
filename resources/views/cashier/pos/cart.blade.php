{{-- resources/views/cashier/pos/cart.blade.php --}}
<div class="bg-white rounded-2xl shadow-sm overflow-hidden sticky top-5">

    <!-- Header -->
    <div class="bg-gradient-to-r from-emerald-600 to-green-600 p-5 text-white">
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold flex items-center gap-2">
                <i class="fas fa-shopping-cart"></i>
                Shopping Cart
            </h3>
            <span id="cartCount" class="bg-white/20 px-3 py-1 rounded-full text-sm font-medium">
                0
            </span>
        </div>
        <p class="text-emerald-100 text-sm mt-1">
            <i class="fas fa-info-circle mr-1"></i>
            {{ auth()->user()->name ?? 'Cashier' }}
        </p>
    </div>

    <!-- Cart Body -->
    <div class="p-4">

        <div id="emptyCart" class="text-center py-8 text-slate-400">
            <i class="fas fa-cart-plus text-4xl block mb-3 opacity-30"></i>
            <p class="text-sm font-medium text-slate-500">Cart is empty</p>
            <p class="text-xs">Add products to get started</p>
        </div>

        <div id="cartContent" class="hidden">
            <div class="overflow-y-auto max-h-80 custom-scroll">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-100">
                            <th class="text-left pb-2 text-xs font-medium text-slate-400 uppercase tracking-wider">Product</th>
                            <th class="text-center pb-2 text-xs font-medium text-slate-400 uppercase tracking-wider">Qty</th>
                            <th class="text-right pb-2 text-xs font-medium text-slate-400 uppercase tracking-wider">Price</th>
                            <th class="w-8"></th>
                        </tr>
                    </thead>
                    <tbody id="cartBody">
                    </tbody>
                </table>
            </div>

            <!-- Customer Selection -->
            <div class="border-t border-slate-200 mt-4 pt-4">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-user mr-1"></i> Customer
                </label>
                <select id="customerSelect"
                        class="w-full border border-slate-200 rounded-xl px-3 py-2.5 text-sm focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition outline-none bg-white">
                    <option value="">--- Select Customer ---</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Payment Method -->
            <div class="mt-3">
                <label class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-2">
                    <i class="fas fa-credit-card mr-1"></i> Payment Method
                </label>
                <div class="grid grid-cols-3 gap-2">
                    <label class="payment-option border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:border-emerald-400 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="payment_method" value="cash" class="hidden" checked>
                        <i class="fas fa-money-bill-wave text-lg text-emerald-600"></i>
                        <p class="text-xs font-medium text-slate-600 mt-1">Cash</p>
                    </label>
                    <label class="payment-option border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:border-emerald-400 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="payment_method" value="aba" class="hidden">
                        <i class="fas fa-university text-lg text-blue-600"></i>
                        <p class="text-xs font-medium text-slate-600 mt-1">ABA</p>
                    </label>
                    <label class="payment-option border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:border-emerald-400 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="payment_method" value="acleda" class="hidden">
                        <i class="fas fa-building-columns text-lg text-purple-600"></i>
                        <p class="text-xs font-medium text-slate-600 mt-1">ACLEDA</p>
                    </label>
                    <label class="payment-option border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:border-emerald-400 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="payment_method" value="wing" class="hidden">
                        <i class="fas fa-mobile-screen text-lg text-rose-600"></i>
                        <p class="text-xs font-medium text-slate-600 mt-1">Wing</p>
                    </label>
                    <label class="payment-option border border-slate-200 rounded-xl p-3 text-center cursor-pointer hover:border-emerald-400 transition has-[:checked]:border-emerald-500 has-[:checked]:bg-emerald-50">
                        <input type="radio" name="payment_method" value="bakong" class="hidden">
                        <i class="fas fa-qrcode text-lg text-emerald-600"></i>
                        <p class="text-xs font-medium text-slate-600 mt-1">Bakong</p>
                    </label>
                </div>
            </div>

            <!-- Total and Checkout -->
            <div class="border-t border-slate-200 mt-4 pt-4">
                <div class="flex justify-between text-lg font-bold text-slate-800">
                    <span>Total</span>
                    <span id="grandTotal" class="text-emerald-600">$0.00</span>
                </div>

                <button onclick="checkout()"
                        id="checkoutBtn"
                        class="w-full mt-4 bg-gradient-to-r from-emerald-500 to-green-600 hover:from-emerald-600 hover:to-green-700 text-white py-3.5 rounded-xl font-semibold transition flex items-center justify-center gap-2 shadow-lg shadow-emerald-500/25 hover:shadow-emerald-500/35">
                    <i class="fas fa-credit-card"></i>
                    Checkout
                </button>
            </div>
        </div>

    </div>

</div>

<style>
    .custom-scroll::-webkit-scrollbar {
        width: 4px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 8px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    .payment-option:has(input:checked) {
        border-color: #10b981;
        background-color: #ecfdf5;
    }

    .payment-option input:checked + i {
        transform: scale(1.1);
    }
</style>

<script>
    const CART_STORAGE_KEY = 'shared_cart';
    let cart = [];

    function getStoredCart() {
        try {
            const shared = localStorage.getItem(CART_STORAGE_KEY);
            if (shared) {
                return JSON.parse(shared) || [];
            }

            const receipt = localStorage.getItem('receipt');
            if (receipt) {
                return JSON.parse(receipt) || [];
            }

            const legacy = localStorage.getItem('cart');
            if (legacy) {
                return JSON.parse(legacy) || [];
            }

            return [];
        } catch (err) {
            console.error('Failed to parse stored cart', err);
            return [];
        }
    }

    function persistCart() {
        localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart));
        localStorage.setItem('receipt', JSON.stringify(cart));
        localStorage.setItem('cart', JSON.stringify(cart));
    }

    // Load cart from localStorage if available
    cart = getStoredCart();

    function addToCart(id, name, price) {
        // tolerate string/number id types
        let item = cart.find(product => product.id == id);

        if (item) {
            item.qty++;
        } else {
            cart.push({ id, name, price, qty: 1 });
        }

        renderCart();
    }

    function increaseQty(id) {
        const item = cart.find(product => product.id == id);
        if (item) {
            item.qty++;
            renderCart();
        }
    }

    function decreaseQty(id) {
        const item = cart.find(product => product.id == id);
        if (item && item.qty > 1) {
            item.qty--;
            renderCart();
        }
    }

    function removeItem(id) {
        if (confirm('Remove this item from cart?')) {
            cart = cart.filter(item => item.id != id);
            renderCart();
        }
    }

    function renderCart() {
        const cartBody = document.getElementById('cartBody');
        const grandTotal = document.getElementById('grandTotal');
        const cartCount = document.getElementById('cartCount');
        const emptyCart = document.getElementById('emptyCart');
        const cartContent = document.getElementById('cartContent');

        let html = '';
        let total = 0;
        let count = 0;

        cart.forEach(item => {
            const subtotal = item.price * item.qty;
            total += subtotal;
            count += item.qty;

            html += `
                <tr class="border-b border-slate-100 hover:bg-slate-50 transition">
                    <td class="py-2.5 text-sm font-medium text-slate-700">${item.name}</td>
                    <td class="text-center">
                        <div class="inline-flex items-center gap-1.5">
                            <button onclick="decreaseQty(${item.id})"
                                    class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 font-bold transition">
                                −
                            </button>
                            <span class="w-6 text-center font-bold text-slate-700">${item.qty}</span>
                            <button onclick="increaseQty(${item.id})"
                                    class="w-7 h-7 bg-slate-100 hover:bg-slate-200 rounded-lg text-slate-600 font-bold transition">
                                +
                            </button>
                        </div>
                    </td>
                    <td class="text-right font-bold text-emerald-600">$${subtotal.toFixed(2)}</td>
                    <td>
                        <button onclick="removeItem(${item.id})"
                                class="text-rose-400 hover:text-rose-600 transition">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        cartBody.innerHTML = html;
        grandTotal.textContent = '$' + total.toFixed(2);
        cartCount.textContent = count;

        // persist cart
        try {
            persistCart();
        } catch (err) {
            console.error('Failed to save cart', err);
        }

        if (cart.length === 0) {
            emptyCart.classList.remove('hidden');
            cartContent.classList.add('hidden');
        } else {
            emptyCart.classList.add('hidden');
            cartContent.classList.remove('hidden');
        }
    }

    function checkout() {
        if (cart.length === 0) {
            alert('Cart is empty');
            return;
        }

        const customerId = document.getElementById('customerSelect').value;
        if (!customerId) {
            alert('Please select a customer');
            document.getElementById('customerSelect').focus();
            return;
        }

        const paymentMethod = document.querySelector('input[name="payment_method"]:checked');
        if (!paymentMethod) {
            alert('Please select a payment method');
            return;
        }

        // Build items payload
        const items = cart.map(item => ({
            product_id: item.id,
            quantity: item.qty,
        }));

        const payload = {
            customer_id: customerId,
            payment_method: paymentMethod.value,
            items: items,
        };

        // Disable button and show loading
        const btn = document.getElementById('checkoutBtn');
        btn.disabled = true;
        btn.innerHTML = paymentMethod.value === 'bakong'
            ? '<i class="fas fa-spinner fa-spin"></i> Creating Bakong QR...'
            : '<i class="fas fa-spinner fa-spin"></i> Processing...';

        // Send POST request
        fetch('{{ route('cashier.checkout') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Clear cart
                cart = [];
                localStorage.removeItem('receipt');

                // Redirect to receipt
                window.location.href = data.redirect_url;
            } else {
                alert(data.message || 'Checkout failed');
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-credit-card"></i> Checkout';
            }
        })
        .catch(error => {
            console.error('Checkout error:', error);
            alert('An error occurred during checkout. Please try again.');
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-credit-card"></i> Checkout';
        });
    }

    // Initial render
    document.addEventListener('DOMContentLoaded', function() {
        renderCart();

        window.addEventListener('storage', function(event) {
            if (event.key === CART_STORAGE_KEY || event.key === 'receipt' || event.key === 'cart') {
                cart = getStoredCart();
                renderCart();
            }
        });

        document.querySelectorAll('input[name="payment_method"]').forEach(input => {
            input.addEventListener('change', function() {
                const btn = document.getElementById('checkoutBtn');

                if (this.value === 'bakong') {
                    btn.innerHTML = '<i class="fas fa-qrcode"></i> Pay with Bakong';
                    return;
                }

                btn.innerHTML = '<i class="fas fa-credit-card"></i> Checkout';
            });
        });
    });
</script>
