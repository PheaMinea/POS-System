{{-- resources/views/customer/home.blade.php --}}

@extends('layouts.app')

@section('title', 'Home - Food Restaurant')

@section('content')


{{-- ============================================================ --}}
{{-- HERO SECTION --}}
{{-- ============================================================ --}}

<section
    id="homePage"
    class="
        relative
        overflow-hidden
        bg-gradient-to-br
        from-blue-600
        via-blue-700
        to-blue-900
        text-white
    "
>

    {{-- Decorative Elements --}}

    <div
        class="
            absolute
            top-20
            right-20
            w-64
            h-64
            bg-white/5
            rounded-full
            blur-2xl
        "
    ></div>

    <div
        class="
            absolute
            bottom-20
            left-20
            w-48
            h-48
            bg-white/5
            rounded-full
            blur-2xl
        "
    ></div>

    <div
        class="
            absolute
            top-1/2
            left-1/2
            -translate-x-1/2
            -translate-y-1/2
            w-96
            h-96
            bg-white/5
            rounded-full
            blur-3xl
        "
    ></div>


    <div
        class="
            max-w-7xl
            mx-auto
            px-4
            sm:px-6
            lg:px-8
            py-16
            md:py-24
            relative
            z-10
        "
    >

        <div
            class="
                grid
                md:grid-cols-2
                gap-12
                items-center
            "
        >


            {{-- ================================================== --}}
            {{-- LEFT --}}
            {{-- ================================================== --}}

            <div class="fade-in-up">

                <div
                    class="
                        inline-flex
                        items-center
                        gap-2
                        bg-white/20
                        backdrop-blur-sm
                        px-4
                        py-2
                        rounded-full
                        text-sm
                        font-medium
                        mb-6
                    "
                >

                    <i
                        class="
                            fas
                            fa-circle
                            text-[6px]
                            text-white/70
                        "
                    ></i>

                    <span>
                        Fresh & Delicious
                    </span>

                    <i
                        class="
                            fas
                            fa-circle
                            text-[6px]
                            text-white/70
                        "
                    ></i>

                </div>


                <h1
                    class="
                        text-4xl
                        sm:text-5xl
                        lg:text-6xl
                        font-extrabold
                        leading-tight
                    "
                >

                    Delicious Food

                    <span class="text-blue-200">

                        Delivered Fast

                    </span>

                    🚀

                </h1>


                <p
                    class="
                        mt-6
                        text-lg
                        text-blue-100/90
                        leading-relaxed
                        max-w-lg
                    "
                >

                    Order your favorite meals online
                    and enjoy fresh, hot food delivered
                    right to your door.

                    Taste the difference!

                </p>


                <div
                    class="
                        mt-8
                        flex
                        flex-wrap
                        gap-4
                        sm:items-center
                    "
                >

                    <a
                        href="{{ route('customer.menu') }}"
                        class="
                            w-full
                            sm:w-auto
                            bg-white
                            text-blue-600
                            hover:bg-blue-50
                            px-8
                            py-3.5
                            rounded-xl
                            font-semibold
                            transition
                            flex
                            items-center
                            justify-center
                            gap-2
                            shadow-lg
                            shadow-blue-500/30
                        "
                    >

                        <i class="fas fa-utensils"></i>

                        Order Now

                        <i
                            class="
                                fas
                                fa-arrow-right
                                ml-1
                            "
                        ></i>

                    </a>


                    <a
                        href="#categories"
                        class="
                            w-full
                            sm:w-auto
                            bg-white/20
                            backdrop-blur-sm
                            hover:bg-white/30
                            text-white
                            px-8
                            py-3.5
                            rounded-xl
                            font-medium
                            transition
                            flex
                            items-center
                            gap-2
                            border
                            border-white/20
                        "
                    >

                        <i class="fas fa-chevron-down"></i>

                        Explore Menu

                    </a>

                </div>


                {{-- Stats --}}

                <div
                    class="
                        flex
                        gap-8
                        mt-10
                        pt-8
                        border-t
                        border-white/20
                    "
                >

                    <div>

                        <p class="text-3xl font-bold">
                            500+
                        </p>

                        <p
                            class="
                                text-sm
                                text-blue-100/80
                            "
                        >
                            Happy Customers
                        </p>

                    </div>


                    <div>

                        <p class="text-3xl font-bold">
                            100+
                        </p>

                        <p
                            class="
                                text-sm
                                text-blue-100/80
                            "
                        >
                            Food Items
                        </p>

                    </div>


                    <div>

                        <p class="text-3xl font-bold">
                            4.9⭐
                        </p>

                        <p
                            class="
                                text-sm
                                text-blue-100/80
                            "
                        >
                            Customer Rating
                        </p>

                    </div>

                </div>

            </div>



            {{-- ================================================== --}}
            {{-- RIGHT --}}
            {{-- ================================================== --}}

            <div
                class="
                    relative
                    flex
                    justify-center
                    md:justify-end
                "
            >

                <div class="relative">

                    <img
                        src="https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&h=400&fit=crop"
                        alt="Delicious Food"
                        class="
                            rounded-3xl
                            shadow-2xl
                            object-cover
                            w-full
                            max-w-md
                            h-80
                            md:h-96
                        "
                    >


                    <div
                        class="
                            absolute
                            -bottom-6
                            -right-6
                            bg-white
                            rounded-2xl
                            p-4
                            shadow-xl
                        "
                    >

                        <div
                            class="
                                flex
                                items-center
                                gap-3
                            "
                        >

                            <div
                                class="
                                    w-12
                                    h-12
                                    bg-blue-100
                                    rounded-xl
                                    flex
                                    items-center
                                    justify-center
                                "
                            >

                                <i
                                    class="
                                        fas
                                        fa-star
                                        text-blue-500
                                        text-xl
                                    "
                                ></i>

                            </div>


                            <div>

                                <p
                                    class="
                                        font-bold
                                        text-slate-800
                                    "
                                >
                                    4.9/5
                                </p>

                                <p
                                    class="
                                        text-xs
                                        text-slate-400
                                    "
                                >
                                    2.5k+ reviews
                                </p>

                            </div>

                        </div>

                    </div>


                    <div
                        class="
                            absolute
                            -top-4
                            -left-4
                            bg-white
                            rounded-2xl
                            p-3
                            shadow-xl
                        "
                    >

                        <div
                            class="
                                flex
                                items-center
                                gap-2
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-clock
                                    text-blue-500
                                "
                            ></i>

                            <span
                                class="
                                    text-sm
                                    font-medium
                                    text-slate-700
                                "
                            >
                                30-45 min
                            </span>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>



