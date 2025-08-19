<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\ServiceCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProviderListingTest extends TestCase
{
    public function test_customer_can_see_all_available_providers()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Visit the providers listing page
        $response = $this->actingAs($customer)->get('/customer/providers');

        // Assert the response is successful
        $response->assertStatus(200);
        
        // Check that all 3 providers are displayed
        $response->assertSee('Wangbu Theeng');
        $response->assertSee('Test Provider Awaiting');
        $response->assertSee('Nupur Khadgi');
        
        // Check that it shows the correct count
        $response->assertSee('Showing 1 to 3 of 3 providers');
    }

    public function test_provider_cards_show_correct_information()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Visit the providers listing page
        $response = $this->actingAs($customer)->get('/customer/providers');

        // Check that provider information is displayed
        $response->assertSee('Rs. 500/hr'); // Wangbu's rate
        $response->assertSee('Rs. 400/hr'); // Test Provider's rate
        $response->assertSee('Rs. 300/hr'); // Nupur's rate
        
        // Check service categories
        $response->assertSee('Plumbing');
        $response->assertSee('Electrical');
        $response->assertSee('Painting');
        
        // Check action buttons
        $response->assertSee('View Profile');
        $response->assertSee('Send Request');
    }

    public function test_search_functionality_works()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Search for a specific provider
        $response = $this->actingAs($customer)->get('/customer/providers?search=Wangbu');

        // Should only show Wangbu
        $response->assertSee('Wangbu Theeng');
        $response->assertDontSee('Test Provider Awaiting');
        $response->assertDontSee('Nupur Khadgi');
    }

    public function test_category_filter_works()
    {
        // Create a customer user
        $customer = User::factory()->create([
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        // Get plumbing category ID
        $plumbingCategory = ServiceCategory::where('slug', 'plumbing')->first();
        
        if ($plumbingCategory) {
            // Filter by plumbing category
            $response = $this->actingAs($customer)->get('/customer/providers?category=' . $plumbingCategory->id);

            // Should only show Wangbu (plumber)
            $response->assertSee('Wangbu Theeng');
            $response->assertDontSee('Test Provider Awaiting');
            $response->assertDontSee('Nupur Khadgi');
        }
    }
}
