{{-- resources/views/customer/menu.blade.php --}}

@extends('layouts.app')

@section('title', 'Menu - Food Restaurant')

@section('content')

<!-- ============================================================ -->
<!-- MENU HEADER -->
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

            <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-extrabold">

                Delicious

                <span class="text-blue-200">
                    Food
                </span>

                Selection

            </h1>

            <p class="mt-4 text-blue-100/90 text-lg max-w-2xl mx-auto">

                Choose your favorite food from our delicious selection of freshly prepared meals

            </p>

            <div class="mt-8 flex flex-wrap justify-center gap-3">

                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10">

                    <i class="fas fa-fire mr-1.5 text-blue-200"></i>

                    Popular

                </span>

                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10">

                    <i class="fas fa-leaf mr-1.5 text-blue-200"></i>

                    Fresh

                </span>

                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10">

                    <i class="fas fa-clock mr-1.5 text-blue-200"></i>

                    Fast Delivery

                </span>

                <span class="bg-white/20 backdrop-blur-sm px-5 py-2.5 rounded-full text-sm font-medium border border-white/10">

                    <i class="fas fa-heart mr-1.5 text-blue-200"></i>

                    Customer Favorite

                </span>

            </div>

        </div>

    </div>

</section>


<!-- ============================================================ -->
<!-- FILTERS -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-6 relative z-20">

    <div class="bg-white rounded-2xl shadow-xl p-4 md:p-6 border border-slate-100">

        <div class="flex flex-col sm:flex-row sm:flex-wrap items-stretch sm:items-center gap-4">

            <!-- SEARCH -->
            <div class="w-full sm:flex-1 sm:min-w-[200px] relative">

                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>

                <input
                    type="text"
                    id="searchProduct"
                    placeholder="Search food..."
                    autocomplete="off"
                    class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-slate-50/50 hover:bg-white text-slate-800"
                >

            </div>


            <!-- CATEGORY -->
            <select
                id="categoryFilter"
                class="w-full sm:w-auto border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-white text-slate-700 appearance-none cursor-pointer"
            >

                <option value="all">
                    All Categories
                </option>

                @foreach($categories ?? [] as $category)

                    <option value="{{ $category->id }}">

                        {{ $category->name }}

                    </option>

                @endforeach

            </select>


            <!-- SORT -->
            <select
                id="sortFilter"
                class="border border-slate-200 rounded-xl px-4 py-3 pr-10 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-white text-slate-700 appearance-none cursor-pointer"
            >

                <option value="default">
                    Sort by
                </option>

                <option value="price_low">
                    Price: Low to High
                </option>

                <option value="price_high">
                    Price: High to Low
                </option>

                <option value="name_asc">
                    Name: A to Z
                </option>

                <option value="name_desc">
                    Name: Z to A
                </option>

            </select>


            <!-- COUNT -->
            <span
                id="productCount"
                class="text-sm text-slate-500 bg-slate-50 px-4 py-2 rounded-full border border-slate-200"
            >

                <i class="fas fa-circle text-[6px] text-blue-500 mr-1.5 align-middle"></i>

                {{ $products->count() }} items

            </span>


            <!-- RESET -->
            <button
                type="button"
                id="resetFilters"
                class="text-sm text-blue-600 hover:text-blue-700 font-medium transition flex items-center gap-1.5 px-3 py-2 hover:bg-blue-50 rounded-xl"
            >

                <i class="fas fa-rotate-right"></i>

                Reset

            </button>

        </div>

    </div>

</section>


