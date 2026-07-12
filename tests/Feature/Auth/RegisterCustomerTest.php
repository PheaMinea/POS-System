<?php

namespace Tests\Feature\Auth;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterCustomerTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_registration_creates_customer_record(): void
    {
        $response = $this->post(route('register.store'), [
            'name' => 'Customer One',
            'email' => 'customer@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'customer',
        ]);

        $response->assertRedirect(route('login'));

        $this->assertDatabaseHas('users', [
            'email' => 'customer@example.com',
            'role' => 'customer',
        ]);

        $user = User::where('email', 'customer@example.com')->first();

        $this->assertNotNull($user);
        $this->assertDatabaseHas('customers', [
            'user_id' => $user->id,
            'name' => 'Customer One',
        ]);
        $this->assertTrue(Customer::where('user_id', $user->id)->exists());
    }
}
