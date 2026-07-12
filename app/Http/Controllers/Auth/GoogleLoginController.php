<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;

class GoogleLoginController extends Controller
{
    /**
     * Redirect to Google
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google Callback
     */
    public function handleGoogleCallback()
    {
        try {

            $googleUser = Socialite::driver('google')->user();

            /*
            |--------------------------------------------------------------------------
            | Find User By Google ID
            |--------------------------------------------------------------------------
            */

            $user = User::where(
                'google_id',
                $googleUser->getId()
            )->first();

            /*
            |--------------------------------------------------------------------------
            | If User Not Found
            |--------------------------------------------------------------------------
            */

            if (!$user) {

                $user = User::where(
                    'email',
                    $googleUser->getEmail()
                )->first();

                /*
                |--------------------------------------------------------------------------
                | Existing Email User
                |--------------------------------------------------------------------------
                */

                if ($user) {

                    $user->update([
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                    ]);

                } else {

                    /*
                    |--------------------------------------------------------------------------
                    | Create New Customer
                    |--------------------------------------------------------------------------
                    */

                    $user = User::create([
                        'name'      => $googleUser->getName(),
                        'email'     => $googleUser->getEmail(),
                        'password'  => bcrypt(uniqid()),
                        'google_id' => $googleUser->getId(),
                        'avatar'    => $googleUser->getAvatar(),
                        'role'      => 'customer',
                    ]);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Login User
            |--------------------------------------------------------------------------
            */

            Auth::guard('web')->login(
                $user,
                true
            );

            request()
                ->session()
                ->regenerate();

            Log::info(
                'Google Login Success',
                [
                    'user_id' => $user->id,
                    'email'   => $user->email,
                    'role'    => $user->role,
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | Redirect By Role
            |--------------------------------------------------------------------------
            */

            if ($user->role === 'admin') {

                return redirect()->route(
                    'admin.dashboard'
                );
            }

            if ($user->role === 'cashier') {

                return redirect()->route(
                    'cashier.dashboard'
                );
            }

            return redirect()->route(
                'customer.home'
            );

        } catch (Exception $e) {

            Log::error(
                'Google Login Error',
                [
                    'message' => $e->getMessage()
                ]
            );

            return redirect()
                ->route('login')
                ->withErrors([
                    'email' => 'Failed to login with Google.'
                ]);
        }
    }
}