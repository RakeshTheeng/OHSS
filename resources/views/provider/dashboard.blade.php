@extends('layouts.app')

@section('title', 'Provider Dashboard')

@push('styles')
<style>
.hover-card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.hover-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

a.text-decoration-none:hover .hover-card .text-gray-800 {
    color: inherit !important;
}
</style>
@endpush

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card bg-gradient-primary text-white">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col">
                            <h2 class="mb-0">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0">Here's what's happening with your services today.</p>
                        </div>
                        <div class="col-auto">
                            <div class="text-center">
                                <h3 class="mb-0">{{ $stats['average_rating'] }}/5</h3>
                                <small>Average Rating</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('provider.service-requests.index') }}" class="text-decoration-none">
                <div class="card border-left-primary shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_requests'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('provider.service-requests.index', ['status' => 'pending']) }}" class="text-decoration-none">
                <div class="card border-left-warning shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Pending Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_requests'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('provider.service-requests.index', ['status' => 'accepted']) }}" class="text-decoration-none">
                <div class="card border-left-success shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Accepted Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['accepted_requests'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <a href="{{ route('provider.service-requests.index', ['status' => 'completed']) }}" class="text-decoration-none">
                <div class="card border-left-info shadow h-100 py-2 hover-card">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Completed Requests
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed_requests'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-double fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Additional Stats -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $stats['completed_bookings'] }}</h5>
                    <p class="card-text">Completed Jobs</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $stats['total_reviews'] }}</h5>
                    <p class="card-text">Total Reviews</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <a href="{{ route('provider.service-requests.index', ['status' => 'accepted']) }}" class="text-decoration-none">
                <div class="card text-center hover-card">
                    <div class="card-body">
                        <h5 class="card-title text-info">{{ $stats['accepted_requests'] }}</h5>
                        <p class="card-text text-muted">Accepted Requests</p>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Recent Service Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    <a href="{{ route('service-requests.index') }}" class="btn btn-sm btn-primary">View All</a>
                </div>
                <div class="card-body">
                    @if($recent_requests->count() > 0)
                        @foreach($recent_requests as $request)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $request->customer->name }}</div>
                                    <div class="text-gray-600">{{ $request->serviceCategory->name }}</div>
                                    <div class="small text-gray-500">{{ $request->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="text-right">
                                    <span class="badge badge-{{ $request->status_badge }}">{{ ucfirst($request->status) }}</span>
                                    @if($request->status === 'pending')
                                        <div class="mt-1">
                                            <a href="{{ route('provider.service-requests.show', $request) }}" class="btn btn-sm btn-primary">View</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No recent service requests</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Bookings -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Upcoming Bookings</h6>
                    <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-success">View All</a>
                </div>
                <div class="card-body">
                    @if($upcoming_bookings->count() > 0)
                        @foreach($upcoming_bookings as $booking)
                            <div class="d-flex align-items-center mb-3 p-3 border rounded">
                                <div class="mr-3">
                                    <div class="icon-circle bg-success">
                                        <i class="fas fa-calendar text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $booking->customer->name }}</div>
                                    <div class="text-gray-600">{{ $booking->serviceRequest->serviceCategory->name }}</div>
                                    <div class="small text-gray-500">{{ $booking->scheduled_date->format('M d, Y H:i') }}</div>
                                </div>
                                <div class="text-right">
                                    <div class="font-weight-bold text-success">Rs. {{ number_format($booking->total_amount, 2) }}</div>
                                    <div class="mt-1">
                                        <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-success">View</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <p class="text-muted text-center">No upcoming bookings</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Messages -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Messages</h6>
                    <div class="d-flex align-items-center">
                        @if($unread_messages_count > 0)
                            <span class="badge bg-danger me-2">{{ $unread_messages_count }} unread</span>
                        @endif
                        <a href="{{ route('provider.chat.index') }}" class="btn btn-sm btn-primary">View All</a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recent_chats->count() > 0)
                        @foreach($recent_chats as $chat)
                            @php
                                $lastMessage = $chat->messages->first();
                                $unreadCount = $chat->getUnreadCountFor(auth()->id());
                            @endphp
                            <div class="d-flex align-items-center mb-3 p-3 border rounded hover-card">
                                <div class="me-3">
                                    @if($chat->customer->profile_picture)
                                        <img src="{{ asset('storage/' . $chat->customer->profile_picture) }}"
                                             alt="{{ $chat->customer->name }}"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="icon-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold" style="width: 40px; height: 40px;">
                                            {{ strtoupper(substr($chat->customer->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="font-weight-bold">{{ $chat->customer->name }}</div>
                                        <div class="d-flex align-items-center">
                                            @if($unreadCount > 0)
                                                <span class="badge bg-danger me-2">{{ $unreadCount }}</span>
                                            @endif
                                            <small class="text-gray-500">{{ $chat->updated_at->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                    @if($lastMessage)
                                        <div class="text-gray-600 small">
                                            @if($lastMessage->sender_id === auth()->id())
                                                <i class="fas fa-reply text-muted me-1"></i>You:
                                            @endif
                                            {{ Str::limit($lastMessage->message, 60) }}
                                        </div>
                                    @endif
                                </div>
                                <div class="ms-3">
                                    <a href="{{ route('provider.chat.show', $chat) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-comment"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-comments fa-3x text-muted opacity-50 mb-3"></i>
                            <p class="text-muted">No recent messages</p>
                            <small class="text-gray-500">Messages from customers will appear here</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Reviews -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Recent Reviews</h6>
                    <a href="#" class="btn btn-sm btn-warning">View All</a>
                </div>
                <div class="card-body">
                    @if($recent_reviews->count() > 0)
                        <div class="row">
                            @foreach($recent_reviews as $review)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-left-warning">
                                        <div class="card-body">
                                            <div class="d-flex align-items-center mb-2">
                                                <div class="mr-3">
                                                    @if($review->customer->profile_image)
                                                        <img class="rounded-circle" src="{{ Storage::url($review->customer->profile_image) }}" alt="{{ $review->customer->name }}" width="40" height="40">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                             style="width: 40px; height: 40px;">
                                                            <i class="fas fa-user text-white"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="font-weight-bold">{{ $review->customer->name }}</div>
                                                    <div class="text-warning">{{ $review->stars }}</div>
                                                </div>
                                                <div class="text-right">
                                                    <small class="text-gray-500">{{ $review->created_at->diffForHumans() }}</small>
                                                </div>
                                            </div>
                                            @if($review->comment)
                                                <p class="mb-0 text-gray-700">{{ Str::limit($review->comment, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted text-center">No reviews yet</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge-pending { background-color: #f6c23e; }
.badge-accepted { background-color: #1cc88a; }
.badge-rejected { background-color: #e74a3b; }
.badge-completed { background-color: #1cc88a; }
.badge-confirmed { background-color: #36b9cc; }
.badge-cancelled { background-color: #858796; }
</style>
@endsection