<!-- ============================================================ -->
<!-- PRODUCT GRID -->
<!-- ============================================================ -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

    <div class="flex justify-between items-center mb-6">

        <p class="text-sm text-slate-500">

            Showing

            <span
                id="visibleCount"
                class="text-slate-800 font-semibold"
            >
                {{ $products->count() }}
            </span>

            of

            <span id="totalProductCount">
                {{ $products->count() }}
            </span>

            products

        </p>

        <div class="flex items-center gap-2 text-xs text-slate-400">

            <i class="fas fa-circle text-[4px] text-emerald-500"></i>

            <span>
                Fresh & Delicious
            </span>

        </div>

    </div>


    <div
        id="productGrid"
        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
    >

        @forelse($products as $product)

            <div
                class="product-card bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition group border border-slate-100"
                data-id="{{ $product->id }}"
                data-name="{{ strtolower($product->name) }}"
                data-price="{{ $product->price }}"
                data-category="{{ $product->category_id }}"
            >

                <!-- ================================================= -->
                <!-- IMAGE -->
                <!-- ================================================= -->
                <div class="relative overflow-hidden bg-slate-100">

                    @if($product->image_url)

                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="product-image w-full h-52 object-cover group-hover:scale-105 transition duration-500"
                        >

                    @else

                        <div class="w-full h-52 bg-gradient-to-br from-blue-100 to-blue-200 flex flex-col items-center justify-center">

                            <i class="fas fa-image text-5xl text-blue-300/50"></i>

                            <span class="text-sm text-blue-300/70 mt-2">

                                No Image

                            </span>

                        </div>

                    @endif


                    <!-- CATEGORY -->
                    <div class="absolute bottom-3 left-3 bg-black/60 backdrop-blur-sm text-white text-xs px-3 py-1.5 rounded-full">

                        <i class="fas fa-tag mr-1"></i>

                        {{ $product->category?->name ?? 'Uncategorized' }}

                    </div>


                    <!-- QUICK ADD -->
                    <button
                        type="button"
                        class="add-to-cart-btn absolute bottom-3 right-3 bg-white rounded-full w-11 h-11 shadow-lg hover:bg-blue-600 hover:text-white transition transform hover:scale-110 flex items-center justify-center"
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->price }}"
                        data-product-image="{{ $product->image_url }}"
                    >

                        <i class="fas fa-plus"></i>

                    </button>

                </div>


                <!-- ================================================= -->
                <!-- PRODUCT CONTENT -->
                <!-- ================================================= -->
                <div class="p-5">

                    <div class="flex justify-between items-start">

                        <div class="flex-1 min-w-0">

                            <h3 class="font-semibold text-slate-800 text-lg group-hover:text-blue-600 transition truncate">

                                {{ $product->name }}

                            </h3>

                            <div class="flex items-center gap-2 mt-1">

                                <span class="text-xs text-slate-400 flex items-center gap-1">

                                    <i class="fas fa-clock"></i>

                                    15-20 min

                                </span>

                                <span class="text-xs text-slate-300">
                                    •
                                </span>

                                <span class="text-xs text-slate-400 flex items-center gap-1">

                                    <i class="fas fa-utensils"></i>

                                    {{ $product->category?->name ?? 'Uncategorized' }}

                                </span>

                            </div>

                        </div>


                        <span class="text-xs bg-blue-50 text-blue-600 px-2.5 py-1 rounded-full font-medium flex items-center gap-1 ml-2">

                            <i class="fas fa-star text-[10px]"></i>

                            4.8

                        </span>

                    </div>


                    <div class="flex items-center justify-between mt-4 pt-4 border-t border-slate-100">

                        <span class="text-xl font-bold text-blue-600">

                            ${{ number_format($product->price, 2) }}

                        </span>


                        <button
                            type="button"
                            class="add-to-cart-btn bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-medium transition flex items-center gap-1.5 shadow-sm hover:shadow-md"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            data-product-price="{{ $product->price }}"
                            data-product-image="{{ $product->image_url }}"
                        >

                            <i class="fas fa-cart-plus"></i>

                            <span>
                                Add
                            </span>

                        </button>

                    </div>

                </div>

            </div>

        @empty

            <div class="col-span-full bg-white rounded-2xl p-16 text-center shadow-sm border border-slate-100">

                <div class="w-24 h-24 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">

                    <i class="fas fa-box-open text-4xl text-blue-400"></i>

                </div>

                <p class="text-xl font-medium text-slate-600">

                    No Products Found

                </p>

                <p class="text-sm text-slate-400 mt-2">

                    No products are currently available.

                </p>

            </div>

        @endforelse

    </div>

</section>


