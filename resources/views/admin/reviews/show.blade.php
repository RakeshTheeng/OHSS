@extends('layouts.admin')

@section('title', 'Review Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Review Details</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                <li class="breadcrumb-item active">Review #{{ $review->id }}</li>
            </ol>
        </nav>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Review Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Review Information</h6>
                    <div>
                        @if($review->is_flagged)
                            <span class="badge badge-danger badge-lg mr-2">Flagged</span>
                        @elseif($review->is_approved)
                            <span class="badge badge-success badge-lg mr-2">Approved</span>
                        @else
                            <span class="badge badge-warning badge-lg mr-2">Pending</span>
                        @endif
                        
                        <div class="text-warning d-inline">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="font-weight-bold">Review #{{ $review->id }}</h5>
                            <div class="mt-2">
                                <strong>Service:</strong> {{ $review->booking->serviceRequest->title }}<br>
                                <strong>Category:</strong> 
                                <span class="badge badge-info">{{ $review->booking->serviceRequest->serviceCategory->name }}</span>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <div class="text-muted">
                                <small>Review Date: {{ $review->created_at->format('M d, Y \a\t h:i A') }}</small><br>
                                <small>Service Date: {{ $review->booking->scheduled_date->format('M d, Y') }}</small>
                            </div>
                        </div>
                    </div>

                    <hr>

                    @if($review->comment)
                        <div class="mt-3">
                            <strong>Customer Comment:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                <p class="mb-0">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @else
                        <div class="mt-3">
                            <strong>Customer Comment:</strong>
                            <div class="mt-2 p-3 bg-light rounded">
                                <p class="mb-0 text-muted">No comment provided</p>
                            </div>
                        </div>
                    @endif

                    @if($review->provider_response)
                        <div class="mt-3">
                            <strong>Provider Response:</strong>
                            <div class="mt-2 p-3 bg-info text-white rounded">
                                <p class="mb-0">{{ $review->provider_response }}</p>
                                @if($review->provider_responded_at)
                                    <small class="d-block mt-2">
                                        Responded on: {{ $review->provider_responded_at->format('M d, Y \a\t h:i A') }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($review->is_flagged && $review->flag_reason)
                        <div class="mt-3">
                            <strong>Flag Reason:</strong>
                            <div class="alert alert-danger mt-2">
                                <i class="fas fa-flag"></i> {{ $review->flag_reason }}
                                @if($review->flagged_at)
                                    <br><small>Flagged on: {{ $review->flagged_at->format('M d, Y \a\t h:i A') }}</small>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Admin Actions -->
                    <div class="mt-4">
                        <h6 class="font-weight-bold">Admin Actions:</h6>
                        <div class="btn-group" role="group">
                            @if(!$review->is_approved && !$review->is_flagged)
                                <form method="POST" action="{{ route('admin.reviews.approve', $review) }}" style="display: inline;">
                                    @csrf
                                    <button type="submit" 
                                            class="btn btn-success" 
                                            onclick="return confirm('Are you sure you want to approve this review?')">
                                        <i class="fas fa-check"></i> Approve Review
                                    </button>
                                </form>
                            @endif
                            
                            @if(!$review->is_flagged)
                                <button type="button" 
                                        class="btn btn-warning" 
                                        data-toggle="modal" 
                                        data-target="#flagModal">
                                    <i class="fas fa-flag"></i> Flag Review
                                </button>
                            @endif
                            
                            <form method="POST" action="{{ route('admin.reviews.destroy', $review) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="btn btn-danger" 
                                        onclick="return confirm('Are you sure you want to delete this review? This action cannot be undone.')">
                                    <i class="fas fa-trash"></i> Delete Review
                                </button>
                            </form>
                        </div>
                    </div>
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
                            <strong>Booking ID:</strong> #{{ $review->booking->id }}<br>
                            <strong>Service:</strong> {{ $review->booking->serviceRequest->title }}<br>
                            <strong>Category:</strong> 
                            <span class="badge badge-info">{{ $review->booking->serviceRequest->serviceCategory->name }}</span><br>
                            <strong>Scheduled Date:</strong> {{ $review->booking->scheduled_date->format('M d, Y \a\t h:i A') }}
                        </div>
                        <div class="col-md-6">
                            <strong>Duration:</strong> {{ $review->booking->duration }} minutes<br>
                            <strong>Total Amount:</strong> Rs. {{ number_format($review->booking->total_amount, 2) }}<br>
                            <strong>Booking Status:</strong> 
                            <span class="badge badge-{{ $review->booking->status === 'completed' ? 'success' : ($review->booking->status === 'cancelled' ? 'danger' : 'info') }}">
                                {{ ucfirst(str_replace('_', ' ', $review->booking->status)) }}
                            </span><br>
                            <strong>Payment Status:</strong> 
                            <span class="badge badge-{{ $review->booking->payment_status === 'paid' ? 'success' : 'warning' }}">
                                {{ ucfirst($review->booking->payment_status) }}
                            </span>
                        </div>
                    </div>

                    @if($review->booking->completion_notes)
                        <div class="mt-3">
                            <strong>Completion Notes:</strong>
                            <div class="alert alert-success mt-2">
                                {{ $review->booking->completion_notes }}
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
                            <strong>Request ID:</strong> #{{ $review->booking->serviceRequest->id }}<br>
                            <strong>Budget:</strong> Rs. {{ number_format($review->booking->serviceRequest->budget, 0) }}<br>
                            <strong>Status:</strong> 
                            <span class="badge badge-{{ $review->booking->serviceRequest->status === 'completed' ? 'success' : ($review->booking->serviceRequest->status === 'cancelled' ? 'danger' : 'info') }}">
                                {{ ucfirst($review->booking->serviceRequest->status) }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <strong>Location:</strong> {{ $review->booking->serviceRequest->location }}<br>
                            <strong>Is Urgent:</strong> 
                            @if($review->booking->serviceRequest->is_urgent)
                                <span class="badge badge-danger">Yes</span>
                            @else
                                <span class="badge badge-secondary">No</span>
                            @endif
                        </div>
                    </div>

                    <div class="mt-3">
                        <strong>Description:</strong>
                        <p class="mt-2">{{ $review->booking->serviceRequest->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            @if($review->booking->payment)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Payment Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <strong>Payment ID:</strong> #{{ $review->booking->payment->id }}<br>
                                <strong>Amount:</strong> Rs. {{ number_format($review->booking->payment->amount, 2) }}<br>
                                <strong>Method:</strong> {{ ucfirst($review->booking->payment->payment_method) }}
                            </div>
                            <div class="col-md-6">
                                <strong>Status:</strong> 
                                <span class="badge badge-{{ $review->booking->payment->status === 'completed' ? 'success' : ($review->booking->payment->status === 'failed' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($review->booking->payment->status) }}
                                </span><br>
                                <strong>Payment Date:</strong> {{ $review->booking->payment->created_at->format('M d, Y \a\t h:i A') }}
                            </div>
                        </div>
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
                    @if($review->customer->profile_image)
                        <img src="{{ Storage::url($review->customer->profile_image) }}" 
                             alt="{{ $review->customer->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $review->customer->name }}</h5>
                    <p class="text-muted">{{ $review->customer->email }}</p>
                    @if($review->customer->phone)
                        <p><i class="fas fa-phone"></i> {{ $review->customer->phone }}</p>
                    @endif
                    @if($review->customer->address)
                        <p><i class="fas fa-map-marker-alt"></i> {{ $review->customer->address }}</p>
                    @endif
                    <div class="mt-3">
                        <small class="text-muted">Customer since: {{ $review->customer->created_at->format('M Y') }}</small>
                    </div>
                </div>
            </div>

            <!-- Provider Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($review->provider->profile_image)
                        <img src="{{ Storage::url($review->provider->profile_image) }}" 
                             alt="{{ $review->provider->name }}" 
                             class="rounded-circle mb-3" 
                             style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 100px; height: 100px;">
                            <i class="fas fa-user text-white fa-3x"></i>
                        </div>
                    @endif
                    <h5>{{ $review->provider->name }}</h5>
                    <p class="text-muted">{{ $review->provider->email }}</p>
                    @if($review->provider->phone)
                        <p><i class="fas fa-phone"></i> {{ $review->provider->phone }}</p>
                    @endif
                    @if($review->provider->hourly_rate)
                        <p><i class="fas fa-dollar-sign"></i> Rs. {{ number_format($review->provider->hourly_rate, 0) }}/hr</p>
                    @endif
                    @if($review->provider->rating > 0)
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->provider->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted">({{ $review->provider->total_reviews }} reviews)</span>
                        </div>
                    @endif
                    <div class="mt-3">
                        <span class="badge badge-{{ $review->provider->provider_status === 'approved' ? 'success' : 'warning' }}">
                            {{ ucfirst($review->provider->provider_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Review Summary -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Review Summary</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Rating:</strong><br>
                        <div class="text-warning">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted">({{ $review->rating }}/5)</span>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Status:</strong><br>
                        @if($review->is_flagged)
                            <span class="badge badge-danger">Flagged</span>
                        @elseif($review->is_approved)
                            <span class="badge badge-success">Approved</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Has Comment:</strong><br>
                        <span class="badge badge-{{ $review->comment ? 'success' : 'secondary' }}">
                            {{ $review->comment ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Provider Response:</strong><br>
                        <span class="badge badge-{{ $review->provider_response ? 'success' : 'secondary' }}">
                            {{ $review->provider_response ? 'Yes' : 'No' }}
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        <strong>Review Date:</strong><br>
                        {{ $review->created_at->format('M d, Y \a\t h:i A') }}
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.reviews.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                        <a href="{{ route('admin.bookings.show', $review->booking) }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-calendar-check"></i> View Booking
                        </a>
                        <a href="{{ route('admin.service-requests.show', $review->booking->serviceRequest) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-clipboard-list"></i> View Service Request
                        </a>
                        @if($review->booking->payment)
                            <a href="{{ route('admin.payments.show', $review->booking->payment) }}" class="btn btn-success btn-sm">
                                <i class="fas fa-credit-card"></i> View Payment
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Flag Modal -->
<div class="modal fade" id="flagModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.reviews.flag', $review) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Flag Review</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="reason">Reason for flagging:</label>
                        <textarea class="form-control" 
                                  id="reason" 
                                  name="reason" 
                                  rows="3" 
                                  required 
                                  placeholder="Please provide a reason for flagging this review..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Flag Review</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
