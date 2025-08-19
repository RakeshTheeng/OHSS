@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Booking Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.bookings.index') }}">Bookings</a></li>
                <li class="breadcrumb-item active">Booking #{{ $booking->id }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Booking Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Booking Information</h6>
                    <div>
                        <span class="badge badge-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'cancelled' ? 'danger' : ($booking->status === 'in_progress' ? 'warning' : 'info')) }} badge-lg mr-2">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                        <span class="badge badge-{{ $booking->payment_status === 'paid' ? 'success' : ($booking->payment_status === 'failed' ? 'danger' : 'warning') }} badge-lg">
                            Payment: {{ ucfirst($booking->payment_status) }}
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">{{ $booking->serviceRequest->title }}</h5>
                            <span class="badge badge-info">{{ $booking->serviceRequest->serviceCategory->name }}</span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <div class="text-muted">
                                <small>Booking ID: #{{ $booking->id }}</small><br>
                                <small>Created: {{ $booking->created_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Scheduled Date & Time:</strong><br>
                            {{ $booking->scheduled_date->format('l, M d, Y \a\t h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Duration:</strong><br>
                            {{ $booking->duration }} minutes ({{ number_format($booking->duration / 60, 1) }} hours)
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Total Amount:</strong><br>
                            <span class="text-success font-weight-bold h5">Rs. {{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Payment Method:</strong><br>
                            {{ ucfirst($booking->payment_method) }}
                        </div>
                    </div>

                    @if($booking->special_instructions)
                        <div class="mt-3">
                            <strong>Special Instructions:</strong>
                            <p class="mt-2">{{ $booking->special_instructions }}</p>
                        </div>
                    @endif

                    @if($booking->started_at)
                        <div class="mt-3">
                            <strong>Service Started:</strong>
                            {{ $booking->started_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    @endif

                    @if($booking->completed_at)
                        <div class="mt-3">
                            <strong>Service Completed:</strong>
                            {{ $booking->completed_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    @endif

                    @if($booking->completion_notes)
                        <div class="mt-3">
                            <strong>Completion Notes:</strong>
                            <div class="alert alert-success mt-2">
                                {{ $booking->completion_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Service Request Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Request ID:</strong> #{{ $booking->serviceRequest->id }}<br>
                            <strong>Budget:</strong> Rs. {{ number_format($booking->serviceRequest->budget, 0) }}<br>
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $booking->serviceRequest->status === 'completed' ? 'success' : ($booking->serviceRequest->status === 'cancelled' ? 'danger' : 'info') }}">
                                {{ ucfirst($booking->serviceRequest->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Preferred Date:</strong> {{ $booking->serviceRequest->preferred_date ? $booking->serviceRequest->preferred_date->format('M d, Y') : 'Flexible' }}<br>
                            <strong>Preferred Time:</strong> {{ $booking->serviceRequest->preferred_time ?: 'Flexible' }}<br>
                            <strong>Is Urgent:</strong> 
                            @if($booking->serviceRequest->is_urgent)
                                <span class="badge badge-danger">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $booking->serviceRequest->description }}</p>
                    </div>

                    <div class="mt-3">
                        <strong>Location:</strong>
                        <p class="mt-2">{{ $booking->serviceRequest->location }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($booking->payment)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Payment ID:</strong> #{{ $booking->payment->id }}<br>
                                <strong>Amount:</strong> Rs. {{ number_format($booking->payment->amount, 2) }}<br>
                                <strong>Method:</strong> {{ ucfirst($booking->payment->payment_method) }}<br>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $booking->payment->status === 'completed' ? 'success' : ($booking->payment->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($booking->payment->status) }}
                                </span>
                            </div>
                            <div class="col-md-6">
                                @if($booking->payment->transaction_id)
                                    <strong>Transaction ID:</strong> {{ $booking->payment->transaction_id }}<br>
                                @endif
                                @if($booking->payment->gateway_response)
                                    <strong>Gateway Response:</strong> {{ $booking->payment->gateway_response }}<br>
                                @endif
                                <strong>Payment Date:</strong> {{ $booking->payment->created_at->format('M d, Y \a\t h:i A') }}<br>
                                @if($booking->payment->paid_at)
                                    <strong>Paid At:</strong> {{ $booking->payment->paid_at->format('M d, Y \a\t h:i A') }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Review Information -->
            @if($booking->review)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Customer Review</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Rating:</strong>
                                <div class="text-warning">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star{{ $i <= $booking->review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="text-muted">({{ $booking->review->rating }}/5)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <strong>Review Date:</strong> {{ $booking->review->created_at->format('M d, Y \a\t h:i A') }}<br>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $booking->review->is_approved ? 'success' : 'warning' }}">
                                    {{ $booking->review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                                @if($booking->review->is_flagged)
                                    <span class="badge badge-danger ml-1">Flagged</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($booking->review->comment)
                            <div class="mt-3">
                                <strong>Customer Comment:</strong>
                                <p class="mt-2">{{ $booking->review->comment }}</p>
                            </div>
                        @endif
                        
                        @if($booking->review->provider_response)
                            <div class="mt-3">
                                <strong>Provider Response:</strong>
                                <div class="alert alert-info mt-2">
                                    {{ $booking->review->provider_response }}
                                    <br><small class="text-muted">Responded on: {{ $booking->review->provider_responded_at->format('M d, Y \a\t h:i A') }}</small>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Customer Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($booking->customer->profile_image)
                        <img src="{{ Storage::url($booking->customer->profile_image) }}" 
                             alt="{{ $booking->customer->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $booking->customer->name }}</h5>
                    <p class="text-muted">{{ $booking->customer->email }}</p>
                    @if($booking->customer->phone)
                        <p><i class="fas fa-phone"></i> {{ $booking->customer->phone }}</p>
                    @endif
                    @if($booking->customer->address)
                        <p><i class="fas fa-map-marker-alt"></i> {{ $booking->customer->address }}</p>
                    @endif
                    <div class="mt-3">
                        <small class="text-muted">Customer since: {{ $booking->customer->created_at->format('M Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Provider Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($booking->provider->profile_image)
                        <img src="{{ Storage::url($booking->provider->profile_image) }}" 
                             alt="{{ $booking->provider->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $booking->provider->name }}</h5>
                    <p class="text-muted">{{ $booking->provider->email }}</p>
                    @if($booking->provider->phone)
                        <p><i class="fas fa-phone"></i> {{ $booking->provider->phone }}</p>
                    @endif
                    @if($booking->provider->hourly_rate)
                        <p><i class="fas fa-dollar-sign"></i> Rs. {{ number_format($booking->provider->hourly_rate, 0) }}/hr</p>
                    @endif
                    @if($booking->provider->experience_years)
                        <p><i class="fas fa-briefcase"></i> {{ $booking->provider->experience_years }} years experience</p>
                    @endif
                    @if($booking->provider->rating > 0)
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $booking->provider->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted">({{ $booking->provider->total_reviews }} reviews)</span>
                        </div>
                    @endif
                    <div class="mt-3">
                        <span class="badge badge-{{ $booking->provider->provider_status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($booking->provider->provider_status) }}
                        </span>
                        <span class="badge badge-{{ $booking->provider->is_available ? 'success' : 'secondary' }} ml-1">
                            {{ $booking->provider->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('admin.service-requests.show', $booking->serviceRequest) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-clipboard-list"></i> View Service Request
                        </a>
                        @if($booking->payment)
                            <a href="{{ route('admin.payments.show', $booking->payment) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-credit-card"></i> View Payment Details
                            </a>
                        @endif
                        @if($booking->review)
                            <a href="{{ route('admin.reviews.show', $booking->review) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-star"></i> View Review
                            </a>
                        @endif
                        @if($booking->chat)
                            <div class="btn btn-info btn-sm">
                                <i class="fas fa-comments"></i> Chat Available
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
