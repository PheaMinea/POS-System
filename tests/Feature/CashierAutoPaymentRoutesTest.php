<?php

namespace Tests\Feature;

use Tests\TestCase;

class CashierAutoPaymentRoutesTest extends TestCase
{
    public function test_cashier_auto_payment_routes_are_registered(): void
    {
        $this->assertNotNull(route('cashier.auto-payment.check', 1));
        $this->assertNotNull(route('cashier.auto-payment.force-verify', 1));
    }
}
