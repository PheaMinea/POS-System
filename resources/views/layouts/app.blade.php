{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', $shop_settings->shop_name ?? 'FoodRestaurant')
    </title>

    <link
        rel="icon"
        href="{{ isset($shop_settings) && $shop_settings->shop_logo ? asset('storage/' . $shop_settings->shop_logo) : asset('favicon.svg') }}"
    >


    {{-- ============================================================ --}}
    {{-- TAILWIND CSS --}}
    {{-- ============================================================ --}}

    <script src="https://cdn.tailwindcss.com"></script>


    {{-- ============================================================ --}}
    {{-- FONT AWESOME --}}
    {{-- ============================================================ --}}

    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
    >


    {{-- ============================================================ --}}
    {{-- GOOGLE FONT --}}
    {{-- ============================================================ --}}

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet"
    >


    {{-- ============================================================ --}}
    {{-- SWEET ALERT --}}
    {{-- ============================================================ --}}

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <style>

        * {
            font-family:
                'Inter',
                -apple-system,
                BlinkMacSystemFont,
                sans-serif;
        }


        body {

            background: #f8fafc;

            color: #1e293b;

            overflow-x: hidden;

        }


        /* ========================================================= */
        /* TOP BAR */
        /* ========================================================= */

        .top-bar {

            background:
                linear-gradient(
                    135deg,
                    #1e40af,
                    #1d4ed8
                );

            padding: 0.4rem 0;

            position: relative;

            z-index: 60;

        }


        .announcement-text {

            font-size: 0.8rem;

            font-weight: 500;

            color: white;

            display: flex;

            align-items: center;

            justify-content: center;

            gap: 0.75rem;

            flex-wrap: wrap;

        }


        .announcement-text .highlight {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.2
                );

            padding:
                0.1rem
                0.75rem;

            border-radius: 50px;

            font-weight: 700;

            font-size: 0.7rem;

        }


        .close-btn {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.15
                );

            color: white;

            width: 26px;

            height: 26px;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

            transition: 0.2s;

        }


        .close-btn:hover {

            background:
                rgba(
                    255,
                    255,
                    255,
                    0.25
                );

            transform: rotate(90deg);

        }


        /* ========================================================= */
        /* NAVBAR */
        /* ========================================================= */

        .navbar {

            background: white;

            border-bottom:
                1px solid #e2e8f0;

            padding:
                0.75rem 0;

            position: sticky;

            top: 0;

            z-index: 50;

            box-shadow:
                0 1px 3px
                rgba(
                    0,
                    0,
                    0,
                    0.04
                );

        }


        /* ========================================================= */
        /* LOGO */
        /* ========================================================= */

        .logo-icon {

            width: 36px;

            height: 36px;

            background:
                linear-gradient(
                    135deg,
                    #2563eb,
                    #1d4ed8
                );

            border-radius: 8px;

            display: flex;

            align-items: center;

            justify-content: center;

            color: white;

            overflow: hidden;

        }


        .logo-icon img {

            width: 100%;

            height: 100%;

            object-fit: contain;

        }


        .logo-text {

            font-size: 1.25rem;

            font-weight: 800;

            color: #0f172a;

            min-width: 0;

            max-width: 14rem;

            overflow: hidden;

            text-overflow: ellipsis;

            white-space: nowrap;

        }


        .logo-text .highlight {

            color: #2563eb;

        }


        /* ========================================================= */
        /* NAV LINK */
        /* ========================================================= */

        .nav-link {

            color: #475569;

            font-weight: 500;

            font-size: 0.875rem;

            position: relative;

            transition: 0.2s;

            padding:
                0.4rem 0;

        }


        .nav-link:hover,
        .nav-link.active {

            color: #2563eb;

        }


        .nav-link::after {

            content: '';

            position: absolute;

            bottom: -2px;

            left: 0;

            width: 0;

            height: 2px;

            background: #2563eb;

            transition: 0.3s;

        }


        .nav-link:hover::after,
        .nav-link.active::after {

            width: 100%;

        }


        /* ========================================================= */
        /* SEARCH */
        /* ========================================================= */

        .search-wrapper {

            position: relative;

            flex: 1;

            max-width: 400px;

        }


        .nav-actions {

            display: flex;

            align-items: center;

            gap: 0.5rem;

            flex-shrink: 0;

        }


        .search-icon {

            position: absolute;

            left: 14px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #94a3b8;

            z-index: 2;

        }


        .search-wrapper input {

            width: 100%;

            padding:
                0.5rem
                3rem
                0.5rem
                2.6rem;

            background: #f1f5f9;

            border:
                1px solid #e2e8f0;

            border-radius: 50px;

            outline: none;

            transition: 0.2s;

        }


        .search-wrapper input:focus {

            background: white;

            border-color: #2563eb;

            box-shadow:
                0 0 0 3px
                rgba(
                    37,
                    99,
                    235,
                    0.08
                );

        }


        .search-loading {

            position: absolute;

            right: 16px;

            top: 50%;

            transform:
                translateY(-50%);

            color: #2563eb;

        }


        /* ========================================================= */
        /* CART */
        /* ========================================================= */

        .cart-btn {

            display: flex;

            align-items: center;

            gap: 0.4rem;

            padding:
                0.45rem
                1rem;

            border-radius: 50px;

            background: #f1f5f9;

            border:
                1px solid #e2e8f0;

            color: #475569;

            font-size: 0.85rem;

            transition: 0.2s;

        }


        .cart-btn:hover {

            background: #e2e8f0;

            color: #2563eb;

        }


        .cart-badge {

            background: #2563eb;

            color: white;

            font-size: 0.65rem;

            font-weight: 700;

            min-width: 20px;

            height: 20px;

            padding:
                0 5px;

            border-radius: 50px;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        /* ========================================================= */
        /* ACCOUNT BUTTON */
        /* ========================================================= */

        .auth-btn {

            display: flex;

            align-items: center;

            gap: 0.4rem;

            padding:
                0.45rem
                1rem;

            border-radius: 50px;

            background: #f1f5f9;

            border:
                1px solid #e2e8f0;

            color: #475569;

            font-size: 0.85rem;

            transition: 0.2s;

        }


        .auth-btn:hover {

            background: #e2e8f0;

            color: #2563eb;

        }


        .user-icon {

            width: 26px;

            height: 26px;

            background: #e2e8f0;

            border-radius: 50%;

            display: flex;

            align-items: center;

            justify-content: center;

        }


        .chevron {

            font-size: 0.6rem;

            transition: 0.2s;

        }


        .chevron.rotated {

            transform: rotate(180deg);

        }


        /* ========================================================= */
        /* DROPDOWN */
        /* ========================================================= */

        .dropdown-wrapper {

            position: relative;

        }


        .dropdown-menu {

            opacity: 0;

            visibility: hidden;

            transform:
                translateY(-8px);

            transition: 0.2s;

            background: white;

            border-radius: 14px;

            box-shadow:
                0 15px 45px
                rgba(
                    0,
                    0,
                    0,
                    0.1
                );

            min-width: 250px;

            border:
                1px solid #e2e8f0;

            position: absolute;

            right: 0;

            top:
                calc(
                    100% + 8px
                );

            z-index: 100;

            overflow: hidden;

        }


        .dropdown-menu.show {

            opacity: 1;

            visibility: visible;

            transform:
                translateY(0);

        }


        .dropdown-header {

            padding: 1rem;

            border-bottom:
                1px solid #e2e8f0;

        }


        .header-title {

            color: #0f172a;

            font-weight: 600;

            font-size: 0.9rem;

        }


        .header-sub {

            color: #94a3b8;

            font-size: 0.75rem;

        }


        .dropdown-item {

            display: flex;

            align-items: center;

            gap: 0.75rem;

            padding:
                0.7rem
                1rem;

            color: #475569;

            font-size: 0.85rem;

            font-weight: 500;

            transition: 0.15s;

            width: 100%;

            text-align: left;

        }


        .dropdown-item:hover {

            background: #f1f5f9;

            color: #2563eb;

        }


        .item-icon {

            width: 1.25rem;

            text-align: center;

        }


        .dropdown-divider {

            height: 1px;

            background: #e2e8f0;

            margin:
                0.25rem 0;

        }


        /* ========================================================= */
        /* MOBILE MENU */
        /* ========================================================= */

        .mobile-menu {

            transition:
                all 0.3s ease;

        }


        .mobile-menu-open {

            max-height: 600px;

            opacity: 1;

            padding:
                1rem 0;

        }


        .mobile-menu-closed {

            max-height: 0;

            opacity: 0;

            overflow: hidden;

            padding: 0;

        }


        /* ========================================================= */
        /* FOOTER */
        /* ========================================================= */

        .footer {

            background: white;

            border-top:
                1px solid #e2e8f0;

            margin-top: 4rem;

        }


        /* ========================================================= */
        /* RESPONSIVE */
        /* ========================================================= */

        @media (max-width: 768px) {

            .navbar {

                padding:
                    0.7rem 0;

            }


            .top-bar {

                padding:
                    0.35rem 0;

            }


            .top-bar > div > div {

                gap: 0.5rem;

            }


            .top-bar .flex-1 {

                display: none;

            }


            .announcement-text {

                flex: 1;

                min-width: 0;

                justify-content: flex-start;

                gap: 0.45rem;

                flex-wrap: nowrap;

                overflow: hidden;

                white-space: nowrap;

                font-size: 0.72rem;

            }


            .announcement-text > span:first-of-type {

                min-width: 0;

                overflow: hidden;

                text-overflow: ellipsis;

            }


            .announcement-text .highlight {

                flex-shrink: 0;

                padding:
                    0.12rem
                    0.55rem;

            }


            .announcement-text > span:nth-of-type(3),
            .announcement-text > i:last-child {

                display: none;

            }


            .close-btn {

                width: 24px;

                height: 24px;

                flex: 0 0 24px;

                font-size: 0.75rem;

            }


            .customer-nav-row {

                display: grid;

                grid-template-columns:
                    minmax(0, 1fr)
                    auto;

                gap: 0.7rem;

                align-items: center;

            }


            .navbar > div > div {

                gap: 0.5rem;

            }


            .logo-icon {

                width: 32px;

                height: 32px;

                border-radius: 7px;

                flex: 0 0 32px;

            }


            .search-wrapper {

                max-width: 100%;

                width: 100%;

                grid-column: 1 / -1;

                order: initial;

            }


            .search-wrapper input {

                min-height: 42px;

                padding:
                    0.6rem
                    2.75rem
                    0.6rem
                    2.55rem;

                border-radius: 14px;

            }


            .btn-text {

                display: none;

            }


            .logo-text {

                font-size: 1rem;

                max-width: 9rem;

            }


            .cart-btn,
            .auth-btn {

                width: 38px;

                height: 38px;

                justify-content: center;

                padding: 0;

                border-radius: 999px;

            }


            .cart-btn {

                position: relative;

            }


            .cart-badge {

                position: absolute;

                top: -6px;

                right: -6px;

                min-width: 18px;

                height: 18px;

                font-size: 0.6rem;

            }


            .dropdown-wrapper {

                position: static;

            }


            .nav-actions {

                justify-self: end;

                gap: 0.45rem;

            }


            #mobileToggle {

                width: 38px;

                height: 38px;

                display: inline-flex;

                align-items: center;

                justify-content: center;

                padding: 0;

                border-radius: 999px;

                background: #f1f5f9;

                border:
                    1px solid #e2e8f0;

            }


            .dropdown-menu {

                position: fixed;

                left: 1rem;

                right: 1rem;

                top: 4.5rem;

                width: auto;

                min-width: 0;

                max-height: calc(100vh - 6rem);

                overflow-y: auto;

            }


            .mobile-menu-open {

                max-height: calc(100vh - 5rem);

                overflow-y: auto;

            }


            .footer {

                margin-top: 2.5rem;

            }

        }


        @media (max-width: 420px) {

            .logo-text {

                font-size: 0.95rem;

                max-width: 8.5rem;

            }

            .navbar .flex.flex-wrap.items-center.justify-between {

                flex-wrap: wrap;

            }

            .navbar a.flex-shrink-0 {

                min-width: 0;

            }

        }


        @media (max-width: 360px) {

            .logo-text {

                max-width: 7rem;

            }


            .announcement-text {

                font-size: 0.68rem;

            }


            .cart-btn,
            .auth-btn,
            #mobileToggle {

                width: 36px;

                height: 36px;

            }

        }

    </style>


    @stack('styles')