{{-- ============================================================ --}}
{{-- CATEGORIES --}}
{{-- ============================================================ --}}

<section
    id="categories"
    class="
        max-w-7xl
        mx-auto
        px-4
        sm:px-6
        lg:px-8
        py-16
        md:py-20
    "
>

    <div class="text-center mb-12">

        <span
            class="
                text-sm
                font-semibold
                text-red-600
                uppercase
                tracking-wider
            "
        >

            Categories

        </span>


        <h2
            class="
                text-3xl
                md:text-4xl
                font-bold
                text-slate-800
                mt-2
            "
        >

            Explore Our

            <span class="text-red-600">

                Food Categories

            </span>

        </h2>


        <p
            class="
                text-slate-400
                mt-2
                max-w-md
                mx-auto
            "
        >

            Discover delicious meals from our
            diverse food categories

        </p>

    </div>


    <div
        class="
            grid
            grid-cols-2
            sm:grid-cols-3
            lg:grid-cols-4
            gap-4
            md:gap-6
        "
    >

        @forelse($categories as $category)

            <a
                href="{{ route('customer.menu', ['category' => $category->id]) }}"
                class="
                    category-card
                    bg-white
                    rounded-2xl
                    p-6
                    text-center
                    shadow-sm
                    hover:shadow-md
                    transition
                    group
                "
            >

                <div
                    class="
                        w-16
                        h-16
                        bg-gradient-to-br
                        from-red-100
                        to-red-200
                        rounded-full
                        mx-auto
                        flex
                        items-center
                        justify-center
                        group-hover:scale-110
                        transition
                    "
                >

                    <i
                        class="
                            fas
                            fa-utensils
                            text-red-500
                            text-2xl
                        "
                    ></i>

                </div>


                <h3
                    class="
                        mt-4
                        font-semibold
                        text-slate-800
                        group-hover:text-red-600
                        transition
                    "
                >

                    {{ $category->name }}

                </h3>


                <p
                    class="
                        text-xs
                        text-slate-400
                        mt-1
                    "
                >

                    {{ $category->products_count ?? 0 }}
                    items

                </p>

            </a>

        @empty

            <div
                class="
                    col-span-full
                    text-center
                    py-12
                    text-slate-400
                "
            >

                <i
                    class="
                        fas
                        fa-folder-open
                        text-4xl
                        block
                        mb-3
                        opacity-30
                    "
                ></i>

                <p
                    class="
                        text-lg
                        font-medium
                        text-slate-500
                    "
                >

                    No Categories Found

                </p>

            </div>

        @endforelse

    </div>

