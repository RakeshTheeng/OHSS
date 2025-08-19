@extends('layouts.app')

@section('title', 'Khalti Payment')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-mobile-alt me-2"></i>
                        Khalti Payment
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
                                    <strong class="h6 text-primary">Rs. {{ number_format($payment->amount, 2) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Payment Information</h6>
                            <div class="bg-light p-3 rounded">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Payment Method:</span>
                                    <strong class="text-primary">Khalti</strong>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Transaction ID:</span>
                                    <strong>{{ $khaltiConfig['product_identity'] }}</strong>
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
                        <p class="mb-0">You must complete the Khalti payment and receive a payment receipt to confirm your booking. Only after successful payment will your service provider be notified.</p>
                    </div>

                    <!-- Khalti Merchant Account Details -->
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h6 class="mb-0"><i class="fas fa-store me-2"></i>Khalti Merchant Account Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Merchant Name</label>
                                        <div class="fw-bold">OHSS - Online Household Services System</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Product Name</label>
                                        <div class="font-monospace">{{ $khaltiConfig['product_name'] }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Payment Gateway</label>
                                        <div class="fw-bold text-primary">Khalti Digital Wallet</div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small text-muted">Security</label>
                                        <div><i class="fas fa-shield-alt text-success me-1"></i>256-bit SSL Encrypted</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Khalti Payment Instructions -->
                    <div class="alert alert-info">
                        <h6><i class="fas fa-info-circle me-2"></i>Payment Process</h6>
                        <ol class="mb-0">
                            <li>Click "Pay with Khalti" button below</li>
                            <li>Khalti payment widget will open</li>
                            <li>Login with your Khalti ID and password</li>
                            <li>Review and confirm the payment details</li>
                            <li>Complete payment using your Khalti balance or linked bank</li>
                            <li>You will receive a payment confirmation</li>
                            <li>Your booking will be automatically confirmed after payment</li>
                        </ol>
                    </div>

                    <!-- Payment Button -->
                    <div class="text-center">
                        <button id="payment-button" class="btn btn-primary btn-lg px-5">
                            <i class="fas fa-credit-card me-2"></i>
                            Pay with Khalti
                        </button>
                        
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
                                    <span>Your payment is secured by Khalti's 256-bit SSL encryption</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Loading Modal -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <h6>Processing Payment...</h6>
                <p class="text-muted mb-0">Please wait while we verify your payment with Khalti.</p>
            </div>
        </div>
    </div>
</div>

<!-- Khalti Checkout Script -->
<script src="https://khalti.com/static/khalti-checkout.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Debug configuration
    console.log('Khalti Configuration:', {
        publicKey: "{{ $khaltiConfig['public_key'] }}",
        amount: {{ $khaltiConfig['amount'] }},
        productIdentity: "{{ $khaltiConfig['product_identity'] }}",
        productName: "{{ $khaltiConfig['product_name'] }}",
        productUrl: "{{ $khaltiConfig['product_url'] }}"
    });

    var config = {
        publicKey: "{{ $khaltiConfig['public_key'] }}",
        productIdentity: "{{ $khaltiConfig['product_identity'] }}",
        productName: "{{ $khaltiConfig['product_name'] }}",
        productUrl: "{{ $khaltiConfig['product_url'] }}",
        eventHandler: {
            onSuccess: function(payload) {
                console.log('Khalti Payment Success:', payload);
                
                // Show loading modal
                var loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
                loadingModal.show();
                
                // Verify payment with server
                fetch("{{ route('customer.payments.khalti.verify') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        token: payload.token,
                        amount: payload.amount,
                        payment_id: {{ $khaltiConfig['payment_id'] }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    loadingModal.hide();
                    
                    if (data.success) {
                        // Redirect to success page
                        window.location.href = data.redirect_url;
                    } else {
                        alert("Payment verification failed: " + data.message);
                        console.error('Verification failed:', data);
                    }
                })
                .catch(error => {
                    loadingModal.hide();
                    console.error('Error:', error);
                    alert("An error occurred while verifying payment. Please contact support.");
                });
            },
            onError: function(error) {
                console.error('Khalti Payment Error:', error);

                // More detailed error handling
                let errorMessage = "Payment failed: ";
                if (error && error.message) {
                    errorMessage += error.message;
                } else if (error && error.detail) {
                    errorMessage += error.detail;
                } else if (typeof error === 'string') {
                    errorMessage += error;
                } else {
                    errorMessage += "Unknown error occurred. Please check your internet connection and try again.";
                }

                alert(errorMessage);
            },
            onClose: function() {
                console.log("Khalti widget closed");
            }
        }
    };

    // Check if KhaltiCheckout is available
    if (typeof KhaltiCheckout === 'undefined') {
        console.error('KhaltiCheckout is not loaded. Please check your internet connection.');
        alert('Khalti payment system is not available. Please check your internet connection and refresh the page.');
        return;
    }

    var checkout = new KhaltiCheckout(config);
    console.log('Khalti checkout initialized successfully');
    
    document.getElementById("payment-button").onclick = function() {
        // Amount in paisa (Rs. 1 = 100 paisa)
        var amount = {{ $khaltiConfig['amount'] }};

        // Validate amount
        if (!amount || amount < 1000) { // Minimum Rs. 10 (1000 paisa)
            alert("Invalid payment amount. Minimum amount is Rs. 10.");
            return;
        }

        console.log('Initiating Khalti payment with amount:', amount);
        checkout.show({amount: amount});
    };
});
</script>
@endsection
