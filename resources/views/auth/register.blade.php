{{-- resources/views/auth/register.blade.php --}}
@extends('layouts.auth')

@section('title', 'Register - POS System')
@section('page_title', 'Create Account 🚀')
@section('subtitle', 'Register to start using POS system')

@section('content')

<form action="{{ route('register.store') }}" method="POST" class="space-y-5">

    @csrf

    <!-- ===== NAME ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-user text-indigo-400 mr-2"></i>Full Name
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-user"></i>
            </span>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   autofocus
                   placeholder="Enter your full name"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none @error('name') border-rose-400 @enderror">
        </div>
        @error('name')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror
    </div>

    <!-- ===== EMAIL ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-envelope text-indigo-400 mr-2"></i>Email Address
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-envelope"></i>
            </span>
            <input type="email"
                   name="email"
                   value="{{ old('email') }}"
                   required
                   placeholder="Enter your email"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none @error('email') border-rose-400 @enderror">
        </div>
        @error('email')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror
    </div>

    <!-- ===== ROLE ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-user-shield text-indigo-400 mr-2"></i>Role
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-user-tag"></i>
            </span>
            <select name="role"
                    required
                    class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none appearance-none @error('role') border-rose-400 @enderror">
                <option value="">Select your role</option>
                <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>
                    <i class="fas fa-crown"></i> Admin
                </option>
                <option value="cashier" {{ old('role') === 'cashier' ? 'selected' : '' }}>
                    <i class="fas fa-cash-register"></i> Cashier
                </option>
                <option value="customer" {{ old('role') === 'customer' ? 'selected' : '' }}>
                    <i class="fas fa-user"></i> Customer
                </option>
            </select>
            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
                <i class="fas fa-chevron-down"></i>
            </span>
        </div>
        @error('role')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror

        <!-- Role Description -->
        <div class="mt-2 text-xs text-slate-400 bg-slate-50 p-3 rounded-xl border border-slate-100">
            <p class="flex items-center gap-1.5">
                <i class="fas fa-info-circle text-indigo-400"></i>
                <span id="roleDescription">Select a role to see description</span>
            </p>
        </div>
    </div>

    <!-- ===== PASSWORD ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-lock text-indigo-400 mr-2"></i>Password
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-lock"></i>
            </span>
            <input type="password"
                   name="password"
                   id="password"
                   required
                   placeholder="Create a password"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-12 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none @error('password') border-rose-400 @enderror">
            <button type="button"
                    onclick="togglePassword(this)"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 transition">
                <i class="fas fa-eye"></i>
            </button>
        </div>

        <!-- Password Strength -->
        <div class="mt-2">
            <div class="h-1.5 rounded-full bg-slate-200 overflow-hidden">
                <div class="h-full w-0 transition-all duration-300 rounded-full" id="strengthBar"></div>
            </div>
            <p class="mt-1.5 text-xs text-slate-400" id="strengthText">
                <i class="fas fa-info-circle mr-1"></i>Minimum 8 characters
            </p>
        </div>

        @error('password')
            <p class="mt-1.5 text-sm text-rose-600"><i class="fas fa-circle-exclamation mr-1.5"></i>{{ $message }}</p>
        @enderror
    </div>

    <!-- ===== CONFIRM PASSWORD ===== -->
    <div>
        <label class="block mb-2 text-sm font-semibold text-slate-700">
            <i class="fas fa-check-circle text-indigo-400 mr-2"></i>Confirm Password
        </label>
        <div class="relative">
            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                <i class="fas fa-check-circle"></i>
            </span>
            <input type="password"
                   name="password_confirmation"
                   required
                   placeholder="Confirm your password"
                   class="w-full border border-slate-200 rounded-xl pl-11 pr-4 py-3.5 focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition outline-none">
        </div>
    </div>

    <!-- ===== TERMS ===== -->
    <div class="flex items-start gap-2.5 pt-1">
        <input type="checkbox"
               required
               id="terms"
               class="w-4.5 h-4.5 text-indigo-600 border-slate-300 rounded-md focus:ring-indigo-500 focus:ring-2 mt-0.5 cursor-pointer">
        <label for="terms" class="text-sm text-slate-600 cursor-pointer leading-relaxed">
            I agree to the
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Terms of Service</a>
            and
            <a href="#" class="text-indigo-600 hover:text-indigo-800 font-medium transition">Privacy Policy</a>
        </label>
    </div>

    <!-- ===== SUBMIT ===== -->
    <button type="submit"
            class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white py-3.5 rounded-xl font-semibold transition flex items-center justify-center gap-2.5 shadow-lg shadow-indigo-500/25 hover:shadow-indigo-500/35">
        <i class="fas fa-user-plus"></i>
        Create Account
    </button>

