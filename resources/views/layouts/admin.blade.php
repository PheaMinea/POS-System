<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $shop_settings->shop_name ?? 'POS' }} Admin - @yield('title', 'Dashboard')</title>
    <link rel="icon" href="{{ isset($shop_settings) && $shop_settings->shop_logo ? asset('storage/' . $shop_settings->shop_logo) : asset('favicon.svg') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Custom Styles -->
    <style>
        /* Sidebar navigation hover & active states */
        .sidebar-nav a {
            transition: background 0.15s, color 0.15s;
        }
        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.06);
            color: #ffffff;
        }
        .sidebar-nav a.active {
            background: rgba(255,255,255,0.12);
            border-left: 3px solid #818cf8;
            color: #ffffff;
        }
        .sidebar-nav a i {
            width: 1.25rem;
            text-align: center;
        }

        /* Scrollbar styling */
        .main-scroll::-webkit-scrollbar {
            width: 5px;
        }
        .main-scroll::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .main-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }

        /* Card hover effect (optional) */
        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0,0,0,0.08), 0 10px 10px -5px rgba(0,0,0,0.02);
        }

        body {
            overflow-x: hidden;
        }

        .sidebar-toggle,
        .sidebar-overlay {
            display: none;
        }

        @media (max-width: 768px) {
            .sidebar-toggle {
                display: inline-flex;
            }

            .admin-sidebar {
                position: fixed;
                left: -100%;
                top: 0;
                bottom: 0;
                z-index: 1000;
                width: 280px;
                max-width: calc(100vw - 2rem);
                transition: left 0.25s ease;
                box-shadow: 4px 0 30px rgba(15, 23, 42, 0.25);
            }

            .admin-sidebar.open {
                left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                z-index: 999;
                background: rgba(15, 23, 42, 0.45);
                backdrop-filter: blur(3px);
            }

            .sidebar-overlay.open {
                display: block;
            }

            .admin-header {
                padding: 0.875rem 1rem;
            }

            .admin-header-title {
                min-width: 0;
            }

            .admin-header-title h1 {
                font-size: 1.25rem;
                line-height: 1.75rem;
            }

            .admin-header-title p {
                max-width: 12rem;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }

            .admin-header-actions {
                gap: 0.75rem;
                margin-left: auto;
            }

            .admin-header-profile-name {
                display: none;
            }

            .admin-main {
                padding: 1rem !important;
            }

            .admin-content-card {
                padding: 1rem !important;
                border-radius: 1rem;
            }
        }
    </style>
</head>

<body class="bg-slate-100 antialiased font-sans">

<div class="sidebar-overlay" id="adminSidebarOverlay"></div>

