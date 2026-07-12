<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        try {
            if (Schema::hasTable('settings')) {
                $settings = Setting::first();
                if (!$settings) {
                    $settings = Setting::create([
                        'shop_name' => 'POS System',
                        'shop_email' => 'admin@example.com',
                        'shop_phone' => '+855 12 345 678',
                        'shop_address' => 'Phnom Penh, Cambodia',
                        'currency_symbol' => '$',
                        'currency_position' => 'before',
                        'vat_percentage' => 0.00,
                    ]);
                }
                view()->share('shop_settings', $settings);
            }
        } catch (\Exception $e) {
            // Avoid throwing errors if DB isn't set up yet or during migration
        }
    }
}
