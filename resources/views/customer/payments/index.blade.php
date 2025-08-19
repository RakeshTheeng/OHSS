@extends('layouts.app')

@section('title', 'Payment History')

@section('content')
<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Payment History</h2>
                <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>
            </div>

            @if($payments->count() > 0)
                <div class="card shadow-sm">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Service</th>
                                        <th>Provider</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>
                                                <div class="fw-bold">{{ $payment->created_at->format('M d, Y') }}</div>
                                                <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $payment->booking->serviceRequest->serviceCategory->name }}</div>
                                                <small class="text-muted">{{ $payment->booking->duration }} hours</small>
                                            </td>
                                            <td>
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
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-success">Rs. {{ number_format($payment->amount, 2) }}</span>
                                            </td>
                                            <td>
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
                                                        <i class="fas fa-money-bill me-1"></i>Cash
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @switch($payment->status)
                                                    @case('awaiting_payment')
                                                        <span class="badge bg-warning">Awaiting Payment</span>
                                                        @break
                                                    @case('pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                        @break
                                                    @case('processing')
                                                        <span class="badge bg-info">Processing</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="badge bg-success">Completed</span>
                                                        @break
                                                    @case('failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                        @break
                                                    @case('refunded')
                                                        <span class="badge bg-secondary">Refunded</span>
                                                        @break
                                                    @default
                                                        <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                                @endswitch
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('customer.payments.show', $payment) }}"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if(in_array($payment->status, ['awaiting_payment', 'pending']))
                                                        @if($payment->payment_method === 'esewa')
                                                            <a href="{{ route('customer.payments.esewa', $payment) }}"
                                                               class="btn btn-sm btn-success"
                                                               title="Complete eSewa Payment">
                                                                <i class="fas fa-credit-card"></i>
                                                            </a>
                                                        @elseif($payment->payment_method === 'khalti')
                                                            <a href="{{ route('customer.payments.khalti', $payment) }}"
                                                               class="btn btn-sm btn-primary"
                                                               title="Complete Khalti Payment">
                                                                <i class="fas fa-wallet"></i>
                                                            </a>
                                                        @endif
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $payments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="fas fa-receipt fa-4x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No Payment History</h4>
                    <p class="text-muted">You haven't made any payments yet.</p>
                    <a href="{{ route('customer.service-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Request a Service
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
