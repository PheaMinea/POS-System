<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW LOGIN PAGE
    |--------------------------------------------------------------------------
    */

    public function login(): View
    {
        return view('auth.login');
    }


    /*
    |--------------------------------------------------------------------------
    | LOGIN STORE
    |--------------------------------------------------------------------------
    */

    public function loginStore(
        LoginRequest $request
    ): RedirectResponse
    {
        $credentials = $request->only(
            'email',
            'password'
        );

        /*
        |--------------------------------------------------------------------------
        | ATTEMPT LOGIN
        |--------------------------------------------------------------------------
        */

        if (
            !Auth::guard('web')->attempt(
                $credentials,
                $request->boolean('remember')
            )
        ) {
            return redirect()
                ->back()
                ->withErrors([
                    'email' => 'Invalid email or password.',
                ])
                ->withInput(
                    $request->only('email')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | REGENERATE SESSION
        |--------------------------------------------------------------------------
        */

        $request
            ->session()
            ->regenerate();


        /*
        |--------------------------------------------------------------------------
        | GET AUTHENTICATED USER
        |--------------------------------------------------------------------------
        */

        $user = Auth::guard('web')->user();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT BY ROLE
        |--------------------------------------------------------------------------
        */

        $role = strtolower(
            $user->role ?? 'customer'
        );


        if ($role === 'admin') {
            return redirect()
                ->intended(
                    route('admin.dashboard')
                );
        }


        if ($role === 'cashier') {
            return redirect()
                ->intended(
                    route('cashier.dashboard')
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->intended(
                route('customer.home')
            );
    }


    /*
    |--------------------------------------------------------------------------
    | SHOW REGISTER PAGE
    |--------------------------------------------------------------------------
    */

    public function register(): View
    {
        return view('auth.register');
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER STORE
    |--------------------------------------------------------------------------
    |
    | SECURITY:
    |
    | Public registration can ONLY create customer accounts.
    |
    | Never trust role from browser, Blade, JavaScript, Postman,
    | DevTools, or request payload.
    |
    |--------------------------------------------------------------------------
    */

    public function registerStore(
        RegisterRequest $request
    ): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | GET VALIDATED DATA
        |--------------------------------------------------------------------------
        */

        $validated = $request->validated();


        /*
        |--------------------------------------------------------------------------
        | CREATE SAFE USER DATA
        |--------------------------------------------------------------------------
        |
        | We explicitly select allowed fields.
        |
        | Do NOT use:
        |
        | $data = $request->validated();
        |
        | then User::create($data)
        |
        | because role could accidentally be added to RegisterRequest later.
        |
        |--------------------------------------------------------------------------
        */

        $data = [
            'name' => $validated['name'],

            'email' => $validated['email'],

            'password' => Hash::make(
                $validated['password']
            ),

            /*
            |--------------------------------------------------------------------------
            | FORCE CUSTOMER ROLE
            |--------------------------------------------------------------------------
            */

            'role' => 'customer',
        ];


        /*
        |--------------------------------------------------------------------------
        | USER IMAGE
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('image')) {
            $data['image'] = $request
                ->file('image')
                ->store(
                    'users',
                    'public'
                );
        }


        /*
        |--------------------------------------------------------------------------
        | CREATE USER
        |--------------------------------------------------------------------------
        */

        User::create($data);


        /*
        |--------------------------------------------------------------------------
        | REDIRECT LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registration successful. Please log in.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LOGOUT
    |--------------------------------------------------------------------------
    */

    public function logout(): RedirectResponse
    {
        /*
        |--------------------------------------------------------------------------
        | LOGOUT WEB GUARD
        |--------------------------------------------------------------------------
        */

        Auth::guard('web')->logout();


        /*
        |--------------------------------------------------------------------------
        | INVALIDATE SESSION
        |--------------------------------------------------------------------------
        */

        request()
            ->session()
            ->invalidate();


        /*
        |--------------------------------------------------------------------------
        | REGENERATE CSRF TOKEN
        |--------------------------------------------------------------------------
        */

        request()
            ->session()
            ->regenerateToken();


        /*
        |--------------------------------------------------------------------------
        | REDIRECT LOGIN
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('login');
    }
}