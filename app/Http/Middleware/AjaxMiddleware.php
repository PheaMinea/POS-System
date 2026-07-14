<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AjaxMiddleware
{
    /**
     * Handle an incoming request.
     * If the request is AJAX, return JSON response instead of redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Check if this is an AJAX request from our SPA
        if ($request->ajax() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            
            // If the response is a redirect, convert it to JSON
            if ($response instanceof \Illuminate\Http\RedirectResponse) {
                $targetUrl = $response->getTargetUrl();
                $sessionData = [];
                
                // Collect session flash data
                if (session()->has('success')) {
                    $sessionData['success'] = session('success');
                }
                if (session()->has('error')) {
                    $sessionData['error'] = session('error');
                }
                if (session()->has('errors')) {
                    $errors = session('errors');
                    if ($errors instanceof \Illuminate\Support\ViewErrorBag) {
                        $sessionData['errors'] = $errors->toArray();
                    }
                }
                if (session()->has('clear_cart')) {
                    $sessionData['clear_cart'] = true;
                }

                return response()->json([
                    'success' => true,
                    'redirect' => $targetUrl,
                    'session' => $sessionData,
                ]);
            }

            // If the response is a view, return the HTML content
            if ($response instanceof \Illuminate\View\View) {
                $html = $response->render();
                return response()->json([
                    'success' => true,
                    'html' => $html,
                ]);
            }
        }

        return $response;
    }
}