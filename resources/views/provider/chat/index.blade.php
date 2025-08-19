@extends('layouts.provider')

@section('title', 'Messages')

@push('styles')
<style>
.chat-item {
    transition: all 0.3s ease;
    cursor: pointer;
}

.chat-item:hover {
    background-color: #f8f9fc;
    transform: translateX(5px);
}

.chat-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.unread-badge {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
    border-radius: 50px;
    min-width: 20px;
    text-align: center;
}

.last-message {
    color: #6c757d;
    font-size: 0.9rem;
}

.chat-time {
    color: #adb5bd;
    font-size: 0.8rem;
}

.online-indicator {
    width: 12px;
    height: 12px;
    background-color: #28a745;
    border: 2px solid #fff;
    border-radius: 50%;
    position: absolute;
    bottom: 2px;
    right: 2px;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-comments text-primary me-2"></i>
            Messages
        </h1>
        <div class="text-muted">
            <i class="fas fa-info-circle me-1"></i>
            Chat with your customers
        </div>
    </div>

    <!-- Modern Chat Interface -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-success text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-comments fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Your Conversations</h5>
                            <small class="opacity-75">Chat with your customers</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    @if($chats->count() > 0)
                        <div class="chat-list">
                            @foreach($chats as $chat)
                                @php
                                    $lastMessage = $chat->messages->first();
                                    $unreadCount = $chat->getUnreadCountFor(auth()->id());
                                @endphp
                                <div class="chat-item border-bottom">
                                    <a href="{{ route('provider.chat.show', $chat) }}" class="text-decoration-none">
                                        <div class="p-4 d-flex align-items-center">
                                            <!-- Customer Avatar -->
                                            <div class="position-relative me-3">
                                                @if($chat->customer->profile_picture)
                                                    <img src="{{ asset('storage/' . $chat->customer->profile_picture) }}" 
                                                         alt="{{ $chat->customer->name }}" 
                                                         class="chat-avatar">
                                                @else
                                                    <div class="chat-avatar bg-primary d-flex align-items-center justify-content-center text-white fw-bold">
                                                        {{ strtoupper(substr($chat->customer->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <!-- Online indicator (you can implement this based on your logic) -->
                                                <div class="online-indicator"></div>
                                            </div>

                                            <!-- Chat Info -->
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="mb-0 text-dark fw-bold">{{ $chat->customer->name }}</h6>
                                                    <div class="d-flex align-items-center">
                                                        @if($unreadCount > 0)
                                                            <span class="unread-badge me-2">{{ $unreadCount }}</span>
                                                        @endif
                                                        <span class="chat-time">
                                                            {{ $chat->updated_at->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                <!-- Service Info -->
                                                @if($chat->booking && $chat->booking->serviceRequest)
                                                    <div class="d-flex align-items-center mb-1">
                                                        <i class="fas fa-wrench text-muted me-1" style="font-size: 0.8rem;"></i>
                                                        <small class="text-muted">
                                                            {{ $chat->booking->serviceRequest->serviceCategory->name ?? 'Service' }}
                                                        </small>
                                                    </div>
                                                @endif

                                                <!-- Last Message -->
                                                @if($lastMessage)
                                                    <p class="last-message mb-0">
                                                        @if($lastMessage->sender_id === auth()->id())
                                                            <i class="fas fa-reply text-muted me-1"></i>
                                                            You: 
                                                        @endif
                                                        {{ Str::limit($lastMessage->message, 50) }}
                                                    </p>
                                                @else
                                                    <p class="last-message mb-0 fst-italic">No messages yet</p>
                                                @endif
                                            </div>

                                            <!-- Arrow -->
                                            <div class="ms-3">
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if($chats->hasPages())
                            <div class="p-3 border-top">
                                {{ $chats->links() }}
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-comments fa-4x text-muted opacity-50"></i>
                            </div>
                            <h5 class="text-muted mb-3">No conversations yet</h5>
                            <p class="text-muted mb-4">
                                When customers book your services and start conversations,<br>
                                they will appear here.
                            </p>
                            <a href="{{ route('provider.service-requests.index') }}" class="btn btn-primary">
                                <i class="fas fa-clipboard-list me-2"></i>
                                View Service Requests
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects
    const chatItems = document.querySelectorAll('.chat-item');
    chatItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = '#f8f9fc';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
});
</script>
@endpush
