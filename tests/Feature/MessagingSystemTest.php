<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;

class MessagingSystemTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $customer;
    protected $provider;
    protected $chat;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->customer = User::factory()->create([
            'role' => 'customer',
            'email' => 'customer@test.com'
        ]);

        $this->provider = User::factory()->create([
            'role' => 'provider',
            'email' => 'provider@test.com'
        ]);

        // Create service category
        $serviceCategory = ServiceCategory::factory()->create();

        // Create service request
        $serviceRequest = ServiceRequest::factory()->create([
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'service_category_id' => $serviceCategory->id,
            'status' => 'accepted'
        ]);

        // Create booking
        $booking = Booking::factory()->create([
            'service_request_id' => $serviceRequest->id,
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id,
            'status' => 'confirmed'
        ]);

        // Create chat
        $this->chat = Chat::factory()->create([
            'booking_id' => $booking->id,
            'customer_id' => $this->customer->id,
            'provider_id' => $this->provider->id
        ]);
    }

    /** @test */
    public function customer_can_view_chat_index()
    {
        $this->actingAs($this->customer);

        $response = $this->get(route('customer.chat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('customer.chat.index');
        $response->assertViewHas('chats');
    }

    /** @test */
    public function provider_can_view_chat_index()
    {
        $this->actingAs($this->provider);

        $response = $this->get(route('provider.chat.index'));

        $response->assertStatus(200);
        $response->assertViewIs('provider.chat.index');
        $response->assertViewHas('chats');
    }

    /** @test */
    public function customer_can_view_specific_chat()
    {
        $this->actingAs($this->customer);

        $response = $this->get(route('customer.chat.show', $this->chat));

        $response->assertStatus(200);
        $response->assertViewIs('customer.chat.show');
        $response->assertViewHas(['chat', 'messages']);
    }

    /** @test */
    public function provider_can_view_specific_chat()
    {
        $this->actingAs($this->provider);

        $response = $this->get(route('provider.chat.show', $this->chat));

        $response->assertStatus(200);
        $response->assertViewIs('provider.chat.show');
        $response->assertViewHas(['chat', 'messages']);
    }

    /** @test */
    public function customer_can_send_message()
    {
        $this->actingAs($this->customer);

        $messageText = 'Hello, when can you start the service?';

        $response = $this->postJson(route('customer.chat.store', $this->chat), [
            'message' => $messageText
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $this->chat->id,
            'sender_id' => $this->customer->id,
            'message' => $messageText
        ]);
    }

    /** @test */
    public function provider_can_send_message()
    {
        $this->actingAs($this->provider);

        $messageText = 'I can start tomorrow morning at 9 AM.';

        $response = $this->postJson(route('provider.chat.store', $this->chat), [
            'message' => $messageText
        ]);

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'chat_id' => $this->chat->id,
            'sender_id' => $this->provider->id,
            'message' => $messageText
        ]);
    }

    /** @test */
    public function customer_cannot_access_other_customers_chat()
    {
        $otherCustomer = User::factory()->create(['role' => 'customer']);
        $this->actingAs($otherCustomer);

        $response = $this->get(route('customer.chat.show', $this->chat));

        $response->assertStatus(403);
    }

    /** @test */
    public function provider_cannot_access_other_providers_chat()
    {
        $otherProvider = User::factory()->create(['role' => 'provider']);
        $this->actingAs($otherProvider);

        $response = $this->get(route('provider.chat.show', $this->chat));

        $response->assertStatus(403);
    }

    /** @test */
    public function messages_are_marked_as_read_when_viewing_chat()
    {
        // Create an unread message from provider to customer
        $message = Message::factory()->create([
            'chat_id' => $this->chat->id,
            'sender_id' => $this->provider->id,
            'message' => 'Test message',
            'read_at' => null
        ]);

        $this->assertNull($message->fresh()->read_at);

        // Customer views the chat
        $this->actingAs($this->customer);
        $this->get(route('customer.chat.show', $this->chat));

        // Message should now be marked as read
        $this->assertNotNull($message->fresh()->read_at);
    }

    /** @test */
    public function unread_message_count_is_calculated_correctly()
    {
        // Create some messages
        Message::factory()->create([
            'chat_id' => $this->chat->id,
            'sender_id' => $this->provider->id,
            'read_at' => null
        ]);

        Message::factory()->create([
            'chat_id' => $this->chat->id,
            'sender_id' => $this->provider->id,
            'read_at' => null
        ]);

        Message::factory()->create([
            'chat_id' => $this->chat->id,
            'sender_id' => $this->customer->id,
            'read_at' => null
        ]);

        // Customer should have 2 unread messages (from provider)
        $unreadCount = $this->chat->getUnreadCountFor($this->customer->id);
        $this->assertEquals(2, $unreadCount);

        // Provider should have 1 unread message (from customer)
        $unreadCount = $this->chat->getUnreadCountFor($this->provider->id);
        $this->assertEquals(1, $unreadCount);
    }
}
