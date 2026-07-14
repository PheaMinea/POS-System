{{-- resources/views/auth/register.blade.php --}}

@extends('layouts.auth')

@section('title', 'Register - Food Restaurant')

@section('page_title', 'Create Account 🚀')

@section('subtitle', 'Create your customer account and start ordering food')

@section('content')


<form
    action="{{ route('register.store') }}"
    method="POST"
    class="space-y-5"
    id="registerForm"
>

    @csrf


    {{-- ============================================================ --}}
    {{-- FULL NAME --}}
    {{-- ============================================================ --}}

    <div>

        <label
            for="name"
            class="block mb-2 text-sm font-semibold text-slate-700"
        >

            <i class="fas fa-user text-indigo-400 mr-2"></i>

            Full Name

        </label>


        <div class="relative">

            <span
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    pointer-events-none
                "
            >

                <i class="fas fa-user"></i>

            </span>


            <input
                type="text"
                name="name"
                id="name"
                value="{{ old('name') }}"
                required
                autofocus
                autocomplete="name"
                placeholder="Enter your full name"
                class="
                    w-full
                    border
                    border-slate-200
                    rounded-xl
                    pl-11
                    pr-4
                    py-3.5
                    focus:ring-2
                    focus:ring-indigo-500/20
                    focus:border-indigo-500
                    transition
                    outline-none
                    @error('name')
                        border-rose-400
                    @enderror
                "
            >

        </div>


        @error('name')

            <p class="mt-1.5 text-sm text-rose-600">

                <i class="fas fa-circle-exclamation mr-1.5"></i>

                {{ $message }}

            </p>

        @enderror

    </div>



    {{-- ============================================================ --}}
    {{-- EMAIL --}}
    {{-- ============================================================ --}}

    <div>

        <label
            for="email"
            class="block mb-2 text-sm font-semibold text-slate-700"
        >

            <i class="fas fa-envelope text-indigo-400 mr-2"></i>

            Email Address

        </label>


        <div class="relative">

            <span
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    pointer-events-none
                "
            >

                <i class="fas fa-envelope"></i>

            </span>


            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                required
                autocomplete="email"
                placeholder="Enter your email"
                class="
                    w-full
                    border
                    border-slate-200
                    rounded-xl
                    pl-11
                    pr-4
                    py-3.5
                    focus:ring-2
                    focus:ring-indigo-500/20
                    focus:border-indigo-500
                    transition
                    outline-none
                    @error('email')
                        border-rose-400
                    @enderror
                "
            >

        </div>


        @error('email')

            <p class="mt-1.5 text-sm text-rose-600">

                <i class="fas fa-circle-exclamation mr-1.5"></i>

                {{ $message }}

            </p>

        @enderror

    </div>



    {{-- ============================================================ --}}
    {{-- PASSWORD --}}
    {{-- ============================================================ --}}

    <div>

        <label
            for="password"
            class="block mb-2 text-sm font-semibold text-slate-700"
        >

            <i class="fas fa-lock text-indigo-400 mr-2"></i>

            Password

        </label>


        <div class="relative">

            <span
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    pointer-events-none
                "
            >

                <i class="fas fa-lock"></i>

            </span>


            <input
                type="password"
                name="password"
                id="password"
                required
                autocomplete="new-password"
                placeholder="Create a password"
                class="
                    w-full
                    border
                    border-slate-200
                    rounded-xl
                    pl-11
                    pr-12
                    py-3.5
                    focus:ring-2
                    focus:ring-indigo-500/20
                    focus:border-indigo-500
                    transition
                    outline-none
                    @error('password')
                        border-rose-400
                    @enderror
                "
            >


            <button
                type="button"
                class="
                    password-toggle
                    absolute
                    right-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    hover:text-slate-600
                    transition
                "
                data-target="password"
            >

                <i class="fas fa-eye"></i>

            </button>

        </div>



        {{-- PASSWORD STRENGTH --}}

        <div class="mt-2">

            <div
                class="
                    h-1.5
                    rounded-full
                    bg-slate-200
                    overflow-hidden
                "
            >

                <div
                    id="strengthBar"
                    class="
                        h-full
                        w-0
                        rounded-full
                    "
                ></div>

            </div>


            <p
                id="strengthText"
                class="mt-1.5 text-xs text-slate-400"
            >

                <i class="fas fa-info-circle mr-1"></i>

                Minimum 8 characters

            </p>

        </div>


        @error('password')

            <p class="mt-1.5 text-sm text-rose-600">

                <i class="fas fa-circle-exclamation mr-1.5"></i>

                {{ $message }}

            </p>

        @enderror

    </div>



    {{-- ============================================================ --}}
    {{-- CONFIRM PASSWORD --}}
    {{-- ============================================================ --}}

    <div>

        <label
            for="password_confirmation"
            class="block mb-2 text-sm font-semibold text-slate-700"
        >

            <i class="fas fa-check-circle text-indigo-400 mr-2"></i>

            Confirm Password

        </label>


        <div class="relative">

            <span
                class="
                    absolute
                    left-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    pointer-events-none
                "
            >

                <i class="fas fa-check-circle"></i>

            </span>


            <input
                type="password"
                name="password_confirmation"
                id="password_confirmation"
                required
                autocomplete="new-password"
                placeholder="Confirm your password"
                class="
                    w-full
                    border
                    border-slate-200
                    rounded-xl
                    pl-11
                    pr-12
                    py-3.5
                    focus:ring-2
                    focus:ring-indigo-500/20
                    focus:border-indigo-500
                    transition
                    outline-none
                "
            >


            <button
                type="button"
                class="
                    password-toggle
                    absolute
                    right-4
                    top-1/2
                    -translate-y-1/2
                    text-slate-400
                    hover:text-slate-600
                    transition
                "
                data-target="password_confirmation"
            >

                <i class="fas fa-eye"></i>

            </button>

        </div>


        <p
            id="passwordMatchText"
            class="hidden mt-1.5 text-xs"
        ></p>

    </div>



    {{-- ============================================================ --}}
    {{-- ACCOUNT SECURITY INFO --}}
    {{-- ============================================================ --}}

    <div
        class="
            bg-indigo-50/70
            border
            border-indigo-100
            rounded-xl
            p-3.5
        "
    >

        <div class="flex items-start gap-3">

            <div
                class="
                    w-9
                    h-9
                    bg-indigo-100
                    text-indigo-600
                    rounded-lg
                    flex
                    items-center
                    justify-center
                    flex-shrink-0
                "
            >

                <i class="fas fa-shield-halved"></i>

            </div>


            <div>

                <p class="text-sm font-semibold text-slate-700">

                    Customer Account

                </p>


                <p class="text-xs text-slate-500 mt-1 leading-relaxed">

                    New accounts are registered as customer accounts.
                    Staff access is managed securely by the administrator.

                </p>

            </div>

        </div>

    </div>



    {{-- ============================================================ --}}
    {{-- TERMS --}}
    {{-- ============================================================ --}}

    <div class="flex items-start gap-2.5 pt-1">

        <input
            type="checkbox"
            required
            id="terms"
            class="
                w-4
                h-4
                text-indigo-600
                border-slate-300
                rounded-md
                focus:ring-indigo-500
                focus:ring-2
                mt-1
                cursor-pointer
            "
        >


        <label
            for="terms"
            class="
                text-sm
                text-slate-600
                cursor-pointer
                leading-relaxed
            "
        >

            I agree to the

            <a
                href="#"
                class="
                    text-indigo-600
                    hover:text-indigo-800
                    font-medium
                    transition
                "
            >

                Terms of Service

            </a>

            and

            <a
                href="#"
                class="
                    text-indigo-600
                    hover:text-indigo-800
                    font-medium
                    transition
                "
            >

                Privacy Policy

            </a>

        </label>

    </div>



    {{-- ============================================================ --}}
    {{-- SUBMIT --}}
    {{-- ============================================================ --}}

    <button
        type="submit"
        id="registerButton"
        class="
            w-full
            bg-gradient-to-r
            from-indigo-600
            to-purple-600
            hover:from-indigo-700
            hover:to-purple-700
            text-white
            py-3.5
            rounded-xl
            font-semibold
            transition
            flex
            items-center
            justify-center
            gap-2.5
            shadow-lg
            shadow-indigo-500/25
            hover:shadow-indigo-500/35
        "
    >

        <i class="fas fa-user-plus"></i>

        <span>Create Customer Account</span>

    </button>


