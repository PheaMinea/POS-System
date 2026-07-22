{{-- resources/views/layouts/cashier.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($shop_settings->shop_name ?? 'POS') . ' Cashier Dashboard')</title>
    <link rel="icon" href="{{ isset($shop_settings) && $shop_settings->shop_logo ? asset('storage/' . $shop_settings->shop_logo) : asset('favicon.ico') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #1a2332 100%);
            transition: all 0.3s ease;
        }

        .sidebar-nav a {
            transition: all 0.2s ease;
            border-radius: 12px;
            position: relative;
            overflow: hidden;
        }

        .sidebar-nav a::before {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%) scaleX(0);
            width: 3px;
            height: 60%;
            background: #2563eb;
            border-radius: 0 4px 4px 0;
            transition: transform 0.25s ease;
        }

        .sidebar-nav a:hover::before,
        .sidebar-nav a.active::before {
            transform: translateY(-50%) scaleX(1);
        }

        .sidebar-nav a:hover {
            background: rgba(255, 255, 255, 0.06);
            color: #ffffff;
            transform: translateX(4px);
        }

        .sidebar-nav a.active {
            background: rgba(37, 99, 235, 0.12);
            color: #60a5fa;
            box-shadow: inset 0 0 20px rgba(37, 99, 235, 0.05);
        }

        .sidebar-nav a i {
            width: 1.25rem;
            text-align: center;
            transition: all 0.2s ease;
        }

        .sidebar-nav a.active i {
            color: #60a5fa;
        }

        .sidebar-nav a:hover i {
            transform: scale(1.1);
        }

        /* ===== SCROLLBAR ===== */
        .sidebar-nav::-webkit-scrollbar,
        .main-scroll::-webkit-scrollbar {
            width: 4px;
        }

        .sidebar-nav::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar-nav::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 8px;
        }

        .main-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 8px;
        }

        .main-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        .main-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* ===== HEADER ===== */
        .header {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.6);
        }

        /* ===== LOGO PULSE ===== */
        .logo-pulse {
            animation: pulse 2.5s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.3);
            }
            50% {
                box-shadow: 0 0 20px rgba(37, 99, 235, 0.15);
            }
        }

        /* ===== STAT CARDS ===== */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            border: 1px solid rgba(226, 232, 240, 0.6);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
            border-color: #2563eb;
        }

        .stat-card .icon-wrapper {
            transition: all 0.3s ease;
        }

        .stat-card:hover .icon-wrapper {
            transform: scale(1.05) rotate(-3deg);
        }

        /* ===== NOTIFICATION BADGE ===== */
        .notification-dot {
            animation: ping 2s ease-in-out infinite;
        }

        @keyframes ping {
            0%, 100% {
                transform: scale(1);
                opacity: 1;
            }
            50% {
                transform: scale(1.3);
                opacity: 0.6;
            }
        }

        /* ===== USER AVATAR ===== */
        .avatar-ring {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .avatar-ring:hover {
            border-color: #2563eb;
            transform: scale(1.05);
        }

        /* ===== MOBILE TOGGLE ===== */
        .sidebar-toggle {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: block !important;
            }

            .sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                z-index: 1000;
                width: 280px;
                transition: left 0.3s ease;
                box-shadow: 4px 0 30px rgba(0, 0, 0, 0.15);
            }

            .sidebar.open {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.4);
                z-index: 999;
                display: none;
                backdrop-filter: blur(4px);
            }

            .sidebar-overlay.open {
                display: block;
            }
        }

        /* ===== INPUT FOCUS ===== */
        input:focus {
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12);
            border-color: #2563eb;
        }

        /* ===== BUTTON HOVER ===== */
        .btn-cashier {
            background: linear-gradient(135deg, #2563eb, #1e40af);
            transition: all 0.3s ease;
        }

        .btn-cashier:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.35);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .header .search-box {
                display: none;
            }

            .header .user-details {
                display: none;
            }

            .main-content {
                padding: 1rem !important;
            }

            .main-content .content-wrapper {
                padding: 1rem !important;
            }
        }
    </style>
</head>

<body class="bg-slate-100">

