<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create service categories
        ServiceCategory::create([
            'name' => 'Plumbing',
            'slug' => 'plumbing',
            'description' => 'Professional plumbing services',
            'icon' => 'fas fa-wrench',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_customer_dashboard_loads_successfully()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Act as the customer and visit the dashboard
        $response = $this->actingAs($customer)->get('/customer/dashboard');

        // Assert the response is successful
        $response->assertStatus(200);
        $response->assertViewIs('customer.dashboard');
        $response->assertSee('Welcome back, ' . $customer->name);
    }

    public function test_customer_dashboard_shows_stats()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Visit the dashboard
        $response = $this->actingAs($customer)->get('/customer/dashboard');

        // Assert stats are displayed
        $response->assertSee('Total Requests');
        $response->assertSee('Pending Requests');
        $response->assertSee('Upcoming Bookings');
        $response->assertSee('Total Spent');
    }

    public function test_customer_dashboard_shows_service_categories()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Visit the dashboard
        $response = $this->actingAs($customer)->get('/customer/dashboard');

        // Assert service categories are displayed
        $response->assertSee('Popular Services');
        $response->assertSee('Plumbing');
        $response->assertSee('Find Providers');
    }

    public function test_non_customer_cannot_access_dashboard()
    {
        // Create a provider user
        $provider = User::factory()->create([
            'role' => 'provider',
            'email_verified_at' => now(),
        ]);

        // Try to access customer dashboard
        $response = $this->actingAs($provider)->get('/customer/dashboard');

        // Should be redirected
        $response->assertRedirect();
    }

    public function test_guest_cannot_access_dashboard()
    {
        // Try to access customer dashboard without authentication
        $response = $this->get('/customer/dashboard');

        // Should be redirected to login
        $response->assertRedirect('/login');
    }
}
