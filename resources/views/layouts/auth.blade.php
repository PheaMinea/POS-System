{{-- resources/views/layouts/auth.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', ($shop_settings->shop_name ?? 'POS System') . ' Authentication')</title>
    <link rel="icon" href="{{ isset($shop_settings) && $shop_settings->shop_logo ? asset('storage/' . $shop_settings->shop_logo) : asset('favicon.ico') }}">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap"
          rel="stylesheet">

    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        body {
            min-height: 100vh;
            background: #f8fafc;
        }

        /* ===== ANIMATIONS ===== */
        .auth-card {
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(40px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .left-side {
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .logo-pulse {
            animation: pulse 3s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% {
                transform: scale(1);
                box-shadow: 0 0 0 0 rgba(255, 255, 255, 0.3);
            }
            50% {
                transform: scale(1.05);
                box-shadow: 0 0 30px rgba(255, 255, 255, 0.2);
            }
        }

        .float-element {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .float-delay-1 {
            animation-delay: 1s;
        }
        .float-delay-2 {
            animation-delay: 2s;
        }

        /* ===== INPUT STYLES ===== */
        input {
            transition: all 0.2s ease;
        }

        input:focus {
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            border-color: #2563eb;
        }

        input.error:focus {
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.12);
            border-color: #ef4444;
        }

        /* ===== BUTTON STYLES ===== */
        .btn-primary {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(37, 99, 235, 0.35);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-primary::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .btn-primary:hover::after {
            opacity: 1;
        }

        /* ===== FEATURE CARDS ===== */
        .feature-card {
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-6px) scale(1.02);
            background: rgba(255, 255, 255, 0.15);
            border-color: rgba(255, 255, 255, 0.25);
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

        /* ===== AUTOFILL ===== */
        input:-webkit-autofill,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px white inset !important;
            -webkit-text-fill-color: #1e293b !important;
        }

        /* ===== DIVIDER ===== */
        .divider {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: linear-gradient(to right, transparent, #e2e8f0, transparent);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .left-side {
                display: none !important;
            }
            .w-full.lg\:w-1\/2 {
                width: 100% !important;
            }
        }

        @media (max-width: 640px) {
            .auth-card .p-6 {
                padding: 1.5rem !important;
            }
            .text-5xl {
                font-size: 2.5rem !important;
            }
        }
    </style>
</head>

<body>

<div class="min-h-screen flex">

    <!-- ============================================================ -->
    <!-- LEFT SIDE - Branding -->
    <!-- ============================================================ -->
    <div class="hidden lg:flex lg:w-1/2 left-side
                bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900
                text-white
                items-center justify-center
                relative overflow-hidden">

        <!-- Decorative Background Elements -->
        <div class="absolute top-10 right-10 w-72 h-72 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute bottom-10 left-10 w-56 h-56 bg-white/5 rounded-full blur-2xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-white/5 rounded-full blur-3xl"></div>

        <!-- Floating Shapes -->
        <div class="absolute top-20 left-20 w-16 h-16 border-4 border-white/10 rounded-full float-element"></div>
        <div class="absolute bottom-32 right-24 w-12 h-12 border-4 border-white/10 rounded-lg float-element float-delay-1"></div>
        <div class="absolute top-40 right-32 w-8 h-8 border-4 border-white/10 rounded-full float-element float-delay-2"></div>

        <div class="text-center px-12 relative z-10">

            <!-- Logo -->
            <div class="w-32 h-32 rounded-3xl
                        bg-white/10 backdrop-blur-md
                        flex items-center justify-center
                        mx-auto logo-pulse
                        border border-white/20
                        shadow-2xl shadow-blue-500/20">
                @if(isset($shop_settings) && $shop_settings->shop_logo)
                    <img
                        src="{{ asset('storage/' . $shop_settings->shop_logo) }}"
                        alt="{{ $shop_settings->shop_name ?? 'Website logo' }}"
                        class="w-full h-full object-contain p-4"
                    >
                @else
                    <i class="fas fa-cash-register text-6xl"></i>
                @endif
            </div>

            <!-- Title -->
            <h1 class="text-5xl font-extrabold mt-8 tracking-tight bg-gradient-to-r from-white to-blue-200 bg-clip-text text-transparent">
                {{ $shop_settings->shop_name ?? 'POS System' }}
            </h1>

            <p class="text-xl text-blue-200/80 mt-4 font-light">
                Smart Point Of Sale Management
            </p>

            <div class="w-24 h-1 bg-gradient-to-r from-blue-300 to-blue-400 mx-auto mt-6 rounded-full"></div>

            <!-- Features -->
            <div class="grid grid-cols-3 gap-4 mt-10">

                <div class="feature-card rounded-2xl p-4">
                    <i class="fas fa-box text-2xl text-blue-200"></i>
                    <p class="mt-2 text-sm font-medium text-blue-100">Products</p>
                </div>

                <div class="feature-card rounded-2xl p-4">
                    <i class="fas fa-users text-2xl text-blue-200"></i>
                    <p class="mt-2 text-sm font-medium text-blue-100">Customers</p>
                </div>

                <div class="feature-card rounded-2xl p-4">
                    <i class="fas fa-chart-line text-2xl text-blue-200"></i>
                    <p class="mt-2 text-sm font-medium text-blue-100">Reports</p>
                </div>

            </div>

            <!-- Stats -->
            <div class="flex justify-center gap-8 mt-8 text-sm">
                <div>
                    <span class="block text-2xl font-bold text-white">99.9%</span>
                    <span class="text-blue-200/60">Uptime</span>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-white">24/7</span>
                    <span class="text-blue-200/60">Support</span>
                </div>
                <div>
                    <span class="block text-2xl font-bold text-white">10k+</span>
                    <span class="text-blue-200/60">Users</span>
                </div>
            </div>

            <!-- Version -->
            <p class="text-blue-300/40 text-xs mt-8">
                <i class="fas fa-code mr-1"></i> v2.0.0
            </p>

        </div>

    </div>

    <!-- ============================================================ -->
    <!-- RIGHT SIDE - Form -->
    <!-- ============================================================ -->
    <div class="w-full lg:w-1/2
                flex items-center justify-center
                p-6 sm:p-8 lg:p-10">

        <div class="w-full max-w-md auth-card">

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-2xl shadow-slate-200/50 p-7 sm:p-9
                        border border-slate-100/50">

                <!-- Header -->
                <div class="text-center mb-7">

                    <div class="w-20 h-20 mx-auto
                                rounded-2xl
                                bg-gradient-to-br from-blue-100 to-blue-200
                                flex items-center justify-center
                                shadow-lg shadow-blue-500/10
                                border border-blue-200/30">
                        <i class="fas fa-store text-3xl text-blue-600"></i>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800 mt-4 tracking-tight">
                        @yield('page_title', 'Welcome Back!')
                    </h2>

                    <p class="text-sm text-slate-400 mt-1 font-light">
                        @yield('subtitle', 'Login to your account')
                    </p>

                </div>

                <!-- Success Message -->
                @if(session('success'))
                    <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-start gap-3">
                        <i class="fas fa-check-circle text-emerald-500 text-lg mt-0.5"></i>
                        <span class="text-sm">{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Error Messages -->
                @if($errors->any())
                    <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-circle text-rose-500 text-lg mt-0.5"></i>
                        <div class="flex-1">
                            <ul class="list-disc ml-5 text-sm space-y-0.5">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <!-- Content -->
                @yield('content')

            </div>

            <!-- Footer -->
            <div class="text-center mt-6">
                <p class="text-xs text-slate-400 font-light">
                    &copy; {{ date('Y') }} {{ $shop_settings->shop_name ?? 'POS System' }}.
                    <span class="hidden sm:inline">All rights reserved.</span>
                </p>
            </div>

        </div>

    </div>

</div>

@stack('scripts')

</body>
</html>
