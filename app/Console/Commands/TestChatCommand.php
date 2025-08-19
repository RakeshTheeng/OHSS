<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;

class TestChatCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:chat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test chat functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // Find or create test users
            $customer = User::where('email', 'test@customer.com')->first();
            if (!$customer) {
                $customer = User::create([
                    'name' => 'Test Customer',
                    'email' => 'test@customer.com',
                    'password' => bcrypt('password'),
                    'role' => 'customer',
                    'phone' => '1234567890',
                    'address' => 'Test Address'
                ]);
                $this->info("Created customer: " . $customer->name . " (ID: " . $customer->id . ")");
            } else {
                $this->info("Found customer: " . $customer->name . " (ID: " . $customer->id . ")");
            }

            $provider = User::where('role', 'provider')->first();
            if (!$provider) {
                $this->error("No provider found");
                return 1;
            }
            $this->info("Found provider: " . $provider->name . " (ID: " . $provider->id . ")");

            // Create or find a chat
            $chat = Chat::where('customer_id', $customer->id)
                        ->where('provider_id', $provider->id)
                        ->first();

            if (!$chat) {
                $chat = Chat::create([
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'booking_id' => null
                ]);
                $this->info("Created chat with ID: " . $chat->id);
            } else {
                $this->info("Found chat with ID: " . $chat->id);
            }

            // Test creating a message
            $message = $chat->messages()->create([
                'sender_id' => $customer->id,
                'message' => 'Test message from customer'
            ]);

            $this->info("Created message with ID: " . $message->id);
            $this->info("Message content: " . $message->message);
            $this->info("Customer Chat URL: http://127.0.0.1:8000/customer/chat/" . $chat->id);
            $this->info("Provider Chat URL: http://127.0.0.1:8000/provider/chat/" . $chat->id);
            $this->info("Customer login: test@customer.com / password");

            // Debug: Check IDs
            $this->info("Debug - Chat customer_id: " . $chat->customer_id);
            $this->info("Debug - Customer ID: " . $customer->id);

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            $this->error("Trace: " . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
