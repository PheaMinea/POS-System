<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Admin - @yield('title', 'Dashboard')</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

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
    </style>
</head>

<body class="bg-slate-100 antialiased font-sans">

<div class="flex h-screen overflow-hidden">

    <!-- ==================== SIDEBAR ==================== -->
    <aside class="w-72 bg-slate-900 text-white flex flex-col flex-shrink-0">

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
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Header -->
        <header class="bg-white border-b border-slate-200 flex-shrink-0">
            <div class="flex flex-wrap justify-between items-center px-6 lg:px-8 py-4 gap-3">

                <!-- Page Title -->
                <div>
                    <h1 class="text-2xl font-bold text-slate-800">
                        @yield('page_title', 'Dashboard')
                    </h1>
                    <p class="text-sm text-slate-500">
                        Welcome back 👋 {{ auth()->user()->name ?? 'Admin' }}
                    </p>
                </div>

                <!-- Right Side: Search, Notifications, Profile -->
                <div class="flex items-center gap-4 lg:gap-5 flex-wrap">

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
                        <div class="hidden sm:block">
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
        <main class="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 main-scroll">

            <div class="bg-white rounded-2xl shadow-sm p-4 md:p-6">

                @yield('content')

            </div>

        </main>

    </div>

</div>

</body>
</html>
