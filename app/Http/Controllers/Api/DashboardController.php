<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Purchase;
use Illuminate\Http\JsonResponse;

class DashboardController extends BaseApiController
{
    /**
     * Dashboard Statistics
     */
    public function index(): JsonResponse
    {
        try {

            $dashboard = [
                'total_products'  => Product::count(),
                'total_customers' => Customer::count(),
                'total_orders'    => Order::count(),
                'total_sales'     => Order::sum('total_price'),
                'total_purchases' => Purchase::sum('total_price'),
            ];

            return $this->success(
                $dashboard,
                'Dashboard Retrieved Successfully'
            );

        } catch (\Exception $e) {

            return $this->error(
                $e->getMessage(),
                500
            );

        }
    }
}