@extends('layouts.provider')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('provider.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('provider.bookings.index') }}">My Bookings</a></li>
                <li class="breadcrumb-item active">Booking #{{ $booking->id }}</li>
            </ol>
        </nav>
    </div>

    <!-- Service Status Alert -->
    @if($booking->status === 'confirmed')
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-clock fa-2x text-warning me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Service Scheduled</h5>
                    <p class="mb-2">Your service is scheduled for <strong>{{ $booking->scheduled_date->format('M d, Y \a\t h:i A') }}</strong>. Make sure to arrive on time!</p>
                    @if($booking->canBeStarted())
                        <form method="POST" action="{{ route('provider.bookings.start', $booking) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="fas fa-play me-1"></i> Start Service
                            </button>
                        </form>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif($booking->status === 'in_progress')
        <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-tools fa-2x text-info me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Service In Progress</h5>
                    <p class="mb-2">You started this service on <strong>{{ $booking->started_at->format('M d, Y \a\t h:i A') }}</strong>. Complete it when finished.</p>
                    @if($booking->canBeCompleted())
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#completeServiceModal">
                            <i class="fas fa-check me-1"></i> Complete Service
                        </button>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @elseif($booking->status === 'completed')
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle fa-2x text-success me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-1">Service Completed! 🎉</h5>
                    <p class="mb-2">You completed this service on <strong>{{ $booking->completed_at->format('M d, Y \a\t h:i A') }}</strong>. Great job!</p>
                    @if($booking->review)
                        <span class="badge bg-warning">Customer left a {{ $booking->review->rating }}-star review</span>
                    @endif
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Customer Review Display -->
    @if($booking->review)
        <div class="alert alert-light border mb-4">
            <div class="d-flex align-items-start">
                <i class="fas fa-star fa-2x text-warning me-3"></i>
                <div class="flex-grow-1">
                    <h5 class="alert-heading mb-2">Customer Review</h5>
                    <div class="d-flex align-items-center mb-2">
                        <div class="text-warning me-2">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $booking->review->rating ? '' : '-o' }}"></i>
                            @endfor
                        </div>
                        <span class="badge bg-warning">{{ $booking->review->rating }}/5 Stars</span>
                    </div>
                    @if($booking->review->comment)
                        <p class="mb-2"><strong>Comment:</strong> "{{ $booking->review->comment }}"</p>
                    @endif
                    @if($booking->review->provider_response)
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>Your Response:</strong>
                            <p class="mb-0 mt-1">{{ $booking->review->provider_response }}</p>
                        </div>
                    @else
                        <a href="{{ route('provider.reviews.show', $booking->review) }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-reply me-1"></i> Respond to Review
                        </a>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Service Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Service Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary">{{ $booking->serviceRequest->title ?? 'Service Request' }}</h5>
                            <p class="text-muted">{{ $booking->serviceRequest->serviceCategory->name ?? 'General Service' }}</p>
                            
                            @if($booking->serviceRequest->description)
                                <div class="mb-3">
                                    <strong>Description:</strong>
                                    <p class="text-muted">{{ $booking->serviceRequest->description }}</p>
                                </div>
                            @endif

                            @if($booking->serviceRequest->address)
                                <div class="mb-3">
                                    <strong>Service Location:</strong>
                                    <p class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        {{ $booking->serviceRequest->address }}
                                    </p>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <strong>Scheduled Date & Time:</strong>
                                <p class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    {{ $booking->scheduled_date->format('l, F j, Y') }}
                                </p>
                                <p class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    {{ $booking->scheduled_date->format('h:i A') }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Duration:</strong>
                                <p class="text-muted">
                                    <i class="fas fa-hourglass-half me-1"></i>
                                    {{ $booking->duration_in_hours }} hours
                                </p>
                            </div>
                            <div class="mb-3">
                                <strong>Status:</strong>
                                <p>
                                    <span class="badge bg-{{ $booking->status === 'confirmed' ? 'warning' : ($booking->status === 'in_progress' ? 'info' : ($booking->status === 'completed' ? 'success' : 'secondary')) }}">
                                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">
                        <i class="fas fa-user me-2"></i>Customer Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="d-flex align-items-center mb-3">
                                @if($booking->customer->profile_image)
                                    <img src="{{ Storage::url($booking->customer->profile_image) }}" 
                                         alt="{{ $booking->customer->name }}" 
                                         class="rounded-circle me-3" 
                                         style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3" 
                                         style="width: 60px; height: 60px; font-size: 24px;">
                                        {{ substr($booking->customer->name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h5 class="mb-1">{{ $booking->customer->name }}</h5>
                                    <p class="text-muted mb-0">Customer</p>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Contact:</strong>
                                    <p class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>
                                        {{ $booking->customer->email }}
                                        @if($booking->customer->phone)
                                            <br><i class="fas fa-phone me-1"></i>
                                            {{ $booking->customer->phone }}
                                        @endif
                                    </p>
                                </div>
                                @if($booking->customer->address)
                                    <div class="col-md-6">
                                        <strong>Location:</strong>
                                        <p class="text-muted">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ $booking->customer->address }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-4">
                            @if($booking->chat)
                                <a href="{{ route('provider.chat.show', $booking->chat) }}" 
                                   class="btn btn-success btn-block mb-2">
                                    <i class="fas fa-comments me-1"></i> Chat with Customer
                                </a>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Chat will be available after payment confirmation
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Payment Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-credit-card me-2"></i>Payment Details
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Service Rate:</span>
                        <span>Rs. {{ number_format($booking->serviceRequest->provider->hourly_rate ?? 0, 0) }}/hr</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Duration:</span>
                        <span>{{ $booking->duration_in_hours }} hours</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal:</span>
                        <span>Rs. {{ number_format(($booking->serviceRequest->provider->hourly_rate ?? 0) * $booking->duration_in_hours, 0) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between mb-3">
                        <strong>Total Amount:</strong>
                        <strong class="text-success">Rs. {{ number_format($booking->total_amount, 0) }}</strong>
                    </div>
                    @if($booking->payment)
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Payment Method:</strong>
                            <span>{{ ucfirst($booking->payment->payment_method) }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <strong>Payment Status:</strong>
                            <span class="badge bg-{{ $booking->payment->status === 'paid' ? 'success' : ($booking->payment->status === 'pending' ? 'warning' : 'danger') }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->payment->status)) }}
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-bolt me-2"></i>Quick Actions
                    </h6>
                </div>
                <div class="card-body">
                    @if($booking->status === 'confirmed' && $booking->canBeStarted())
                        <form method="POST" action="{{ route('provider.bookings.start', $booking) }}" class="mb-2">
                            @csrf
                            <button type="submit" 
                                    class="btn btn-success btn-block"
                                    onclick="return confirm('Are you sure you want to start this service?')">
                                <i class="fas fa-play me-1"></i> Start Service
                            </button>
                        </form>
                    @endif

                    @if($booking->status === 'in_progress' && $booking->canBeCompleted())
                        <button type="button" 
                                class="btn btn-primary btn-block mb-2"
                                data-bs-toggle="modal" 
                                data-bs-target="#completeServiceModal">
                            <i class="fas fa-check me-1"></i> Complete Service
                        </button>
                    @endif

                    <a href="{{ route('provider.bookings.index') }}" 
                       class="btn btn-outline-secondary btn-block">
                        <i class="fas fa-arrow-left me-1"></i> Back to Bookings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Service Modal -->
@if($booking->status === 'in_progress' && $booking->canBeCompleted())
<div class="modal fade" id="completeServiceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('provider.bookings.complete', $booking) }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="completion_notes" class="form-label">Completion Notes (Optional)</label>
                        <textarea class="form-control" id="completion_notes" name="completion_notes" rows="4" 
                                  placeholder="Add any notes about the completed service..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i> Complete Service
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
@endsection

@section('styles')
<style>
.btn-block {
    width: 100%;
}
</style>
@endsection
