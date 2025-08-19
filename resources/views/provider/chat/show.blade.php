@extends('layouts.provider')

@section('title', 'Chat with ' . $chat->customer->name)

@push('styles')
<style>
.chat-container {
    height: 70vh;
    display: flex;
    flex-direction: column;
}

.messages-container {
    flex: 1;
    overflow-y: auto;
    padding: 1rem;
    background-color: #f8f9fc;
}

.message-group {
    margin-bottom: 1rem;
}

.message-bubble {
    max-width: 70%;
    padding: 0.75rem 1rem;
    border-radius: 1rem;
    word-wrap: break-word;
    position: relative;
}

.own-message {
    text-align: right;
}

.own-message .message-bubble {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    margin-left: auto;
    border-bottom-right-radius: 0.25rem;
}

.other-message .message-bubble {
    background: white;
    color: #333;
    border: 1px solid #e3e6f0;
    margin-right: auto;
    border-bottom-left-radius: 0.25rem;
}

.message-time {
    font-size: 0.75rem;
    color: #6c757d;
    margin-top: 0.25rem;
}

.own-message .message-time {
    color: rgba(255, 255, 255, 0.8);
}

.message-input-container {
    border-top: 1px solid #e3e6f0;
    background: white;
    padding: 1rem;
}

.customer-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.customer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid rgba(255, 255, 255, 0.3);
}

.online-status {
    width: 12px;
    height: 12px;
    background-color: #28a745;
    border: 2px solid #fff;
    border-radius: 50%;
    position: absolute;
    bottom: 2px;
    right: 2px;
}

.service-info-card {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 0.5rem;
}

.typing-indicator {
    display: none;
    padding: 0.5rem 1rem;
    color: #6c757d;
    font-style: italic;
}

.typing-dots {
    display: inline-block;
}

.typing-dots span {
    display: inline-block;
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background-color: #6c757d;
    margin: 0 1px;
    animation: typing 1.4s infinite ease-in-out;
}

.typing-dots span:nth-child(1) { animation-delay: -0.32s; }
.typing-dots span:nth-child(2) { animation-delay: -0.16s; }

@keyframes typing {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Back Button -->
    <div class="mb-3">
        <a href="{{ route('provider.chat.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Back to Messages
        </a>
    </div>

    <!-- Chat Interface -->
    <div class="card shadow-lg border-0">
        <!-- Customer Header -->
        <div class="card-header customer-header py-3">
            <div class="row align-items-center">
                <div class="col">
                    <div class="d-flex align-items-center">
                        <!-- Customer Avatar -->
                        <div class="position-relative me-3">
                            @if($chat->customer->profile_picture)
                                <img src="{{ asset('storage/' . $chat->customer->profile_picture) }}" 
                                     alt="{{ $chat->customer->name }}" 
                                     class="customer-avatar">
                            @else
                                <div class="customer-avatar bg-white bg-opacity-25 d-flex align-items-center justify-content-center text-white fw-bold">
                                    {{ strtoupper(substr($chat->customer->name, 0, 1)) }}
                                </div>
                            @endif
                            <div class="online-status"></div>
                        </div>

                        <!-- Customer Info -->
                        <div>
                            <h5 class="mb-0">{{ $chat->customer->name }}</h5>
                            <small class="opacity-75">
                                <i class="fas fa-circle text-success me-1" style="font-size: 0.5rem;"></i>
                                Online
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Service Info -->
                @if($chat->booking && $chat->booking->serviceRequest)
                <div class="col-auto">
                    <div class="service-info-card p-2 text-center">
                        <div class="fw-bold">{{ $chat->booking->serviceRequest->serviceCategory->name ?? 'Service' }}</div>
                        <small class="opacity-75">
                            Scheduled: {{ $chat->booking->scheduled_date->format('M d, Y h:i A') }}
                        </small>
                        <div class="mt-1">
                            <span class="badge bg-light text-dark">{{ ucfirst($chat->booking->status) }}</span>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Chat Container -->
        <div class="chat-container">
            <!-- Messages Area -->
            <div class="messages-container" id="messagesContainer">
                @foreach($messages as $message)
                    <div class="message-group {{ $message->sender_id === auth()->id() ? 'own-message' : 'other-message' }}">
                        <div class="message-bubble">
                            {{ $message->message }}
                        </div>
                        <div class="message-time">
                            {{ $message->created_at->format('h:i A') }}
                        </div>
                    </div>
                @endforeach
                
                <!-- Typing Indicator -->
                <div class="typing-indicator" id="typingIndicator">
                    {{ $chat->customer->name }} is typing
                    <div class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>

            <!-- Message Input -->
            <div class="message-input-container">
                <form id="messageForm" action="{{ route('provider.chat.store', $chat) }}" method="POST">
                    @csrf
                    <div class="input-group">
                        <input type="text" 
                               class="form-control border-0" 
                               id="messageInput"
                               name="message" 
                               placeholder="Type your message..." 
                               required
                               autocomplete="off">
                        <button class="btn btn-success" type="submit" id="sendButton">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($messages->hasPages())
        <div class="mt-3">
            {{ $messages->links() }}
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const messagesContainer = document.getElementById('messagesContainer');
    const messageForm = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const sendButton = document.getElementById('sendButton');
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};

    // Scroll to bottom
    function scrollToBottom() {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Initial scroll to bottom
    scrollToBottom();

    // Focus on input
    messageInput.focus();

    // Handle form submission
    messageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        if (!message) return;

        // Disable form
        sendButton.disabled = true;
        messageInput.disabled = true;

        // Send message via AJAX
        console.log('Sending message:', message);
        console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

        fetch(messageForm.action, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ message: message })
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
            if (data.success) {
                // Add message to chat
                addMessageToChat(data.message, true);
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

    // Add message to chat
    function addMessageToChat(message, isOwn) {
        const messageGroup = document.createElement('div');
        messageGroup.className = `message-group ${isOwn ? 'own-message' : 'other-message'}`;
        
        messageGroup.innerHTML = `
            <div class="message-bubble">
                ${message.message}
            </div>
            <div class="message-time">
                ${message.created_at}
            </div>
        `;
        
        messagesContainer.appendChild(messageGroup);
    }

    // Poll for new messages every 3 seconds
    setInterval(function() {
        fetch(`{{ route("provider.chat.show", $chat) }}/messages?last_message_id=${lastMessageId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    data.messages.forEach(message => {
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
});
</script>
@endpush
