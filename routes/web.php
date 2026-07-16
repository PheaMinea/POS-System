<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Auth Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\GoogleLoginController;

/*
|--------------------------------------------------------------------------
| Customer Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\Customer\HomeController;
use App\Http\Controllers\Frontend\Customer\MenuController;
use App\Http\Controllers\Frontend\Customer\CartController;
use App\Http\Controllers\Frontend\Customer\CheckoutController;
use App\Http\Controllers\Frontend\Customer\OrderController as CustomerOrderController;

/*
|--------------------------------------------------------------------------
| Admin Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Frontend\Admin\UserController;
use App\Http\Controllers\Frontend\Admin\CategoryController;
use App\Http\Controllers\Frontend\Admin\ProductController;
use App\Http\Controllers\Frontend\Admin\SupplierController;
use App\Http\Controllers\Frontend\Admin\PurchaseController;
use App\Http\Controllers\Frontend\Admin\ReportController;
use App\Http\Controllers\Frontend\Admin\SettingController;

/*
|--------------------------------------------------------------------------
| Cashier Controllers
|--------------------------------------------------------------------------
*/

use App\Http\Controllers\Frontend\Cashier\DashboardController as CashierDashboardController;
use App\Http\Controllers\Frontend\Cashier\POSController;
use App\Http\Controllers\Frontend\Cashier\CustomerController;
use App\Http\Controllers\Frontend\Cashier\OrderController;
use App\Http\Controllers\Frontend\Cashier\PaymentController;
use App\Http\Controllers\Frontend\Cashier\AutoPaymentController;

/*
|--------------------------------------------------------------------------
| Customer Frontend
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('customer.home');
});

Route::prefix('customer')
    ->name('customer.')
    ->group(function () {

        Route::get('/', [HomeController::class, 'index'])
            ->name('home');

        Route::get('/menu', [MenuController::class, 'index'])
            ->name('menu');

        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart');

        Route::middleware(['auth:web', 'no.cache'])->group(function () {

            Route::get('/cart/current', [CartController::class, 'current'])
                ->name('cart.current');

            Route::get('/checkout', [CheckoutController::class, 'index'])
                ->name('checkout');

            Route::post('/checkout', [CheckoutController::class, 'store'])
                ->name('checkout.store');

            Route::get('/payment/{order}', [CheckoutController::class, 'payment'])
                ->name('payment');

            Route::post('/payment/{order}/verify', [CheckoutController::class, 'verifyPayment'])
                ->name('payment.verify');

            Route::post('/payment/{order}/confirm-cash', [CheckoutController::class, 'confirmCashPayment'])
                ->name('payment.confirm-cash');

            Route::get('/orders', [CustomerOrderController::class, 'index'])
                ->name('orders');

            Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])
                ->name('orders.show');

            Route::get('/receipt/{order}', [CustomerOrderController::class, 'receipt'])
                ->name('receipt');
        });
    });

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest:web')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'loginStore'])
        ->name('login.store');

    Route::get('/register', [AuthController::class, 'register'])
        ->name('register');

    Route::post('/register', [AuthController::class, 'registerStore'])
        ->name('register.store');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware(['auth:web', 'no.cache'])
    ->name('logout');

Route::get('/profile', [AuthController::class, 'profile'])
    ->middleware(['auth:web', 'no.cache'])
    ->name('profile');

/*
|--------------------------------------------------------------------------
| Google Login
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleLoginController::class, 'redirectToGoogle'])
    ->name('auth.google');

Route::get('/auth/google/callback', [GoogleLoginController::class, 'handleGoogleCallback'])
    ->name('auth.google.callback');

/*
|--------------------------------------------------------------------------
| Home Redirect
|--------------------------------------------------------------------------
*/

Route::get('/home', function () {

    $user = auth('web')->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'cashier') {
        return redirect()->route('cashier.dashboard');
    }

    // Customer role or default
    return redirect()->route('customer.home');

})->middleware(['auth:web', 'no.cache'])->name('home');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth:web', 'admin', 'no.cache'])
    ->group(function () {

        Route::get('/dashboard', [
            AdminDashboardController::class,
            'index'
        ])->name('dashboard');

        Route::resource('users', UserController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::resource('suppliers', SupplierController::class);
        Route::resource('purchases', PurchaseController::class);

        Route::get(
            '/purchases/{purchase}/invoice',
            [PurchaseController::class, 'invoice']
        )->name('purchases.invoice');

        Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports.index');

        Route::get('/reports/sales', [ReportController::class, 'sales'])
            ->name('reports.sales');

        Route::get('/reports/sales/export', [ReportController::class, 'salesExport'])
            ->name('reports.sales.export');

        Route::get('/reports/purchases', [ReportController::class, 'purchases'])
            ->name('reports.purchases');

        Route::get('/reports/purchases/export', [ReportController::class, 'purchasesExport'])
            ->name('reports.purchases.export');

        Route::get('/reports/stocks', [ReportController::class, 'stocks'])
            ->name('reports.stocks');

        Route::get('/reports/stocks/export', [ReportController::class, 'stocksExport'])
            ->name('reports.stocks.export');

        Route::get('/reports/profit', [ReportController::class, 'profit'])
            ->name('reports.profit');

        Route::get('/settings', [SettingController::class, 'index'])
            ->name('settings.index');

        Route::post('/settings', [SettingController::class, 'update'])
            ->name('settings.update');
    });

/*
|--------------------------------------------------------------------------
| Cashier Routes
|--------------------------------------------------------------------------
*/

Route::prefix('cashier')
    ->name('cashier.')
    ->middleware(['auth:web', 'cashier', 'no.cache'])
    ->group(function () {

        Route::get('/dashboard', [
            CashierDashboardController::class,
            'index'
        ])->name('dashboard');

        Route::get('/pos', [
            POSController::class,
            'index'
        ])->name('pos.index');

        Route::post('/checkout', [
            POSController::class,
            'checkout'
        ])->name('checkout');

        Route::get('/receipt/{order}', [
            POSController::class,
            'receipt'
        ])->name('receipt');

        Route::resource('customers', CustomerController::class);

        Route::get('orders', [OrderController::class, 'index'])
            ->name('orders.index');

        Route::get('orders/data', [OrderController::class, 'data'])
            ->name('orders.data');

        Route::get('orders/{order}', [OrderController::class, 'show'])
            ->name('orders.show');

        Route::get(
            '/orders/{order}/receipt',
            [OrderController::class, 'receipt']
        )->name('orders.receipt');

        Route::put(
            'orders/{order}/status',
            [OrderController::class, 'updateStatus']
        )->name('orders.update-status');

        Route::delete(
            'orders/{order}',
            [OrderController::class, 'destroy']
        )->name('orders.destroy');

        Route::get(
            'payments',
            [PaymentController::class, 'index']
        )->name('payments.index');

        Route::get(
            'payments/{order}/receipt',
            [PaymentController::class, 'receipt']
        )->name('payments.receipt');

        Route::get(
            'payments/{order}/generate-qr',
            [PaymentController::class, 'generateQR']
        )->name('payments.generate-qr');

        Route::get(
            'payments/{order}/auto-check',
            [AutoPaymentController::class, 'check']
        )->name('auto-payment.check');

        Route::post(
            'payments/{order}/force-verify',
            [AutoPaymentController::class, 'forceVerify']
        )->name('auto-payment.force-verify');

        Route::post(
            'payments/verify',
            [PaymentController::class, 'verify']
        )->name('payments.verify');
    });

Route::fallback(function () {
    abort(404);
});
