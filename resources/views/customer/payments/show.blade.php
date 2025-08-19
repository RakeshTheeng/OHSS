@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Payment Details</h2>
                <a href="{{ route('customer.payments.index') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Payments
                </a>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">Payment #{{ $payment->id }}</h5>
                            <small class="text-muted">{{ $payment->created_at->format('F d, Y \a\t h:i A') }}</small>
                        </div>
                        <div class="col-auto">
                            @switch($payment->status)
                                @case('awaiting_payment')
                                    <span class="badge bg-warning fs-6">Awaiting Payment</span>
                                    @break
                                @case('pending')
                                    <span class="badge bg-warning fs-6">Pending</span>
                                    @break
                                @case('processing')
                                    <span class="badge bg-info fs-6">Processing</span>
                                    @break
                                @case('completed')
                                    <span class="badge bg-success fs-6">Completed</span>
                                    @break
                                @case('failed')
                                    <span class="badge bg-danger fs-6">Failed</span>
                                    @break
                                @case('refunded')
                                    <span class="badge bg-secondary fs-6">Refunded</span>
                                    @break
                                @default
                                    <span class="badge bg-secondary fs-6">{{ ucfirst($payment->status) }}</span>
                            @endswitch
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Payment Information -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">Payment Information</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Amount</label>
                                    <div class="h5 text-success mb-0">Rs. {{ number_format($payment->amount, 2) }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Payment Method</label>
                                    <div>
                                        @if($payment->payment_method === 'esewa')
                                            <span class="badge bg-success">
                                                <i class="fas fa-mobile-alt me-1"></i>eSewa
                                            </span>
                                        @elseif($payment->payment_method === 'khalti')
                                            <span class="badge bg-primary">
                                                <i class="fas fa-wallet me-1"></i>Khalti
                                            </span>
                                        @else
                                            <span class="badge bg-warning">
                                                <i class="fas fa-money-bill me-1"></i>Cash on Hand
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($payment->transaction_id)
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Transaction ID</label>
                                        <div class="font-monospace">{{ $payment->transaction_id }}</div>
                                    </div>
                                @endif
                                @if($payment->esewa_ref_id)
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">eSewa Reference ID</label>
                                        <div class="font-monospace">{{ $payment->esewa_ref_id }}</div>
                                    </div>
                                @endif
                                @if($payment->paid_at)
                                    <div class="mb-0">
                                        <label class="form-label small text-muted">Paid At</label>
                                        <div>{{ $payment->paid_at->format('F d, Y \a\t h:i A') }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Service Information -->
                        <div class="col-md-6 mb-4">
                            <h6 class="text-muted mb-3">Service Information</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Service</label>
                                    <div class="fw-bold">{{ $payment->booking->serviceRequest->serviceCategory->name }}</div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Provider</label>
                                    <div class="d-flex align-items-center">
                                        @if($payment->provider->profile_image)
                                            <img src="{{ asset('storage/' . $payment->provider->profile_image) }}" 
                                                 alt="{{ $payment->provider->name }}" 
                                                 class="rounded-circle me-2" 
                                                 width="32" height="32">
                                        @else
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                 style="width: 32px; height: 32px;">
                                                <span class="text-white small fw-bold">
                                                    {{ substr($payment->provider->name, 0, 1) }}
                                                </span>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $payment->provider->name }}</div>
                                            <small class="text-muted">{{ $payment->provider->phone }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Duration</label>
                                    <div>{{ $payment->booking->duration }} hours</div>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label small text-muted">Scheduled Date</label>
                                    <div>{{ $payment->booking->scheduled_date->format('F d, Y \a\t h:i A') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row">
                        <div class="col-12">
                            <div class="d-flex gap-2">
                                @if(in_array($payment->status, ['awaiting_payment', 'pending']))
                                    @if($payment->payment_method === 'esewa')
                                        <a href="{{ route('customer.payments.esewa', $payment) }}" class="btn btn-success">
                                            <i class="fas fa-credit-card me-2"></i>Complete eSewa Payment
                                        </a>
                                    @elseif($payment->payment_method === 'khalti')
                                        <a href="{{ route('customer.payments.khalti', $payment) }}" class="btn btn-primary">
                                            <i class="fas fa-wallet me-2"></i>Complete Khalti Payment
                                        </a>
                                    @endif
                                @endif
                                <a href="{{ route('customer.bookings.show', $payment->booking) }}" class="btn btn-outline-primary">
                                    <i class="fas fa-eye me-2"></i>View Booking
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($payment->failure_reason)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-danger">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Payment Failed</h6>
                                    <p class="mb-0">{{ $payment->failure_reason }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
