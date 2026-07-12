{{-- resources/views/auth/login.blade.php --}}
@extends('layouts.auth')

@section('title', 'Login - POS System')
@section('page_title', 'Welcome Back! 👋')
@section('subtitle', 'Login to your POS account')

@section('content')

<form action="{{ route('login.store') }}" method="POST" class="space-y-5">

    @csrf

    @if(session('error'))
        <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-exclamation-circle text-rose-500 text-xl mt-0.5"></i>
            <span class="text-sm">{{ session('error') }}</span>
        </div>
    @endif

    @if(session('success'))
        <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 p-4 rounded-xl flex items-start gap-3">
            <i class="fas fa-check-circle text-emerald-500 text-xl mt-0.5"></i>
            <span class="text-sm">{{ session('success') }}</span>
        </div>
    @endif

    <!-- ===== EMAIL ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-envelope text-blue-400 mr-2"></i>Email Address
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-envelope"></i>
            </span>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   autofocus
                   placeholder="Enter your email"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none @error('email') border-rose-400 @enderror">
        </div>
        @error('email')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror
    </div>

    <!-- ===== PASSWORD ===== -->
    <div>
        <div class="flex justify-between items-center mb-2">
            <label class="text-sm font-semibold text-slate-700">
                <i class="fas fa-lock text-blue-400 mr-2"></i>Password
            </label>
            <a href="#" class="text-sm text-blue-500 hover:text-blue-700 font-medium transition">
                Forgot Password?
            </a>
        </div>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   placeholder="Enter your password"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-12 py-3.5 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 transition outline-none @error('password') border-rose-400 @enderror">
            <button type="button"
                    onclick="togglePassword(this)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-eye"></i>
            </button>
        </div>
        @error('password')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror
    </div>

    <!-- ===== REMEMBER ME ===== -->
    <div class="flex items-center justify-between">
        <label class="flex items-center gap-2.5 cursor-pointer group">
            <input type="checkbox"
                   name="remember"
                   class="w-4.5 h-4.5 text-blue-600 border-slate-300 rounded-md focus:ring-blue-500 focus:ring-2 cursor-pointer transition">
            <span class="text-sm text-slate-600 group-hover:text-slate-800 transition">Remember me</span>
        </label>
        <span class="text-xs text-slate-400 flex items-center gap-1.5">
            <i class="fas fa-shield-alt text-emerald-500"></i>
            <span>Secure Login</span>
        </span>
    </div>

    <!-- ===== SUBMIT ===== -->
    <button type="submit"
            class="w-full bg-gradient-to-r from-blue-600 to-blue-800 hover:from-blue-700 hover:to-blue-900 text-white py-3.5 rounded-xl font-semibold transition flex items-center justify-center gap-2.5 shadow-lg shadow-blue-500/25 hover:shadow-blue-500/35">
        <i class="fas fa-arrow-right-to-bracket"></i>
        Login
    </button>

</form>

<!-- ===== DIVIDER ===== -->
<div class="relative my-6">
    <div class="absolute inset-0 flex items-center">
        <div class="w-full border-t border-slate-200"></div>
    </div>
    <div class="relative flex justify-center text-sm">
        <span class="px-4 bg-white text-slate-400 font-medium">Or continue with</span>
    </div>
</div>

<!-- ===== GOOGLE LOGIN ===== -->
<a href="{{ route('auth.google') }}"
   class="w-full bg-white border-2 border-slate-200 hover:border-blue-300 text-slate-700 py-3.5 rounded-xl font-semibold flex items-center justify-center gap-3 transition-all duration-200 hover:shadow-md hover:bg-slate-50 group">
    <svg class="w-5 h-5" viewBox="0 0 24 24">
        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z"/>
        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
    </svg>
    <span>Sign in with Google</span>
    <i class="fas fa-arrow-right text-slate-300 group-hover:text-blue-500 transition ml-1"></i>
</a>

<!-- ===== REGISTER LINK ===== -->
<div class="text-center mt-6 pt-5 border-t border-slate-100">
    <span class="text-slate-500 text-sm">Don't have an account?</span>
    <a href="{{ route('register') }}"
       class="ml-1.5 text-blue-600 hover:text-blue-800 font-semibold transition">
        Register Now
        <i class="fas fa-arrow-right ml-1.5"></i>
    </a>
</div>

@push('scripts')
<script>
    // ===== PASSWORD TOGGLE =====
    function togglePassword(button) {
        const input = button.closest('.relative').querySelector('input');
        const icon = button.querySelector('i');

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // ===== AUTO FOCUS =====
    document.addEventListener('DOMContentLoaded', function() {
        const emailInput = document.querySelector('input[name="email"]');
        if (emailInput && !emailInput.value) {
            setTimeout(() => emailInput.focus(), 100);
        }
    });

    // ===== KEYBOARD SHORTCUT =====
    document.addEventListener('keydown', function(e) {
        // Ctrl + Shift + L to focus email
        if (e.ctrlKey && e.shiftKey && (e.key === 'L' || e.key === 'l')) {
            e.preventDefault();
            const emailInput = document.querySelector('input[name="email"]');
            if (emailInput) emailInput.focus();
        }
    });

    // ===== ENTER KEY ON PASSWORD FIELD =====
    document.getElementById('password')?.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const form = this.closest('form');
            if (form) form.submit();
        }
    });

    console.log('🔐 Login page loaded successfully');
    console.log('⌨️  Ctrl+Shift+L to focus email field');
</script>
@endpush

<!-- ===== EXTRA STYLES ===== -->
<style>
    /* Button hover animation */
    .btn-primary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(37, 99, 235, 0.35);
    }

    /* Google button hover */
    .google-btn {
        transition: all 0.3s ease;
    }
    .google-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06);
    }

    /* Input focus glow */
    input:focus {
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.08);
    }

    /* Input error state */
    input.border-rose-400:focus {
        box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.08);
    }

    /* Checkbox styling */
    input[type="checkbox"] {
        accent-color: #2563eb;
        cursor: pointer;
    }

    /* Responsive */
    @media (max-width: 640px) {
        .py-3\.5 {
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }
        .pl-11 {
            padding-left: 2.5rem;
        }
        .space-y-5 > * + * {
            margin-top: 1rem;
        }
    }

    /* Password toggle button hover */
    .password-toggle:hover {
        color: #2563eb;
    }

    /* Divider styling */
    .divider-line {
        flex: 1;
        height: 1px;
        background: linear-gradient(to right, transparent, #e2e8f0, transparent);
    }

    /* Alert messages */
    .alert-enter {
        animation: slideDown 0.3s ease-out;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .alert-exit {
        animation: slideUp 0.3s ease-in forwards;
    }

    @keyframes slideUp {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(-10px);
        }
    }
</style>

@endsection