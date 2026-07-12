<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class CashierPaymentsRoutesTest extends TestCase
{
    public function test_cashier_payment_routes_are_registered(): void
    {
        $this->assertTrue(Route::has('cashier.payments.index'));
        $this->assertTrue(Route::has('cashier.payments.receipt'));
        $this->assertTrue(Route::has('cashier.orders.data'));
    }

    public function test_cashier_receipt_page_auto_prints_when_loaded(): void
    {
        $user = User::factory()->create(['name' => 'Cashier']);
        $this->actingAs($user);

        $order = new class {
            public $id = 1;
            public $created_at;
            public $payment;
            public $customer;
            public $orderItems;
            public $total_price = 25.00;

            public function __construct()
            {
                $this->created_at = now();
                $this->payment = new class {
                    public $reference_no = 'INV-0001';
                    public $payment_method = 'cash';
                };
                $this->customer = new class {
                    public $name = 'Walk-in';
                };
                $this->orderItems = collect([]);
            }
        };

        $html = view('cashier.pos.receipt', compact('order'))->render();

        $this->assertStringContainsString('window.addEventListener(\'load\'', $html);
        $this->assertStringContainsString('setTimeout(function ()', $html);
    }
}
