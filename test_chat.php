<?php

require_once 'vendor/autoload.php';

use App\Models\User;
use App\Models\Chat;
use App\Models\Message;

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

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
        echo "Created customer: " . $customer->name . " (ID: " . $customer->id . ")\n";
    } else {
        echo "Found customer: " . $customer->name . " (ID: " . $customer->id . ")\n";
    }

    $provider = User::where('role', 'provider')->first();
    if (!$provider) {
        echo "No provider found\n";
        exit(1);
    }
    echo "Found provider: " . $provider->name . " (ID: " . $provider->id . ")\n";

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
        echo "Created chat with ID: " . $chat->id . "\n";
    } else {
        echo "Found chat with ID: " . $chat->id . "\n";
    }

    // Test creating a message
    $message = $chat->messages()->create([
        'sender_id' => $customer->id,
        'message' => 'Test message from customer'
    ]);
    
    echo "Created message with ID: " . $message->id . "\n";
    echo "Message content: " . $message->message . "\n";
    echo "Chat URL: http://127.0.0.1:8000/customer/chat/" . $chat->id . "\n";
    echo "Provider Chat URL: http://127.0.0.1:8000/provider/chat/" . $chat->id . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
