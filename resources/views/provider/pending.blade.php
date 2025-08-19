<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Account Under Review - {{ config('app.name', 'Laravel') }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .status-icon {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 2rem;
        }
        .status-pending {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            color: white;
        }
        .status-rejected {
            background: linear-gradient(135deg, #dc3545, #c82333);
            color: white;
        }
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: #e9ecef;
        }
        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #28a745;
        }
        .timeline-item.pending::before {
            background: #ffc107;
        }
        .timeline-item.future::before {
            background: #e9ecef;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body p-5 text-center">
                        @if(auth()->user()->provider_status === 'awaiting')
                            <div class="status-icon status-pending">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h2 class="text-warning mb-3">Account Under Review</h2>
                            <p class="lead mb-4">
                                Thank you for registering as a service provider! Your account is currently being reviewed by our admin team.
                            </p>
                        @elseif(auth()->user()->provider_status === 'rejected')
                            <div class="status-icon status-rejected">
                                <i class="fas fa-times"></i>
                            </div>
                            <h2 class="text-danger mb-3">Application Rejected</h2>
                            <p class="lead mb-4">
                                Unfortunately, your provider application has been rejected.
                            </p>
                            @if(auth()->user()->rejection_reason)
                                <div class="alert alert-danger text-start">
                                    <h6><i class="fas fa-exclamation-triangle me-2"></i>Rejection Reason:</h6>
                                    <p class="mb-0">{{ auth()->user()->rejection_reason }}</p>
                                </div>
                            @endif
                        @endif

                        <div class="row mt-5">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-user-check text-primary me-2"></i>
                                            Review Process
                                        </h5>
                                        <div class="timeline">
                                            <div class="timeline-item">
                                                <h6>Application Submitted</h6>
                                                <small class="text-muted">{{ auth()->user()->created_at->format('M d, Y H:i') }}</small>
                                            </div>
                                            <div class="timeline-item {{ auth()->user()->provider_status === 'awaiting' ? 'pending' : (auth()->user()->provider_status === 'rejected' ? '' : 'future') }}">
                                                <h6>Admin Review</h6>
                                                <small class="text-muted">
                                                    @if(auth()->user()->provider_status === 'awaiting')
                                                        In Progress
                                                    @elseif(auth()->user()->provider_status === 'rejected')
                                                        Completed - Rejected
                                                    @else
                                                        Pending
                                                    @endif
                                                </small>
                                            </div>
                                            <div class="timeline-item future">
                                                <h6>Account Activation</h6>
                                                <small class="text-muted">Pending</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            <i class="fas fa-info-circle text-info me-2"></i>
                                            What's Next?
                                        </h5>
                                        @if(auth()->user()->provider_status === 'awaiting')
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-check text-success me-2"></i>
                                                    Wait for admin approval
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-envelope text-primary me-2"></i>
                                                    You'll receive an email notification
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-upload text-warning me-2"></i>
                                                    Prepare your KYC documents
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-star text-info me-2"></i>
                                                    Start receiving service requests
                                                </li>
                                            </ul>
                                        @elseif(auth()->user()->provider_status === 'rejected')
                                            <ul class="list-unstyled">
                                                <li class="mb-2">
                                                    <i class="fas fa-edit text-primary me-2"></i>
                                                    Review the rejection reason
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-redo text-warning me-2"></i>
                                                    Update your information
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-paper-plane text-success me-2"></i>
                                                    Resubmit your application
                                                </li>
                                                <li class="mb-2">
                                                    <i class="fas fa-phone text-info me-2"></i>
                                                    Contact support if needed
                                                </li>
                                            </ul>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            @if(auth()->user()->provider_status === 'awaiting')
                                <div class="alert alert-info">
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Estimated Review Time:</strong> 1-3 business days
                                </div>
                            @endif
                            
                            <div class="d-flex gap-3 justify-content-center">
                                <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-edit me-2"></i>Edit Profile
                                </a>
                                
                                @if(auth()->user()->provider_status === 'rejected')
                                    <a href="{{ route('provider.register.form') }}" class="btn btn-warning">
                                        <i class="fas fa-redo me-2"></i>Reapply
                                    </a>
                                @endif
                                
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary">
                                        <i class="fas fa-sign-out-alt me-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="mt-4 pt-4 border-top">
                            <p class="text-muted mb-0">
                                <i class="fas fa-question-circle me-2"></i>
                                Need help? Contact us at 
                                <a href="mailto:support@{{ config('app.name') }}.com">support@{{ strtolower(config('app.name')) }}.com</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
