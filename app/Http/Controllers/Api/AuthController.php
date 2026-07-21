<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends BaseApiController
{
    /**
     * Register
     */
    public function register(Request $request)
    {
        try {

            $request->validate([
                'name'                  => 'required|string|max:255',
                'email'                 => 'required|email|unique:users,email',
                'password'              => 'required|min:8|confirmed',
                'image'                 => 'nullable|string',
            ]);

            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => 'customer',
                'image'     => $request->image,
            ]);

            return $this->success(
                $user,
                'User Registered Successfully',
                201
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Login
     */
    public function login(Request $request)
    {
        try {

            $credentials = $request->validate([
                'email'    => 'required|email',
                'password' => 'required',
            ]);

            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password'
                ], 401);
            }

            return response()->json([
                'success'      => true,
                'message'      => 'Login Successfully',
                'access_token' => $token,
                'token_type'   => 'Bearer',
                'expires_in'   => auth('api')->factory()->getTTL() * 60,
                'user'         => auth()->user(),
            ], 200);

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Profile
     */
    public function profile()
    {
        try {

            return $this->success(
                auth()->user(),
                'Profile Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Logout
     */
    public function logout()
    {
        try {

            JWTAuth::invalidate(JWTAuth::getToken());

            return $this->success(
                null,
                'Logout Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }

    /**
     * Refresh Token
     */
    public function refresh()
    {
        try {

            $token = JWTAuth::refresh();

            return response()->json([
                'success'      => true,
                'message'      => 'Token Refreshed Successfully',
                'access_token' => $token,
                'token_type'   => 'Bearer',
            ]);

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}
