{{-- resources/views/layouts/customer.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'FoodRestaurant')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            background: #f8fafc;
            color: #1e293b;
        }

        /* ===== TOP BAR / ANNOUNCEMENT BAR ===== */
        .top-bar {
            background: linear-gradient(135deg, #1e40af, #1d4ed8);
            padding: 0.4rem 0;
            position: relative;
            z-index: 60;
        }

        .top-bar .announcement-text {
            font-size: 0.8rem;
            font-weight: 500;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            flex-wrap: wrap;
        }

        .top-bar .announcement-text i {
            font-size: 0.9rem;
        }

        .top-bar .announcement-text .highlight {
            background: rgba(255, 255, 255, 0.2);
            padding: 0.1rem 0.75rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.7rem;
        }

        .top-bar .close-btn {
            background: rgba(255, 255, 255, 0.15);
            border: none;
            color: white;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.7rem;
            flex-shrink: 0;
        }

        .top-bar .close-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: rotate(90deg);
        }

        /* ===== NAVBAR ===== */
        .navbar {
            background: white;
            border-bottom: 1px solid #e2e8f0;
            padding: 0.75rem 0;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }

        /* ===== LOGO ===== */
        .logo-text {
            font-size: 1.25rem;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
        }

        .logo-text .highlight {
            color: #2563eb;
        }

        .logo-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        /* ===== NAV LINKS ===== */
        .nav-link {
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            padding: 0.4rem 0;
            position: relative;
        }

        .nav-link:hover {
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
            transition: width 0.3s ease;
            border-radius: 2px;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #2563eb;
        }

        /* ===== SEARCH BAR ===== */
        .search-wrapper {
            position: relative;
            flex: 1;
            max-width: 400px;
        }

        .search-wrapper .search-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .search-wrapper input {
            width: 100%;
            padding: 0.5rem 1rem 0.5rem 2.6rem;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            color: #1e293b;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            outline: none;
        }

        .search-wrapper input::placeholder {
            color: #94a3b8;
        }

        .search-wrapper input:focus {
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
        }

        /* ===== CART BUTTON ===== */
        .cart-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            border-radius: 50px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            cursor: pointer;
            text-decoration: none;
        }

        .cart-btn:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .cart-btn .cart-badge {
            background: #2563eb;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            padding: 0.1rem 0.4rem;
            border-radius: 50px;
        }

        /* ===== AUTH BUTTON ===== */
        .auth-btn {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1.25rem 0.45rem 0.9rem;
            border-radius: 50px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-weight: 500;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .auth-btn:hover {
            background: #e2e8f0;
            border-color: #cbd5e1;
            color: #1e293b;
        }

        .auth-btn .user-icon {
            width: 26px;
            height: 26px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-size: 0.75rem;
            transition: color 0.2s ease;
        }

        .auth-btn:hover .user-icon {
            color: #2563eb;
        }

        .auth-btn .chevron {
            font-size: 0.6rem;
            color: #94a3b8;
            transition: transform 0.2s ease;
        }

        .auth-btn .chevron.rotated {
            transform: rotate(180deg);
        }

        /* ===== REGISTER BUTTON ===== */
        .register-btn {
            padding: 0.45rem 1.5rem;
            border-radius: 50px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            white-space: nowrap;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .register-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        /* ===== DROPDOWN ===== */
        .dropdown-wrapper {
            position: relative;
        }

        .dropdown-menu {
            opacity: 0;
            visibility: hidden;
            transform: translateY(-8px);
            transition: all 0.2s ease;
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
            min-width: 220px;
            border: 1px solid #e2e8f0;
            position: absolute;
            right: 0;
            top: calc(100% + 8px);
            z-index: 50;
            overflow: hidden;
        }

        .dropdown-menu.show {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            color: #475569;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }

        .dropdown-item:hover {
            background: #f1f5f9;
            color: #2563eb;
        }

        .dropdown-item .item-icon {
            width: 1.25rem;
            text-align: center;
            font-size: 0.85rem;
            flex-shrink: 0;
            color: #94a3b8;
        }

        .dropdown-item:hover .item-icon {
            color: #2563eb;
        }

        .dropdown-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 0.25rem 0;
        }

        .dropdown-header {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #e2e8f0;
        }

        .dropdown-header .header-title {
            color: #0f172a;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .dropdown-header .header-sub {
            color: #94a3b8;
            font-size: 0.75rem;
        }

        /* ===== FOOTER ===== */
        .footer {
            background: white;
            border-top: 1px solid #e2e8f0;
            margin-top: 4rem;
        }

        .footer-link {
            transition: all 0.2s ease;
        }

        .footer-link:hover {
            color: #2563eb;
        }

        /* ===== MOBILE MENU ===== */
        .mobile-menu {
            transition: all 0.3s ease;
        }

        .mobile-menu-open {
            max-height: 500px;
            opacity: 1;
            padding: 1rem 0;
        }

        .mobile-menu-closed {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            padding: 0;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .search-wrapper {
                max-width: 100%;
                flex: none;
                width: 100%;
                order: 3;
                margin-top: 0.5rem;
            }

            .navbar .flex-wrap {
                flex-wrap: wrap;
            }

            .auth-btn .btn-text {
                display: none;
            }

            .register-btn {
                padding: 0.35rem 1rem;
                font-size: 0.75rem;
            }

            .logo-text {
                font-size: 1rem;
            }

            .nav-links {
                display: none;
            }

            .top-bar .announcement-text {
                font-size: 0.65rem;
                gap: 0.3rem;
            }

            .top-bar .announcement-text .highlight {
                font-size: 0.55rem;
                padding: 0.1rem 0.4rem;
            }

            .top-bar .announcement-text i {
                font-size: 0.65rem;
            }
        }

        @media (max-width: 480px) {
            .register-btn {
                display: none;
            }
        }

        /* ===== SCROLLBAR ===== */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body>

    <!-- ============================================================ -->
    <!-- TOP BAR / ANNOUNCEMENT BAR -->
    <!-- ============================================================ -->
    <div class="top-bar" id="topBar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between gap-4">
                <div class="flex-1"></div>

                <div class="announcement-text">
                    <i class="fas fa-utensils"></i>
                    <span>Welcome to <strong>FoodRestaurant</strong></span>
                    <span class="highlight"><i class="fas fa-tag mr-1"></i>10% OFF</span>
                    <span>on your first order</span>
                    <i class="fas fa-fire"></i>
                </div>

                <button class="close-btn" onclick="closeTopBar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- ============================================================ -->
    <!-- NAVBAR -->
    <!-- ============================================================ -->
    <nav class="navbar" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <div class="flex flex-wrap items-center justify-between gap-3">

                <!-- Logo -->
                <a href="{{ route('customer.home') }}"
                   class="flex items-center gap-2.5 group flex-shrink-0">
                    <div class="logo-icon">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <span class="logo-text">
                        Food<span class="highlight">Restaurant</span>
                    </span>
                </a>

                <!-- Nav Links -->
                <div class="hidden md:flex items-center gap-6 nav-links">
                    <a href="{{ route('customer.home') }}"
                       class="nav-link {{ request()->routeIs('customer.home') ? 'active' : '' }}">
                        <i class="fas fa-home mr-1.5"></i>Home
                    </a>
                    <a href="{{ route('customer.menu') }}"
                       class="nav-link {{ request()->routeIs('customer.menu') ? 'active' : '' }}">
                        <i class="fas fa-utensils mr-1.5"></i>Menu
                    </a>
                    @auth('web')
                        <a href="{{ route('customer.orders') }}"
                           class="nav-link {{ request()->routeIs('customer.orders') ? 'active' : '' }}">
                            <i class="fas fa-receipt mr-1.5"></i>Orders
                        </a>
                    @endauth
                </div>

                <!-- Search Bar -->
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text"
                           id="searchInput"
                           placeholder="Search food..."
                           class="search-input">
                </div>

                <!-- Right Side -->
                <div class="flex items-center gap-2 sm:gap-3 flex-shrink-0">

                    <!-- Cart -->
                    <a href="{{ route('customer.cart') }}"
                       class="cart-btn">
                        <i class="fas fa-shopping-cart"></i>
                        <span class="hidden sm:inline">Cart</span>
                        <span class="cart-badge" id="cartCount">
                            {{ session('cart_count', 0) }}
                        </span>
                    </a>

                    @auth('web')
                        <!-- ===== USER DROPDOWN (Logged In) ===== -->
                        <div class="dropdown-wrapper" id="userDropdown">
                            <button onclick="toggleDropdown('userDropdown')"
                                    class="auth-btn group">
                                <div class="user-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="btn-text">{{ auth('web')->user()->name ?? 'User' }}</span>
                                <i class="chevron fas fa-chevron-down" id="userChevron"></i>
                            </button>

                            <div class="dropdown-menu" id="userDropdownMenu">
                                <div class="dropdown-header">
                                    <p class="header-title">{{ auth('web')->user()->name ?? 'User' }}</p>
                                    <p class="header-sub">{{ auth('web')->user()->email ?? '' }}</p>
                                </div>

                                <a href="" class="dropdown-item">
                                    <i class="item-icon fas fa-user-circle"></i>
                                    My Profile
                                </a>
                                <a href="{{ route('customer.orders') }}" class="dropdown-item">
                                    <i class="item-icon fas fa-receipt"></i>
                                    My Orders
                                </a>

                                <div class="dropdown-divider"></div>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-red-600 hover:text-red-700 hover:bg-red-50">
                                        <i class="item-icon fas fa-right-from-bracket text-red-500"></i>
                                        Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- ===== LOGIN / REGISTER DROPDOWN (Not Logged In) ===== -->
                        <div class="dropdown-wrapper" id="authDropdown">
                            <button onclick="toggleDropdown('authDropdown')"
                                    class="auth-btn group">
                                <div class="user-icon">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="btn-text">Account</span>
                                <i class="chevron fas fa-chevron-down" id="authChevron"></i>
                            </button>

                            <div class="dropdown-menu" id="authDropdownMenu">
                                <div class="dropdown-header text-center">
                                    <p class="header-title">Welcome!</p>
                                    <p class="header-sub">Login or create an account</p>
                                </div>

                                <a href="{{ route('login') }}" class="dropdown-item">
                                    <i class="item-icon fas fa-sign-in-alt text-blue-600"></i>
                                    <span class="font-semibold">Login</span>
                                    <i class="fas fa-arrow-right ml-auto text-blue-300 text-xs"></i>
                                </a>

                                <a href="{{ route('register') }}" class="dropdown-item">
                                    <i class="item-icon fas fa-user-plus text-emerald-600"></i>
                                    <span class="font-semibold">Register</span>
                                    <i class="fas fa-arrow-right ml-auto text-emerald-300 text-xs"></i>
                                </a>

                                <div class="dropdown-divider"></div>

                                <div class="text-center py-2 px-4">
                                    <p class="text-xs text-slate-400">
                                        <i class="fas fa-shield-alt mr-1 text-emerald-500"></i>
                                        Secure login
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Register Button -->
                        <a href="{{ route('register') }}"
                           class="register-btn">
                            <i class="fas fa-user-plus mr-1.5"></i>
                            Register
                        </a>
                    @endauth

                    <!-- Mobile Toggle -->
                    <button id="mobileToggle"
                            class="md:hidden text-slate-600 hover:text-blue-600 transition text-xl p-2">
                        <i class="fas fa-bars"></i>
                    </button>

                </div>

            </div>

            <!-- Mobile Menu -->
            <div id="mobileMenu" class="md:hidden mobile-menu mobile-menu-closed border-t border-slate-100 mt-3">
                <div class="flex flex-col space-y-2 py-2">

                    <a href="{{ route('customer.home') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition {{ request()->routeIs('customer.home') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">
                        <i class="fas fa-home w-5"></i>
                        <span>Home</span>
                    </a>

                    <a href="{{ route('customer.menu') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition {{ request()->routeIs('customer.menu') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">
                        <i class="fas fa-utensils w-5"></i>
                        <span>Menu</span>
                    </a>

                    <a href="{{ route('customer.cart') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition {{ request()->routeIs('customer.cart') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">
                        <i class="fas fa-shopping-cart w-5"></i>
                        <span>Cart</span>
                    </a>

                    @auth('web')
                        <a href="{{ route('customer.orders') }}"
                           class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition {{ request()->routeIs('customer.orders') ? 'text-blue-600 bg-blue-50' : 'text-slate-600' }}">
                            <i class="fas fa-receipt w-5"></i>
                            <span>Orders</span>
                        </a>

                        <div class="border-t border-slate-100 my-2"></div>

                        <div class="px-3 py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-bold text-sm">
                                    {{ strtoupper(substr(auth('web')->user()->name ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-slate-800 text-sm">
                                        {{ auth('web')->user()->name }}
                                    </p>
                                    <p class="text-xs text-slate-400">
                                        {{ auth('web')->user()->email }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-red-50 transition text-red-600 w-full">
                                <i class="fas fa-right-from-bracket w-5"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg hover:bg-slate-50 transition text-slate-600">
                            <i class="fas fa-sign-in-alt w-5 text-blue-600"></i>
                            <span>Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                            <i class="fas fa-user-plus w-5"></i>
                            <span>Register</span>
                        </a>
                    @endauth

                </div>
            </div>

        </div>
    </nav>

    <!-- ============================================================ -->
    <!-- CONTENT -->
    <!-- ============================================================ -->
    <main class="min-h-screen">

        @yield('content')

    </main>

    <!-- ============================================================ -->
    <!-- FOOTER -->
    <!-- ============================================================ -->
    <footer class="footer text-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

            <div class="grid md:grid-cols-4 gap-8">

                <div class="md:col-span-1">
                    <div class="flex items-center gap-2.5 mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-utensils text-white text-lg"></i>
                        </div>
                        <span class="text-xl font-bold text-slate-800">
                            Food<span class="text-blue-600">Restaurant</span>
                        </span>
                    </div>
                    <p class="text-slate-500 text-sm leading-relaxed">
                        Delicious food, fast delivery, and great service.
                    </p>
                    <div class="flex gap-3 mt-4">
                        <a href="#" class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-500 hover:bg-blue-600 hover:text-white transition">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-800 mb-4">Quick Links</h4>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Home</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Menu</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Cart</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">About Us</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-800 mb-4">Support</h4>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Help Center</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Terms of Service</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Privacy Policy</a></li>
                        <li><a href="#" class="footer-link hover:text-blue-600 transition">Contact Us</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="font-semibold text-slate-800 mb-4">Contact</h4>
                    <ul class="space-y-2.5 text-sm text-slate-500">
                        <li class="flex items-center gap-2">
                            <i class="fas fa-map-pin text-blue-600"></i>
                            <span>Phnom Penh, Cambodia</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-phone text-blue-600"></i>
                            <span>+855 12 345 678</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-600"></i>
                            <span>info@foodrestaurant.com</span>
                        </li>
                    </ul>
                </div>

            </div>

            <div class="border-t border-slate-200 mt-10 pt-6 flex flex-wrap justify-between items-center gap-4 text-sm text-slate-500">
                <p>&copy; {{ date('Y') }} FoodRestaurant. All rights reserved.</p>
                <div class="flex gap-4">
                    <a href="#" class="hover:text-blue-600 transition">Privacy Policy</a>
                    <a href="#" class="hover:text-blue-600 transition">Terms of Service</a>
                </div>
            </div>

        </div>
    </footer>

    <!-- ============================================================ -->
    <!-- SCRIPTS -->
    <!-- ============================================================ -->
    <script>
        // ===== CLOSE TOP BAR =====
        function closeTopBar() {
            const topBar = document.getElementById('topBar');
            if (topBar) {
                topBar.style.transition = 'all 0.3s ease';
                topBar.style.transform = 'translateY(-100%)';
                topBar.style.opacity = '0';
                setTimeout(function() {
                    topBar.style.display = 'none';
                }, 300);
            }
        }

        // ===== TOGGLE DROPDOWN =====
        function toggleDropdown(wrapperId) {
            const wrapper = document.getElementById(wrapperId);
            if (!wrapper) return;

            const menu = wrapper.querySelector('.dropdown-menu');
            const chevron = wrapper.querySelector('.chevron');

            if (!menu) return;

            document.querySelectorAll('.dropdown-wrapper .dropdown-menu').forEach(m => {
                if (m !== menu) {
                    m.classList.remove('show');
                    const otherChevron = m.closest('.dropdown-wrapper').querySelector('.chevron');
                    if (otherChevron) otherChevron.classList.remove('rotated');
                }
            });

            menu.classList.toggle('show');
            if (chevron) chevron.classList.toggle('rotated');
        }

        // ===== CLOSE DROPDOWN ON CLICK OUTSIDE =====
        document.addEventListener('click', function(event) {
            document.querySelectorAll('.dropdown-wrapper').forEach(wrapper => {
                if (!wrapper.contains(event.target)) {
                    const menu = wrapper.querySelector('.dropdown-menu');
                    const chevron = wrapper.querySelector('.chevron');
                    if (menu) {
                        menu.classList.remove('show');
                        if (chevron) chevron.classList.remove('rotated');
                    }
                }
            });
        });

        // ===== CLOSE DROPDOWN ON ESCAPE =====
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                    menu.classList.remove('show');
                    const chevron = menu.closest('.dropdown-wrapper').querySelector('.chevron');
                    if (chevron) chevron.classList.remove('rotated');
                });
            }
        });

        // ===== MOBILE MENU TOGGLE =====
        document.getElementById('mobileToggle').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('mobile-menu-closed');
            menu.classList.toggle('mobile-menu-open');
        });

        // ===== CLOSE MOBILE MENU ON LINK CLICK =====
        document.querySelectorAll('#mobileMenu a, #mobileMenu button').forEach(link => {
            link.addEventListener('click', function() {
                const menu = document.getElementById('mobileMenu');
                menu.classList.add('mobile-menu-closed');
                menu.classList.remove('mobile-menu-open');
            });
        });

        // ===== UPDATE CART COUNT =====
        function updateCartCount(count) {
            const badge = document.getElementById('cartCount');
            if (badge) badge.textContent = count;
        }

        console.log('🍽️ FoodRestaurant Layout loaded successfully!');
        console.log('🎨 Clean & Professional design with Blue/Red theme');
    </script>

    @stack('scripts')

</body>
</html>