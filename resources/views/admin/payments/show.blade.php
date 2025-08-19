@extends('layouts.admin')

@section('title', 'Payment Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Payment Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.payments.index') }}">Payments</a></li>
                <li class="breadcrumb-item active">Payment #{{ $payment->id }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Payment Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Payment Information</h6>
                    <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'secondary' : 'warning')) }} badge-lg">
                        {{ ucfirst($payment->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Payment #{{ $payment->id }}</h5>
                            <div class="mt-2">
                                @if($payment->payment_method === 'esewa')
                                    <span class="badge badge-info badge-lg">
                                        <i class="fas fa-mobile-alt"></i> eSewa Payment
                                    </span>
                                @else
                                    <span class="badge badge-secondary badge-lg">
                                        <i class="fas fa-money-bill-wave"></i> Cash Payment
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <div class="text-muted">
                                <small>Created: {{ $payment->created_at->format('M d, Y \a\t h:i A') }}</small><br>
                                @if($payment->paid_at)
                                    <small>Paid: {{ $payment->paid_at->format('M d, Y \a\t h:i A') }}</small>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <strong>Amount:</strong><br>
                            <span class="text-success font-weight-bold h4">Rs. {{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Transaction ID:</strong><br>
                            @if($payment->transaction_id)
                                <code>{{ $payment->transaction_id }}</code>
                            @elseif($payment->esewa_ref_id)
                                <code>{{ $payment->esewa_ref_id }}</code>
                            @else
                                <span class="text-muted">Not available</span>
                            @endif
                        </div>
                    </div>

                    @if($payment->esewa_ref_id && $payment->payment_method === 'esewa')
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <strong>eSewa Reference ID:</strong><br>
                                <code>{{ $payment->esewa_ref_id }}</code>
                            </div>
                            <div class="col-md-6">
                                <strong>Gateway Response:</strong><br>
                                @if($payment->gateway_response)
                                    <button class="btn btn-sm btn-outline-info" data-toggle="collapse" data-target="#gatewayResponse">
                                        View Response
                                    </button>
                                @else
                                    <span class="text-muted">Not available</span>
                                @endif
                            </div>
                        </div>

                        @if($payment->gateway_response)
                            <div class="collapse mt-3" id="gatewayResponse">
                                <div class="card card-body bg-light">
                                    <pre><code>{{ json_encode($payment->gateway_response, JSON_PRETTY_PRINT) }}</code></pre>
                                </div>
                            </div>
                        @endif
                    @endif

                    @if($payment->failure_reason)
                        <div class="mt-3">
                            <strong>Failure Reason:</strong>
                            <div class="alert alert-danger mt-2">
                                {{ $payment->failure_reason }}
                            </div>
                        </div>
                    @endif

                    @if($payment->status === 'refunded')
                        <div class="mt-3">
                            <div class="alert alert-warning">
                                <h6><i class="fas fa-undo"></i> Refund Information</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Refund Amount:</strong> Rs. {{ number_format($payment->refund_amount, 2) }}<br>
                                        <strong>Refunded At:</strong> {{ $payment->refunded_at->format('M d, Y \a\t h:i A') }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Refund Reason:</strong><br>
                                        {{ $payment->refund_reason }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Booking Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Related Booking Information</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Booking ID:</strong> #{{ $payment->booking->id }}<br>
                            <strong>Service:</strong> {{ $payment->booking->serviceRequest->title }}<br>
                            <strong>Category:</strong> 
                            <span class="badge badge-info">{{ $payment->booking->serviceRequest->serviceCategory->name }}</span><br>
                            <strong>Scheduled Date:</strong> {{ $payment->booking->scheduled_date->format('M d, Y \a\t h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Duration:</strong> {{ $payment->booking->duration }} minutes<br>
                            <strong>Booking Status:</strong> 
                            <span class="badge badge-{{ $payment->booking->status === 'completed' ? 'success' : ($payment->booking->status === 'cancelled' ? 'danger' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $payment->booking->status)) }}
                            </span><br>
                            <strong>Payment Status:</strong> 
                            <span class="badge badge-{{ $payment->booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($payment->booking->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($payment->booking->special_instructions)
                        <div class="mt-3">
                            <strong>Special Instructions:</strong>
                            <p class="mt-2">{{ $payment->booking->special_instructions }}</p>
                        </div>
                    @endif

                    @if($payment->booking->completion_notes)
                        <div class="mt-3">
                            <strong>Completion Notes:</strong>
                            <div class="alert alert-success mt-2">
                                {{ $payment->booking->completion_notes }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Service Request Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-secondary">Service Request Details</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <strong>Request ID:</strong> #{{ $payment->booking->serviceRequest->id }}<br>
                            <strong>Budget:</strong> Rs. {{ number_format($payment->booking->serviceRequest->budget, 0) }}<br>
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $payment->booking->serviceRequest->status === 'completed' ? 'success' : ($payment->booking->serviceRequest->status === 'cancelled' ? 'danger' : 'info') }}">
                                {{ ucfirst($payment->booking->serviceRequest->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Location:</strong> {{ $payment->booking->serviceRequest->location }}<br>
                            <strong>Is Urgent:</strong> 
                            @if($payment->booking->serviceRequest->is_urgent)
                                <span class="badge badge-danger">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $payment->booking->serviceRequest->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Review Information -->
            @if($payment->booking->review)
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
                                        <i class="fas fa-star{{ $i <= $payment->booking->review->rating ? '' : '-o' }}"></i>
                                    @endfor
                                    <span class="text-muted">({{ $payment->booking->review->rating }}/5)</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <strong>Review Date:</strong> {{ $payment->booking->review->created_at->format('M d, Y') }}<br>
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $payment->booking->review->is_approved ? 'success' : 'warning' }}">
                                    {{ $payment->booking->review->is_approved ? 'Approved' : 'Pending' }}
                                </span>
                            </div>
                        </div>
                        
                        @if($payment->booking->review->comment)
                            <div class="mt-3">
                                <strong>Customer Comment:</strong>
                                <p class="mt-2">{{ $payment->booking->review->comment }}</p>
                            </div>
                        @endif
                        
                        @if($payment->booking->review->provider_response)
                            <div class="mt-3">
                                <strong>Provider Response:</strong>
                                <div class="alert alert-info mt-2">
                                    {{ $payment->booking->review->provider_response }}
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
                    @if($payment->customer->profile_image)
                        <img src="{{ Storage::url($payment->customer->profile_image) }}" 
                             alt="{{ $payment->customer->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $payment->customer->name }}</h5>
                    <p class="text-muted">{{ $payment->customer->email }}</p>
                    @if($payment->customer->phone)
                        <p><i class="fas fa-phone"></i> {{ $payment->customer->phone }}</p>
                    @endif
                    @if($payment->customer->address)
                        <p><i class="fas fa-map-marker-alt"></i> {{ $payment->customer->address }}</p>
                    @endif
                    <div class="mt-3">
                        <small class="text-muted">Customer since: {{ $payment->customer->created_at->format('M Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Provider Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($payment->provider->profile_image)
                        <img src="{{ Storage::url($payment->provider->profile_image) }}" 
                             alt="{{ $payment->provider->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $payment->provider->name }}</h5>
                    <p class="text-muted">{{ $payment->provider->email }}</p>
                    @if($payment->provider->phone)
                        <p><i class="fas fa-phone"></i> {{ $payment->provider->phone }}</p>
                    @endif
                    @if($payment->provider->hourly_rate)
                        <p><i class="fas fa-dollar-sign"></i> Rs. {{ number_format($payment->provider->hourly_rate, 0) }}/hr</p>
                    @endif
                    @if($payment->provider->rating > 0)
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $payment->provider->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted">({{ $payment->provider->total_reviews }} reviews)</span>
                        </div>
                    @endif
                    <div class="mt-3">
                        <span class="badge badge-{{ $payment->provider->provider_status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($payment->provider->provider_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Payment Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Payment Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Payment Method:</strong><br>
                        @if($payment->payment_method === 'esewa')
                            <span class="badge badge-info">eSewa</span>
                        @else
                            <span class="badge badge-secondary">Cash</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Amount:</strong><br>
                        <span class="text-success font-weight-bold">Rs. {{ number_format($payment->amount, 2) }}</span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge badge-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : ($payment->status === 'refunded' ? 'secondary' : 'warning')) }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </div>
                    
                    @if($payment->refund_amount > 0)
                        <div class="mb-3">
                            <strong>Refund Amount:</strong><br>
                            <span class="text-danger font-weight-bold">Rs. {{ number_format($payment->refund_amount, 2) }}</span>
                        </div>
                    @endif
                    
                    <div class="mb-3">
                        <strong>Created:</strong><br>
                        {{ $payment->created_at->format('M d, Y \a\t h:i A') }}
                    </div>
                    
                    @if($payment->paid_at)
                        <div class="mb-3">
                            <strong>Paid At:</strong><br>
                            {{ $payment->paid_at->format('M d, Y \a\t h:i A') }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('admin.bookings.show', $payment->booking) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar-check"></i> View Booking
                        </a>
                        <a href="{{ route('admin.service-requests.show', $payment->booking->serviceRequest) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-clipboard-list"></i> View Service Request
                        </a>
                        @if($payment->booking->review)
                            <a href="{{ route('admin.reviews.show', $payment->booking->review) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-star"></i> View Review
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