</section>



{{-- ============================================================ --}}
{{-- POPULAR PRODUCTS --}}
{{-- ============================================================ --}}

<section
    id="popularProducts"
    class="
        max-w-7xl
        mx-auto
        px-4
        sm:px-6
        lg:px-8
        pb-16
        md:pb-20
    "
>

    <div
        class="
            flex
            flex-wrap
            justify-between
            items-center
            mb-12
        "
    >

        <div>

            <span
                class="
                    text-sm
                    font-semibold
                    text-red-600
                    uppercase
                    tracking-wider
                "
            >

                Popular

            </span>


            <h2
                class="
                    text-3xl
                    md:text-4xl
                    font-bold
                    text-slate-800
                    mt-2
                "
            >

                Our

                <span class="text-red-600">

                    Popular Foods

                </span>

            </h2>

        </div>


        <a
            href="{{ route('customer.menu') }}"
            class="
                text-red-600
                hover:text-red-700
                font-medium
                transition
                flex
                items-center
                gap-1
            "
        >

            View All

            <i
                class="
                    fas
                    fa-arrow-right
                    text-sm
                "
            ></i>

        </a>

    </div>


    <div
        class="
            grid
            grid-cols-1
            sm:grid-cols-2
            lg:grid-cols-4
            gap-6
        "
    >

        @forelse($products as $product)

            <div
                class="
                    product-card
                    bg-white
                    rounded-2xl
                    overflow-hidden
                    shadow-sm
                    hover:shadow-xl
                    transition
                    group
                "
                data-product-card
                data-name="{{ strtolower($product->name) }}"
            >


                {{-- IMAGE --}}

                <div
                    class="
                        relative
                        overflow-hidden
                        bg-slate-100
                    "
                >

                    @if($product->image_url)

                        <img
                            src="{{ $product->image_url }}"
                            alt="{{ $product->name }}"
                            class="
                                product-image
                                w-full
                                h-52
                                object-cover
                            "
                        >

                    @else

                        <div
                            class="
                                w-full
                                h-52
                                bg-gradient-to-br
                                from-red-100
                                to-red-200
                                flex
                                items-center
                                justify-center
                            "
                        >

                            <i
                                class="
                                    fas
                                    fa-image
                                    text-4xl
                                    text-red-300/50
                                "
                            ></i>

                        </div>

                    @endif



                    {{-- POPULAR BADGE --}}

                    <div
                        class="
                            absolute
                            top-3
                            left-3
                            bg-red-600
                            text-white
                            text-xs
                            font-bold
                            px-3
                            py-1
                            rounded-full
                        "
                    >

                        <i class="fas fa-fire mr-1"></i>

                        Popular

                    </div>



                    {{-- QUICK ADD --}}

                    <button
                        type="button"
                        class="
                            add-to-cart-btn
                            absolute
                            bottom-3
                            right-3
                            bg-white
                            rounded-full
                            p-3
                            shadow-lg
                            hover:bg-red-600
                            hover:text-white
                            transition
                        "
                        data-product-id="{{ $product->id }}"
                        data-product-name="{{ $product->name }}"
                        data-product-price="{{ $product->price }}"
                        data-product-image="{{ $product->image_url }}"
                    >

                        <i class="fas fa-plus"></i>

                    </button>

                </div>



                {{-- CONTENT --}}

                <div class="p-5">

                    <div
                        class="
                            flex
                            justify-between
                            items-start
                        "
                    >

                        <div>

                            <h3
                                class="
                                    font-semibold
                                    text-slate-800
                                    text-lg
                                    group-hover:text-red-600
                                    transition
                                "
                            >

                                {{ $product->name }}

                            </h3>


                            <p
                                class="
                                    text-xs
                                    text-slate-400
                                    mt-1
                                "
                            >

                                <i
                                    class="
                                        fas
                                        fa-tag
                                        mr-1
                                    "
                                ></i>

                                {{ $product->category?->name ?? 'Uncategorized' }}

                            </p>

                        </div>


                        <span
                            class="
                                text-xs
                                bg-red-50
                                text-red-600
                                px-2
                                py-1
                                rounded-full
                                font-medium
                            "
                        >

                            ★ 4.8

                        </span>

                    </div>


                    <div
                        class="
                            flex
                            items-center
                            justify-between
                            mt-4
                        "
                    >

                        <span
                            class="
                                text-xl
                                font-bold
                                text-red-600
                            "
                        >

                            ${{ number_format($product->price, 2) }}

                        </span>


                        <button
                            type="button"
                            class="
                                add-to-cart-btn
                                bg-red-600
                                hover:bg-red-700
                                text-white
                                px-4
                                py-2
                                rounded-xl
                                text-sm
                                font-medium
                                transition
                                flex
                                items-center
                                gap-1.5
                            "
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

            <div
                class="
                    col-span-full
                    bg-white
                    rounded-2xl
                    p-12
                    text-center
                "
            >

                <i
                    class="
                        fas
                        fa-box-open
                        text-5xl
                        block
                        mb-4
                        text-slate-300
                    "
                ></i>


                <p
                    class="
                        text-lg
                        font-medium
                        text-slate-600
                    "
                >

                    No Products Found

                </p>


                <p
                    class="
                        text-sm
                        text-slate-400
                        mt-1
                    "
                >

                    Please add products from
                    the admin dashboard

                </p>

            </div>

        @endforelse

    </div>

