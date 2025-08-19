<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\ServiceCategory;

class DemoController extends Controller
{
    /**
     * Show messaging system demo
     */
    public function messaging()
    {
        // Get sample data for demo
        $customerChats = Chat::with(['provider', 'messages' => function($query) {
                                $query->latest()->limit(1);
                            }])
                            ->whereHas('customer')
                            ->orderBy('updated_at', 'desc')
                            ->take(3)
                            ->get();

        $providerChats = Chat::with(['customer', 'messages' => function($query) {
                                $query->latest()->limit(1);
                            }])
                            ->whereHas('provider')
                            ->orderBy('updated_at', 'desc')
                            ->take(3)
                            ->get();

        return view('demo.messaging', compact('customerChats', 'providerChats'));
    }

    /**
     * Create sample data for messaging demo
     */
    public function createSampleData()
    {
        try {
            // Create sample users if they don't exist
            $customer = User::firstOrCreate(
                ['email' => 'demo.customer@household.com'],
                [
                    'name' => 'Demo Customer',
                    'role' => 'customer',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now()
                ]
            );

            $provider = User::firstOrCreate(
                ['email' => 'demo.provider@household.com'],
                [
                    'name' => 'Demo Provider',
                    'role' => 'provider',
                    'password' => bcrypt('password'),
                    'email_verified_at' => now(),
                    'provider_status' => 'approved',
                    'is_available' => true
                ]
            );

            // Create service category
            $serviceCategory = ServiceCategory::firstOrCreate(
                ['name' => 'Plumbing'],
                ['description' => 'Professional plumbing services']
            );

            // Create service request
            $serviceRequest = ServiceRequest::firstOrCreate(
                [
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id
                ],
                [
                    'service_category_id' => $serviceCategory->id,
                    'title' => 'Fix Kitchen Sink',
                    'description' => 'Kitchen sink is leaking and needs repair',
                    'location' => 'Kathmandu, Nepal',
                    'preferred_date' => now()->addDays(1),
                    'budget_min' => 1000,
                    'budget_max' => 3000,
                    'status' => 'accepted'
                ]
            );

            // Create booking
            $booking = Booking::firstOrCreate(
                ['service_request_id' => $serviceRequest->id],
                [
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'scheduled_date' => now()->addDays(1)->setTime(10, 0),
                    'duration_hours' => 2,
                    'total_amount' => 2000,
                    'status' => 'confirmed'
                ]
            );

            // Create chat
            $chat = Chat::firstOrCreate(
                ['booking_id' => $booking->id],
                [
                    'customer_id' => $customer->id,
                    'provider_id' => $provider->id,
                    'is_active' => true
                ]
            );

            // Create sample messages
            $messages = [
                [
                    'sender_id' => $customer->id,
                    'message' => 'Hello! I have a leaking kitchen sink that needs urgent repair. When can you come?',
                    'created_at' => now()->subHours(2)
                ],
                [
                    'sender_id' => $provider->id,
                    'message' => 'Hi! I can come tomorrow morning around 10 AM. Is that convenient for you?',
                    'created_at' => now()->subHours(1)->subMinutes(30)
                ],
                [
                    'sender_id' => $customer->id,
                    'message' => 'Perfect! 10 AM works great. Do you need me to prepare anything beforehand?',
                    'created_at' => now()->subHour()
                ],
                [
                    'sender_id' => $provider->id,
                    'message' => 'Just make sure the area under the sink is clear so I can access the pipes easily. I\'ll bring all necessary tools.',
                    'created_at' => now()->subMinutes(30)
                ],
                [
                    'sender_id' => $customer->id,
                    'message' => 'Great! I\'ll clear the area. See you tomorrow at 10 AM. Thank you!',
                    'created_at' => now()->subMinutes(15)
                ]
            ];

            foreach ($messages as $messageData) {
                Message::firstOrCreate(
                    [
                        'chat_id' => $chat->id,
                        'sender_id' => $messageData['sender_id'],
                        'message' => $messageData['message']
                    ],
                    [
                        'created_at' => $messageData['created_at'],
                        'updated_at' => $messageData['created_at']
                    ]
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Sample messaging data created successfully!',
                'data' => [
                    'customer_email' => $customer->email,
                    'provider_email' => $provider->email,
                    'chat_id' => $chat->id,
                    'messages_count' => $chat->messages()->count()
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating sample data: ' . $e->getMessage()
            ], 500);
        }
    }
}
