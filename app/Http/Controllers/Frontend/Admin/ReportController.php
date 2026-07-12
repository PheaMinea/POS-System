<?php

namespace App\Http\Controllers\Frontend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Purchase;

class ReportController extends Controller
{
    public function index()
    {
        $summary = [
            'total_sales' => Order::sum('total_price'),
            'total_purchases' => Purchase::sum('total_price'),
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock', '<=', 5)->count(),
        ];

        return view('admin.reports.index', compact('summary'));
    }

    public function sales()
    {
        $orders = Order::with('customer')->latest()->get();

        return view('admin.reports.sales', compact('orders'));
    }

    public function salesExport()
    {
        $orders = Order::with('customer')->latest()->get();
        $filename = 'sales-report-' . now()->format('Ymd-His') . '.doc';

        return response()->streamDownload(function () use ($orders) {
            echo view('admin.reports.sales_export', compact('orders'))->render();
        }, $filename, [
            'Content-Type' => 'application/msword; charset=utf-8',
        ]);
    }

    public function purchases()
    {
        $purchases = Purchase::with('supplier')->latest()->get();

        return view('admin.reports.purchases', compact('purchases'));
    }

    public function purchasesExport()
    {
        $purchases = Purchase::with('supplier')->latest()->get();
        $filename = 'purchases-report-' . now()->format('Ymd-His') . '.doc';

        return response()->streamDownload(function () use ($purchases) {
            echo view('admin.reports.purchases_export', compact('purchases'))->render();
        }, $filename, [
            'Content-Type' => 'application/msword; charset=utf-8',
        ]);
    }

    public function stocks()
    {
        $products = Product::orderBy('name')->get();

        return view('admin.reports.stocks', compact('products'));
    }

    public function stocksExport()
    {
        $products = Product::orderBy('name')->get();
        $filename = 'stock-report-' . now()->format('Ymd-His') . '.doc';

        return response()->streamDownload(function () use ($products) {
            echo view('admin.reports.stocks_export', compact('products'))->render();
        }, $filename, [
            'Content-Type' => 'application/msword; charset=utf-8',
        ]);
    }

    public function profit()
    {
        $totalSales = Order::sum('total_price');
        $totalPurchases = Purchase::sum('total_price');
        $orderCount = Order::count();
        $purchaseCount = Purchase::count();
        $profit = $totalSales - $totalPurchases;

        return view(
            'admin.reports.profit',
            compact('totalSales', 'totalPurchases', 'profit', 'orderCount', 'purchaseCount')
        );
    }
}
