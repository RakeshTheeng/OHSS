@extends('layouts.admin')

@section('title', 'Provider Details - ' . $provider->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.providers.index') }}">Providers</a></li>
                    <li class="breadcrumb-item active">{{ $provider->name }}</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-cog me-2"></i>Provider Details
            </h1>
        </div>
        <div class="d-sm-flex gap-2">
            @if($provider->provider_status === 'awaiting')
                <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#approveModal">
                    <i class="fas fa-check me-1"></i>Approve
                </button>
                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#rejectModal">
                    <i class="fas fa-times me-1"></i>Reject
                </button>
            @elseif($provider->provider_status === 'approved')
                <form method="POST" action="{{ route('admin.providers.toggle-availability', $provider) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-{{ $provider->is_available ? 'warning' : 'success' }} btn-sm">
                        <i class="fas fa-{{ $provider->is_available ? 'pause' : 'play' }} me-1"></i>
                        {{ $provider->is_available ? 'Mark Unavailable' : 'Mark Available' }}
                    </button>
                </form>
            @endif
            <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Provider Information -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>Provider Information
                    </h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($provider->profile_image)
                            <img src="{{ Storage::url($provider->profile_image) }}" 
                                 class="rounded-circle mb-3" width="100" height="100" alt="Profile">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    
                    <h5 class="mb-1">{{ $provider->name }}</h5>
                    <p class="text-muted mb-3">{{ $provider->email }}</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="mb-0">{{ $provider->experience_years }}</h6>
                                <small class="text-muted">Years Exp.</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border-end">
                                <h6 class="mb-0">{{ number_format($provider->rating, 1) }}</h6>
                                <small class="text-muted">Rating</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <h6 class="mb-0">{{ $provider->total_reviews }}</h6>
                            <small class="text-muted">Reviews</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <span class="badge bg-{{ $provider->provider_status === 'approved' ? 'success' : ($provider->provider_status === 'awaiting' ? 'warning' : 'danger') }} fs-6">
                            {{ ucfirst($provider->provider_status) }}
                        </span>
                        @if($provider->provider_status === 'approved')
                            @php
                                $availabilityStatus = $provider->getAvailabilityStatus();
                            @endphp
                            <span class="badge {{ $availabilityStatus['badge_class'] }} fs-6 ms-2">
                                {{ $availabilityStatus['status'] }}
                            </span>
                            <div class="text-muted small mt-1">
                                {{ $availabilityStatus['message'] }}
                            </div>
                        @endif
                    </div>

                    @if($provider->provider_status === 'rejected' && $provider->rejection_reason)
                        <div class="alert alert-danger text-start">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Rejection Reason:</h6>
                            <p class="mb-0 small">{{ $provider->rejection_reason }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-address-card me-2"></i>Contact Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label small text-muted">Phone</label>
                        <p class="mb-0">{{ $provider->phone }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Address</label>
                        <p class="mb-0">{{ $provider->address }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small text-muted">Hourly Rate</label>
                        <p class="mb-0">Rs. {{ number_format($provider->hourly_rate, 2) }}</p>
                    </div>
                    <div class="mb-0">
                        <label class="form-label small text-muted">Member Since</label>
                        <p class="mb-0">{{ $provider->created_at->format('M d, Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Services Offered -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tools me-2"></i>Services Offered
                    </h6>
                </div>
                <div class="card-body">
                    @if($provider->serviceCategories->count() > 0)
                        <div class="row">
                            @foreach($provider->serviceCategories as $category)
                                <div class="col-md-6 mb-3">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $category->icon }} fa-2x text-primary me-3"></i>
                                        <div>
                                            <h6 class="mb-0">{{ $category->name }}</h6>
                                            <small class="text-muted">{{ $category->description }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">No services specified.</p>
                    @endif
                </div>
            </div>

            <!-- Bio/Description -->
            @if($provider->bio)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info-circle me-2"></i>About Provider
                        </h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0">{{ $provider->bio }}</p>
                    </div>
                </div>
            @endif

            <!-- KYC Documents -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-alt me-2"></i>KYC Documents
                    </h6>
                </div>
                <div class="card-body">
                    @if($provider->kycDocuments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Document Type</th>
                                        <th>Status</th>
                                        <th>Uploaded</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($provider->kycDocuments as $document)
                                        <tr>
                                            <td>{{ ucfirst($document->document_type) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $document->status === 'approved' ? 'success' : ($document->status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($document->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $document->created_at->format('M d, Y') }}</td>
                                            <td>
                                                @if($document->file_path)
                                                    <a href="{{ Storage::url($document->file_path) }}" 
                                                       target="_blank" class="btn btn-outline-primary btn-sm">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="fas fa-file-upload fa-2x text-muted mb-2"></i>
                            <p class="text-muted mb-0">No KYC documents uploaded yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-clipboard-list me-2"></i>Recent Service Requests
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($provider->serviceRequests->count() > 0)
                                @foreach($provider->serviceRequests->take(5) as $request)
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 small">{{ $request->serviceCategory->name ?? 'Service' }}</h6>
                                            <small class="text-muted">{{ $request->created_at->diffForHumans() }}</small>
                                        </div>
                                        <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0">No service requests yet.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-star me-2"></i>Recent Reviews
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($provider->reviews->count() > 0)
                                @foreach($provider->reviews->take(3) as $review)
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-0 small">{{ $review->customer->name }}</h6>
                                                <div class="text-warning small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                                                    @endfor
                                                </div>
                                            </div>
                                            <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                        </div>
                                        @if($review->comment)
                                            <p class="mb-0 small text-muted">{{ Str::limit($review->comment, 100) }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted small mb-0">No reviews yet.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="approveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Approve Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong>{{ $provider->name }}</strong> as a service provider?</p>
                <p class="text-success small">This will allow them to receive and accept service requests.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.providers.approve', $provider) }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">Approve Provider</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.providers.reject', $provider) }}">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting <strong>{{ $provider->name }}</strong>:</p>
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Explain why this provider application is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject Provider</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