<!-- ===== SIDEBAR OVERLAY (Mobile) ===== -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="flex h-screen overflow-hidden">

    <!-- ============================================================ -->
    <!-- SIDEBAR -->
    <!-- ============================================================ -->
    <aside class="sidebar w-72 text-white flex flex-col flex-shrink-0" id="sidebar">

        <!-- Brand / Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-700/50 flex-shrink-0">
            <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-700 rounded-xl flex items-center justify-center logo-pulse overflow-hidden">
                @if(isset($shop_settings) && $shop_settings->shop_logo)
                    <img src="{{ asset('storage/' . $shop_settings->shop_logo) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-cash-register text-xl text-white"></i>
                @endif
            </div>
            <div class="ml-3">
                <h2 class="font-bold text-lg tracking-tight text-white leading-tight">
                    {{ $shop_settings->shop_name ?? 'Cashier POS' }}
                </h2>
                <p class="text-xs text-slate-400 font-light">
                    <i class="fas fa-circle text-[6px] text-blue-400 mr-1.5 align-middle"></i>
                    Sales Management
                </p>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto sidebar-nav">

            <!-- Dashboard -->
            <a href="{{ route('cashier.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 {{ request()->routeIs('cashier.dashboard') ? 'active' : '' }}">
                <i class="fas fa-gauge-high"></i>
                <span>Dashboard</span>
            </a>

            <!-- Homepage -->
            <a href="{{ route('customer.home') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300">
                <i class="fas fa-house"></i>
                <span>Homepage</span>
            </a>

            <!-- POS System -->
            <a href="{{ route('cashier.pos.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 {{ request()->routeIs('cashier.pos.*') ? 'active' : '' }}">
                <i class="fas fa-cart-shopping"></i>
                <span>POS System</span>
                <span class="ml-auto text-[10px] bg-blue-500/20 text-blue-400 px-2 py-0.5 rounded-full">New</span>
            </a>

            <!-- Customers -->
            <a href="{{ route('cashier.customers.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 {{ request()->routeIs('cashier.customers.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Customers</span>
            </a>

            <!-- Orders -->
            <a href="{{ route('cashier.orders.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 {{ request()->routeIs('cashier.orders.*') ? 'active' : '' }}">
                <i class="fas fa-receipt"></i>
                <span>Orders</span>
                <span class="ml-auto text-[10px] bg-amber-500/20 text-amber-400 px-2 py-0.5 rounded-full">5</span>
            </a>

            <!-- Payments -->
            <a href="{{ route('cashier.payments.index') }}"
               class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 {{ request()->routeIs('cashier.payments.*') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i>
                <span>Payments</span>
            </a>

            <!-- Divider -->
            <div class="h-px bg-slate-700/30 my-3"></div>

            <!-- Help & Support -->
            <a href="https://t.me/PheaMinea88"
            class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-slate-300 hover:bg-slate-800/50 rounded-xl transition group">
                <i class="fas fa-circle-question text-blue-400 group-hover:text-blue-300 transition"></i>
                <span class="group-hover:text-white transition">Help & Support</span>
            </a>

        </nav>

        <!-- Logout -->
        <div class="p-4 border-t border-slate-700/50 flex-shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full bg-rose-500/10 hover:bg-rose-500/20 text-rose-400 hover:text-rose-300 py-3 rounded-xl flex items-center justify-center gap-2.5 text-sm font-medium transition-all duration-200 border border-rose-500/10 hover:border-rose-500/20">
                    <i class="fas fa-right-from-bracket"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>

    </aside>

    <!-- ============================================================ -->
    <!-- MAIN CONTENT -->
    <!-- ============================================================ -->
    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="header flex-shrink-0">
            <div class="flex flex-wrap justify-between items-center px-4 md:px-8 py-3 md:py-4 gap-3">

                <!-- Left -->
                <div class="flex items-center gap-3">
                    <!-- Mobile Toggle -->
                    <button class="sidebar-toggle text-slate-600 hover:text-slate-900 text-xl"
                            onclick="toggleSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>

                    <div>
                        <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">
                            @yield('page_title', 'Cashier Dashboard')
                        </h1>
                        <p class="text-xs md:text-sm text-slate-400 font-light">
                            <i class="fas fa-clock mr-1.5 text-blue-400"></i>
                            Welcome back, {{ auth()->user()->name ?? 'Cashier' }}
                        </p>
                    </div>
                </div>

                <!-- Right -->
                <div class="flex flex-shrink-0 items-center justify-end gap-3 md:gap-5">

                    <!-- Search -->
                    <div class="relative hidden md:block search-box">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input type="text"
                               placeholder="Search..."
                               class="pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl w-52 lg:w-64 text-sm focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none bg-slate-50/50 hover:bg-white">
                    </div>

                    <!-- Notification -->
                    <button class="relative text-slate-500 hover:text-slate-700 transition">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1.5 -right-1.5 w-5 h-5 bg-rose-500 text-white rounded-full text-[10px] flex items-center justify-center font-bold shadow-lg shadow-rose-500/20">
                            3
                        </span>
                        <span class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full notification-dot opacity-0"></span>
                    </button>

                    <!-- Quick Customers Link -->
                    <a href="{{ route('cashier.customers.index') }}"
                       class="hidden items-center gap-2 rounded-xl border border-blue-200 bg-blue-50 px-3 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition sm:inline-flex">
                        <i class="fas fa-users"></i>
                        <span>Customers</span>
                    </a>

                    <!-- User -->
                    @php
                        $authUser = auth()->user();
                        $cashierImage = null;

                        if ($authUser?->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($authUser->image)) {
                            $cashierImage = asset('storage/' . $authUser->image);
                        } elseif ($authUser?->avatar) {
                            $cashierImage = $authUser->avatar;
                        }
                    @endphp
                    <div class="flex items-center gap-3">
                        @if($cashierImage)
                            <img src="{{ $cashierImage }}"
                                 class="w-10 h-10 rounded-full object-cover avatar-ring shadow-sm"
                                 alt="{{ $authUser->name ?? 'Cashier' }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold avatar-ring shadow-sm">
                                {{ strtoupper(substr($authUser->name ?? 'C', 0, 1)) }}
                            </div>
                        @endif
                        <div class="hidden sm:block user-details">
                            <h4 class="font-semibold text-sm text-slate-800">
                                {{ $authUser->name ?? 'Cashier' }}
                            </h4>
                            <p class="text-xs text-slate-400 font-light">
                                <i class="fas fa-circle text-[6px] text-blue-400 mr-1 align-middle"></i>
                                Cashier
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <!-- Content -->
        <main class="main-content flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 main-scroll">

            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-4 bg-blue-50 border border-blue-200 text-blue-700 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-blue-500 text-xl"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button" class="ml-auto text-blue-400 hover:text-blue-600 transition"
                            onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-exclamation-circle text-rose-500 text-xl"></i>
                    <span>{{ session('error') }}</span>
                    <button type="button" class="ml-auto text-rose-400 hover:text-rose-600 transition"
                            onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <!-- Content Wrapper -->
            <div class="content-wrapper bg-white rounded-2xl shadow-sm p-4 md:p-6 lg:p-8 border border-slate-100/50">

                @yield('content')

            </div>

        </main>

    </div>

</div>

<!-- ============================================================ -->
<!-- SCRIPTS -->
<!-- ============================================================ -->
<script>
    // ===== SIDEBAR TOGGLE (Mobile) =====
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.toggle('open');
        overlay.classList.toggle('open');

        document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
    }

    // Close sidebar on overlay click
    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        toggleSidebar();
    });

    // Close sidebar on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const sidebar = document.getElementById('sidebar');
            if (sidebar.classList.contains('open')) {
                toggleSidebar();
            }
        }
    });

    // ===== AUTO CLOSE MESSAGES =====
    document.addEventListener('DOMContentLoaded', function() {
        // Auto close success/error messages after 5 seconds
        const messages = document.querySelectorAll('.bg-blue-50, .bg-rose-50');
        messages.forEach(msg => {
            setTimeout(() => {
                msg.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                msg.style.opacity = '0';
                msg.style.transform = 'translateY(-10px)';
                setTimeout(() => msg.remove(), 500);
            }, 5000);
        });
    });
</script>

    <!-- SPA Router - Prevents page refreshes -->
    <script src="{{ asset('js/spa.js') }}"></script>

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
