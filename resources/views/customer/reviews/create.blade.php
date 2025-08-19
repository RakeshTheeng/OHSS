@extends('layouts.customer')

@section('title', 'Write Review')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Write Review</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.reviews.index') }}">My Reviews</a></li>
                <li class="breadcrumb-item active">Write Review</li>
            </ol>
        </nav>
    </div>

    @if($bookings->count() > 0)
        <!-- Instructions -->
        <div class="alert alert-info mb-4">
            <div class="d-flex align-items-center">
                <i class="fas fa-info-circle fa-2x me-3"></i>
                <div>
                    <h5 class="alert-heading mb-1">Select a Service to Review</h5>
                    <p class="mb-0">Choose from your completed services below to write a review. Your feedback helps other customers and improves service quality.</p>
                </div>
            </div>
        </div>

        <!-- Available Services to Review -->
        <div class="row">
            @foreach($bookings as $booking)
                <div class="col-lg-6 col-xl-4 mb-4">
                    <div class="card shadow h-100 review-card">
                        <div class="card-body">
                            <!-- Service Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="service-icon me-3">
                                    @if($booking->serviceRequest->serviceCategory)
                                        <i class="{{ $booking->serviceRequest->serviceCategory->icon ?? 'fas fa-tools' }} fa-2x text-primary"></i>
                                    @else
                                        <i class="fas fa-tools fa-2x text-primary"></i>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <h5 class="card-title mb-1">{{ $booking->serviceRequest->title }}</h5>
                                    <p class="text-muted small mb-0">
                                        {{ $booking->serviceRequest->serviceCategory->name ?? 'General Service' }}
                                    </p>
                                </div>
                            </div>

                            <!-- Provider Info -->
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($booking->provider->profile_image)
                                        <img src="{{ Storage::url($booking->provider->profile_image) }}"
                                             alt="{{ $booking->provider->name }}"
                                             class="rounded-circle"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold">{{ $booking->provider->name }}</div>
                                    <div class="text-warning small">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= ($booking->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                        @endfor
                                        <span class="text-muted ms-1">({{ $booking->provider->total_reviews }} reviews)</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Details -->
                            <div class="mb-3">
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="small text-muted">Service Date</div>
                                        <div class="fw-bold">{{ $booking->scheduled_date->format('M d, Y') }}</div>
                                    </div>
                                    <div class="col-6">
                                        <div class="small text-muted">Amount Paid</div>
                                        <div class="fw-bold text-success">Rs. {{ number_format($booking->total_amount, 0) }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Status Badge -->
                            <div class="text-center mb-3">
                                <span class="badge bg-success">
                                    <i class="fas fa-check-circle me-1"></i>Service Completed
                                </span>
                            </div>

                            <!-- Review Button -->
                            <div class="text-center">
                                <a href="{{ route('customer.reviews.create-from-booking', $booking) }}" 
                                   class="btn btn-primary btn-lg w-100 review-btn">
                                    <i class="fas fa-star me-2"></i>Write Review
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination if needed -->
        @if($bookings->count() >= 12)
            <div class="d-flex justify-content-center mt-4">
                <p class="text-muted">Showing {{ $bookings->count() }} completed services available for review</p>
            </div>
        @endif

    @else
        <!-- No Services to Review -->
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-star fa-4x text-muted"></i>
            </div>
            <h3 class="text-muted mb-3">No Services to Review</h3>
            <p class="text-muted mb-4">You don't have any completed services that haven't been reviewed yet.</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('customer.service-requests.index') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Request a Service
                </a>
                <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-calendar me-2"></i>View My Bookings
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.review-card {
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.review-card:hover {
    transform: translateY(-5px);
    border-color: #4e73df;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.service-icon {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(78, 115, 223, 0.1);
    border-radius: 10px;
}

.review-btn {
    transition: all 0.3s ease;
}

.review-btn:hover {
    transform: scale(1.05);
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}
</style>
@endsection
