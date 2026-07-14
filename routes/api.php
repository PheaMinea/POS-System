<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\SupplierController;

use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PurchaseController;
use App\Http\Controllers\Api\PaymentController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {

    Route::post('/register', [
        AuthController::class,
        'register'
    ]);

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);
});

/*
|--------------------------------------------------------------------------
| Customer Public API
|--------------------------------------------------------------------------
*/

Route::get('/categories', [
    CategoryController::class,
    'index'
]);

Route::get('/categories/{category}', [
    CategoryController::class,
    'show'
]);

Route::get('/products', [
    ProductController::class,
    'index'
]);

Route::get('/products/{product}', [
    ProductController::class,
    'show'
]);

/*
|--------------------------------------------------------------------------
| Customer Bakong Payment Verify
|--------------------------------------------------------------------------
|
| Customer checkout popup uses this route to check Bakong payment.
| This route must be outside cashier middleware.
|
*/

Route::post('/payments/{id}/verify', [
    PaymentController::class,
    'verify'
])->name('payments.verify');

/*
|--------------------------------------------------------------------------
| Protected Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth:api')->group(function () {

    Route::get('/profile', [
        AuthController::class,
        'profile'
    ]);

    Route::post('/logout', [
        AuthController::class,
        'logout'
    ]);

    Route::post('/refresh', [
        AuthController::class,
        'refresh'
    ]);

    Route::get('/dashboard', [
        DashboardController::class,
        'index'
    ]);
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:api',
    'admin'
])->group(function () {

    Route::apiResource(
        'categories',
        CategoryController::class
    );

    Route::apiResource(
        'products',
        ProductController::class
    );

    Route::apiResource(
        'suppliers',
        SupplierController::class
    );

    Route::apiResource(
        'purchases',
        PurchaseController::class
    );
});

/*
|--------------------------------------------------------------------------
| Cashier Routes
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth:api',
    'cashier'
])->group(function () {

    Route::apiResource(
        'customers',
        CustomerController::class
    );

    Route::apiResource(
        'orders',
        OrderController::class
    );

    Route::apiResource(
        'payments',
        PaymentController::class
    );
});

/*
|--------------------------------------------------------------------------
| Fallback
|--------------------------------------------------------------------------
*/

Route::fallback(function () {

    return response()->json([
        'success' => false,
        'message' => 'API Route Not Found'
    ], 404);

});