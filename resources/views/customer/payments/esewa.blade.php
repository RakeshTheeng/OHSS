@extends('layouts.app')

@section('title', 'eSewa Payment')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>
                        eSewa Payment
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Payment Summary -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Details</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Service:</span>
                                    <strong>{{ $payment->booking->serviceRequest->serviceCategory->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Provider:</span>
                                    <strong>{{ $payment->provider->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Duration:</span>
                                    <strong>{{ $payment->booking->duration }} hours</strong>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <span class="h6">Total Amount:</span>
                                    <strong class="h6 text-success">Rs. {{ number_format($payment->amount, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Information</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Method:</span>
                                    <strong class="text-success">eSewa</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transaction ID:</span>
                                    <strong>{{ $esewaConfig['transaction_uuid'] }}</strong>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Status:</span>
                                    <span class="badge bg-warning">Pending</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Important Notice -->
                    <div class="alert alert-warning">
                        <h6><i class="fas fa-exclamation-triangle me-2"></i>Important Notice</h6>
                        <p class="mb-2"><strong>Your booking is NOT confirmed yet!</strong></p>
                        <p class="mb-0">You must complete the eSewa payment and receive a payment receipt to confirm your booking. Only after successful payment will your service provider be notified.</p>
                    </div>

                    <!-- eSewa Merchant Account Details -->
                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <h6 class="mb-0"><i class="fas fa-store me-2"></i>eSewa Merchant Account Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Merchant Name</label>
                                        <div class="fw-bold">OHSS - Online Household Services System</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Merchant Code</label>
                                        <div class="font-monospace">{{ config('services.esewa.product_code', 'EPAYTEST') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Payment Gateway</label>
                                        <div class="fw-bold text-success">eSewa ePay</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Security</label>
                                        <div><i class="fas fa-shield-alt text-success me-1"></i>256-bit SSL Encrypted</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- eSewa Payment Instructions -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Payment Process</h6>
                        <ol class="mb-0">
                            <li>Click "Pay with eSewa" button below</li>
                            <li>You will be redirected to eSewa's secure payment page</li>
                            <li>Login with your eSewa ID and password</li>
                            <li>Review and confirm the payment details</li>
                            <li>Complete payment using your eSewa balance or linked bank</li>
                            <li>You will receive a payment receipt upon successful payment</li>
                            <li>Your booking will be automatically confirmed after payment</li>
                        </ol>
                    </div>

                    <!-- Payment Form -->
                    <div class="text-center">
                        <form action="{{ config('services.esewa.payment_url', 'https://rc-epay.esewa.com.np/api/epay/main/v2/form') }}" method="POST" id="esewaForm">
                            <input type="hidden" name="amount" value="{{ $esewaConfig['amount'] }}">
                            <input type="hidden" name="tax_amount" value="{{ $esewaConfig['tax_amount'] }}">
                            <input type="hidden" name="total_amount" value="{{ $esewaConfig['total_amount'] }}">
                            <input type="hidden" name="transaction_uuid" value="{{ $esewaConfig['transaction_uuid'] }}">
                            <input type="hidden" name="product_code" value="{{ $esewaConfig['product_code'] }}">
                            <input type="hidden" name="product_service_charge" value="{{ $esewaConfig['product_service_charge'] }}">
                            <input type="hidden" name="product_delivery_charge" value="{{ $esewaConfig['product_delivery_charge'] }}">
                            <input type="hidden" name="success_url" value="{{ $esewaConfig['success_url'] }}">
                            <input type="hidden" name="failure_url" value="{{ $esewaConfig['failure_url'] }}">
                            <input type="hidden" name="signed_field_names" value="{{ $esewaConfig['signed_field_names'] }}">
                            <input type="hidden" name="signature" value="{{ $esewaConfig['signature'] }}">
                            
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="fas fa-credit-card me-2"></i>
                                Pay with eSewa
                            </button>
                        </form>
                        
                        <div class="mt-3">
                            <a href="{{ route('customer.bookings.show', $payment->booking) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>
                                Cancel Payment
                            </a>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-4">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="fas fa-shield-alt me-2 text-success"></i>
                                    <span>Your payment is secured by eSewa's 256-bit SSL encryption</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Add confirmation before form submission
document.getElementById('esewaForm').addEventListener('submit', function(e) {
    if (!confirm('You will be redirected to eSewa for payment. Continue?')) {
        e.preventDefault();
    }
});
</script>
@endsection