</head>


<body>


{{-- ============================================================ --}}
{{-- TOP BAR --}}
{{-- ============================================================ --}}

<div
    class="top-bar"
    id="topBar"
>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
    >

        <div
            class="flex items-center justify-between gap-4"
        >

            <div class="flex-1"></div>


            <div class="announcement-text">

                <i class="fas fa-utensils"></i>


                <span>

                    Welcome to

                    <strong>
                        {{ $shop_settings->shop_name ?? 'FoodRestaurant' }}
                    </strong>

                </span>


                <span class="highlight">

                    <i class="fas fa-tag mr-1"></i>

                    10% OFF

                </span>


                <span>

                    on your first order

                </span>


                <i class="fas fa-fire"></i>

            </div>


            <button
                type="button"
                class="close-btn"
                onclick="closeTopBar()"
            >

                <i class="fas fa-times"></i>

            </button>

        </div>

    </div>

</div>



{{-- ============================================================ --}}
{{-- NAVBAR --}}
{{-- ============================================================ --}}

<nav
    class="navbar"
    id="navbar"
>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
    >

        <div
            class="customer-nav-row flex flex-wrap items-center justify-between gap-3"
        >


            {{-- ================================================== --}}
            {{-- LOGO --}}
            {{-- ================================================== --}}

            <a
                href="{{ route('customer.home') }}"
                class="flex items-center gap-2.5 flex-shrink-0"
            >

                <div class="logo-icon">

                    @if(isset($shop_settings) && $shop_settings->shop_logo)

                        <img
                            src="{{ asset('storage/' . $shop_settings->shop_logo) }}"
                            alt="{{ $shop_settings->shop_name ?? 'Website logo' }}"
                        >

                    @else

                        <i class="fas fa-utensils"></i>

                    @endif

                </div>


                <span class="logo-text">

                    {{ $shop_settings->shop_name ?? 'FoodRestaurant' }}

                </span>

            </a>



            {{-- ================================================== --}}
            {{-- NAV LINKS --}}
            {{-- ================================================== --}}

            <div
                class="hidden md:flex items-center gap-6"
            >

                <a
                    href="{{ route('customer.home') }}"
                    class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}"
                >

                    <i class="fas fa-home mr-1"></i>

                    Home

                </a>


                <a
                    href="{{ route('customer.menu') }}"
                    class="nav-link {{ request()->routeIs('customer.menu') ? 'active' : '' }}"
                >

                    <i class="fas fa-utensils mr-1"></i>

                    Menu

                </a>


                @auth('web')

                    <a
                        href="{{ route('customer.orders') }}"
                        class="nav-link {{ request()->routeIs('customer.orders*') ? 'active' : '' }}"
                    >

                        <i class="fas fa-receipt mr-1"></i>

                        Orders

                    </a>

                @endauth

            </div>



            {{-- ================================================== --}}
            {{-- GLOBAL FOOD SEARCH --}}
            {{-- ================================================== --}}

            <div class="search-wrapper">

                <i
                    class="fas fa-search search-icon"
                ></i>


                <input
                    type="text"
                    id="searchInput"
                    placeholder="Search food..."
                    autocomplete="off"
                    value="{{ request('search') }}"
                >


                <div
                    id="searchLoading"
                    class="search-loading hidden"
                >

                    <i class="fas fa-spinner fa-spin"></i>

                </div>

            </div>



            {{-- ================================================== --}}
            {{-- RIGHT SIDE --}}
            {{-- ================================================== --}}

            <div class="nav-actions">


                {{-- ============================================== --}}
                {{-- CART --}}
                {{-- ============================================== --}}

                <a
                    href="{{ route('customer.cart') }}"
                    class="cart-btn"
                >

                    <i class="fas fa-shopping-cart"></i>


                    <span class="hidden sm:inline">

                        Cart

                    </span>


                    <span
                        class="cart-badge"
                        id="cartCount"
                    >

                        0

                    </span>

                </a>



                {{-- ============================================== --}}
                {{-- AUTH USER --}}
                {{-- ============================================== --}}

                @auth('web')

                    @php

                        $currentUser =
                            auth('web')->user();

                        $userRole =
                            strtolower(
                                $currentUser->role ?? 'customer'
                            );

                    @endphp


                    <div
                        class="dropdown-wrapper"
                        id="userDropdown"
                    >


                        <button
                            type="button"
                            onclick="toggleDropdown('userDropdown')"
                            class="auth-btn"
                        >

                            <div class="user-icon">

                                <i class="fas fa-user"></i>

                            </div>


                            <span class="btn-text">

                                {{ $currentUser->name ?? 'User' }}

                            </span>


                            <i
                                class="chevron fas fa-chevron-down"
                            ></i>

                        </button>


                        <div
                            class="dropdown-menu"
                            id="userDropdownMenu"
                        >


                            <div class="dropdown-header">

                                <div
                                    class="flex items-center gap-3"
                                >

                                    <div
                                        class="
                                            w-10
                                            h-10
                                            bg-blue-100
                                            rounded-full
                                            flex
                                            items-center
                                            justify-center
                                            text-blue-600
                                            font-bold
                                        "
                                    >

                                        {{ strtoupper(substr($currentUser->name ?? 'U', 0, 1)) }}

                                    </div>


                                    <div class="min-w-0">

                                        <p
                                            class="header-title truncate"
                                        >

                                            {{ $currentUser->name ?? 'User' }}

                                        </p>


                                        <p
                                            class="header-sub truncate"
                                        >

                                            {{ $currentUser->email ?? '' }}

                                        </p>

                                    </div>

                                </div>

                            </div>



                            {{-- MY PROFILE --}}

                            <a
                                href="{{ route('profile') }}"
                                class="dropdown-item"
                            >

                                <i
                                    class="item-icon fas fa-user-circle"
                                ></i>

                                <span>

                                    My Profile

                                </span>

                            </a>



                            {{-- MY ORDERS --}}

                            <a
                                href="{{ route('customer.orders') }}"
                                class="dropdown-item"
                            >

                                <i
                                    class="item-icon fas fa-receipt"
                                ></i>

                                <span>

                                    My Orders

                                </span>

                            </a>



                            {{-- ADMIN DASHBOARD --}}

                            @if($userRole === 'admin')

                                <div
                                    class="dropdown-divider"
                                ></div>


                                <div
                                    class="px-4 py-1.5"
                                >

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            text-slate-400
                                            uppercase
                                            tracking-wider
                                        "
                                    >

                                        Management

                                    </p>

                                </div>


                                <a
                                    href="{{ route('admin.dashboard') }}"
                                    class="
                                        dropdown-item
                                        text-violet-600
                                        hover:text-violet-700
                                        hover:bg-violet-50
                                    "
                                >

                                    <i
                                        class="
                                            item-icon
                                            fas
                                            fa-gauge-high
                                            text-violet-500
                                        "
                                    ></i>


                                    <span class="font-semibold">

                                        Admin Dashboard

                                    </span>


                                    <i
                                        class="
                                            fas
                                            fa-arrow-right
                                            ml-auto
                                            text-violet-300
                                            text-xs
                                        "
                                    ></i>

                                </a>

                            @endif



                            {{-- CASHIER DASHBOARD --}}

                            @if($userRole === 'cashier')

                                <div
                                    class="dropdown-divider"
                                ></div>


                                <div
                                    class="px-4 py-1.5"
                                >

                                    <p
                                        class="
                                            text-[10px]
                                            font-bold
                                            text-slate-400
                                            uppercase
                                            tracking-wider
                                        "
                                    >

                                        POS System

                                    </p>

                                </div>


                                <a
                                    href="{{ route('cashier.dashboard') }}"
                                    class="
                                        dropdown-item
                                        text-emerald-600
                                        hover:text-emerald-700
                                        hover:bg-emerald-50
                                    "
                                >

                                    <i
                                        class="
                                            item-icon
                                            fas
                                            fa-cash-register
                                            text-emerald-500
                                        "
                                    ></i>


                                    <span class="font-semibold">

                                        Cashier Dashboard

                                    </span>


                                    <i
                                        class="
                                            fas
                                            fa-arrow-right
                                            ml-auto
                                            text-emerald-300
                                            text-xs
                                        "
                                    ></i>

                                </a>

                            @endif



                            {{-- LOGOUT --}}

                            <div
                                class="dropdown-divider"
                            ></div>


                            <form
                                action="{{ route('logout') }}"
                                method="POST"
                            >

                                @csrf


                                <button
                                    type="submit"
                                    class="
                                        dropdown-item
                                        text-red-600
                                        hover:text-red-700
                                        hover:bg-red-50
                                    "
                                >

                                    <i
                                        class="
                                            item-icon
                                            fas
                                            fa-right-from-bracket
                                            text-red-500
                                        "
                                    ></i>


                                    <span>

                                        Logout

                                    </span>

                                </button>

                            </form>

                        </div>

                    </div>


                @else


                    {{-- ========================================== --}}
                    {{-- GUEST ACCOUNT --}}
                    {{-- ========================================== --}}

                    <div
                        class="dropdown-wrapper"
                        id="authDropdown"
                    >

                        <button
                            type="button"
                            onclick="toggleDropdown('authDropdown')"
                            class="auth-btn"
                        >

                            <div class="user-icon">

                                <i class="fas fa-user"></i>

                            </div>


                            <span class="btn-text">

                                Account

                            </span>


                            <i
                                class="chevron fas fa-chevron-down"
                            ></i>

                        </button>


                        <div class="dropdown-menu">

                            <div
                                class="dropdown-header text-center"
                            >

                                <p class="header-title">

                                    Welcome!

                                </p>


                                <p class="header-sub">

                                    Login or create account

                                </p>

                            </div>


                            <a
                                href="{{ route('login') }}"
                                class="dropdown-item"
                            >

                                <i
                                    class="
                                        item-icon
                                        fas
                                        fa-sign-in-alt
                                        text-blue-600
                                    "
                                ></i>

                                Login

                            </a>


                            <a
                                href="{{ route('register') }}"
                                class="dropdown-item"
                            >

                                <i
                                    class="
                                        item-icon
                                        fas
                                        fa-user-plus
                                        text-emerald-600
                                    "
                                ></i>

                                Register

                            </a>

                        </div>

                    </div>

                @endauth



                {{-- MOBILE BUTTON --}}

                <button
                    type="button"
                    id="mobileToggle"
                    class="
                        md:hidden
                        text-slate-600
                        hover:text-blue-600
                        text-xl
                        p-2
                    "
                >

                    <i class="fas fa-bars"></i>

                </button>

            </div>

        </div>



        {{-- ====================================================== --}}
        {{-- MOBILE MENU --}}
        {{-- ====================================================== --}}

        <div
            id="mobileMenu"
            class="
                md:hidden
                mobile-menu
                mobile-menu-closed
                border-t
                border-slate-100
                mt-3
            "
        >

            <div
                class="flex flex-col space-y-2 py-2"
            >

                <a
                    href="{{ route('customer.home') }}"
                    class="
                        flex
                        items-center
                        gap-3
                        px-3
                        py-2.5
                        rounded-lg
                        hover:bg-slate-50
                    "
                >

                    <i class="fas fa-home w-5"></i>

                    Home

                </a>


                <a
                    href="{{ route('customer.menu') }}"
                    class="
                        flex
                        items-center
                        gap-3
                        px-3
                        py-2.5
                        rounded-lg
                        hover:bg-slate-50
                    "
                >

                    <i class="fas fa-utensils w-5"></i>

                    Menu

                </a>


                <a
                    href="{{ route('customer.cart') }}"
                    class="
                        flex
                        items-center
                        gap-3
                        px-3
                        py-2.5
                        rounded-lg
                        hover:bg-slate-50
                    "
                >

                    <i class="fas fa-shopping-cart w-5"></i>

                    Cart

                </a>


                @auth('web')

                    <a
                        href="{{ route('customer.orders') }}"
                        class="
                            flex
                            items-center
                            gap-3
                            px-3
                            py-2.5
                            rounded-lg
                            hover:bg-slate-50
                        "
                    >

                        <i class="fas fa-receipt w-5"></i>

                        My Orders

                    </a>


                    @if($userRole === 'admin')

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="
                                flex
                                items-center
                                gap-3
                                px-3
                                py-2.5
                                rounded-lg
                                bg-violet-50
                                text-violet-600
                            "
                        >

                            <i class="fas fa-gauge-high w-5"></i>

                            Admin Dashboard

                        </a>

                    @endif


                    @if($userRole === 'cashier')

                        <a
                            href="{{ route('cashier.dashboard') }}"
                            class="
                                flex
                                items-center
                                gap-3
                                px-3
                                py-2.5
                                rounded-lg
                                bg-emerald-50
                                text-emerald-600
                            "
                        >

                            <i class="fas fa-cash-register w-5"></i>

                            Cashier Dashboard

                        </a>

                    @endif


                    <form
                        action="{{ route('logout') }}"
                        method="POST"
                    >

                        @csrf


                        <button
                            type="submit"
                            class="
                                flex
                                items-center
                                gap-3
                                px-3
                                py-2.5
                                rounded-lg
                                hover:bg-red-50
                                text-red-600
                                w-full
                            "
                        >

                            <i
                                class="fas fa-right-from-bracket w-5"
                            ></i>

                            Logout

                        </button>

                    </form>

                @endauth

            </div>

        </div>

    </div>