</form>



{{-- ============================================================ --}}
{{-- LOGIN LINK --}}
{{-- ============================================================ --}}

<div
    class="
        text-center
        mt-6
        pt-5
        border-t
        border-slate-100
    "
>

    <span class="text-slate-500 text-sm">

        Already have an account?

    </span>


    <a
        href="{{ route('login') }}"
        class="
            ml-1.5
            text-indigo-600
            hover:text-indigo-800
            font-semibold
            transition
        "
    >

        Login Here

        <i class="fas fa-arrow-right ml-1.5"></i>

    </a>

</div>



{{-- ============================================================ --}}
{{-- JAVASCRIPT --}}
{{-- ============================================================ --}}

@push('scripts')

<script>

(function () {

    'use strict';


    /*
    |--------------------------------------------------------------------------
    | PASSWORD TOGGLE
    |--------------------------------------------------------------------------
    */

    document
        .querySelectorAll(
            '.password-toggle'
        )
        .forEach(

            function (
                button
            ) {

                button.addEventListener(

                    'click',

                    function () {

                        const targetId =
                            this.dataset.target;


                        const input =
                            document.getElementById(
                                targetId
                            );


                        const icon =
                            this.querySelector(
                                'i'
                            );


                        if (
                            !input
                            ||
                            !icon
                        ) {

                            return;

                        }


                        if (
                            input.type
                            === 'password'
                        ) {

                            input.type =
                                'text';


                            icon.classList.remove(
                                'fa-eye'
                            );


                            icon.classList.add(
                                'fa-eye-slash'
                            );

                        }
                        else {

                            input.type =
                                'password';


                            icon.classList.remove(
                                'fa-eye-slash'
                            );


                            icon.classList.add(
                                'fa-eye'
                            );

                        }

                    }

                );

            }

        );



    /*
    |--------------------------------------------------------------------------
    | PASSWORD ELEMENTS
    |--------------------------------------------------------------------------
    */

    const passwordInput =
        document.getElementById(
            'password'
        );


    const confirmPasswordInput =
        document.getElementById(
            'password_confirmation'
        );


    const strengthBar =
        document.getElementById(
            'strengthBar'
        );


    const strengthText =
        document.getElementById(
            'strengthText'
        );


    const passwordMatchText =
        document.getElementById(
            'passwordMatchText'
        );



    /*
    |--------------------------------------------------------------------------
    | PASSWORD STRENGTH
    |--------------------------------------------------------------------------
    */

    function updatePasswordStrength() {

        if (
            !passwordInput
            ||
            !strengthBar
            ||
            !strengthText
        ) {

            return;

        }


        const password =
            passwordInput.value;


        let score =
            0;


        if (
            password.length >= 8
        ) {

            score++;

        }


        if (
            /[a-z]/.test(
                password
            )
            &&
            /[A-Z]/.test(
                password
            )
        ) {

            score++;

        }


        if (
            /\d/.test(
                password
            )
        ) {

            score++;

        }


        if (
            /[^A-Za-z0-9]/.test(
                password
            )
        ) {

            score++;

        }


        let width =
            0;


        let color =
            '#e2e8f0';


        let label =
            'Enter a password';


        let icon =
            'fa-info-circle';


        if (
            password.length === 0
        ) {

            width =
                0;

        }
        else if (
            score <= 1
        ) {

            width =
                25;


            color =
                '#ef4444';


            label =
                'Weak';


            icon =
                'fa-circle-exclamation';

        }
        else if (
            score === 2
        ) {

            width =
                50;


            color =
                '#f59e0b';


            label =
                'Fair';


            icon =
                'fa-triangle-exclamation';

        }
        else if (
            score === 3
        ) {

            width =
                75;


            color =
                '#3b82f6';


            label =
                'Good';


            icon =
                'fa-circle-check';

        }
        else {

            width =
                100;


            color =
                '#10b981';


            label =
                'Strong';


            icon =
                'fa-shield-halved';

        }


        strengthBar.style.width =
            width + '%';


        strengthBar.style.background =
            color;


        strengthText.innerHTML = `

            <i
                class="fas ${icon} mr-1"
                style="color: ${color}"
            ></i>

            Password strength:

            <span
                class="font-medium"
                style="color: ${color}"
            >

                ${label}

            </span>

        `;

    }



    /*
    |--------------------------------------------------------------------------
    | PASSWORD MATCH
    |--------------------------------------------------------------------------
    */

    function checkPasswordMatch() {

        if (
            !passwordInput
            ||
            !confirmPasswordInput
            ||
            !passwordMatchText
        ) {

            return;

        }


        const password =
            passwordInput.value;


        const confirmPassword =
            confirmPasswordInput.value;


        if (
            confirmPassword.length === 0
        ) {

            passwordMatchText.classList.add(
                'hidden'
            );


            return;

        }


        passwordMatchText.classList.remove(
            'hidden'
        );


        if (
            password
            === confirmPassword
        ) {

            passwordMatchText.className =
                'mt-1.5 text-xs text-emerald-600';


            passwordMatchText.innerHTML = `

                <i class="fas fa-circle-check mr-1"></i>

                Passwords match

            `;

        }
        else {

            passwordMatchText.className =
                'mt-1.5 text-xs text-rose-600';


            passwordMatchText.innerHTML = `

                <i class="fas fa-circle-xmark mr-1"></i>

                Passwords do not match

            `;

        }

    }



    /*
    |--------------------------------------------------------------------------
    | INPUT EVENTS
    |--------------------------------------------------------------------------
    */

    if (
        passwordInput
    ) {

        passwordInput.addEventListener(

            'input',

            function () {

                updatePasswordStrength();


                checkPasswordMatch();

            }

        );

    }


    if (
        confirmPasswordInput
    ) {

        confirmPasswordInput.addEventListener(

            'input',

            checkPasswordMatch

        );

    }



    /*
    |--------------------------------------------------------------------------
    | FORM SUBMIT
    |--------------------------------------------------------------------------
    */

    const registerForm =
        document.getElementById(
            'registerForm'
        );


    const registerButton =
        document.getElementById(
            'registerButton'
        );


    if (
        registerForm
        &&
        registerButton
    ) {

        registerForm.addEventListener(

            'submit',

            function (
                event
            ) {

                if (
                    passwordInput.value
                    !==
                    confirmPasswordInput.value
                ) {

                    event.preventDefault();


                    Swal.fire({

                        title:
                            'Password Mismatch',

                        text:
                            'Password and confirmation password do not match.',

                        icon:
                            'warning',

                        confirmButtonColor:
                            '#4f46e5',

                    });


                    return;

                }


                registerButton.disabled =
                    true;


                registerButton.classList.add(
                    'opacity-70',
                    'cursor-not-allowed'
                );


                registerButton.innerHTML = `

                    <i class="fas fa-spinner fa-spin"></i>

                    <span>Creating Account...</span>

                `;

            }

        );

    }



    /*
    |--------------------------------------------------------------------------
    | AUTO FOCUS
    |--------------------------------------------------------------------------
    */

    const nameInput =
        document.getElementById(
            'name'
        );


    if (
        nameInput
        &&
        !nameInput.value
    ) {

        nameInput.focus();

    }


    console.log(
        '🔐 Secure customer register page loaded'
    );


})();

</script>

@endpush



{{-- ============================================================ --}}
{{-- EXTRA STYLE --}}
{{-- ============================================================ --}}

<style>

    input[type="checkbox"] {

        accent-color: #4f46e5;

        cursor: pointer;

    }


    input:focus {

        box-shadow:
            0 0 0 4px
            rgba(
                79,
                70,
                229,
                0.08
            );

    }


    input.border-rose-400:focus {

        box-shadow:
            0 0 0 4px
            rgba(
                244,
                63,
                94,
                0.08
            );

    }


    #strengthBar {

        transition:
            width 0.4s ease,
            background 0.4s ease;

    }


    #registerButton {

        transition:
            all 0.3s
            cubic-bezier(
                0.4,
                0,
                0.2,
                1
            );

    }


    #registerButton:not(:disabled):hover {

        transform:
            translateY(-2px);

    }


    @media (
        max-width: 640px
    ) {

        .py-3\.5 {

            padding-top:
                0.75rem;

            padding-bottom:
                0.75rem;

        }


        .pl-11 {

            padding-left:
                2.5rem;

        }

    }

</style>


@endsection