</section>



{{-- ============================================================ --}}
{{-- CTA --}}
{{-- ============================================================ --}}

<section
    class="
        max-w-7xl
        mx-auto
        px-4
        sm:px-6
        lg:px-8
        pb-16
    "
>

    <div
        class="
            bg-gradient-to-r
            from-blue-600
            to-blue-800
            rounded-3xl
            p-8
            md:p-12
            text-white
            relative
            overflow-hidden
        "
    >

        <div
            class="
                absolute
                top-0
                right-0
                w-64
                h-64
                bg-white/5
                rounded-full
                blur-2xl
            "
        ></div>


        <div
            class="
                relative
                z-10
                flex
                flex-col
                md:flex-row
                items-center
                justify-between
                gap-6
            "
        >

            <div>

                <h3
                    class="
                        text-2xl
                        md:text-3xl
                        font-bold
                    "
                >

                    Hungry? Order Now!

                </h3>


                <p
                    class="
                        text-blue-100/80
                        mt-2
                    "
                >

                    Get your favorite meals
                    delivered to your door

                </p>

            </div>


            <a
                href="{{ route('customer.menu') }}"
                class="
                    bg-white
                    text-blue-600
                    hover:bg-blue-50
                    px-8
                    py-3.5
                    rounded-xl
                    font-semibold
                    transition
                    shadow-lg
                    flex
                    items-center
                    gap-2
                "
            >

                <i class="fas fa-utensils"></i>

                Order Now

            </a>

        </div>

    </div>

</section>


@endsection



{{-- ============================================================ --}}
{{-- HOME STYLES --}}
{{-- ============================================================ --}}

@push('styles')

