@extends('layouts.admin')

@section('title', 'Service Request Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Service Request Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.service-requests.index') }}">Service Requests</a></li>
                <li class="breadcrumb-item active">Request #{{ $serviceRequest->id }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Service Request Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Information</h6>
                    <span class="badge badge-{{ $serviceRequest->status === 'completed' ? 'success' : ($serviceRequest->status === 'cancelled' ? 'danger' : ($serviceRequest->status === 'accepted' ? 'info' : 'warning')) }} badge-lg">
                        {{ ucfirst(str_replace('_', ' ', $serviceRequest->status)) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">{{ $serviceRequest->title }}</h5>
                            @if($serviceRequest->is_urgent)
                                <span class="badge badge-danger mb-2">Urgent Request</span>
                            @endif
                        </div>
                        <div class="col-md-6 text-md-right">
                            <div class="text-muted">
                                <small>Request ID: #{{ $serviceRequest->id }}</small><br>
                                <small>Created: {{ $serviceRequest->created_at->format('M d, Y \a\t h:i A') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Service Category:</strong>
                            <span class="badge badge-info ml-1">{{ $serviceRequest->serviceCategory->name }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Budget:</strong>
                            <span class="text-success font-weight-bold">Rs. {{ number_format($serviceRequest->budget, 0) }}</span>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <strong>Preferred Date:</strong>
                            {{ $serviceRequest->preferred_date ? $serviceRequest->preferred_date->format('M d, Y') : 'Flexible' }}
                        </div>
                        <div class="col-md-6">
                            <strong>Preferred Time:</strong>
                            {{ $serviceRequest->preferred_time ?: 'Flexible' }}
                        </div>
                    </div>

                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $serviceRequest->description }}</p>
                    </div>

                    <div class="mt-3">
                        <strong>Location:</strong>
                        <p class="mt-2">{{ $serviceRequest->location }}</p>
                    </div>

                    @if($serviceRequest->provider_response)
                        <div class="mt-3">
                            <strong>Provider Response:</strong>
                            <div class="alert alert-info mt-2">
                                {{ $serviceRequest->provider_response }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Information -->
            @if($serviceRequest->booking)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Booking Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Booking ID:</strong> #{{ $serviceRequest->booking->id }}<br>
                                <strong>Scheduled Date:</strong> {{ $serviceRequest->booking->scheduled_date->format('M d, Y \a\t h:i A') }}<br>
                                <strong>Duration:</strong> {{ $serviceRequest->booking->duration }} minutes<br>
                                <strong>Total Amount:</strong> <span class="text-success font-weight-bold">Rs. {{ number_format($serviceRequest->booking->total_amount, 2) }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Payment Method:</strong> {{ ucfirst($serviceRequest->booking->payment_method) }}<br>
                                <strong>Payment Status:</strong> 
                                <span class="badge badge-{{ $serviceRequest->booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                    {{ ucfirst($serviceRequest->booking->payment_status) }}
                                </span><br>
                                <strong>Booking Status:</strong> 
                                <span class="badge badge-{{ $serviceRequest->booking->status === 'completed' ? 'success' : ($serviceRequest->booking->status === 'cancelled' ? 'danger' : 'info') }}">
                                    {{ ucfirst($serviceRequest->booking->status) }}
                                </span>
                            </div>
                        </div>

                        @if($serviceRequest->booking->special_instructions)
                            <div class="mt-3">
                                <strong>Special Instructions:</strong>
                                <p class="mt-2">{{ $serviceRequest->booking->special_instructions }}</p>
                            </div>
                        @endif

                        @if($serviceRequest->booking->completion_notes)
                            <div class="mt-3">
                                <strong>Completion Notes:</strong>
                                <div class="alert alert-success mt-2">
                                    {{ $serviceRequest->booking->completion_notes }}
                                </div>
                            </div>
                        @endif

                        <!-- Payment Information -->
                        @if($serviceRequest->booking->payment)
                            <hr>
                            <h6 class="font-weight-bold text-primary">Payment Details</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Payment ID:</strong> #{{ $serviceRequest->booking->payment->id }}<br>
                                    <strong>Amount:</strong> Rs. {{ number_format($serviceRequest->booking->payment->amount, 2) }}<br>
                                    <strong>Method:</strong> {{ ucfirst($serviceRequest->booking->payment->payment_method) }}
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong> 
                                    <span class="badge badge-{{ $serviceRequest->booking->payment->status === 'completed' ? 'success' : ($serviceRequest->booking->payment->status === 'failed' ? 'danger' : 'warning') }}">
                                        {{ ucfirst($serviceRequest->booking->payment->status) }}
                                    </span><br>
                                    @if($serviceRequest->booking->payment->transaction_id)
                                        <strong>Transaction ID:</strong> {{ $serviceRequest->booking->payment->transaction_id }}<br>
                                    @endif
                                    <strong>Payment Date:</strong> {{ $serviceRequest->booking->payment->created_at->format('M d, Y \a\t h:i A') }}
                                </div>
                            </div>
                        @endif

                        <!-- Review Information -->
                        @if($serviceRequest->booking->review)
                            <hr>
                            <h6 class="font-weight-bold text-primary">Customer Review</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Rating:</strong>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star{{ $i <= $serviceRequest->booking->review->rating ? '' : '-o' }}"></i>
                                        @endfor
                                        <span class="text-muted">({{ $serviceRequest->booking->review->rating }}/5)</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <strong>Review Date:</strong> {{ $serviceRequest->booking->review->created_at->format('M d, Y') }}
                                </div>
                            </div>
                            @if($serviceRequest->booking->review->comment)
                                <div class="mt-2">
                                    <strong>Comment:</strong>
                                    <p class="mt-2">{{ $serviceRequest->booking->review->comment }}</p>
                                </div>
                            @endif
                            @if($serviceRequest->booking->review->provider_response)
                                <div class="mt-2">
                                    <strong>Provider Response:</strong>
                                    <div class="alert alert-info mt-2">
                                        {{ $serviceRequest->booking->review->provider_response }}
                                    </div>
                                </div>
                            @endif
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
                    @if($serviceRequest->customer->profile_image)
                        <img src="{{ Storage::url($serviceRequest->customer->profile_image) }}" 
                             alt="{{ $serviceRequest->customer->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $serviceRequest->customer->name }}</h5>
                    <p class="text-muted">{{ $serviceRequest->customer->email }}</p>
                    @if($serviceRequest->customer->phone)
                        <p><i class="fas fa-phone"></i> {{ $serviceRequest->customer->phone }}</p>
                    @endif
                    @if($serviceRequest->customer->address)
                        <p><i class="fas fa-map-marker-alt"></i> {{ $serviceRequest->customer->address }}</p>
                    @endif
                </div>
            </div>

            <!-- Provider Information -->
            @if($serviceRequest->provider)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                    </div>
                    <div class="card-body text-center">
                        @if($serviceRequest->provider->profile_image)
                            <img src="{{ Storage::url($serviceRequest->provider->profile_image) }}" 
                                 alt="{{ $serviceRequest->provider->name }}" 
                                 class="rounded-circle mb-3" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user text-white fa-3x"></i>
                            </div>
                        @endif
                        <h5>{{ $serviceRequest->provider->name }}</h5>
                        <p class="text-muted">{{ $serviceRequest->provider->email }}</p>
                        @if($serviceRequest->provider->phone)
                            <p><i class="fas fa-phone"></i> {{ $serviceRequest->provider->phone }}</p>
                        @endif
                        @if($serviceRequest->provider->hourly_rate)
                            <p><i class="fas fa-dollar-sign"></i> Rs. {{ number_format($serviceRequest->provider->hourly_rate, 0) }}/hr</p>
                        @endif
                        @if($serviceRequest->provider->rating > 0)
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $serviceRequest->provider->rating ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted">({{ $serviceRequest->provider->total_reviews }} reviews)</span>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.service-requests.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        @if($serviceRequest->booking)
                            <a href="{{ route('admin.bookings.show', $serviceRequest->booking) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-calendar-check"></i> View Booking
                            </a>
                        @endif
                        @if($serviceRequest->booking && $serviceRequest->booking->payment)
                            <a href="{{ route('admin.payments.show', $serviceRequest->booking->payment) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-credit-card"></i> View Payment
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
