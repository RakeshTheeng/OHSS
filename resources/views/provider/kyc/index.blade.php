@extends('layouts.provider')

@section('title', 'KYC Documents')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">KYC Documents</h1>
        <a href="{{ route('provider.kyc.create') }}" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>Upload New Document
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- KYC Status Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-shield-alt me-2"></i>Verification Status
                    </h6>
                </div>
                <div class="card-body">
                    @php
                        $verifiedCount = $kycDocuments->where('status', 'approved')->count();
                        $pendingCount = $kycDocuments->where('status', 'pending')->count();
                        $rejectedCount = $kycDocuments->where('status', 'rejected')->count();
                        $totalCount = $kycDocuments->count();
                    @endphp
                    
                    <div class="row text-center">
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h4>{{ $verifiedCount }}</h4>
                                    <p class="mb-0">Verified</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <h4>{{ $pendingCount }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h4>{{ $rejectedCount }}</h4>
                                    <p class="mb-0">Rejected</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <h4>{{ $totalCount }}</h4>
                                    <p class="mb-0">Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-file-alt me-2"></i>Your Documents
            </h6>
        </div>
        <div class="card-body">
            @if($kycDocuments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Document Type</th>
                                <th>Document Number</th>
                                <th>Status</th>
                                <th>Uploaded Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kycDocuments as $document)
                                <tr>
                                    <td>
                                        <i class="fas fa-file-alt me-2"></i>
                                        {{ ucfirst(str_replace('_', ' ', $document->document_type)) }}
                                    </td>
                                    <td>{{ $document->document_number }}</td>
                                    <td>
                                        @if($document->status === 'approved')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Approved
                                            </span>
                                        @elseif($document->status === 'pending')
                                            <span class="badge bg-warning">
                                                <i class="fas fa-clock me-1"></i>Pending Review
                                            </span>
                                        @else
                                            <span class="badge bg-danger">
                                                <i class="fas fa-times me-1"></i>Rejected
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $document->created_at->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if($document->file_path)
                                                <a href="{{ Storage::url($document->file_path) }}" 
                                                   target="_blank" 
                                                   class="btn btn-outline-primary btn-sm">
                                                    <i class="fas fa-eye"></i> View
                                                </a>
                                            @endif
                                            
                                            <a href="{{ route('provider.kyc.show', $document) }}" 
                                               class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-info-circle"></i> Details
                                            </a>
                                            
                                            @if($document->status !== 'approved')
                                                <form method="POST"
                                                      action="{{ route('provider.kyc.destroy', $document) }}"
                                                      class="d-inline"
                                                      onsubmit="return confirm('Are you sure you want to delete this document?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                                        <i class="fas fa-trash"></i> Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-file-upload fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No KYC Documents Uploaded</h5>
                    <p class="text-muted mb-4">Upload your identification documents to verify your account and start receiving service requests.</p>
                    <a href="{{ route('provider.kyc.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Upload Your First Document
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Information Card -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-left-info shadow">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Important Information
                            </div>
                            <div class="text-gray-900">
                                <ul class="mb-0">
                                    <li>All documents must be clear and readable</li>
                                    <li>Accepted formats: PDF, JPG, JPEG, PNG (Max 5MB)</li>
                                    <li>Documents are reviewed within 24-48 hours</li>
                                    <li>Verified documents cannot be deleted</li>
                                    <li>Contact support if you need to update verified documents</li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-info-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
</style>
@endsection
