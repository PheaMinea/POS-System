{{-- resources/views/customer/cart.blade.php --}}

@extends('layouts.app')

@section('title', 'Shopping Cart - Food Restaurant')

@section('content')

<!-- ============================================================ -->
<!-- CART HEADER -->
<!-- ============================================================ -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <div class="flex flex-wrap justify-between items-center gap-4">

            <div>

                <h1 class="text-3xl md:text-4xl font-extrabold flex items-center gap-3">

                    <i class="fas fa-shopping-cart"></i>

                    Shopping Cart

                </h1>

                <p class="text-blue-100/90 mt-2">

                    <i class="fas fa-circle text-[6px] mr-2 align-middle"></i>

                    Review your order before checkout

                </p>

            </div>


            <a
                href="{{ route('customer.menu') }}"
                class="bg-white/20 backdrop-blur-sm hover:bg-white/30 text-white px-6 py-3 rounded-xl font-medium transition flex items-center gap-2 border border-white/20"
            >

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


        <!-- ====================================================== -->
        <!-- CART ITEMS -->
        <!-- ====================================================== -->
        <div class="lg:col-span-2">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">


                <!-- CART TABLE HEADER -->
                <div class="hidden md:grid grid-cols-12 gap-4 bg-slate-50 px-6 py-4 border-b border-slate-200">

                    <div class="col-span-5 text-xs font-semibold text-slate-400 uppercase tracking-wider">

                        Product

                    </div>


                    <div class="col-span-3 text-xs font-semibold text-slate-400 uppercase tracking-wider text-center">

                        Quantity

                    </div>


                    <div class="col-span-2 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">

                        Price

                    </div>


                    <div class="col-span-2 text-xs font-semibold text-slate-400 uppercase tracking-wider text-right">

                        Total

                    </div>

                </div>


                <!-- ================================================== -->
                <!-- CART ITEMS CONTAINER -->
                <!-- ================================================== -->
                <div id="cartItems">


                    <!-- EMPTY CART -->
                    <div
                        id="emptyCart"
                        class="hidden text-center py-16 px-6"
                    >

                        <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">

                            <i class="fas fa-shopping-cart text-4xl text-blue-400"></i>

                        </div>


                        <h3 class="text-xl font-semibold text-slate-700">

                            Your Cart is Empty

                        </h3>


                        <p class="text-slate-400 mt-2 text-sm">

                            Looks like you haven't added any items yet

                        </p>


                        <a
                            href="{{ route('customer.menu') }}"
                            class="inline-flex items-center mt-6 bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl font-medium transition shadow-lg shadow-blue-500/25"
                        >

                            <i class="fas fa-utensils mr-2"></i>

                            Browse Menu

                        </a>

                    </div>


                    <!-- CART ITEMS LIST -->
                    <div
                        id="cartItemsList"
                        class="divide-y divide-slate-100"
                    ></div>


                </div>

            </div>

        </div>


        <!-- ====================================================== -->
        <!-- ORDER SUMMARY -->
        <!-- ====================================================== -->
        <div class="lg:col-span-1">

            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sticky top-24">


                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2 pb-4 border-b border-slate-100">

                    <i class="fas fa-receipt text-blue-500"></i>

                    Order Summary

                </h3>


                <!-- SUMMARY VALUES -->
                <div class="space-y-3 py-4 border-b border-slate-100">


                    <!-- SUBTOTAL -->
                    <div class="flex justify-between text-sm">

                        <span class="text-slate-500">

                            Subtotal

                        </span>


                        <span
                            id="subtotal"
                            class="font-medium text-slate-700"
                        >

                            $0.00

                        </span>

                    </div>


                    <!-- DELIVERY FEE -->
                    <div class="flex justify-between text-sm">

                        <span class="text-slate-500">

                            Delivery Fee

                        </span>


                        <span
                            id="deliveryFee"
                            class="font-medium text-slate-700"
                        >

                            $0.00

                        </span>

                    </div>


                    <!-- TAX -->
                    <div class="flex justify-between text-sm">

                        <span class="text-slate-500">

                            Tax (10%)

                        </span>


                        <span
                            id="taxAmount"
                            class="font-medium text-slate-700"
                        >

                            $0.00

                        </span>

                    </div>


                </div>


                <!-- GRAND TOTAL -->
                <div class="flex justify-between items-center pt-4">

                    <span class="text-lg font-bold text-slate-800">

                        Grand Total

                    </span>


                    <span
                        id="grandTotal"
                        class="text-2xl font-bold text-blue-600"
                    >

                        $0.00

                    </span>

                </div>


                <!-- CART ITEM COUNT -->
                <div class="mt-4 bg-blue-50 rounded-xl p-3 flex items-center justify-between">

                    <span class="text-sm text-blue-700">

                        <i class="fas fa-shopping-bag mr-2"></i>

                        Cart Items

                    </span>


                    <span
                        id="summaryItemCount"
                        class="bg-blue-600 text-white text-xs font-bold px-3 py-1 rounded-full"
                    >

                        0

                    </span>

                </div>


                <!-- ACTION BUTTONS -->
                <div class="mt-6 space-y-3">


                    <!-- CHECKOUT -->
                    <button
                        type="button"
                        id="checkoutButton"
                        class="w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white py-3.5 rounded-xl font-semibold text-center transition shadow-lg shadow-blue-500/25"
                    >

                        <i class="fas fa-credit-card mr-2"></i>

                        Proceed to Checkout

                    </button>


                    <!-- CLEAR CART -->
                    <button
                        type="button"
                        id="clearCartButton"
                        class="w-full bg-slate-100 hover:bg-slate-200 text-slate-600 py-2.5 rounded-xl text-sm font-medium transition"
                    >

                        <i class="fas fa-trash-alt mr-2"></i>

                        Clear Cart

                    </button>


                </div>


                <!-- SECURITY -->
                <div class="mt-4 pt-4 border-t border-slate-100">

                    <p class="text-xs text-slate-400 text-center">

                        <i class="fas fa-lock mr-1.5"></i>

                        Secure checkout • Bakong KHQR Payment

                    </p>

                </div>


            </div>

        </div>


    </div>

