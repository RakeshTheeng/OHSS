@extends('layouts.customer')

@section('title', 'Chat with ' . $chat->provider->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">Chat with {{ $chat->provider->name }}</h1>
            <p class="text-muted mb-0">{{ $chat->booking?->serviceRequest?->title ?? 'General conversation' }}</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.chat.index') }}">Messages</a></li>
                <li class="breadcrumb-item active">{{ $chat->provider->name }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Modern Chat Interface -->
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 chat-container">
                <!-- Enhanced Chat Header -->
                <div class="card-header bg-gradient-primary text-white py-3 border-0">
                    <div class="d-flex align-items-center">
                        <a href="{{ route('customer.chat.index') }}" class="btn btn-outline-light btn-sm me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>

                        <div class="position-relative me-3">
                            @if($chat->provider->profile_image)
                                <img src="{{ Storage::url($chat->provider->profile_image) }}"
                                     alt="{{ $chat->provider->name }}"
                                     class="rounded-circle"
                                     style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-white rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 50px; height: 50px;">
                                    <i class="fas fa-user text-primary fa-lg"></i>
                                </div>
                            @endif
                            @if($chat->provider->is_available)
                                <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle"
                                      style="width: 15px; height: 15px;"></span>
                            @endif
                        </div>

                        <div class="flex-grow-1">
                            <h5 class="mb-0">{{ $chat->provider->name }}</h5>
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($chat->provider->rating ?? 0) ? '' : '-o' }} fa-sm"></i>
                                    @endfor
                                </div>
                                <small class="opacity-75">({{ number_format($chat->provider->rating ?? 0, 1) }})</small>
                                <span class="mx-2">•</span>
                                <small class="opacity-75">
                                    @if($chat->provider->is_available)
                                        <i class="fas fa-circle text-success"></i> Online
                                    @else
                                        <i class="fas fa-circle text-secondary"></i> Last seen recently
                                    @endif
                                </small>
                            </div>
                        </div>

                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('customer.providers.show', $chat->provider) }}">
                                    <i class="fas fa-user me-2"></i>View Profile
                                </a></li>
                                @if($chat->booking)
                                <li><a class="dropdown-item" href="{{ route('customer.bookings.show', $chat->booking) }}">
                                    <i class="fas fa-calendar me-2"></i>View Booking
                                </a></li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>
                        @if($chat->booking)
                            <a href="{{ route('customer.bookings.show', $chat->booking) }}"
                               class="btn btn-light btn-sm">
                                <i class="fas fa-calendar-check"></i> View Booking
                            </a>
                        @endif
                        </div>
                    </div>
                </div>

                <!-- Enhanced Messages Area -->
                <div class="card-body p-0 position-relative">
                    <div id="messagesContainer" class="messages-container p-3" style="height: 450px; overflow-y: auto; background: linear-gradient(to bottom, #f8f9fc, #ffffff);">
                        <div id="messagesWrapper">
                            @if($messages->count() > 0)
                                @foreach($messages as $message)
                                    <div class="message-item mb-3 {{ $message->sender_id === auth()->id() ? 'own-message' : 'other-message' }}"
                                         data-message-id="{{ $message->id }}">
                                        <div class="d-flex {{ $message->sender_id === auth()->id() ? 'justify-content-end' : 'justify-content-start' }} align-items-end">
                                            @if($message->sender_id !== auth()->id())
                                                <div class="me-2">
                                                    @if($chat->provider->profile_image)
                                                        <img src="{{ Storage::url($chat->provider->profile_image) }}"
                                                             alt="{{ $chat->provider->name }}"
                                                             class="rounded-circle"
                                                             style="width: 32px; height: 32px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 32px; height: 32px;">
                                                            <i class="fas fa-user text-white fa-sm"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <div class="message-bubble {{ $message->sender_id === auth()->id() ? 'bg-primary text-white own-bubble' : 'bg-white other-bubble' }}"
                                                 style="max-width: 70%; padding: 12px 16px; border-radius: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                                <div class="message-content">{{ $message->message }}</div>
                                                <div class="message-time d-flex justify-content-between align-items-center mt-1">
                                                    <small class="{{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                                        {{ $message->created_at->format('h:i A') }}
                                                    </small>
                                                    @if($message->sender_id === auth()->id())
                                                        <small class="text-white-50 ms-2">
                                                            @if($message->read_at)
                                                                <i class="fas fa-check-double"></i>
                                                            @else
                                                                <i class="fas fa-check"></i>
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-5" id="emptyState">
                                    <div class="mb-4">
                                        <i class="fas fa-comments fa-4x text-muted opacity-50"></i>
                                    </div>
                                    <h5 class="text-muted mb-2">Start the conversation</h5>
                                    <p class="text-muted">Send a message to {{ $chat->provider->name }} about your service.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Typing Indicator -->
                    <div id="typingIndicator" class="typing-indicator px-3 py-2" style="display: none;">
                        <div class="d-flex align-items-center">
                            <div class="me-2">
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 24px; height: 24px;">
                                    <i class="fas fa-user text-white fa-xs"></i>
                                </div>
                            </div>
                            <div class="typing-dots">
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                            <small class="text-muted ms-2">{{ $chat->provider->name }} is typing...</small>
                        </div>
                    </div>
                </div>

                <!-- Enhanced Message Input -->
                <div class="card-footer bg-light border-0">
                    <form id="messageForm" class="d-flex align-items-end">
                        @csrf
                        <input type="hidden" name="chat_id" value="{{ $chat->id }}">
                        <div class="flex-grow-1 me-3">
                            <div class="input-group">
                                <textarea class="form-control border-0 shadow-sm"
                                         id="messageInput"
                                         name="message"
                                         placeholder="Type your message..."
                                         maxlength="1000"
                                         rows="1"
                                         style="resize: none; border-radius: 25px; padding: 12px 20px;"
                                         required></textarea>
                            </div>
                            <div class="text-end mt-1">
                                <small class="text-muted">
                                    <span id="charCount">0</span>/1000
                                </small>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary rounded-circle" style="width: 50px; height: 50px;" id="sendButton">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                        <!-- Debug button -->
                        <button type="button" class="btn btn-secondary ms-2" onclick="testAddMessage()" id="testButton">Test</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Service Details -->
            @if($chat->booking)
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Details</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="{{ $chat->booking->serviceRequest->serviceCategory->icon }} fa-2x text-primary mb-2"></i>
                        <h6>{{ $chat->booking->serviceRequest->title }}</h6>
                        <p class="text-muted small">{{ $chat->booking->serviceRequest->serviceCategory->name }}</p>
                    </div>

                    <div class="mb-2">
                        <strong>Scheduled Date:</strong>
                        <div class="text-muted">{{ $chat->booking->scheduled_date->format('M d, Y h:i A') }}</div>
                    </div>

                    <div class="mb-2">
                        <strong>Duration:</strong>
                        <div class="text-muted">{{ $chat->booking->duration }} hours</div>
                    </div>

                    <div class="mb-2">
                        <strong>Total Amount:</strong>
                        <div class="text-success fw-bold">Rs. {{ number_format($chat->booking->total_amount, 0) }}</div>
                    </div>

                    <div class="mb-2">
                        <strong>Status:</strong>
                        @php
                            $statusColors = [
                                'confirmed' => 'info',
                                'in_progress' => 'primary',
                                'completed' => 'success',
                                'cancelled' => 'secondary'
                            ];
                        @endphp
                        <div>
                            <span class="badge bg-{{ $statusColors[$chat->booking->status] ?? 'secondary' }}">
                                {{ ucfirst(str_replace('_', ' ', $chat->booking->status)) }}
                            </span>
                        </div>
                    </div>

                    @if($chat->booking->special_instructions)
                        <div class="mb-2">
                            <strong>Special Instructions:</strong>
                            <div class="text-muted small">{{ $chat->booking->special_instructions }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Provider Info -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($chat->provider->profile_image)
                            <img src="{{ Storage::url($chat->provider->profile_image) }}"
                                 alt="{{ $chat->provider->name }}"
                                 class="rounded-circle mb-2"
                                 style="width: 80px; height: 80px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mb-2 mx-auto"
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <h6>{{ $chat->provider->name }}</h6>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= ($chat->provider->rating ?? 0) ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted ms-1">({{ number_format($chat->provider->rating ?? 0, 1) }})</span>
                        </div>
                    </div>

                    <div class="mb-2">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        <span>Rs. {{ number_format($chat->provider->hourly_rate, 0) }}/hour</span>
                    </div>

                    <div class="mb-2">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        <span>{{ $chat->provider->experience_years ?? 0 }} years experience</span>
                    </div>

                    @if($chat->provider->phone)
                        <div class="mb-2">
                            <i class="fas fa-phone text-success me-2"></i>
                            <span>{{ $chat->provider->phone }}</span>
                        </div>
                    @endif

                    <div class="mt-3">
                        <a href="{{ route('customer.providers.show', $chat->provider) }}" 
                           class="btn btn-outline-primary btn-sm w-100">
                            <i class="fas fa-user"></i> View Full Profile
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($chat->booking)
                        <a href="{{ route('customer.bookings.show', $chat->booking) }}"
                           class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-calendar-check"></i> View Booking Details
                        </a>

                        @if($chat->booking->status === 'completed' && !$chat->booking->review)
                            <a href="{{ route('customer.reviews.create-from-booking', $chat->booking) }}"
                               class="btn btn-outline-warning btn-sm">
                                <i class="fas fa-star"></i> Write Review
                            </a>
                        @endif
                        @else
                        <p class="text-muted">No booking associated with this chat.</p>
                        @endif

                        @if($chat->booking && $chat->booking->status === 'confirmed' && $chat->booking->scheduled_date->diffInHours(now()) >= 24)
                            <form method="POST" action="{{ route('customer.bookings.cancel', $chat->booking) }}">
                                @csrf
                                <button type="submit"
                                        class="btn btn-outline-danger btn-sm w-100"
                                        onclick="return confirm('Are you sure you want to cancel this booking?')">
                                    <i class="fas fa-times"></i> Cancel Booking
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const messagesContainer = document.getElementById('messagesContainer');
    let lastMessageId = {{ $messages->last()->id ?? 0 }};

    // Scroll to bottom of messages
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initial scroll to bottom
    scrollToBottom();

    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable send button to prevent double submission
        const sendButton = document.getElementById('sendButton');
        sendButton.disabled = true;
        messageInput.disabled = true;

        // Send message via AJAX
        console.log('Sending message:', message);
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch('{{ route("customer.chat.store", $chat) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                message: message
            })
        })
        .then(response => {
            console.log('Response status:', response.status);
            console.log('Response headers:', response.headers);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            console.log('Message data:', data.message);
            if (data.success) {
                // Add message to chat
                console.log('About to add message to chat');
                addMessageToChat(data.message, true);
                console.log('Message added to chat');
                messageInput.value = '';
                scrollToBottom();
                lastMessageId = data.message.id;
            } else {
                throw new Error(data.message || 'Failed to send message');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Failed to send message. Please try again.');
        })
        .finally(() => {
            // Re-enable form
            sendButton.disabled = false;
            messageInput.disabled = false;
            messageInput.focus();
        });
    });

    // Add message to chat UI
    function addMessageToChat(message, isOwn) {
        console.log('addMessageToChat called with:', message, isOwn);
        console.log('messagesContainer:', messagesContainer);

        const messageHtml = `
            <div class="message-item mb-3 ${isOwn ? 'own-message' : 'other-message'}">
                <div class="d-flex ${isOwn ? 'justify-content-end' : 'justify-content-start'}">
                    <div class="message-bubble ${isOwn ? 'bg-primary text-white' : 'bg-light'}"
                         style="max-width: 70%; padding: 10px 15px; border-radius: 18px;">
                        <div class="message-content">${message.message}</div>
                        <div class="message-time text-end mt-1">
                            <small class="${isOwn ? 'text-white-50' : 'text-muted'}">
                                ${message.created_at}
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        `;

        const messagesDiv = messagesContainer.querySelector('#messagesWrapper') || messagesContainer;
        console.log('messagesDiv:', messagesDiv);
        console.log('About to insert HTML:', messageHtml);
        messagesDiv.insertAdjacentHTML('beforeend', messageHtml);
        console.log('HTML inserted successfully');
    }

    // Poll for new messages every 3 seconds
    setInterval(function() {
        fetch(`{{ route("customer.chat.show", $chat) }}/messages?last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                console.log('Polling response:', data);
                if (data.success && data.messages.length > 0) {
                    console.log('New messages found:', data.messages.length);
                    data.messages.forEach(message => {
                        console.log('Adding polled message:', message);
                        addMessageToChat(message, message.is_own);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    scrollToBottom();
                }
            })
            .catch(error => {
                console.error('Error polling messages:', error);
            });
    }, 3000);

    // Test function for debugging
    window.testAddMessage = function() {
        console.log('Testing addMessageToChat function');
        const testMessage = {
            id: 999,
            message: 'Test message from JavaScript',
            created_at: new Date().toLocaleTimeString(),
            sender_name: 'Test User'
        };
        addMessageToChat(testMessage, true);
        scrollToBottom();
    };
});
</script>

<style>
.messages-container {
    background-color: #f8f9fc;
}

.message-bubble {
    word-wrap: break-word;
}

.own-message .message-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

/* Enhanced Chat Styles */
.chat-container {
    border-radius: 15px;
    overflow: hidden;
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.message-item {
    animation: fadeInUp 0.3s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.own-bubble {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
    border-bottom-right-radius: 8px !important;
}

.other-bubble {
    border: 1px solid #e3e6f0;
    border-bottom-left-radius: 8px !important;
}

.messages-container {
    scrollbar-width: thin;
    scrollbar-color: #d1d3e2 transparent;
}

.messages-container::-webkit-scrollbar {
    width: 6px;
}

.messages-container::-webkit-scrollbar-track {
    background: transparent;
}

.messages-container::-webkit-scrollbar-thumb {
    background-color: #d1d3e2;
    border-radius: 3px;
}

.messages-container::-webkit-scrollbar-thumb:hover {
    background-color: #858796;
}

.typing-indicator {
    background: rgba(248, 249, 252, 0.9);
    border-top: 1px solid #e3e6f0;
}

.typing-dots {
    display: flex;
    align-items: center;
    gap: 3px;
}

.typing-dots span {
    width: 6px;
    height: 6px;
    background-color: #858796;
    border-radius: 50%;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) {
    animation-delay: -0.32s;
}

.typing-dots span:nth-child(2) {
    animation-delay: -0.16s;
}

@keyframes typing {
    0%, 80%, 100% {
        transform: scale(0.8);
        opacity: 0.5;
    }
    40% {
        transform: scale(1);
        opacity: 1;
    }
}

#messageInput {
    transition: all 0.3s ease;
}

#messageInput:focus {
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    border-color: #4e73df;
}

#sendButton {
    transition: all 0.3s ease;
}

#sendButton:hover {
    transform: scale(1.05);
}

#sendButton:disabled {
    opacity: 0.6;
    transform: none;
}
</style>
@endsection
