@extends('layouts.customer')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.bookings.index') }}">My Bookings</a></li>
                <li class="breadcrumb-item active">Booking #{{ $booking->id }}</li>
            </ol>
        </nav>
    </div>

    <!-- Review Prompt for Completed Services -->
    @if($booking->status === 'completed' && !$booking->review)
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-star fa-2x text-warning me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Service Completed Successfully! 🎉</h5>
                    <p class="mb-2">How was your experience with <strong>{{ $booking->provider->name }}</strong>? Your feedback helps other customers and improves our service quality.</p>
                    <a href="{{ route('customer.reviews.create-from-booking', $booking) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-star me-1"></i> Write Review Now
                    </a>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Existing Review Display -->
    @if($booking->review)
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-start">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Your Review</h5>
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $booking->review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <span class="badge bg-primary">{{ $booking->review->rating }}/5 Stars</span>
                    </div>
                    @if($booking->review->comment)
                        <p class="mb-2">"{{ $booking->review->comment }}"</p>
                    @endif
                    <small class="text-muted">Reviewed on {{ $booking->review->created_at->format('M d, Y \a\t h:i A') }}</small>
                    @if($booking->review->created_at->diffInHours(now()) <= 24)
                        <div class="mt-2">
                            <a href="{{ route('customer.reviews.edit', $booking->review) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i> Edit Review
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Main Booking Details -->
        <div class="col-lg-8">
            <!-- Service Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary">{{ $booking->serviceRequest->title }}</h5>
                            <p class="text-muted mb-3">{{ $booking->serviceRequest->serviceCategory->name }}</p>
                            
                            @if($booking->serviceRequest->description)
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p class="mt-1">{{ $booking->serviceRequest->description }}</p>
                                </div>
                            @endif

                            <div class="mb-3">
                                <strong>Service Location:</strong>
                                <p class="mt-1">{{ $booking->serviceRequest->address }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong>Scheduled Date & Time:</strong>
                                <p class="mt-1">
                                    <i class="fas fa-calendar me-2 text-primary"></i>
                                    {{ $booking->scheduled_date->format('l, F j, Y') }}
                                </p>
                                <p>
                                    <i class="fas fa-clock me-2 text-primary"></i>
                                    {{ $booking->scheduled_date->format('h:i A') }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <strong>Duration:</strong>
                                <p class="mt-1">
                                    <i class="fas fa-hourglass-half me-2 text-primary"></i>
                                    {{ $booking->duration }} hour{{ $booking->duration > 1 ? 's' : '' }}
                                </p>
                            </div>

                            <div class="mb-3">
                                <strong>Status:</strong>
                                <p class="mt-1">
                                    @php
                                        $statusColors = [
                                            'confirmed' => 'info',
                                            'in_progress' => 'primary',
                                            'completed' => 'success',
                                            'cancelled' => 'secondary'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }} p-2">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Provider</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($booking->provider->profile_image)
                            <img src="{{ Storage::url($booking->provider->profile_image) }}" 
                                 class="rounded-circle me-3" 
                                 width="80" height="80" 
                                 alt="{{ $booking->provider->name }}"
                                 style="object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 80px; height: 80px;">
                                <i class="fas fa-user text-white fa-2x"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h5 class="mb-1">{{ $booking->provider->name }}</h5>
                            <div class="text-warning mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= ($booking->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ number_format($booking->provider->rating ?? 0, 1) }})</span>
                            </div>
                            <p class="text-muted mb-0">{{ $booking->provider->experience_years }} years experience</p>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('customer.providers.show', $booking->provider) }}" 
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye me-1"></i> View Profile
                            </a>
                        </div>
                    </div>

                    @if($booking->provider->bio)
                        <div class="mb-3">
                            <strong>About:</strong>
                            <p class="mt-1">{{ $booking->provider->bio }}</p>
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Contact:</strong>
                            <p class="mt-1">
                                <i class="fas fa-envelope me-2"></i> {{ $booking->provider->email }}<br>
                                @if($booking->provider->phone)
                                    <i class="fas fa-phone me-2"></i> {{ $booking->provider->phone }}
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($booking->provider->address)
                                <strong>Location:</strong>
                                <p class="mt-1">
                                    <i class="fas fa-map-marker-alt me-2"></i> {{ $booking->provider->address }}
                                </p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Service Rate:</span>
                        <span>Rs. {{ number_format($booking->hourly_rate, 0) }}/hr</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Duration:</span>
                        <span>{{ $booking->duration }} hour{{ $booking->duration > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format($booking->hourly_rate * $booking->duration, 0) }}</span>
                    </div>
                    @if($booking->service_fee > 0)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Service Fee:</span>
                            <span>Rs. {{ number_format($booking->service_fee, 0) }}</span>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total Amount:</strong>
                        <strong class="text-success">Rs. {{ number_format($booking->total_amount, 0) }}</strong>
                    </div>

                    <div class="mb-2">
                        <strong>Payment Method:</strong>
                        <p class="mt-1">{{ ucfirst($booking->payment_method) }}</p>
                    </div>

                    <div class="mb-2">
                        <strong>Payment Status:</strong>
                        <p class="mt-1">
                            <span class="badge bg-{{ $booking->payment_status === 'completed' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst($booking->payment_status) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($booking->status === 'completed' && !$booking->review)
                        <a href="{{ route('customer.reviews.create-from-booking', $booking) }}" 
                           class="btn btn-warning btn-block mb-2">
                            <i class="fas fa-star me-1"></i> Write Review
                        </a>
                    @endif

                    @if($booking->status === 'confirmed' && $booking->scheduled_date->diffInHours(now()) >= 24)
                        <form method="POST" action="{{ route('customer.bookings.cancel', $booking) }}" class="mb-2">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-danger btn-block"
                                    onclick="return confirm('Are you sure you want to cancel this booking?')">
                                <i class="fas fa-times me-1"></i> Cancel Booking
                            </button>
                        </form>
                    @endif

                    @if($booking->chat)
                        <a href="{{ route('customer.chat.show', $booking->chat) }}"
                           class="btn btn-success btn-block mb-2">
                            <i class="fas fa-comments me-1"></i> Chat with Provider
                        </a>
                    @else
                        <div class="alert alert-info mb-2">
                            <i class="fas fa-info-circle me-1"></i>
                            Chat will be available after payment confirmation
                        </div>
                    @endif

                    <a href="{{ route('customer.bookings.index') }}" 
                       class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                    </a>
                </div>
            </div>

            <!-- Booking Timeline -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Timeline</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Booking Created</h6>
                                <p class="timeline-text">{{ $booking->created_at->format('M d, Y \a\t h:i A') }}</p>
                            </div>
                        </div>

                        @if($booking->status !== 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Service Confirmed</h6>
                                    <p class="timeline-text">Provider accepted your request</p>
                                </div>
                            </div>
                        @endif

                        @if($booking->status === 'completed')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Service Completed</h6>
                                    <p class="timeline-text">{{ $booking->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>

                            @if($booking->review)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-warning"></div>
                                    <div class="timeline-content">
                                        <h6 class="timeline-title">Review Submitted</h6>
                                        <p class="timeline-text">{{ $booking->review->created_at->format('M d, Y \a\t h:i A') }}</p>
                                    </div>
                                </div>
                            @endif
                        @endif

                        @if($booking->status === 'cancelled')
                            <div class="timeline-item">
                                <div class="timeline-marker bg-danger"></div>
                                <div class="timeline-content">
                                    <h6 class="timeline-title">Booking Cancelled</h6>
                                    <p class="timeline-text">{{ $booking->updated_at->format('M d, Y \a\t h:i A') }}</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e3e6f0;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e3e6f0;
}

.timeline-content {
    padding-left: 15px;
}

.timeline-title {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 12px;
    color: #6c757d;
    margin-bottom: 0;
}

.btn-block {
    width: 100%;
}
</style>
@endsection