</form>

<!-- ===== LOGIN LINK ===== -->
<div class="text-center mt-6 pt-5 border-t border-slate-100">
    <span class="text-slate-500 text-sm">Already have an account?</span>
    <a href="{{ route('login') }}"
       class="ml-1.5 text-indigo-600 hover:text-indigo-800 font-semibold transition">
        Login Here
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

    // ===== PASSWORD STRENGTH =====
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const strengthBar = document.getElementById('strengthBar');
        const strengthText = document.getElementById('strengthText');

        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const len = this.value.length;
                let pct = 0, color = '#e2e8f0', label = 'Enter a password', icon = 'fa-info-circle';

                if (len === 0) {
                    pct = 0; color = '#e2e8f0'; label = 'Enter a password'; icon = 'fa-info-circle';
                } else if (len < 4) {
                    pct = 25; color = '#ef4444'; label = 'Weak'; icon = 'fa-exclamation-circle';
                } else if (len < 8) {
                    pct = 50; color = '#f59e0b'; label = 'Fair'; icon = 'fa-exclamation-triangle';
                } else if (len < 12) {
                    pct = 75; color = '#3b82f6'; label = 'Good'; icon = 'fa-check-circle';
                } else {
                    pct = 100; color = '#10b981'; label = 'Strong'; icon = 'fa-check-circle';
                }

                strengthBar.style.width = pct + '%';
                strengthBar.style.background = color;
                strengthText.innerHTML = `
                    <i class="fas ${icon} mr-1" style="color: ${color}"></i>
                    Password strength: <span class="font-medium" style="color: ${color}">${label}</span>
                    <span class="text-slate-400">(${len} characters)</span>
                `;
            });
        }
    });

    // ===== ROLE DESCRIPTION =====
    document.addEventListener('DOMContentLoaded', function() {
        const roleSelect = document.querySelector('select[name="role"]');
        const roleDesc = document.getElementById('roleDescription');

        const descriptions = {
            'admin': '🔑 Full access to all features. Manage users, products, categories, and reports.',
            'cashier': '💳 Process orders and manage customer payments at the POS counter.',
            'customer': '🛒 Browse products, place orders, and track your purchase history.'
        };

        if (roleSelect) {
            roleSelect.addEventListener('change', function() {
                const selected = this.value;
                roleDesc.textContent = selected && descriptions[selected]
                    ? descriptions[selected]
                    : 'Select a role to see description';
            });

            // Trigger on load if there's an old value
            if (roleSelect.value) {
                const selected = roleSelect.value;
                roleDesc.textContent = selected && descriptions[selected]
                    ? descriptions[selected]
                    : 'Select a role to see description';
            }
        }
    });

    // ===== AUTO FOCUS =====
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.querySelector('input[name="name"]');
        if (nameInput && !nameInput.value) {
            nameInput.focus();
        }
    });

    console.log('📝 Register page loaded successfully!');
</script>
@endpush

<!-- ===== EXTRA STYLES ===== -->
<style>
    /* Select dropdown styling */
    select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        cursor: pointer;
    }

    select option {
        padding: 8px 12px;
    }

    /* Focus ring for select */
    select:focus {
        ring: 2px solid rgba(79, 70, 229, 0.2);
        border-color: #6366f1;
    }

    /* Checkbox styling */
    input[type="checkbox"] {
        accent-color: #4f6ef7;
        cursor: pointer;
    }

    /* Input focus glow */
    input:focus, select:focus {
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.08);
    }

    /* Input error state */
    input.border-rose-400:focus,
    select.border-rose-400:focus {
        box-shadow: 0 0 0 4px rgba(244, 63, 94, 0.08);
    }

    /* Password strength bar transition */
    #strengthBar {
        transition: width 0.4s ease, background 0.4s ease;
    }

    /* Role description box */
    #roleDescription {
        transition: color 0.2s ease;
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
    }

    /* Button hover animation */
    .btn-primary {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(99, 102, 241, 0.35);
    }
</style>

@endsection