@extends('layouts.admin')

@section('title', 'Pending Provider Approvals')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.providers.index') }}">Providers</a></li>
                    <li class="breadcrumb-item active">Pending Approvals</li>
                </ol>
            </nav>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clock me-2"></i>Pending Provider Approvals
            </h1>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to All Providers
            </a>
        </div>
    </div>

    @if($providers->count() > 0)
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            <strong>{{ $providers->total() }}</strong> provider{{ $providers->total() > 1 ? 's' : '' }} awaiting approval.
            Review their information and approve or reject their applications.
        </div>

        <!-- Pending Providers List -->
        @foreach($providers as $provider)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user me-2"></i>{{ $provider->name }}
                        <span class="badge bg-warning ms-2">Pending</span>
                    </h6>
                    <small class="text-muted">Applied {{ $provider->created_at->diffForHumans() }}</small>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Provider Info -->
                        <div class="col-md-3">
                            <div class="text-center mb-3">
                                @if($provider->profile_image)
                                    <img src="{{ Storage::url($provider->profile_image) }}" 
                                         class="rounded-circle mb-2" width="80" height="80" alt="Profile">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2" 
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-user fa-2x text-white"></i>
                                    </div>
                                @endif
                                <h6 class="mb-1">{{ $provider->name }}</h6>
                                <small class="text-muted">{{ $provider->experience_years }} years experience</small>
                            </div>
                            
                            <div class="mb-2">
                                <strong class="small">Contact:</strong>
                                <div class="small text-muted">{{ $provider->email }}</div>
                                <div class="small text-muted">{{ $provider->phone }}</div>
                            </div>
                            
                            <div class="mb-2">
                                <strong class="small">Hourly Rate:</strong>
                                <div class="small">Rs. {{ number_format($provider->hourly_rate, 2) }}</div>
                            </div>
                        </div>

                        <!-- Services & Details -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <strong class="small">Services Offered:</strong>
                                <div class="mt-1">
                                    @foreach($provider->serviceCategories as $category)
                                        <span class="badge bg-info me-1 mb-1">
                                            <i class="{{ $category->icon }} me-1"></i>{{ $category->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mb-3">
                                <strong class="small">Address:</strong>
                                <p class="small text-muted mb-0">{{ $provider->address }}</p>
                            </div>

                            @if($provider->bio)
                                <div class="mb-3">
                                    <strong class="small">Bio:</strong>
                                    <p class="small text-muted mb-0">{{ Str::limit($provider->bio, 200) }}</p>
                                </div>
                            @endif

                            <!-- KYC Documents Status -->
                            <div class="mb-3">
                                <strong class="small">KYC Documents:</strong>
                                <div class="mt-1">
                                    @if($provider->kycDocuments->count() > 0)
                                        @foreach($provider->kycDocuments as $document)
                                            <div class="d-flex justify-content-between align-items-center small mb-1">
                                                <span>{{ ucfirst($document->document_type) }}</span>
                                                <div>
                                                    <span class="badge bg-{{ $document->status === 'approved' ? 'success' : ($document->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($document->status) }}
                                                    </span>
                                                    @if($document->file_path)
                                                        <a href="{{ Storage::url($document->file_path) }}" 
                                                           target="_blank" class="btn btn-outline-primary btn-sm ms-1">
                                                            <i class="fas fa-eye"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <small class="text-muted">No documents uploaded</small>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="col-md-3">
                            <div class="d-grid gap-2">
                                <a href="{{ route('admin.providers.show', $provider) }}" 
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View Full Details
                                </a>
                                
                                <button type="button" class="btn btn-success btn-sm" 
                                        onclick="approveProvider({{ $provider->id }}, '{{ $provider->name }}')">
                                    <i class="fas fa-check me-1"></i>Approve
                                </button>
                                
                                <button type="button" class="btn btn-danger btn-sm" 
                                        onclick="rejectProvider({{ $provider->id }}, '{{ $provider->name }}')">
                                    <i class="fas fa-times me-1"></i>Reject
                                </button>
                            </div>

                            <!-- Quick Stats -->
                            <div class="mt-3 pt-3 border-top">
                                <div class="row text-center">
                                    <div class="col-12 mb-2">
                                        <small class="text-muted d-block">Application Date</small>
                                        <strong class="small">{{ $provider->created_at->format('M d, Y') }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">
                    Showing {{ $providers->firstItem() }} to {{ $providers->lastItem() }} of {{ $providers->total() }} results
                </small>
            </div>
            <div>
                {{ $providers->links() }}
            </div>
        </div>
    @else
        <!-- No Pending Providers -->
        <div class="card shadow">
            <div class="card-body text-center py-5">
                <i class="fas fa-check-circle fa-4x text-success mb-4"></i>
                <h4 class="text-success">All Caught Up!</h4>
                <p class="text-muted mb-4">There are no pending provider approvals at this time.</p>
                <a href="{{ route('admin.providers.index') }}" class="btn btn-primary">
                    <i class="fas fa-users me-2"></i>View All Providers
                </a>
            </div>
        </div>
    @endif
</div>

<!-- Quick Approve Modal -->
<div class="modal fade" id="quickApproveModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Approve Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to approve <strong id="approveProviderName"></strong> as a service provider?</p>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    This will allow them to receive and accept service requests immediately.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="quickApproveForm" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check me-1"></i>Approve Provider
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quick Reject Modal -->
<div class="modal fade" id="quickRejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick Reject Provider</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="quickRejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <p>Please provide a reason for rejecting <strong id="rejectProviderName"></strong>:</p>
                    <div class="mb-3">
                        <label for="quick_rejection_reason" class="form-label">Rejection Reason <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="quick_rejection_reason" name="rejection_reason" 
                                  rows="3" required placeholder="Explain why this provider application is being rejected..."></textarea>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        The provider will be notified of this rejection and the reason provided.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>Reject Provider
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function approveProvider(providerId, providerName) {
        document.getElementById('approveProviderName').textContent = providerName;
        document.getElementById('quickApproveForm').action = `/admin/providers/${providerId}/approve`;
        new bootstrap.Modal(document.getElementById('quickApproveModal')).show();
    }

    function rejectProvider(providerId, providerName) {
        document.getElementById('rejectProviderName').textContent = providerName;
        document.getElementById('quickRejectForm').action = `/admin/providers/${providerId}/reject`;
        document.getElementById('quick_rejection_reason').value = '';
        new bootstrap.Modal(document.getElementById('quickRejectModal')).show();
    }
</script>
@endpush
