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
                <li class="breadcrumb-item"><a href="{{ route('customer.bookings.index') }}">My Bookings</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.bookings.show', $booking) }}">Booking #{{ $booking->id }}</a></li>
                <li class="breadcrumb-item active">Write Review</li>
            </ol>
        </nav>
    </div>

    <!-- Success Message -->
    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
        <div class="d-flex align-items-center">
            <i class="fas fa-check-circle fa-2x text-success me-3"></i>
            <div>
                <h5 class="alert-heading mb-1">Service Completed Successfully! 🎉</h5>
                <p class="mb-0">Thank you for using our platform. Please take a moment to share your experience with <strong>{{ $booking->provider->name }}</strong>.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <div class="row">
        <!-- Service Details -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Service Details</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        <i class="{{ $booking->serviceRequest->serviceCategory->icon }} fa-3x text-primary mb-2"></i>
                        <h5>{{ $booking->serviceRequest->title }}</h5>
                        <p class="text-muted">{{ $booking->serviceRequest->serviceCategory->name }}</p>
                    </div>

                    <div class="mb-2">
                        <strong>Service Date:</strong>
                        <div class="text-muted">{{ $booking->scheduled_date->format('M d, Y h:i A') }}</div>
                    </div>

                    <div class="mb-2">
                        <strong>Duration:</strong>
                        <div class="text-muted">{{ $booking->duration }} hours</div>
                    </div>

                    <div class="mb-2">
                        <strong>Total Amount:</strong>
                        <div class="text-success fw-bold">Rs. {{ number_format($booking->total_amount, 0) }}</div>
                    </div>

                    <div class="mb-2">
                        <strong>Status:</strong>
                        <div>
                            <span class="badge bg-success">Completed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Provider Details -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Provider Details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        @if($booking->provider->profile_image)
                            <img src="{{ Storage::url($booking->provider->profile_image) }}"
                                 alt="{{ $booking->provider->name }}"
                                 class="rounded-circle me-3"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-white fa-lg"></i>
                            </div>
                        @endif
                        <div>
                            <h5 class="mb-1">{{ $booking->provider->name }}</h5>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= ($booking->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted ms-1">({{ number_format($booking->provider->rating ?? 0, 1) }})</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-2">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        <span>Rs. {{ number_format($booking->provider->hourly_rate, 0) }}/hour</span>
                    </div>

                    <div class="mb-2">
                        <i class="fas fa-calendar text-primary me-2"></i>
                        <span>{{ $booking->provider->experience_years ?? 0 }} years experience</span>
                    </div>

                    @if($booking->provider->phone)
                        <div class="mb-2">
                            <i class="fas fa-phone text-success me-2"></i>
                            <span>{{ $booking->provider->phone }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Review Form -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Share Your Experience</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.reviews.store-from-booking', $booking) }}">
                        @csrf

                        <!-- Rating Section -->
                        <div class="mb-4">
                            <label class="form-label h5">How would you rate this service? <span class="text-danger">*</span></label>
                            <div class="rating-container text-center py-4 bg-light rounded">
                                <div class="star-rating mb-3" data-rating="0">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star" data-rating="{{ $i }}"></i>
                                    @endfor
                                </div>
                                <div class="rating-text">
                                    <h6 id="ratingText" class="text-muted mb-1">Click on stars to rate</h6>
                                    <p id="ratingDescription" class="small text-muted mb-0"></p>
                                </div>
                                <input type="hidden" name="rating" id="ratingInput" value="{{ old('rating') }}" required>
                                @error('rating')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Comment Section -->
                        <div class="mb-4">
                            <label for="comment" class="form-label h6">Tell us about your experience</label>
                            <textarea class="form-control @error('comment') is-invalid @enderror" 
                                      id="comment" 
                                      name="comment" 
                                      rows="5" 
                                      placeholder="Share details about the service quality, punctuality, professionalism, and overall experience...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Your review helps other customers make informed decisions and helps providers improve their services.</div>
                        </div>

                        <!-- Review Guidelines -->
                        <div class="alert alert-info">
                            <h6><i class="fas fa-info-circle me-2"></i>Review Guidelines</h6>
                            <ul class="mb-0 small">
                                <li>Be honest and fair in your review</li>
                                <li>Focus on the service quality and experience</li>
                                <li>Avoid personal attacks or inappropriate language</li>
                                <li>You can edit or delete your review within 24 hours</li>
                            </ul>
                        </div>

                        <!-- Rating Breakdown Helper -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="card-title">Rating Guide</h6>
                                <div class="row small">
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <span class="text-warning">★★★★★</span> Excellent - Exceeded expectations
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-warning">★★★★☆</span> Good - Met expectations
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-warning">★★★☆☆</span> Average - Acceptable service
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-1">
                                            <span class="text-warning">★★☆☆☆</span> Below Average - Some issues
                                        </div>
                                        <div class="mb-1">
                                            <span class="text-warning">★☆☆☆☆</span> Poor - Did not meet expectations
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.bookings.show', $booking) }}" 
                               class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Booking
                            </a>
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-star"></i> Submit Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const stars = document.querySelectorAll('.star');
    const ratingInput = document.getElementById('ratingInput');
    const ratingText = document.getElementById('ratingText');
    const submitBtn = document.getElementById('submitBtn');
    
    const ratingTexts = {
        1: 'Poor - Did not meet expectations',
        2: 'Below Average - Some issues',
        3: 'Average - Acceptable service',
        4: 'Good - Met expectations',
        5: 'Excellent - Exceeded expectations'
    };

    const ratingDescriptions = {
        1: 'The service had significant issues and did not meet basic expectations.',
        2: 'The service had some problems but was partially acceptable.',
        3: 'The service was okay and met basic requirements.',
        4: 'The service was good and met most expectations well.',
        5: 'The service was outstanding and exceeded all expectations!'
    };

    // Set initial rating if exists
    const initialRating = {{ old('rating', 0) }};
    if (initialRating > 0) {
        setRating(initialRating);
    }

    stars.forEach(star => {
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            setRating(rating);
        });

        star.addEventListener('mouseover', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            highlightStars(rating);
        });
    });

    document.querySelector('.star-rating').addEventListener('mouseleave', function() {
        const currentRating = parseInt(ratingInput.value) || 0;
        highlightStars(currentRating);
    });

    function setRating(rating) {
        ratingInput.value = rating;
        ratingText.textContent = ratingTexts[rating] || 'Click on stars to rate';
        document.getElementById('ratingDescription').textContent = ratingDescriptions[rating] || '';
        highlightStars(rating);
        submitBtn.disabled = rating === 0;

        // Add animation
        ratingText.style.transform = 'scale(1.1)';
        setTimeout(() => {
            ratingText.style.transform = 'scale(1)';
        }, 200);
    }

    function highlightStars(rating) {
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.remove('far');
                star.classList.add('fas');
                star.style.color = '#f6c23e';
            } else {
                star.classList.remove('fas');
                star.classList.add('far');
                star.style.color = '#d1d3e2';
            }
        });
    }
});
</script>

<style>
.star-rating {
    font-size: 2.5rem;
    cursor: pointer;
}

.star {
    color: #d1d3e2;
    transition: all 0.3s ease;
    margin: 0 5px;
}

.star:hover {
    color: #f6c23e !important;
    transform: scale(1.2);
}

.star:active {
    transform: scale(0.9);
}

.text-warning .fas.fa-star {
    color: #f6c23e;
}

.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

.rating-container {
    background: linear-gradient(135deg, #f8f9fc 0%, #e3e6f0 100%);
    border-radius: 15px;
    border: 2px solid #e3e6f0;
    transition: all 0.3s ease;
}

.rating-container:hover {
    border-color: #f6c23e;
    box-shadow: 0 0 20px rgba(246, 194, 62, 0.2);
}

#ratingText {
    transition: all 0.3s ease;
}

#ratingDescription {
    opacity: 0.8;
    font-style: italic;
}

.btn-primary:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.alert-success {
    border-left: 5px solid #1cc88a;
}

.card {
    transition: transform 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
}
</style>
@endsection
