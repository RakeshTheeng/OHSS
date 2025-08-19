@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container py-5">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-clipboard-list me-2"></i>Service Request Details
                    </h1>
                    <p class="text-muted mb-0">Request #{{ $serviceRequest->id }}</p>
                </div>
                <div>
                    <a href="{{ route('customer.service-requests.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>Back to Requests
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Request Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                    <span class="badge bg-{{ 
                        $serviceRequest->status === 'pending' ? 'warning' : 
                        ($serviceRequest->status === 'accepted' ? 'success' : 
                        ($serviceRequest->status === 'completed' ? 'primary' : 'danger')) 
                    }} fs-6">
                        {{ ucfirst($serviceRequest->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ $serviceRequest->title }}</h5>
                            <div class="mb-2">
                                <strong>Category:</strong>
                                <span class="badge bg-primary ms-2">
                                    @if($serviceRequest->serviceCategory)
                                        <i class="{{ $serviceRequest->serviceCategory->icon }} me-1"></i>
                                        {{ $serviceRequest->serviceCategory->name }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            <div class="mb-2">
                                <strong>Priority:</strong>
                                <span class="badge bg-{{ 
                                    $serviceRequest->urgency === 'high' ? 'danger' : 
                                    ($serviceRequest->urgency === 'medium' ? 'warning' : 'info') 
                                }} ms-2">
                                    {{ ucfirst($serviceRequest->urgency) }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-2">
                                <strong>Preferred Date:</strong>
                                <div class="text-muted">{{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('l, F d, Y') }}</div>
                            </div>
                            <div class="mb-2">
                                <strong>Preferred Time:</strong>
                                <div class="text-muted">{{ ucfirst($serviceRequest->preferred_time) }}</div>
                            </div>
                            <div class="mb-2">
                                <strong>Created:</strong>
                                <div class="text-muted">{{ $serviceRequest->created_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <p class="mt-2 text-muted">{{ $serviceRequest->description }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Service Address:</strong>
                        <p class="mt-2 text-muted">{{ $serviceRequest->address }}</p>
                    </div>

                    @if($serviceRequest->required_hours || $serviceRequest->total_budget)
                        <div class="mb-4">
                            <strong>Service Details:</strong>
                            <div class="row mt-2">
                                @if($serviceRequest->required_hours)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Required Hours</div>
                                        <div class="text-primary fw-bold">{{ $serviceRequest->required_hours }} hours</div>
                                    </div>
                                @endif

                                @if($serviceRequest->hourly_rate)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Hourly Rate</div>
                                        <div class="text-info fw-bold">Rs. {{ number_format($serviceRequest->hourly_rate) }}/hr</div>
                                    </div>
                                @endif

                                @if($serviceRequest->total_budget)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Total Budget</div>
                                        <div class="text-success fw-bold">Rs. {{ number_format($serviceRequest->total_budget) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- Keep old budget fields for backward compatibility --}}
                    @if(($serviceRequest->budget_min || $serviceRequest->budget_max) && !$serviceRequest->total_budget)
                        <div class="mb-4">
                            <strong>Budget Range:</strong>
                            <div class="text-success mt-2">
                                Rs. {{ number_format($serviceRequest->budget_min ?? 0) }}
                                @if($serviceRequest->budget_max && $serviceRequest->budget_max != $serviceRequest->budget_min)
                                    - Rs. {{ number_format($serviceRequest->budget_max) }}
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($serviceRequest->additional_notes)
                        <div class="mb-4">
                            <strong>Additional Notes:</strong>
                            <p class="mt-2 text-muted">{{ $serviceRequest->additional_notes }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Provider Information -->
            @if($serviceRequest->provider)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Assigned Provider</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            @if($serviceRequest->provider->profile_image)
                                <img src="{{ Storage::url($serviceRequest->provider->profile_image) }}" 
                                     alt="{{ $serviceRequest->provider->name }}" 
                                     class="rounded-circle me-3" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-user text-white"></i>
                                </div>
                            @endif
                            <div class="flex-grow-1">
                                <h6 class="mb-1">{{ $serviceRequest->provider->name }}</h6>
                                <div class="text-warning mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= ($serviceRequest->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="text-muted small ms-1">({{ $serviceRequest->provider->rating ?? 0 }})</span>
                                </div>
                                <div class="text-success">Rs. {{ number_format($serviceRequest->provider->hourly_rate ?? 0, 0) }}/hr</div>
                            </div>
                            <div>
                                <a href="{{ route('providers.profile', $serviceRequest->provider) }}" 
                                   class="btn btn-outline-primary btn-sm" target="_blank">
                                    <i class="fas fa-external-link-alt me-1"></i>View Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Booking -->
            @if($serviceRequest->booking)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">Booking Information</h6>
                    </div>
                    <div class="card-body">
                        @php $booking = $serviceRequest->booking @endphp
                            <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                                <div>
                                    <div class="fw-bold">Booking #{{ $booking->id }}</div>
                                    <div class="text-muted small">
                                        {{ $booking->scheduled_date ? $booking->scheduled_date->format('M d, Y \a\t g:i A') : 'Not scheduled' }}
                                    </div>
                                </div>
                                <div class="text-end">
                                    <span class="badge bg-{{ 
                                        $booking->status === 'confirmed' ? 'success' : 
                                        ($booking->status === 'completed' ? 'primary' : 
                                        ($booking->status === 'cancelled' ? 'danger' : 'warning')) 
                                    }}">
                                        {{ ucfirst($booking->status) }}
                                    </span>
                                    <div class="text-success small">Rs. {{ number_format($booking->total_amount ?? 0, 2) }}</div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-info btn-sm">
                                    <i class="fas fa-eye me-2"></i>View Booking Details
                                </a>
                            </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4">
            <!-- Actions -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->status === 'pending')
                        <a href="{{ route('customer.service-requests.edit', $serviceRequest) }}" 
                           class="btn btn-warning w-100 mb-2">
                            <i class="fas fa-edit me-2"></i>Edit Request
                        </a>
                        
                        <form method="POST" action="{{ route('customer.service-requests.cancel', $serviceRequest) }}">
                            @csrf
                            <button type="submit" class="btn btn-danger w-100 mb-2"
                                    onclick="return confirm('Are you sure you want to cancel this request?')">
                                <i class="fas fa-times me-2"></i>Cancel Request
                            </button>
                        </form>
                    @endif

                    @if($serviceRequest->status === 'accepted')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Your request has been accepted! The provider will contact you soon.
                        </div>
                    @endif

                    @if($serviceRequest->status === 'completed')
                        <div class="alert alert-primary">
                            <i class="fas fa-star me-2"></i>
                            Service completed! Don't forget to leave a review.
                        </div>
                    @endif

                    <a href="{{ route('customer.service-requests.create') }}" 
                       class="btn btn-outline-primary w-100">
                        <i class="fas fa-plus me-2"></i>Create New Request
                    </a>
                </div>
            </div>

            <!-- Request Summary -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Request Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary fw-bold h5">{{ $serviceRequest->status === 'completed' ? '✓' : '⏳' }}</div>
                            <div class="text-muted small">Status</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-success fw-bold h5">{{ $serviceRequest->urgency === 'high' ? '🔥' : ($serviceRequest->urgency === 'medium' ? '⚡' : '📅') }}</div>
                            <div class="text-muted small">Priority</div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <div class="text-muted small">Request ID</div>
                        <div class="fw-bold">#{{ $serviceRequest->id }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
