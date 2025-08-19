@extends('layouts.customer')

@section('title', 'Book Service')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Book Service</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.service-requests.index') }}">My Requests</a></li>
                <li class="breadcrumb-item active">Book Service</li>
            </ol>
        </nav>
    </div>

    @if($serviceRequest)
        <div class="row">
            <!-- Service Request Details -->
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <i class="{{ $serviceRequest->serviceCategory->icon }} fa-3x text-primary mb-2"></i>
                            <h5>{{ $serviceRequest->title }}</h5>
                            <p class="text-muted">{{ $serviceRequest->serviceCategory->name }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p class="text-muted">{{ $serviceRequest->description }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Preferred Date:</strong>
                            <p class="text-muted">{{ $serviceRequest->preferred_date->format('M d, Y') }} at {{ $serviceRequest->preferred_time }}</p>
                        </div>

                        <div class="mb-3">
                            <strong>Address:</strong>
                            <p class="text-muted">{{ $serviceRequest->address }}</p>
                        </div>

                        @if($serviceRequest->additional_notes)
                            <div class="mb-3">
                                <strong>Additional Notes:</strong>
                                <p class="text-muted">{{ $serviceRequest->additional_notes }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Provider Details -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Provider Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            @if($serviceRequest->provider->profile_image)
                                <img src="{{ Storage::url($serviceRequest->provider->profile_image) }}"
                                     alt="{{ $serviceRequest->provider->name }}"
                                     class="rounded-circle me-3"
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white fa-lg"></i>
                                </div>
                            @endif
                            <div>
                                <h5 class="mb-1">{{ $serviceRequest->provider->name }}</h5>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($serviceRequest->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="text-muted ms-1">({{ number_format($serviceRequest->provider->rating ?? 0, 1) }})</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-2">
                            <i class="fas fa-money-bill-wave text-success me-2"></i>
                            <strong>Rs. {{ number_format($serviceRequest->provider->hourly_rate, 0) }}/hour</strong>
                        </div>

                        <div class="mb-2">
                            <i class="fas fa-calendar text-primary me-2"></i>
                            <span>{{ $serviceRequest->provider->experience_years ?? 0 }} years experience</span>
                        </div>

                        @if($serviceRequest->provider->phone)
                            <div class="mb-2">
                                <i class="fas fa-phone text-success me-2"></i>
                                <span>{{ $serviceRequest->provider->phone }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Book Your Service</h6>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('customer.bookings.store') }}" id="bookingForm">
                            @csrf
                            <input type="hidden" name="service_request_id" value="{{ $serviceRequest->id }}">

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="scheduled_date" class="form-label">Service Date <span class="text-danger">*</span></label>
                                    <input type="datetime-local" 
                                           class="form-control @error('scheduled_date') is-invalid @enderror" 
                                           id="scheduled_date" 
                                           name="scheduled_date" 
                                           value="{{ old('scheduled_date') }}"
                                           min="{{ now()->addHours(2)->format('Y-m-d\TH:i') }}"
                                           required>
                                    @error('scheduled_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Service must be scheduled at least 2 hours in advance.</small>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="duration" class="form-label">Duration (hours) <span class="text-danger">*</span></label>
                                    <select class="form-select @error('duration') is-invalid @enderror" 
                                            id="duration" 
                                            name="duration" 
                                            required>
                                        <option value="">Select Duration</option>
                                        @for($i = 1; $i <= 12; $i++)
                                            <option value="{{ $i }}" {{ old('duration') == $i ? 'selected' : '' }}>
                                                {{ $i }} hour{{ $i > 1 ? 's' : '' }}
                                            </option>
                                        @endfor
                                    </select>
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Cost Calculation -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">Cost Calculation</h6>
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="text-muted">Hourly Rate:</div>
                                                    <div class="fw-bold">Rs. {{ number_format($serviceRequest->provider->hourly_rate, 0) }}</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-muted">Duration:</div>
                                                    <div class="fw-bold" id="selectedDuration">0 hours</div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="text-muted">Total Amount:</div>
                                                    <div class="fw-bold text-success h5" id="totalAmount">Rs. 0</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Method -->
                            <div class="mb-4">
                                <label class="form-label h6">Choose Payment Method <span class="text-danger">*</span></label>
                                <div class="payment-methods">
                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <div class="payment-card">
                                                <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                                       type="radio"
                                                       name="payment_method"
                                                       id="esewa"
                                                       value="esewa"
                                                       {{ old('payment_method') == 'esewa' ? 'checked' : '' }}>
                                                <label class="payment-label" for="esewa">
                                                    <div class="payment-content">
                                                        <div class="payment-icon">
                                                            <i class="fas fa-mobile-alt text-success fa-2x"></i>
                                                        </div>
                                                        <div class="payment-info">
                                                            <h6 class="mb-1">eSewa</h6>
                                                            <small class="text-muted">Digital wallet payment</small>
                                                        </div>
                                                        <div class="payment-badge">
                                                            <span class="badge bg-success">Popular</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-card">
                                                <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                                       type="radio"
                                                       name="payment_method"
                                                       id="khalti"
                                                       value="khalti"
                                                       {{ old('payment_method') == 'khalti' ? 'checked' : '' }}>
                                                <label class="payment-label" for="khalti">
                                                    <div class="payment-content">
                                                        <div class="payment-icon">
                                                            <i class="fas fa-wallet text-primary fa-2x"></i>
                                                        </div>
                                                        <div class="payment-info">
                                                            <h6 class="mb-1">Khalti</h6>
                                                            <small class="text-muted">Digital wallet payment</small>
                                                        </div>
                                                        <div class="payment-badge">
                                                            <span class="badge bg-primary">Secure</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="payment-card">
                                                <input class="form-check-input @error('payment_method') is-invalid @enderror"
                                                       type="radio"
                                                       name="payment_method"
                                                       id="cash"
                                                       value="cash"
                                                       {{ old('payment_method') == 'cash' ? 'checked' : '' }}>
                                                <label class="payment-label" for="cash">
                                                    <div class="payment-content">
                                                        <div class="payment-icon">
                                                            <i class="fas fa-money-bill text-success fa-2x"></i>
                                                        </div>
                                                        <div class="payment-info">
                                                            <h6 class="mb-1">Cash on Hand</h6>
                                                            <small class="text-muted">Pay when service is completed</small>
                                                        </div>
                                                        <div class="payment-badge">
                                                            <span class="badge bg-warning">No Advance</span>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('payment_method')
                                    <div class="text-danger small mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Special Instructions -->
                            <div class="mb-4">
                                <label for="special_instructions" class="form-label">Special Instructions</label>
                                <textarea class="form-control @error('special_instructions') is-invalid @enderror" 
                                          id="special_instructions" 
                                          name="special_instructions" 
                                          rows="3" 
                                          placeholder="Any special instructions for the provider...">{{ old('special_instructions') }}</textarea>
                                @error('special_instructions')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Terms and Conditions -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="terms" required>
                                    <label class="form-check-label" for="terms">
                                        I agree to the <a href="#" target="_blank">Terms and Conditions</a> and 
                                        <a href="#" target="_blank">Cancellation Policy</a>
                                    </label>
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="d-flex justify-content-between">
                                <a href="{{ route('customer.service-requests.show', $serviceRequest) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left"></i> Back to Request
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-calendar-check"></i> Confirm Booking
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                <h4 class="text-muted">No Service Request Found</h4>
                <p class="text-muted">Please select a service request to book.</p>
                <a href="{{ route('customer.service-requests.index') }}" class="btn btn-primary">
                    <i class="fas fa-arrow-left"></i> Back to Requests
                </a>
            </div>
        </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const durationSelect = document.getElementById('duration');
    const selectedDurationDiv = document.getElementById('selectedDuration');
    const totalAmountDiv = document.getElementById('totalAmount');
    const hourlyRate = {{ $serviceRequest ? $serviceRequest->provider->hourly_rate : 0 }};

    durationSelect.addEventListener('change', function() {
        const duration = parseInt(this.value) || 0;
        const total = duration * hourlyRate;
        
        selectedDurationDiv.textContent = duration + ' hour' + (duration !== 1 ? 's' : '');
        totalAmountDiv.textContent = 'Rs. ' + total.toLocaleString();
    });
});
</script>

<style>
.text-purple {
    color: #6f42c1;
}
.text-warning .fas.fa-star {
    color: #f6c23e;
}
.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

/* Payment Method Styles */
.payment-methods {
    background: #f8f9fc;
    padding: 20px;
    border-radius: 10px;
    border: 2px solid #e3e6f0;
}

.payment-card {
    position: relative;
    height: 100%;
}

.payment-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 100%;
    margin: 0;
    cursor: pointer;
    z-index: 2;
}

.payment-label {
    display: block;
    background: white;
    border: 2px solid #e3e6f0;
    border-radius: 10px;
    padding: 15px;
    cursor: pointer;
    transition: all 0.3s ease;
    height: 100%;
    position: relative;
}

.payment-label:hover {
    border-color: #4e73df;
    box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    transform: translateY(-2px);
}

.payment-card input[type="radio"]:checked + .payment-label {
    border-color: #4e73df;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    box-shadow: 0 4px 15px rgba(78, 115, 223, 0.4);
}

.payment-card input[type="radio"]:checked + .payment-label .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

.payment-card input[type="radio"]:checked + .payment-label .payment-icon i {
    color: white !important;
}

.payment-content {
    display: flex;
    align-items: center;
    gap: 15px;
    position: relative;
}

.payment-icon {
    flex-shrink: 0;
}

.payment-info {
    flex-grow: 1;
}

.payment-badge {
    position: absolute;
    top: -5px;
    right: -5px;
}

.payment-badge .badge {
    font-size: 10px;
    padding: 4px 8px;
}

/* Notification Banner Styles */
.notification-banner {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    color: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
}

.notification-banner .alert-heading {
    color: white;
}

.notification-icon {
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.accepted-request-card {
    border-left: 4px solid #1cc88a;
    transition: transform 0.2s ease;
}

.accepted-request-card:hover {
    transform: translateX(5px);
}

/* Enhanced Provider Card Styles */
.provider-avatar {
    transition: transform 0.3s ease;
}

.provider-avatar:hover {
    transform: scale(1.1);
}

.stat-card {
    transition: all 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.bg-primary-light {
    background-color: rgba(78, 115, 223, 0.1);
}

.bg-success-light {
    background-color: rgba(28, 200, 138, 0.1);
}

.bg-info-light {
    background-color: rgba(54, 185, 204, 0.1);
}

.service-badge {
    transition: all 0.2s ease;
}

.service-badge:hover {
    transform: scale(1.05);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.description-box {
    border-left: 3px solid #4e73df;
}

.btn-hover {
    transition: all 0.3s ease;
}

.btn-hover:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endsection
