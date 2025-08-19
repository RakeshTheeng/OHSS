@extends('layouts.customer')

@section('title', 'Messages')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Messages</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Messages</li>
            </ol>
        </nav>
    </div>

    <!-- Modern Chat Interface -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-comments fa-2x me-3"></i>
                        <div>
                            <h5 class="mb-0">Your Conversations</h5>
                            <small class="opacity-75">Chat with your service providers</small>
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
                                    <a href="{{ route('customer.chat.show', $chat) }}" class="text-decoration-none">
                                        <div class="d-flex align-items-center p-4 chat-hover">
                                            <!-- Provider Avatar -->
                                            <div class="position-relative me-3">
                                                @if($chat->provider->profile_image)
                                                    <img src="{{ Storage::url($chat->provider->profile_image) }}"
                                                         alt="{{ $chat->provider->name }}"
                                                         class="rounded-circle chat-avatar"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center chat-avatar"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-user text-white fa-lg"></i>
                                                    </div>
                                                @endif
                                                @if($chat->provider->is_available)
                                                    <span class="position-absolute bottom-0 end-0 bg-success border border-white rounded-circle"
                                                          style="width: 18px; height: 18px;" title="Online"></span>
                                                @endif
                                            </div>

                                            <!-- Chat Content -->
                                            <div class="flex-grow-1 min-width-0">
                                                <div class="d-flex justify-content-between align-items-start mb-1">
                                                    <h6 class="mb-0 text-dark fw-bold">{{ $chat->provider->name }}</h6>
                                                    <div class="d-flex align-items-center">
                                                        @if($unreadCount > 0)
                                                            <span class="badge bg-danger rounded-pill me-2">{{ $unreadCount }}</span>
                                                        @endif
                                                        @if($lastMessage)
                                                            <small class="text-muted">{{ $lastMessage->created_at->diffForHumans() }}</small>
                                                        @endif
                                                    </div>
                                                </div>

                                                <!-- Provider Rating -->
                                                <div class="d-flex align-items-center mb-2">
                                                    <div class="text-warning me-2">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i <= ($chat->provider->rating ?? 0) ? '' : '-o' }} fa-sm"></i>
                                                        @endfor
                                                    </div>
                                                    <small class="text-muted">({{ number_format($chat->provider->rating ?? 0, 1) }})</small>
                                                </div>

                                                <!-- Service Info -->
                                                @if($chat->booking && $chat->booking->serviceRequest)
                                                    <div class="d-flex align-items-center mb-2">
                                                        <i class="fas fa-tools text-primary me-2"></i>
                                                        <small class="text-muted">{{ $chat->booking->serviceRequest->serviceCategory->name ?? 'Service' }}</small>
                                                    </div>
                                                @endif

                                                <!-- Last Message -->
                                                @if($lastMessage)
                                                    <p class="mb-0 text-muted small text-truncate" style="max-width: 300px;">
                                                        <i class="fas fa-{{ $lastMessage->sender_id === auth()->id() ? 'reply' : 'comment' }} me-1"></i>
                                                        {{ $lastMessage->message }}
                                                    </p>
                                                @else
                                                    <p class="mb-0 text-muted small">
                                                        <i class="fas fa-comment-dots me-1"></i>
                                                        Start a conversation...
                                                    </p>
                                                @endif
                                            </div>

                                            <!-- Chat Status -->
                                            <div class="text-end">
                                                <i class="fas fa-chevron-right text-muted"></i>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach

                        <!-- Pagination -->
                        @if($chats->hasPages())
                            <div class="d-flex justify-content-center p-4 border-top">
                                {{ $chats->links() }}
                            </div>
                        @endif
                    @else
                        <!-- No Conversations -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-comments fa-4x text-muted opacity-50"></i>
                            </div>
                            <h4 class="text-muted mb-3">No conversations yet</h4>
                            <p class="text-muted mb-4">
                                Messages will appear here once you book a service and start communicating with providers.
                            </p>
                            <div class="d-flex justify-content-center gap-3">
                                <a href="{{ route('customer.service-requests.index') }}" class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Request a Service
                                </a>
                                <a href="{{ route('customer.providers.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-search me-2"></i>Find Providers
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.chat-hover {
    transition: all 0.3s ease;
}

.chat-hover:hover {
    background-color: #f8f9fc;
    transform: translateX(5px);
}

.chat-item {
    transition: all 0.2s ease;
}

.chat-item:hover {
    background-color: rgba(78, 115, 223, 0.05);
}

.chat-avatar {
    transition: transform 0.3s ease;
}

.chat-item:hover .chat-avatar {
    transform: scale(1.05);
}

.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

.min-width-0 {
    min-width: 0;
}
</style>
@endsection
