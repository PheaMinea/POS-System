<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CashierMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        $guard = $request->is('api/*') ? 'api' : 'web';
        $user = auth($guard)->user();

        if (!$user) {

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        if ($user->role !== 'cashier') {

            return response()->json([
                'success' => false,
                'message' => 'Access Denied. Cashier Only'
            ], 403);
        }

        return $next($request);
    }
}
