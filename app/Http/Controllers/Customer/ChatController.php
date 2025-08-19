<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\Booking;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'customer') {
                abort(403, 'Access denied. Customer access required.');
            }
            return $next($request);
        });
    }

    /**
     * Display all chat conversations for the customer
     */
    public function index()
    {
        $customer = auth()->user();

        // Get all chats where the customer is involved
        $chats = Chat::where('customer_id', $customer->id)
                    ->with(['provider', 'booking.serviceRequest.serviceCategory', 'messages' => function($query) {
                        $query->latest()->limit(1);
                    }])
                    ->orderBy('updated_at', 'desc')
                    ->paginate(15);

        return view('customer.chat.index', compact('chats'));
    }

    /**
     * Display a specific chat conversation
     */
    public function show(Chat $chat)
    {
        // Ensure the chat belongs to the authenticated customer
        if ($chat->customer_id !== auth()->id()) {
            abort(403);
        }

        // Load messages with sender information
        $messages = $chat->messages()
                        ->with('sender')
                        ->orderBy('created_at', 'asc')
                        ->paginate(50);

        // Mark messages as read
        $chat->messages()
             ->where('sender_id', '!=', auth()->id())
             ->whereNull('read_at')
             ->update(['read_at' => now()]);

        $chat->load(['provider', 'booking.serviceRequest.serviceCategory']);

        return view('customer.chat.show', compact('chat', 'messages'));
    }

    /**
     * Send a message in the chat
     */
    public function store(Request $request, Chat $chat)
    {
        try {
            // Debug logging
            \Log::info('Chat message attempt', [
                'chat_id' => $chat->id,
                'chat_customer_id' => $chat->customer_id,
                'auth_user_id' => auth()->id(),
                'auth_user' => auth()->user() ? auth()->user()->toArray() : null,
                'message' => $request->message
            ]);

            // Ensure the chat belongs to the authenticated customer
            if ($chat->customer_id !== auth()->id()) {
                \Log::warning('Chat access denied', [
                    'chat_customer_id' => $chat->customer_id,
                    'auth_user_id' => auth()->id(),
                    'auth_user_role' => auth()->user() ? auth()->user()->role : 'null',
                    'auth_check' => auth()->check()
                ]);

                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access to this chat.'
                    ], 403);
                }
                abort(403);
            }

            $request->validate([
                'message' => 'required|string|max:1000',
            ]);

            $message = $chat->messages()->create([
                'sender_id' => auth()->id(),
                'message' => $request->message,
            ]);

            // Update chat's last activity
            $chat->touch();

            // Send notification to provider
            \App\Models\Notification::messageReceived($chat->provider_id, auth()->user(), $chat);

            if ($request->ajax()) {
                $responseData = [
                    'success' => true,
                    'message' => [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_name' => $message->sender->name,
                        'is_own' => true,
                        'created_at' => $message->created_at->format('h:i A'),
                    ]
                ];

                \Log::info('Chat message response', $responseData);

                return response()->json($responseData);
            }

            return back()->with('success', 'Message sent successfully!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Chat message store error: ' . $e->getMessage(), [
                'chat_id' => $chat->id,
                'user_id' => auth()->id(),
                'message' => $request->message,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred while sending the message: ' . $e->getMessage()
                ], 500);
            }
            return back()->with('error', 'Failed to send message. Please try again: ' . $e->getMessage());
        }
    }

    /**
     * Get new messages via AJAX
     */
    public function getMessages(Chat $chat, Request $request)
    {
        // Ensure the chat belongs to the authenticated customer
        if ($chat->customer_id !== auth()->id()) {
            abort(403);
        }

        $lastMessageId = $request->get('last_message_id', 0);

        $messages = $chat->messages()
                        ->with('sender')
                        ->where('id', '>', $lastMessageId)
                        ->orderBy('created_at', 'asc')
                        ->get();

        // Mark new messages as read
        $chat->messages()
             ->where('sender_id', '!=', auth()->id())
             ->where('id', '>', $lastMessageId)
             ->whereNull('read_at')
             ->update(['read_at' => now()]);

        $formattedMessages = $messages->map(function($message) {
            return [
                'id' => $message->id,
                'message' => $message->message,
                'sender_name' => $message->sender->name,
                'is_own' => $message->sender_id === auth()->id(),
                'created_at' => $message->created_at->format('h:i A'),
            ];
        });

        return response()->json([
            'success' => true,
            'messages' => $formattedMessages
        ]);
    }
}
