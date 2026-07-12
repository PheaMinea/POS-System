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
    public function login(): View
    {
        return view('auth.login');
    }

    public function loginStore(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only(
            'email',
            'password'
        );

        if (
            !Auth::guard('web')->attempt(
                $credentials,
                $request->has('remember')
            )
        ) {
            return redirect()
                ->back()
                ->withErrors([
                    'email' => 'Invalid email or password.'
                ])
                ->withInput(
                    $request->only('email')
                );
        }

        $request->session()->regenerate();

        return redirect()->intended(
            route('home')
        );
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function registerStore(
        RegisterRequest $request
    ): RedirectResponse {

        $data = $request->safe()->except([
            'password_confirmation',
            'image'
        ]);

        $data['password'] = Hash::make(
            $data['password']
        );

        $data['role'] = $data['role'] ?? 'customer';

        if ($request->hasFile('image')) {

            $data['image'] = $request->file('image')
                ->store(
                    'users',
                    'public'
                );

        }

        User::create($data);

        return redirect()
            ->route('login')
            ->with(
                'success',
                'Registration successful. Please log in.'
            );
    }

    public function logout(): RedirectResponse
    {
        Auth::guard('web')->logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect()
            ->route('login');
    }
}