<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="admin-sidebar w-72 bg-slate-900 text-white flex flex-col flex-shrink-0" id="adminSidebar">

        <!-- Brand / Logo -->
        <div class="h-20 flex items-center px-6 border-b border-slate-800">
            <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center overflow-hidden">
                @if(isset($shop_settings) && $shop_settings->shop_logo)
                    <img src="{{ asset('storage/' . $shop_settings->shop_logo) }}" class="w-full h-full object-cover">
                @else
                    <i class="fas fa-store text-xl"></i>
                @endif
            </div>
            <div class="ml-3">
                <h2 class="font-bold text-lg leading-tight">{{ $shop_settings->shop_name ?? 'POS System' }}</h2>
                <p class="text-xs text-slate-400">Admin Dashboard</p>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 p-4 space-y-2 overflow-y-auto sidebar-nav">

            <!-- Dashboard -->
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>

            <!-- Homepage -->
            <a href="{{ route('customer.home') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300">
                <i class="fas fa-house"></i>
                <span>Homepage</span>
            </a>

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>

            <!-- Categories -->
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                <i class="fas fa-layer-group"></i>
                <span>Categories</span>
            </a>

            <!-- Products -->
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="fas fa-box-open"></i>
                <span>Products</span>
            </a>

            <!-- Suppliers -->
            <a href="{{ route('admin.suppliers.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.suppliers.*') ? 'active' : '' }}">
                <i class="fas fa-truck"></i>
                <span>Suppliers</span>
            </a>

            <!-- Purchases -->
            <a href="{{ route('admin.purchases.index') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.purchases.*') ? 'active' : '' }}">
                <i class="fas fa-cart-shopping"></i>
                <span>Purchases</span>
            </a>

            <!-- Reports Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                    Reports
                </p>

                <a href="{{ route('admin.reports.sales') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span>Sales Report</span>
                </a>

                <a href="{{ route('admin.reports.purchases') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.reports.purchases') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice-dollar"></i>
                    <span>Purchase Report</span>
                </a>

                <a href="{{ route('admin.reports.stocks') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.reports.stocks') ? 'active' : '' }}">
                    <i class="fas fa-warehouse"></i>
                    <span>Stock Report</span>
                </a>

                <a href="{{ route('admin.reports.profit') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.reports.profit') ? 'active' : '' }}">
                    <i class="fas fa-sack-dollar"></i>
                    <span>Profit Report</span>
                </a>
            </div>

            <!-- System Section -->
            <div class="pt-2">
                <p class="px-4 py-2 text-xs uppercase text-slate-500 font-semibold tracking-wider">
                    System
                </p>

                <a href="{{ route('admin.settings.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium text-slate-300 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fas fa-gear"></i>
                    <span>Settings</span>
                </a>
            </div>

        </nav>

        <!-- Logout Button -->
        <div class="p-4 border-t border-slate-800">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="w-full bg-red-500 hover:bg-red-600 py-3 rounded-xl flex items-center justify-center gap-2 text-sm font-medium transition">
                    <i class="fas fa-right-from-bracket"></i>
                    Logout
                </button>
            </form>
        </div>

    </aside>

    <!-- ==================== MAIN CONTENT ==================== -->
    <div class="flex-1 min-w-0 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white border-b border-slate-200 flex-shrink-0">
            <div class="admin-header flex flex-wrap justify-between items-center px-6 lg:px-8 py-4 gap-3">

                <!-- Page Title -->
                <div class="admin-header-title flex min-w-0 flex-1 items-center gap-3">
                    <button type="button"
                            class="sidebar-toggle w-10 h-10 rounded-xl border border-slate-200 text-slate-600 items-center justify-center hover:bg-slate-50 transition"
                            onclick="toggleAdminSidebar()">
                        <i class="fas fa-bars"></i>
                    </button>
                    <div class="min-w-0">
                    <h1 class="truncate text-2xl font-bold text-slate-800">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <p class="truncate text-sm text-slate-500">
                        Welcome back 👋 {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                </div>

                </div>

                <!-- Right Side: Search, Notifications, Profile -->
                <div class="admin-header-actions flex flex-shrink-0 flex-wrap items-center justify-end gap-4 lg:gap-5">

                    <!-- Search Bar -->
                    <div class="relative hidden sm:block">
                        <i class="fas fa-search absolute left-4 top-3 text-gray-400 text-sm"></i>
                        <input type="text"
                               placeholder="Search..."
                               class="pl-11 pr-4 py-2 border rounded-xl w-48 md:w-64 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300">
                    </div>

                    <!-- Notifications -->
                    <button class="relative">
                        <i class="fas fa-bell text-xl text-slate-600"></i>
                        <span class="absolute -top-2 -right-2 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">
                            3
                        </span>
                    </button>

                    <!-- User Profile -->
                    @php
                        $authUser = auth()->user();
                        $adminImage = null;

                        if ($authUser?->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($authUser->image)) {
                            $adminImage = asset('storage/' . $authUser->image);
                        } elseif ($authUser?->avatar) {
                            $adminImage = $authUser->avatar;
                        }
                    @endphp
                    <div class="flex items-center gap-3">
                        @if($adminImage)
                            <img src="{{ $adminImage }}"
                                 class="w-10 h-10 rounded-full object-cover border border-slate-200"
                                 alt="{{ $authUser->name ?? 'Admin' }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold border border-slate-200">
                                {{ strtoupper(substr($authUser->name ?? 'A', 0, 1)) }}
                            </div>
                        @endif
                        <div class="admin-header-profile-name hidden sm:block">
                            <h4 class="font-semibold text-sm text-slate-800">
                                {{ $authUser->name ?? 'Admin' }}
                            </h4>
                            <p class="text-xs text-slate-500">Administrator</p>
                        </div>
                    </div>

                </div>

            </div>
        </header>

        <!-- Page Content (yield) -->
        <main class="admin-main flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 main-scroll">

            @if(session('success'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-center gap-3">
                    <i class="fas fa-check-circle text-emerald-500 text-xl"></i>
                    <span>{{ session('success') }}</span>
                    <button type="button"
                            class="ml-auto text-emerald-400 hover:text-emerald-600 transition"
                            onclick="this.parentElement.remove()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            @endif

            <div class="admin-content-card bg-white rounded-2xl shadow-sm p-4 md:p-6">

                @yield('content')

            </div>

        </main>

    </div>

</div>

    <!-- SPA Router - Prevents page refreshes -->
    <script src="{{ asset('js/spa.js') }}"></script>

    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');

            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
            document.body.style.overflow = sidebar.classList.contains('open') ? 'hidden' : '';
        }

        document.getElementById('adminSidebarOverlay').addEventListener('click', toggleAdminSidebar);

        document.addEventListener('keydown', function (event) {
            const sidebar = document.getElementById('adminSidebar');

            if (event.key === 'Escape' && sidebar.classList.contains('open')) {
                toggleAdminSidebar();
            }
        });
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