</section>


<!-- ============================================================ -->
<!-- CART STYLE -->
<!-- ============================================================ -->
<style>

    /*
    |--------------------------------------------------------------------------
    | Cart Item
    |--------------------------------------------------------------------------
    */

    .customer-cart-item {

        transition:
            background-color 0.2s ease,
            transform 0.2s ease;

    }


    .customer-cart-item:hover {

        background-color: #f8fafc;

    }


    /*
    |--------------------------------------------------------------------------
    | Quantity Button
    |--------------------------------------------------------------------------
    */

    .cart-qty-button {

        transition: all 0.2s ease;

        user-select: none;

    }


    .cart-qty-button:hover {

        background-color: #e2e8f0;

    }


    .cart-qty-button:active {

        transform: scale(0.92);

    }


    /*
    |--------------------------------------------------------------------------
    | Empty Cart
    |--------------------------------------------------------------------------
    */

    #emptyCart .empty-cart-icon {

        animation: cartBounce 2s ease-in-out infinite;

    }


    @keyframes cartBounce {

        0%,
        100% {

            transform: translateY(0);

        }


        50% {

            transform: translateY(-8px);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | Mobile
    |--------------------------------------------------------------------------
    */

    @media (max-width: 767px) {

        .customer-cart-item {

            padding: 1rem;

        }

    }

</style>


<!-- ============================================================ -->
<!-- CART JAVASCRIPT -->
<!-- ============================================================ -->
<script>

(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | CART STORAGE KEY
    |--------------------------------------------------------------------------
    |
    | IMPORTANT:
    |
    | Menu and Cart MUST use the same key.
    |
    |--------------------------------------------------------------------------
    */

    const CART_KEY = 'shared_cart';


    /*
    |--------------------------------------------------------------------------
    | CART STATE
    |--------------------------------------------------------------------------
    */

    let cart = [];


    /*
    |--------------------------------------------------------------------------
    | GET CART
    |--------------------------------------------------------------------------
    */

    function getCart() {

        try {

            const cartData =
                localStorage.getItem(CART_KEY)
                || localStorage.getItem('cart');


            if (!cartData) {

                return [];

            }


            const parsedCart =
                JSON.parse(cartData);


            if (!Array.isArray(parsedCart)) {

                return [];

            }


            return parsedCart.map(
                function (item) {

                    return {

                        id: Number(item.id),

                        name: String(
                            item.name || 'Product'
                        ),

                        price: Number(
                            item.price || 0
                        ),

                        qty: Number(
                            item.qty || 1
                        ),

                    };

                }
            );


        } catch (error) {

            console.error(
                'Get cart error:',
                error
            );


            return [];

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SAVE CART
    |--------------------------------------------------------------------------
    */

    function saveCart(items) {

        cart = items;


        const cartJson =
            JSON.stringify(cart);


        /*
        |--------------------------------------------------------------------------
        | Main Shared Cart
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
            CART_KEY,
            cartJson
        );


        /*
        |--------------------------------------------------------------------------
        | Legacy Cart
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
            'cart',
            cartJson
        );


        /*
        |--------------------------------------------------------------------------
        | Receipt Snapshot
        |--------------------------------------------------------------------------
        |
        | Checkout can read this.
        |
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
            'receipt',
            cartJson
        );


        /*
        |--------------------------------------------------------------------------
        | Update Navbar Count
        |--------------------------------------------------------------------------
        */

        updateCartBadge();

    }


    /*
    |--------------------------------------------------------------------------
    | GET CART QUANTITY COUNT
    |--------------------------------------------------------------------------
    */

    function getCartQuantityCount() {

        return cart.reduce(
            function (total, item) {

                return total
                    + Number(
                        item.qty || 0
                    );

            },
            0
        );

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE NAVBAR CART BADGE
    |--------------------------------------------------------------------------
    */

    function updateCartBadge() {

        const cartCount =
            getCartQuantityCount();


        document
            .querySelectorAll('#cartCount')
            .forEach(
                function (badge) {

                    badge.textContent =
                        cartCount;

                }
            );


        const summaryItemCount =
            document.getElementById(
                'summaryItemCount'
            );


        if (summaryItemCount) {

            summaryItemCount.textContent =
                cartCount;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    |
    | Product name comes from data.
    |
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const element =
            document.createElement('div');


        element.textContent =
            String(value);


        return element.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | CALCULATE CART
    |--------------------------------------------------------------------------
    */

    function calculateCart() {

        const subtotal =
            cart.reduce(
                function (total, item) {

                    const itemTotal =
                        Number(item.price)
                        * Number(item.qty);


                    return total
                        + itemTotal;

                },
                0
            );


        /*
        |--------------------------------------------------------------------------
        | Tax 10%
        |--------------------------------------------------------------------------
        */

        const tax =
            subtotal * 0.10;


        /*
        |--------------------------------------------------------------------------
        | Delivery Fee
        |--------------------------------------------------------------------------
        |
        | Free when subtotal >= $50.
        |
        |--------------------------------------------------------------------------
        */

        let deliveryFee = 0;


        if (
            subtotal > 0
            && subtotal < 50
        ) {

            deliveryFee = 2.50;

        }


        /*
        |--------------------------------------------------------------------------
        | Grand Total
        |--------------------------------------------------------------------------
        */

        const grandTotal =
            subtotal
            + tax
            + deliveryFee;


        return {

            subtotal: subtotal,

            tax: tax,

            deliveryFee: deliveryFee,

            grandTotal: grandTotal,

        };

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE ORDER SUMMARY
    |--------------------------------------------------------------------------
    */

    function updateSummary() {

        const totals =
            calculateCart();


        const subtotalElement =
            document.getElementById(
                'subtotal'
            );


        const taxElement =
            document.getElementById(
                'taxAmount'
            );


        const deliveryFeeElement =
            document.getElementById(
                'deliveryFee'
            );


        const grandTotalElement =
            document.getElementById(
                'grandTotal'
            );


        if (subtotalElement) {

            subtotalElement.textContent =
                '$'
                + totals.subtotal.toFixed(2);

        }


        if (taxElement) {

            taxElement.textContent =
                '$'
                + totals.tax.toFixed(2);

        }


        if (deliveryFeeElement) {

            deliveryFeeElement.textContent =
                '$'
                + totals.deliveryFee.toFixed(2);

        }


        if (grandTotalElement) {

            grandTotalElement.textContent =
                '$'
                + totals.grandTotal.toFixed(2);

        }


        /*
        |--------------------------------------------------------------------------
        | Save Checkout Summary
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
            'checkout_summary',
            JSON.stringify(totals)
        );

    }


    /*
    |--------------------------------------------------------------------------
    | RENDER CART
    |--------------------------------------------------------------------------
    */

    function renderCart() {

        /*
        |--------------------------------------------------------------------------
        | Load Latest Cart
        |--------------------------------------------------------------------------
        */

        cart = getCart();


        const emptyCart =
            document.getElementById(
                'emptyCart'
            );


        const cartItemsList =
            document.getElementById(
                'cartItemsList'
            );


        const checkoutButton =
            document.getElementById(
                'checkoutButton'
            );


        const clearCartButton =
            document.getElementById(
                'clearCartButton'
            );


        if (
            !emptyCart
            || !cartItemsList
        ) {

            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Empty Cart
        |--------------------------------------------------------------------------
        */

        if (cart.length === 0) {

            emptyCart.classList.remove(
                'hidden'
            );


            cartItemsList.classList.add(
                'hidden'
            );


            cartItemsList.innerHTML = '';


            if (checkoutButton) {

                checkoutButton.disabled = true;


                checkoutButton.classList.add(
                    'opacity-50',
                    'cursor-not-allowed'
                );

            }


            if (clearCartButton) {

                clearCartButton.disabled = true;


                clearCartButton.classList.add(
                    'opacity-50',
                    'cursor-not-allowed'
                );

            }


            updateSummary();

            updateCartBadge();


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Cart Has Items
        |--------------------------------------------------------------------------
        */

        emptyCart.classList.add(
            'hidden'
        );


        cartItemsList.classList.remove(
            'hidden'
        );


        if (checkoutButton) {

            checkoutButton.disabled = false;


            checkoutButton.classList.remove(
                'opacity-50',
                'cursor-not-allowed'
            );

        }


        if (clearCartButton) {

            clearCartButton.disabled = false;


            clearCartButton.classList.remove(
                'opacity-50',
                'cursor-not-allowed'
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Generate Cart Items
        |--------------------------------------------------------------------------
        */

        let html = '';


        cart.forEach(
            function (item, index) {

                const itemTotal =
                    Number(item.price)
                    * Number(item.qty);


                html += `

                    <div
                        class="
                            customer-cart-item
                            grid
                            grid-cols-1
                            md:grid-cols-12
                            gap-4
                            items-center
                            px-6
                            py-5
                        "
                    >


                        <!-- PRODUCT -->
                        <div class="md:col-span-5">

                            <div class="flex items-center gap-3">


                                <div
                                    class="
                                        w-14
                                        h-14
                                        bg-blue-100
                                        rounded-xl
                                        flex
                                        items-center
                                        justify-center
                                        text-blue-500
                                        flex-shrink-0
                                    "
                                >

                                    <i class="fas fa-utensils text-xl"></i>

                                </div>


                                <div class="min-w-0">

                                    <p
                                        class="
                                            font-semibold
                                            text-slate-800
                                            text-sm
                                            truncate
                                        "
                                    >

                                        ${escapeHtml(item.name)}

                                    </p>


                                    <p class="text-xs text-slate-400 mt-1">

                                        Product #${item.id}

                                    </p>


                                    <button
                                        type="button"
                                        class="
                                            cart-remove-button
                                            text-xs
                                            text-rose-500
                                            hover:text-rose-700
                                            mt-2
                                        "
                                        data-index="${index}"
                                    >

                                        <i class="fas fa-trash-alt mr-1"></i>

                                        Remove

                                    </button>

                                </div>


                            </div>

                        </div>


                        <!-- QUANTITY -->
                        <div class="md:col-span-3">

                            <div class="flex md:justify-center items-center gap-2">


                                <button
                                    type="button"
                                    class="
                                        cart-qty-button
                                        cart-decrease-button
                                        w-9
                                        h-9
                                        bg-slate-100
                                        rounded-lg
                                        text-slate-600
                                        font-bold
                                    "
                                    data-index="${index}"
                                >

                                    −

                                </button>


                                <span
                                    class="
                                        w-10
                                        text-center
                                        font-bold
                                        text-slate-700
                                    "
                                >

                                    ${item.qty}

                                </span>


                                <button
                                    type="button"
                                    class="
                                        cart-qty-button
                                        cart-increase-button
                                        w-9
                                        h-9
                                        bg-slate-100
                                        rounded-lg
                                        text-slate-600
                                        font-bold
                                    "
                                    data-index="${index}"
                                >

                                    +

                                </button>


                            </div>

                        </div>


                        <!-- PRICE -->
                        <div class="md:col-span-2 md:text-right">

                            <span class="md:hidden text-xs text-slate-400 mr-2">

                                Price:

                            </span>


                            <span class="font-medium text-slate-700 text-sm">

                                $${Number(item.price).toFixed(2)}

                            </span>

                        </div>


                        <!-- TOTAL -->
                        <div class="md:col-span-2 md:text-right">

                            <span class="md:hidden text-xs text-slate-400 mr-2">

                                Total:

                            </span>


                            <span class="font-bold text-blue-600">

                                $${itemTotal.toFixed(2)}

                            </span>

                        </div>


                    </div>

                `;

            }
        );


        cartItemsList.innerHTML =
            html;


        /*
        |--------------------------------------------------------------------------
        | Bind Dynamic Buttons
        |--------------------------------------------------------------------------
        */

        bindCartButtons();


        /*
        |--------------------------------------------------------------------------
        | Update Summary
        |--------------------------------------------------------------------------
        */

        updateSummary();


        /*
        |--------------------------------------------------------------------------
        | Update Navbar Badge
        |--------------------------------------------------------------------------
        */

        updateCartBadge();

    }


    /*
    |--------------------------------------------------------------------------
    | BIND CART BUTTONS
    |--------------------------------------------------------------------------
    */

    function bindCartButtons() {


        /*
        |--------------------------------------------------------------------------
        | Increase Quantity
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.cart-increase-button'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const index =
                                Number(
                                    this.dataset.index
                                );


                            updateQuantity(
                                index,
                                1
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Decrease Quantity
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.cart-decrease-button'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const index =
                                Number(
                                    this.dataset.index
                                );


                            updateQuantity(
                                index,
                                -1
                            );

                        }
                    );

                }
            );


        /*
        |--------------------------------------------------------------------------
        | Remove Product
        |--------------------------------------------------------------------------
        */

        document
            .querySelectorAll(
                '.cart-remove-button'
            )
            .forEach(
                function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            const index =
                                Number(
                                    this.dataset.index
                                );


                            removeItem(
                                index
                            );

                        }
                    );

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE QUANTITY
    |--------------------------------------------------------------------------
    */

    function updateQuantity(
        index,
        change
    ) {

        cart = getCart();


        if (!cart[index]) {

            return;

        }


        const currentQuantity =
            Number(
                cart[index].qty || 0
            );


        const newQuantity =
            currentQuantity
            + Number(change);


        /*
        |--------------------------------------------------------------------------
        | Remove If Quantity <= 0
        |--------------------------------------------------------------------------
        */

        if (newQuantity <= 0) {

            removeItem(index);


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Update Quantity
        |--------------------------------------------------------------------------
        */

        cart[index].qty =
            newQuantity;


        saveCart(cart);


        renderCart();

    }


    /*
    |--------------------------------------------------------------------------
    | REMOVE ITEM
    |--------------------------------------------------------------------------
    */

    function removeItem(index) {

        cart = getCart();


        if (!cart[index]) {

            return;

        }


        const productName =
            cart[index].name;


        /*
        |--------------------------------------------------------------------------
        | Confirmation
        |--------------------------------------------------------------------------
        */

        Swal.fire({

            title: 'Remove Item?',

            text:
                'Remove '
                + productName
                + ' from your cart?',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText:
                'Yes, remove',

            cancelButtonText:
                'Cancel',

            confirmButtonColor:
                '#dc2626',

        }).then(
            function (result) {

                if (!result.isConfirmed) {

                    return;

                }


                cart.splice(
                    index,
                    1
                );


                saveCart(cart);


                renderCart();


                Swal.fire({

                    title: 'Removed',

                    text:
                        productName
                        + ' removed from cart.',

                    icon: 'success',

                    timer: 1200,

                    showConfirmButton: false,

                });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR CART
    |--------------------------------------------------------------------------
    */

    function clearCart() {

        cart = getCart();


        if (cart.length === 0) {

            return;

        }


        Swal.fire({

            title: 'Clear Cart?',

            text:
                'All products will be removed.',

            icon: 'warning',

            showCancelButton: true,

            confirmButtonText:
                'Yes, clear cart',

            cancelButtonText:
                'Cancel',

            confirmButtonColor:
                '#dc2626',

        }).then(
            function (result) {

                if (!result.isConfirmed) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | Clear Cart
                |--------------------------------------------------------------------------
                */

                saveCart([]);


                /*
                |--------------------------------------------------------------------------
                | Remove Checkout Summary
                |--------------------------------------------------------------------------
                */

                localStorage.removeItem(
                    'checkout_summary'
                );


                /*
                |--------------------------------------------------------------------------
                | Render Cart
                |--------------------------------------------------------------------------
                */

                renderCart();


                Swal.fire({

                    title: 'Cart Cleared',

                    text:
                        'Your cart is now empty.',

                    icon: 'success',

                    timer: 1200,

                    showConfirmButton: false,

                });

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | PROCEED TO CHECKOUT
    |--------------------------------------------------------------------------
    */

    function proceedToCheckout() {

        cart = getCart();


        /*
        |--------------------------------------------------------------------------
        | Check Empty Cart
        |--------------------------------------------------------------------------
        */

        if (cart.length === 0) {

            Swal.fire({

                title: 'Cart is Empty',

                text:
                    'Please add products before checkout.',

                icon: 'warning',

                confirmButtonColor:
                    '#2563eb',

            });


            return;

        }


        /*
        |--------------------------------------------------------------------------
        | Calculate Totals
        |--------------------------------------------------------------------------
        */

        const totals =
            calculateCart();


        /*
        |--------------------------------------------------------------------------
        | Save Latest Cart
        |--------------------------------------------------------------------------
        */

        saveCart(cart);


        /*
        |--------------------------------------------------------------------------
        | Save Checkout Summary
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(

            'checkout_summary',

            JSON.stringify({

                subtotal:
                    Number(
                        totals.subtotal.toFixed(2)
                    ),

                tax:
                    Number(
                        totals.tax.toFixed(2)
                    ),

                deliveryFee:
                    Number(
                        totals.deliveryFee.toFixed(2)
                    ),

                grandTotal:
                    Number(
                        totals.grandTotal.toFixed(2)
                    ),

            })

        );


        /*
        |--------------------------------------------------------------------------
        | Open Checkout
        |--------------------------------------------------------------------------
        */

        window.location.href =
            @json(
                route('customer.checkout')
            );

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE CART
    |--------------------------------------------------------------------------
    */

    function initCustomerCart() {

        console.log(
            'Initializing customer cart...'
        );


        /*
        |--------------------------------------------------------------------------
        | Render Cart
        |--------------------------------------------------------------------------
        */

        renderCart();


        /*
        |--------------------------------------------------------------------------
        | Checkout Button
        |--------------------------------------------------------------------------
        */

        const checkoutButton =
            document.getElementById(
                'checkoutButton'
            );


        if (checkoutButton) {

            checkoutButton.addEventListener(
                'click',
                proceedToCheckout
            );

        }


        /*
        |--------------------------------------------------------------------------
        | Clear Cart Button
        |--------------------------------------------------------------------------
        */

        const clearCartButton =
            document.getElementById(
                'clearCartButton'
            );


        if (clearCartButton) {

            clearCartButton.addEventListener(
                'click',
                clearCart
            );

        }


        console.log(
            'Customer cart initialized.'
        );


        console.log(
            'Cart items:',
            cart
        );

    }


    /*
    |--------------------------------------------------------------------------
    | START CART
    |--------------------------------------------------------------------------
    */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initCustomerCart
        );


    } else {

        initCustomerCart();

    }


})();

</script>

@endsection