</nav>



{{-- ============================================================ --}}
{{-- PAGE CONTENT --}}
{{-- ============================================================ --}}

<main class="min-h-screen">

    @yield('content')

</main>



{{-- ============================================================ --}}
{{-- FOOTER --}}
{{-- ============================================================ --}}

<footer
    class="footer text-slate-700"
>

    <div
        class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12"
    >

        <div
            class="grid md:grid-cols-4 gap-8"
        >

            <div>

                <div
                    class="flex items-center gap-2 mb-4"
                >

                    <div
                        class="
                            w-10
                            h-10
                            bg-blue-600
                            rounded-xl
                            flex
                            items-center
                            justify-center
                        "
                    >

                        @if(isset($shop_settings) && $shop_settings->shop_logo)

                            <img
                                src="{{ asset('storage/' . $shop_settings->shop_logo) }}"
                                alt="{{ $shop_settings->shop_name ?? 'Website logo' }}"
                                class="w-full h-full object-contain"
                            >

                        @else

                            <i
                                class="fas fa-utensils text-white"
                            ></i>

                        @endif

                    </div>


                    <span
                        class="text-xl font-bold"
                    >

                        {{ $shop_settings->shop_name ?? 'FoodRestaurant' }}

                    </span>

                </div>


                <p
                    class="text-slate-500 text-sm"
                >

                    Delicious food, fast delivery,
                    and great service.

                </p>

            </div>


            <div>

                <h4
                    class="font-semibold mb-4"
                >

                    Quick Links

                </h4>


                <div
                    class="space-y-2 text-sm text-slate-500"
                >

                    <a
                        href="{{ route('customer.home') }}"
                        class="block hover:text-blue-600"
                    >

                        Home

                    </a>


                    <a
                        href="{{ route('customer.menu') }}"
                        class="block hover:text-blue-600"
                    >

                        Menu

                    </a>


                    <a
                        href="{{ route('customer.cart') }}"
                        class="block hover:text-blue-600"
                    >

                        Cart

                    </a>

                </div>

            </div>


            <div>

                <h4
                    class="font-semibold mb-4"
                >

                    Support

                </h4>


                <div
                    class="space-y-2 text-sm text-slate-500"
                >

                    <p>Help Center</p>

                    <p>Privacy Policy</p>

                    <p>Terms of Service</p>

                </div>

            </div>


            <div>

                <h4
                    class="font-semibold mb-4"
                >

                    Contact

                </h4>


                <div
                    class="space-y-2 text-sm text-slate-500"
                >

                    <p>

                        <i
                            class="fas fa-map-pin text-blue-600 mr-2"
                        ></i>

                        {{ $shop_settings->shop_address ?? 'Phnom Penh, Cambodia' }}

                    </p>


                    <p>

                        <i
                            class="fas fa-phone text-blue-600 mr-2"
                        ></i>

                        {{ $shop_settings->shop_phone ?? '+855 12 345 678' }}

                    </p>


                    <p>

                        <i
                            class="fas fa-envelope text-blue-600 mr-2"
                        ></i>

                        {{ $shop_settings->shop_email ?? 'info@foodrestaurant.com' }}

                    </p>

                </div>

            </div>

        </div>


        <div
            class="
                border-t
                border-slate-200
                mt-10
                pt-6
                text-sm
                text-slate-500
            "
        >

            &copy; {{ date('Y') }} {{ $shop_settings->shop_name ?? 'FoodRestaurant' }}.
            All rights reserved.

        </div>

    </div>

