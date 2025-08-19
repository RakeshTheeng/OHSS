<?php

namespace App\Http\Controllers\Provider;

use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use App\Models\Booking;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    /**
     * Display all chat conversations for the provider
     */
    public function index()
    {
        $provider = auth()->user();

        // Get all chats where the provider is involved
        $chats = Chat::where('provider_id', $provider->id)
                    ->with(['customer', 'booking.serviceRequest', 'messages' => function($query) {
                        $query->latest()->limit(1);
                    }])
                    ->orderBy('updated_at', 'desc')
                    ->paginate(15);

        return view('provider.chat.index', compact('chats'));
    }

    /**
     * Display a specific chat conversation
     */
    public function show(Chat $chat)
    {
        // Ensure the chat belongs to the authenticated provider
        if ($chat->provider_id !== auth()->id()) {
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

        $chat->load(['customer', 'booking.serviceRequest.serviceCategory']);

        return view('provider.chat.show', compact('chat', 'messages'));
    }

    /**
     * Send a message in the chat
     */
    public function store(Request $request, Chat $chat)
    {
        try {
            // Ensure the chat belongs to the authenticated provider
            if ($chat->provider_id !== auth()->id()) {
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

            // Send notification to customer
            \App\Models\Notification::messageReceived($chat->customer_id, auth()->user(), $chat);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => [
                        'id' => $message->id,
                        'message' => $message->message,
                        'sender_name' => $message->sender->name,
                        'is_own' => true,
                        'created_at' => $message->created_at->format('h:i A'),
                    ]
                ]);
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
        // Ensure the chat belongs to the authenticated provider
        if ($chat->provider_id !== auth()->id()) {
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
            'messages' => $formattedMessages
        ]);
    }
}