<!-- ============================================================ -->
<!-- STYLE -->
<!-- ============================================================ -->
<style>

    .product-card {

        transition: all 0.35s cubic-bezier(
            0.4,
            0,
            0.2,
            1
        );

        border:
            1px solid
            rgba(
                226,
                232,
                240,
                0.6
            );

    }


    .product-card:hover {

        transform:
            translateY(-8px);

        border-color:
            #2563eb;

        box-shadow:
            0 20px 40px -15px
            rgba(
                0,
                0,
                0,
                0.1
            );

    }


    .product-image {

        transition:
            transform 0.5s ease;

    }


    .add-to-cart-btn {

        cursor: pointer;

        transition:
            all 0.2s ease;

    }


    .add-to-cart-btn:active {

        transform:
            scale(0.92);

    }


    @keyframes menuToastIn {

        from {

            opacity: 0;

            transform:
                translateX(30px);

        }

        to {

            opacity: 1;

            transform:
                translateX(0);

        }

    }


    .menu-cart-toast {

        animation:
            menuToastIn
            0.3s
            ease-out;

    }

</style>


<!-- ============================================================ -->
<!-- MENU JAVASCRIPT -->
<!-- ============================================================ -->
<script>

(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | PREVENT DUPLICATE INITIALIZATION
    |--------------------------------------------------------------------------
    */

    if (window.customerMenuInitialized) {

        console.log(
            'Menu already initialized.'
        );

        return;

    }


    window.customerMenuInitialized = true;


    /*
    |--------------------------------------------------------------------------
    | CART KEY
    |--------------------------------------------------------------------------
    */

    const CART_KEY =
        'shared_cart';


    /*
    |--------------------------------------------------------------------------
    | GET CART
    |--------------------------------------------------------------------------
    */

    function getCart() {

        try {

            const data =
                localStorage.getItem(
                    CART_KEY
                );


            if (!data) {

                return [];

            }


            const parsed =
                JSON.parse(data);


            return Array.isArray(parsed)
                ? parsed
                : [];


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

    function saveCart(cart) {

        const cartJson =
            JSON.stringify(cart);


        localStorage.setItem(
            CART_KEY,
            cartJson
        );


        /*
        |--------------------------------------------------------------------------
        | Legacy Compatibility
        |--------------------------------------------------------------------------
        */

        localStorage.setItem(
            'cart',
            cartJson
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE CART BADGE
        |--------------------------------------------------------------------------
        */

        updateCartCount(cart);

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE CART COUNT
    |--------------------------------------------------------------------------
    */

    function updateCartCount(cart) {

        const totalQuantity =
            cart.reduce(
                function (total, item) {

                    return total
                        + Number(
                            item.qty || 0
                        );

                },
                0
            );


        document
            .querySelectorAll(
                '#cartCount'
            )
            .forEach(
                function (badge) {

                    badge.textContent =
                        totalQuantity;

                }
            );

    }


    /*
    |--------------------------------------------------------------------------
    | ADD TO CART
    |--------------------------------------------------------------------------
    */

    function addToCart(
        id,
        name,
        price,
        image
    ) {

        /*
        |--------------------------------------------------------------------------
        | GET LATEST CART
        |--------------------------------------------------------------------------
        */

        const cart =
            getCart();


        const productId =
            Number(id);


        /*
        |--------------------------------------------------------------------------
        | FIND PRODUCT
        |--------------------------------------------------------------------------
        */

        const item =
            cart.find(
                function (cartItem) {

                    return Number(
                        cartItem.id
                    ) === productId;

                }
            );


        /*
        |--------------------------------------------------------------------------
        | UPDATE QUANTITY
        |--------------------------------------------------------------------------
        */

        if (item) {

            item.qty =
                Number(
                    item.qty || 0
                ) + 1;

        } else {

            cart.push({

                id:
                    productId,

                name:
                    String(name),

                price:
                    Number(price),

                qty:
                    1,

                image:
                    image || '',

            });

        }


        /*
        |--------------------------------------------------------------------------
        | SAVE
        |--------------------------------------------------------------------------
        */

        saveCart(cart);


        /*
        |--------------------------------------------------------------------------
        | SHOW TOAST
        |--------------------------------------------------------------------------
        */

        showCartToast(
            name
        );


        /*
        |--------------------------------------------------------------------------
        | IMPORTANT
        |--------------------------------------------------------------------------
        |
        | NO window.location.href
        |
        | NO SPA.navigateTo
        |
        | Customer stays on Menu.
        |
        |--------------------------------------------------------------------------
        */

        console.log(
            'Product added:',
            {
                id: productId,
                name: name
            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | SHOW CART TOAST
    |--------------------------------------------------------------------------
    */

    function showCartToast(
        productName
    ) {

        const oldToast =
            document.getElementById(
                'menuCartToast'
            );


        if (oldToast) {

            oldToast.remove();

        }


        const toast =
            document.createElement(
                'div'
            );


        toast.id =
            'menuCartToast';


        toast.className =
            'menu-cart-toast fixed top-20 left-4 right-4 sm:left-auto sm:right-4 z-[9999]';


        toast.innerHTML = `

            <div
                class="
                    bg-emerald-500
                    text-white
                    px-5
                    py-4
                    rounded-xl
                    shadow-2xl
                    flex
                    items-center
                    gap-3
                    w-full
                    sm:w-auto
                    sm:min-w-[280px]
                "
            >

                <div
                    class="
                        w-10
                        h-10
                        bg-white/20
                        rounded-full
                        flex
                        items-center
                        justify-center
                    "
                >

                    <i class="fas fa-check"></i>

                </div>


                <div class="flex-1">

                    <p class="font-semibold">

                        Added to Cart

                    </p>

                    <p class="text-xs text-white/80">

                        ${escapeHtml(productName)}

                    </p>

                </div>


                <button
                    type="button"
                    id="closeMenuCartToast"
                    class="
                        text-white/70
                        hover:text-white
                    "
                >

                    <i class="fas fa-times"></i>

                </button>

            </div>

        `;


        document.body.appendChild(
            toast
        );


        const closeButton =
            document.getElementById(
                'closeMenuCartToast'
            );


        if (closeButton) {

            closeButton.onclick =
                function () {

                    toast.remove();

                };

        }


        setTimeout(
            function () {

                if (
                    document.body.contains(
                        toast
                    )
                ) {

                    toast.remove();

                }

            },
            2500
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ESCAPE HTML
    |--------------------------------------------------------------------------
    */

    function escapeHtml(value) {

        const div =
            document.createElement(
                'div'
            );


        div.textContent =
            String(value || '');


        return div.innerHTML;

    }


    /*
    |--------------------------------------------------------------------------
    | APPLY FILTERS
    |--------------------------------------------------------------------------
    |
    | Search + Category work together.
    |
    |--------------------------------------------------------------------------
    */

    function applyFilters() {

        const searchInput =
            document.getElementById(
                'searchProduct'
            );


        const categoryFilter =
            document.getElementById(
                'categoryFilter'
            );


        const keyword =
            searchInput
                ? searchInput
                    .value
                    .trim()
                    .toLowerCase()
                : '';


        const categoryId =
            categoryFilter
                ? categoryFilter.value
                : 'all';


        const cards =
            document.querySelectorAll(
                '.product-card'
            );


        let visible =
            0;


        cards.forEach(
            function (card) {

                const productName =
                    String(
                        card.dataset.name || ''
                    ).toLowerCase();


                const productCategory =
                    String(
                        card.dataset.category || ''
                    );


                const matchesSearch =
                    productName.includes(
                        keyword
                    );


                const matchesCategory =
                    categoryId === 'all'
                    || productCategory === categoryId;


                const shouldShow =
                    matchesSearch
                    && matchesCategory;


                card.classList.toggle(
                    'hidden',
                    !shouldShow
                );


                if (shouldShow) {

                    visible++;

                }

            }
        );


        updateProductCount(
            visible
        );

    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUCT COUNT
    |--------------------------------------------------------------------------
    */

    function updateProductCount(
        visible
    ) {

        const productCount =
            document.getElementById(
                'productCount'
            );


        const visibleCount =
            document.getElementById(
                'visibleCount'
            );


        if (productCount) {

            productCount.innerHTML = `

                <i
                    class="
                        fas
                        fa-circle
                        text-[6px]
                        text-blue-500
                        mr-1.5
                        align-middle
                    "
                ></i>

                ${visible} items

            `;

        }


        if (visibleCount) {

            visibleCount.textContent =
                visible;

        }

    }


    /*
    |--------------------------------------------------------------------------
    | SORT PRODUCTS
    |--------------------------------------------------------------------------
    */

    function sortProducts() {

        const sortFilter =
            document.getElementById(
                'sortFilter'
            );


        const grid =
            document.getElementById(
                'productGrid'
            );


        if (
            !sortFilter
            || !grid
        ) {

            return;

        }


        const sortValue =
            sortFilter.value;


        const cards =
            Array.from(
                grid.querySelectorAll(
                    '.product-card'
                )
            );


        cards.sort(
            function (a, b) {

                const nameA =
                    String(
                        a.dataset.name || ''
                    );


                const nameB =
                    String(
                        b.dataset.name || ''
                    );


                const priceA =
                    Number(
                        a.dataset.price || 0
                    );


                const priceB =
                    Number(
                        b.dataset.price || 0
                    );


                switch (sortValue) {

                    case 'price_low':

                        return priceA - priceB;


                    case 'price_high':

                        return priceB - priceA;


                    case 'name_asc':

                        return nameA.localeCompare(
                            nameB
                        );


                    case 'name_desc':

                        return nameB.localeCompare(
                            nameA
                        );


                    default:

                        return 0;

                }

            }
        );


        cards.forEach(
            function (card) {

                grid.appendChild(
                    card
                );

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | RESET FILTERS
    |--------------------------------------------------------------------------
    */

    function resetFilters() {

        const searchInput =
            document.getElementById(
                'searchProduct'
            );


        const categoryFilter =
            document.getElementById(
                'categoryFilter'
            );


        const sortFilter =
            document.getElementById(
                'sortFilter'
            );


        if (searchInput) {

            searchInput.value = '';

        }


        if (categoryFilter) {

            categoryFilter.value =
                'all';

        }


        if (sortFilter) {

            sortFilter.value =
                'default';

        }


        applyFilters();

    }


    /*
    |--------------------------------------------------------------------------
    | MENU CLICK HANDLER
    |--------------------------------------------------------------------------
    |
    | Event Delegation prevents duplicate button binding.
    |
    |--------------------------------------------------------------------------
    */

    function handleMenuClick(event) {

        const addButton =
            event.target.closest(
                '.add-to-cart-btn'
            );


        if (addButton) {

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT
            |--------------------------------------------------------------------------
            |
            | Prevent SPA link/event conflicts.
            |
            |--------------------------------------------------------------------------
            */

            event.preventDefault();

            event.stopPropagation();

            event.stopImmediatePropagation();


            /*
            |--------------------------------------------------------------------------
            | ADD PRODUCT ONCE
            |--------------------------------------------------------------------------
            */

            addToCart(

                addButton.dataset.productId,

                addButton.dataset.productName,

                addButton.dataset.productPrice,

                addButton.dataset.productImage

            );


            return;

        }


        const resetButton =
            event.target.closest(
                '#resetFilters'
            );


        if (resetButton) {

            event.preventDefault();

            resetFilters();

        }

    }


    /*
    |--------------------------------------------------------------------------
    | INITIALIZE MENU
    |--------------------------------------------------------------------------
    */

    function initMenu() {

        console.log(
            'Initializing customer menu...'
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE CART COUNT
        |--------------------------------------------------------------------------
        */

        updateCartCount(
            getCart()
        );


        /*
        |--------------------------------------------------------------------------
        | SINGLE CLICK LISTENER
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'click',
            handleMenuClick,
            true
        );


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        const searchInput =
            document.getElementById(
                'searchProduct'
            );


        if (searchInput) {

            searchInput.oninput =
                applyFilters;

        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORY
        |--------------------------------------------------------------------------
        */

        const categoryFilter =
            document.getElementById(
                'categoryFilter'
            );


        if (categoryFilter) {

            categoryFilter.onchange =
                applyFilters;

        }


        /*
        |--------------------------------------------------------------------------
        | SORT
        |--------------------------------------------------------------------------
        */

        const sortFilter =
            document.getElementById(
                'sortFilter'
            );


        if (sortFilter) {

            sortFilter.onchange =
                function () {

                    sortProducts();

                    applyFilters();

                };

        }


        console.log(
            'Customer menu initialized successfully.'
        );

    }


    /*
    |--------------------------------------------------------------------------
    | START
    |--------------------------------------------------------------------------
    */

    if (
        document.readyState === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initMenu,
            {
                once: true
            }
        );

    } else {

        initMenu();

    }


})();

</script>

@endsection