</footer>



{{-- ============================================================ --}}
{{-- GLOBAL JAVASCRIPT --}}
{{-- ============================================================ --}}

<script>

    window.customerCartStorageKey =
        'shared_cart';


    /* ========================================================= */
    /* GET CART */
    /* ========================================================= */

    function getGlobalCart() {

        try {

            const data =
                localStorage.getItem(
                    window.customerCartStorageKey
                )
                ||
                localStorage.getItem(
                    'cart'
                );


            return data
                ? JSON.parse(data)
                : [];

        }
        catch (error) {

            console.error(
                'Cart parse error:',
                error
            );


            return [];

        }

    }



    /* ========================================================= */
    /* UPDATE CART BADGE */
    /* ========================================================= */

    function updateGlobalCartCount() {

        const cart =
            getGlobalCart();


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
                function (badge) {

                    badge.textContent =
                        count;

                }
            );

    }



    /* ========================================================= */
    /* CLOSE TOP BAR */
    /* ========================================================= */

    function closeTopBar() {

        const topBar =
            document.getElementById(
                'topBar'
            );


        if (!topBar) {

            return;

        }


        topBar.style.transition =
            'all 0.3s ease';


        topBar.style.transform =
            'translateY(-100%)';


        topBar.style.opacity =
            '0';


        setTimeout(
            function () {

                topBar.style.display =
                    'none';

            },
            300
        );

    }



    /* ========================================================= */
    /* DROPDOWN */
    /* ========================================================= */

    function toggleDropdown(
        wrapperId
    ) {

        const wrapper =
            document.getElementById(
                wrapperId
            );


        if (!wrapper) {

            return;

        }


        const menu =
            wrapper.querySelector(
                '.dropdown-menu'
            );


        const chevron =
            wrapper.querySelector(
                '.chevron'
            );


        if (!menu) {

            return;

        }


        document
            .querySelectorAll(
                '.dropdown-menu'
            )
            .forEach(
                function (item) {

                    if (item !== menu) {

                        item.classList.remove(
                            'show'
                        );

                    }

                }
            );


        menu.classList.toggle(
            'show'
        );


        if (chevron) {

            chevron.classList.toggle(
                'rotated'
            );

        }

    }



    /* ========================================================= */
    /* FOOD SEARCH */
    /* ========================================================= */

    (function () {

        'use strict';


        let searchTimer = null;


        const MENU_URL =
            @json(route('customer.menu'));



        /* ===================================================== */
        /* SHOW LOADING */
        /* ===================================================== */

        function showSearchLoading() {

            const loading =
                document.getElementById(
                    'searchLoading'
                );


            if (loading) {

                loading.classList.remove(
                    'hidden'
                );

            }

        }



        /* ===================================================== */
        /* HIDE LOADING */
        /* ===================================================== */

        function hideSearchLoading() {

            const loading =
                document.getElementById(
                    'searchLoading'
                );


            if (loading) {

                loading.classList.add(
                    'hidden'
                );

            }

        }



        /* ===================================================== */
        /* UPDATE URL WITHOUT REFRESH */
        /* ===================================================== */

        function updateSearchUrl(
            keyword
        ) {

            const url =
                new URL(
                    window.location.href
                );


            if (keyword === '') {

                url.searchParams.delete(
                    'search'
                );

            }
            else {

                url.searchParams.set(
                    'search',
                    keyword
                );

            }


            window.history.replaceState(
                {},
                '',
                url.toString()
            );

        }



        /* ===================================================== */
        /* SEARCH FOOD */
        /* ===================================================== */

        function searchFood(
            keyword
        ) {

            keyword =
                String(
                    keyword || ''
                ).trim();


            /*
            |------------------------------------------------------
            | MENU PAGE
            |------------------------------------------------------
            */

            if (
                typeof window.filterMenuProducts
                ===
                'function'
            ) {

                window.filterMenuProducts(
                    keyword
                );


                updateSearchUrl(
                    keyword
                );


                hideSearchLoading();


                return;

            }


            /*
            |------------------------------------------------------
            | OTHER PAGE
            |------------------------------------------------------
            */

            if (keyword === '') {

                hideSearchLoading();


                return;

            }


            window.location.href =
                MENU_URL
                +
                '?search='
                +
                encodeURIComponent(
                    keyword
                );

        }



        /* ===================================================== */
        /* INITIALIZE SEARCH */
        /* ===================================================== */

        function initFoodSearch() {

            const searchInput =
                document.getElementById(
                    'searchInput'
                );


            if (!searchInput) {

                return;

            }



            /* ================================================= */
            /* SEARCH WHILE TYPING */
            /* ================================================= */

            searchInput.addEventListener(
                'input',
                function () {

                    const keyword =
                        this.value;


                    clearTimeout(
                        searchTimer
                    );


                    /*
                    |----------------------------------------------
                    | MENU PAGE
                    |----------------------------------------------
                    */

                    if (
                        typeof window.filterMenuProducts
                        ===
                        'function'
                    ) {

                        showSearchLoading();


                        searchTimer =
                            setTimeout(
                                function () {

                                    searchFood(
                                        keyword
                                    );

                                },
                                200
                            );


                        return;

                    }


                    /*
                    |----------------------------------------------
                    | OTHER PAGE
                    |----------------------------------------------
                    */

                    if (
                        keyword
                            .trim()
                            .length
                        <
                        2
                    ) {

                        hideSearchLoading();


                        return;

                    }


                    showSearchLoading();


                    searchTimer =
                        setTimeout(
                            function () {

                                searchFood(
                                    keyword
                                );

                            },
                            700
                        );

                }
            );



            /* ================================================= */
            /* ENTER SEARCH */
            /* ================================================= */

            searchInput.addEventListener(
                'keydown',
                function (
                    event
                ) {

                    if (
                        event.key
                        !==
                        'Enter'
                    ) {

                        return;

                    }


                    event.preventDefault();


                    clearTimeout(
                        searchTimer
                    );


                    showSearchLoading();


                    searchFood(
                        this.value
                    );

                }
            );

        }



        /* ===================================================== */
        /* START SEARCH */
        /* ===================================================== */

        if (
            document.readyState
            ===
            'loading'
        ) {

            document.addEventListener(
                'DOMContentLoaded',
                initFoodSearch
            );

        }
        else {

            initFoodSearch();

        }


    })();



    /* ========================================================= */
    /* DOCUMENT READY */
    /* ========================================================= */

    document.addEventListener(
        'DOMContentLoaded',
        function () {


            updateGlobalCartCount();



            /* ================================================= */
            /* MOBILE MENU */
            /* ================================================= */

            const mobileToggle =
                document.getElementById(
                    'mobileToggle'
                );


            const mobileMenu =
                document.getElementById(
                    'mobileMenu'
                );


            if (
                mobileToggle
                &&
                mobileMenu
            ) {

                mobileToggle.addEventListener(
                    'click',
                    function () {

                        mobileMenu.classList.toggle(
                            'mobile-menu-closed'
                        );


                        mobileMenu.classList.toggle(
                            'mobile-menu-open'
                        );

                    }
                );

            }


        }
    );



    /* ========================================================= */
    /* CLOSE DROPDOWN OUTSIDE */
    /* ========================================================= */

    document.addEventListener(
        'click',
        function (
            event
        ) {

            document
                .querySelectorAll(
                    '.dropdown-wrapper'
                )
                .forEach(
                    function (wrapper) {

                        if (
                            !wrapper.contains(
                                event.target
                            )
                        ) {

                            const menu =
                                wrapper.querySelector(
                                    '.dropdown-menu'
                                );


                            const chevron =
                                wrapper.querySelector(
                                    '.chevron'
                                );


                            if (menu) {

                                menu.classList.remove(
                                    'show'
                                );

                            }


                            if (chevron) {

                                chevron.classList.remove(
                                    'rotated'
                                );

                            }

                        }

                    }
                );

        }
    );



    /* ========================================================= */
    /* STORAGE CHANGE */
    /* ========================================================= */

    window.addEventListener(
        'storage',
        function (
            event
        ) {

            if (
                event.key
                ===
                window.customerCartStorageKey
                ||
                event.key
                ===
                'cart'
            ) {

                updateGlobalCartCount();

            }

        }
    );


    console.log(
        '🍽️ FoodRestaurant App Layout Loaded'
    );

</script>

@if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: 'Login successful',
                text: @json(session('success')),
                timer: 1800,
                showConfirmButton: false
            });
        });
    </script>
@endif


@stack('scripts')


</body>

</html>
