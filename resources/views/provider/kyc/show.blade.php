@extends('layouts.provider')

@section('title', 'KYC Document Details')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Document Details</h1>
        <a href="{{ route('provider.kyc.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Documents
        </a>
    </div>

    <div class="row">
        <!-- Document Information -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i>Document Information
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Document Type:</strong>
                        <div class="text-muted">{{ ucfirst(str_replace('_', ' ', $kyc->document_type)) }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Document Number:</strong>
                        <div class="text-muted">{{ $kyc->document_number }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong>
                        <div>
                            @if($kyc->status === 'approved')
                                <span class="badge bg-success">
                                    <i class="fas fa-check me-1"></i>Approved
                                </span>
                            @elseif($kyc->status === 'pending')
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock me-1"></i>Pending Review
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="fas fa-times me-1"></i>Rejected
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Uploaded:</strong>
                        <div class="text-muted">{{ $kyc->created_at->format('M d, Y h:i A') }}</div>
                    </div>
                    
                    @if($kyc->updated_at != $kyc->created_at)
                        <div class="mb-3">
                            <strong>Last Updated:</strong>
                            <div class="text-muted">{{ $kyc->updated_at->format('M d, Y h:i A') }}</div>
                        </div>
                    @endif

                    @if($kyc->rejection_reason)
                        <div class="mb-3">
                            <strong>Rejection Reason:</strong>
                            <div class="text-muted">{{ $kyc->rejection_reason }}</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-cogs me-2"></i>Actions
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($kyc->file_path)
                            <a href="{{ Storage::url($kyc->file_path) }}" 
                               target="_blank" 
                               class="btn btn-outline-primary">
                                <i class="fas fa-download me-2"></i>Download Document
                            </a>
                        @endif
                        
                        @if($kyc->status !== 'approved')
                            <form method="POST"
                                  action="{{ route('provider.kyc.destroy', $kyc) }}"
                                  onsubmit="return confirm('Are you sure you want to delete this document?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Delete Document
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-eye me-2"></i>Document Preview
                    </h6>
                </div>
                <div class="card-body">
                    @if($kyc->file_path)
                        @php
                            $fileExtension = pathinfo($kyc->file_path, PATHINFO_EXTENSION);
                            $isImage = in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png']);
                        @endphp
                        
                        @if($isImage)
                            <div class="text-center">
                                <img src="{{ Storage::url($kyc->file_path) }}" 
                                     alt="Document Preview" 
                                     class="img-fluid border rounded shadow-sm"
                                     style="max-height: 600px;">
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="fas fa-file-pdf fa-5x text-danger mb-3"></i>
                                <h5>PDF Document</h5>
                                <p class="text-muted">Click the download button above to view the PDF document.</p>
                                <a href="{{ Storage::url($kyc->file_path) }}" 
                                   target="_blank" 
                                   class="btn btn-primary">
                                    <i class="fas fa-external-link-alt me-2"></i>Open in New Tab
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5>File Not Found</h5>
                            <p class="text-muted">The document file could not be located.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Information -->
    @if($kyc->status === 'pending')
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-warning" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-clock me-2"></i>Under Review
                    </h4>
                    <p>Your document is currently being reviewed by our verification team. This process typically takes 24-48 hours.</p>
                    <hr>
                    <p class="mb-0">You will receive an email notification once the review is complete.</p>
                </div>
            </div>
        </div>
    @elseif($kyc->status === 'approved')
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-check-circle me-2"></i>Approved
                    </h4>
                    <p>Congratulations! Your document has been successfully approved.</p>
                    <hr>
                    <p class="mb-0">This document is now part of your verified profile and cannot be deleted.</p>
                </div>
            </div>
        </div>
    @elseif($kyc->status === 'rejected')
        <div class="row mt-4">
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <h4 class="alert-heading">
                        <i class="fas fa-times-circle me-2"></i>Document Rejected
                    </h4>
                    <p>Unfortunately, your document could not be verified. Please check the rejection reason above for specific details.</p>
                    <hr>
                    <p class="mb-0">You can delete this document and upload a new one that meets our requirements.</p>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