<style>

    .fade-in-up {

        animation:
            fadeInUp
            0.8s
            ease-out
            forwards;

    }


    @keyframes fadeInUp {

        from {

            opacity: 0;

            transform:
                translateY(30px);

        }


        to {

            opacity: 1;

            transform:
                translateY(0);

        }

    }


    .category-card {

        transition:
            all
            0.3s
            cubic-bezier(
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


    .category-card:hover {

        transform:
            translateY(-6px);

        border-color:
            #dc2626;

    }


    .product-card {

        transition:
            all
            0.35s
            cubic-bezier(
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
            #dc2626;

    }


    .product-image {

        transition:
            transform
            0.5s
            ease;

    }


    .product-card:hover
    .product-image {

        transform:
            scale(1.05);

    }


    .add-to-cart-btn {

        cursor: pointer;

    }


    .add-to-cart-btn:disabled {

        cursor: not-allowed;

        opacity: 0.8;

    }

</style>

@endpush



{{-- ============================================================ --}}
{{-- HOME JAVASCRIPT --}}
{{-- ============================================================ --}}

@push('scripts')

<script>

(function () {

    'use strict';


    /* ========================================================= */
    /* CART STORAGE */
    /* ========================================================= */

    const CART_STORAGE_KEY =
        'shared_cart';



    /* ========================================================= */
    /* GET CART */
    /* ========================================================= */

    function getHomeCart() {

        try {

            const data =
                localStorage.getItem(
                    CART_STORAGE_KEY
                );


            if (!data) {

                return [];

            }


            const cart =
                JSON.parse(data);


            return Array.isArray(cart)
                ? cart
                : [];

        }
        catch (error) {

            console.error(
                '❌ Cart parse error:',
                error
            );


            return [];

        }

    }



    /* ========================================================= */
    /* SAVE CART */
    /* ========================================================= */

    function saveHomeCart(
        cart
    ) {

        localStorage.setItem(
            CART_STORAGE_KEY,
            JSON.stringify(cart)
        );


        updateHomeCartCount();

    }



    /* ========================================================= */
    /* UPDATE CART COUNT */
    /* ========================================================= */

    function updateHomeCartCount() {

        const cart =
            getHomeCart();


        const count =
            cart.reduce(
                function (
                    total,
                    item
                ) {

                    return total
                        +
                        Number(
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
                function (
                    badge
                ) {

                    badge.textContent =
                        count;

                }
            );

    }



    /* ========================================================= */
    /* ESCAPE HTML */
    /* ========================================================= */

    function escapeHtml(
        value
    ) {

        const div =
            document.createElement(
                'div'
            );


        div.textContent =
            String(
                value || ''
            );


        return div.innerHTML;

    }



    /* ========================================================= */
    /* TOAST */
    /* ========================================================= */

    function showCartToast(
        productName
    ) {

        document
            .getElementById(
                'home-cart-toast'
            )
            ?.remove();


        const toast =
            document.createElement(
                'div'
            );


        toast.id =
            'home-cart-toast';


        toast.className = `
            fixed
            top-24
            left-4
            right-4
            sm:left-auto
            sm:right-5
            z-[9999]
            bg-white
            border
            border-emerald-100
            rounded-2xl
            shadow-2xl
            p-4
            w-auto
            sm:min-w-[300px]
            sm:max-w-sm
        `;


        toast.innerHTML = `

            <div
                class="
                    flex
                    items-center
                    gap-3
                "
            >

                <div
                    class="
                        w-11
                        h-11
                        bg-emerald-100
                        rounded-full
                        flex
                        items-center
                        justify-center
                    "
                >

                    <i
                        class="
                            fas
                            fa-check
                            text-emerald-600
                        "
                    ></i>

                </div>


                <div class="flex-1 min-w-0">

                    <p
                        class="
                            text-sm
                            font-bold
                            text-slate-800
                        "
                    >

                        Added to Cart

                    </p>


                    <p
                        class="
                            text-xs
                            text-slate-500
                            truncate
                        "
                    >

                        ${escapeHtml(productName)}

                    </p>

                </div>


                <button
                    type="button"
                    data-close-home-toast
                    class="
                        text-slate-400
                        hover:text-slate-600
                    "
                >

                    <i class="fas fa-times"></i>

                </button>

            </div>

        `;


        document.body.appendChild(
            toast
        );


        setTimeout(
            function () {

                document
                    .getElementById(
                        'home-cart-toast'
                    )
                    ?.remove();

            },
            2500
        );

    }



    /* ========================================================= */
    /* ADD TO CART */
    /* ========================================================= */

    function addProductToCart(
        productId,
        productName,
        productPrice,
        productImage
    ) {

        let cart =
            getHomeCart();


        const id =
            Number(productId);


        const price =
            Number(productPrice);


        const existingItem =
            cart.find(
                function (
                    item
                ) {

                    return Number(
                        item.id
                    ) === id;

                }
            );


        if (existingItem) {

            existingItem.qty =
                Number(
                    existingItem.qty || 0
                )
                + 1;

        }
        else {

            cart.push({

                id: id,

                name:
                    productName,

                price:
                    price,

                qty: 1,

                image:
                    productImage || ''

            });

        }


        saveHomeCart(
            cart
        );


        showCartToast(
            productName
        );


        console.log(
            '🛒 Added:',
            productName
        );


        console.log(
            '📦 Cart:',
            cart
        );

    }



    /* ========================================================= */
    /* GLOBAL CLICK HANDLER */
    /* ========================================================= */

    if (
        !window.homeCartHandlerRegistered
    ) {

        window.homeCartHandlerRegistered =
            true;


        document.addEventListener(
            'click',
            function (
                event
            ) {


                /* ================================================= */
                /* CLOSE TOAST */
                /* ================================================= */

                const closeToast =
                    event.target.closest(
                        '[data-close-home-toast]'
                    );


                if (closeToast) {

                    document
                        .getElementById(
                            'home-cart-toast'
                        )
                        ?.remove();


                    return;

                }



                /* ================================================= */
                /* ADD TO CART */
                /* ================================================= */

                const button =
                    event.target.closest(
                        '.add-to-cart-btn'
                    );


                if (!button) {

                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | HOME PAGE ONLY
                |--------------------------------------------------------------------------
                */

                const homePage =
                    document.getElementById(
                        'homePage'
                    );


                if (!homePage) {

                    return;

                }


                event.preventDefault();

                event.stopPropagation();


                /*
                |--------------------------------------------------------------------------
                | PREVENT DOUBLE CLICK
                |--------------------------------------------------------------------------
                */

                if (
                    button.dataset.processing
                    === 'true'
                ) {

                    return;

                }


                button.dataset.processing =
                    'true';


                const productId =
                    button.dataset.productId;


                const productName =
                    button.dataset.productName;


                const productPrice =
                    button.dataset.productPrice;


                const productImage =
                    button.dataset.productImage;


                if (!productId) {

                    button.dataset.processing =
                        'false';


                    return;

                }


                addProductToCart(

                    productId,

                    productName,

                    productPrice,

                    productImage

                );



                /* ================================================= */
                /* BUTTON FEEDBACK */
                /* ================================================= */

                const originalHtml =
                    button.innerHTML;


                button.innerHTML = `

                    <i class="fas fa-check"></i>

                    <span>
                        Added
                    </span>

                `;


                button.disabled =
                    true;


                setTimeout(
                    function () {

                        button.innerHTML =
                            originalHtml;


                        button.disabled =
                            false;


                        button.dataset.processing =
                            'false';

                    },
                    700
                );

            }
        );

    }



    /* ========================================================= */
    /* INITIALIZE HOME */
    /* ========================================================= */

    function initializeHomePage() {

        updateHomeCartCount();


        console.log(
            '🏠 Home Page Initialized'
        );

    }



    /* ========================================================= */
    /* DOM READY */
    /* ========================================================= */

    if (
        document.readyState
        === 'loading'
    ) {

        document.addEventListener(
            'DOMContentLoaded',
            initializeHomePage,
            {
                once: true
            }
        );

    }
    else {

        initializeHomePage();

    }



    /* ========================================================= */
    /* SPA PAGE LOADED */
    /* ========================================================= */

    document.addEventListener(
        'spa:page-loaded',
        initializeHomePage
    );


})();

</script>

@endpush
