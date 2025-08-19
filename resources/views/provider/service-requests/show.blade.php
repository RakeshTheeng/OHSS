@extends('layouts.provider')

@section('title', 'Service Request Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list me-2"></i>Service Request Details
            </h1>
            <p class="text-muted mb-0">Request #{{ $serviceRequest->id }}</p>
        </div>
        <div>
            <a href="{{ route('provider.service-requests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Requests
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Request Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Request Information</h6>
                    <span class="badge bg-{{
                        $serviceRequest->status === 'pending' ? 'warning' :
                        ($serviceRequest->status === 'accepted' ? 'primary' :
                        ($serviceRequest->status === 'completed' ? 'success' :
                        ($serviceRequest->status === 'in_progress' ? 'info' : 'danger')))
                    }} fs-6">
                        {{ ucfirst($serviceRequest->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">{{ $serviceRequest->title ?? 'Service Request' }}</h5>
                            <div class="mb-2">
                                <strong>Category:</strong>
                                <span class="badge bg-primary ms-2">
                                    @if($serviceRequest->serviceCategory)
                                        <i class="{{ $serviceRequest->serviceCategory->icon }} me-1"></i>
                                        {{ $serviceRequest->serviceCategory->name }}
                                    @else
                                        N/A
                                    @endif
                                </span>
                            </div>
                            @if($serviceRequest->urgency)
                                <div class="mb-2">
                                    <strong>Priority:</strong>
                                    <span class="badge bg-{{ 
                                        $serviceRequest->urgency === 'high' ? 'danger' : 
                                        ($serviceRequest->urgency === 'medium' ? 'warning' : 'info') 
                                    }} ms-2">
                                        {{ ucfirst($serviceRequest->urgency) }}
                                    </span>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($serviceRequest->preferred_date)
                                <div class="mb-2">
                                    <strong>Preferred Date:</strong>
                                    <div class="text-muted">{{ \Carbon\Carbon::parse($serviceRequest->preferred_date)->format('l, F d, Y') }}</div>
                                </div>
                            @endif
                            @if($serviceRequest->preferred_time)
                                <div class="mb-2">
                                    <strong>Preferred Time:</strong>
                                    <div class="text-muted">{{ ucfirst($serviceRequest->preferred_time) }}</div>
                                </div>
                            @endif
                            <div class="mb-2">
                                <strong>Received:</strong>
                                <div class="text-muted">{{ $serviceRequest->created_at->format('M d, Y \a\t g:i A') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <strong>Description:</strong>
                        <p class="mt-2 text-muted">{{ $serviceRequest->description }}</p>
                    </div>

                    <div class="mb-4">
                        <strong>Service Address:</strong>
                        <p class="mt-2 text-muted">{{ $serviceRequest->address }}</p>
                    </div>

                    @if($serviceRequest->required_hours || $serviceRequest->total_budget)
                        <div class="mb-4">
                            <strong>Service Details:</strong>
                            <div class="row mt-2">
                                @if($serviceRequest->required_hours)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Required Hours</div>
                                        <div class="text-primary fw-bold">{{ $serviceRequest->required_hours }} hours</div>
                                    </div>
                                @endif

                                @if($serviceRequest->hourly_rate)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Hourly Rate</div>
                                        <div class="text-info fw-bold">Rs. {{ number_format($serviceRequest->hourly_rate) }}/hr</div>
                                    </div>
                                @endif

                                @if($serviceRequest->total_budget)
                                    <div class="col-md-4">
                                        <div class="text-muted small">Total Budget</div>
                                        <div class="text-success fw-bold">Rs. {{ number_format($serviceRequest->total_budget) }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    @if($serviceRequest->additional_notes)
                        <div class="mb-4">
                            <strong>Additional Notes:</strong>
                            <p class="mt-2 text-muted">{{ $serviceRequest->additional_notes }}</p>
                        </div>
                    @endif

                    @if($serviceRequest->provider_response)
                        <div class="mb-4">
                            <strong>Your Response:</strong>
                            <div class="alert alert-info mt-2">
                                {{ $serviceRequest->provider_response }}
                                @if($serviceRequest->responded_at)
                                    <div class="small text-muted mt-1">
                                        Responded on {{ $serviceRequest->responded_at->format('M d, Y \a\t g:i A') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Customer Information -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Customer Information</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        @if($serviceRequest->customer->profile_image)
                            <img src="{{ Storage::url($serviceRequest->customer->profile_image) }}" 
                                 alt="{{ $serviceRequest->customer->name }}" 
                                 class="rounded-circle me-3" 
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" 
                                 style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-white"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1">
                            <h6 class="mb-1">{{ $serviceRequest->customer->name }}</h6>
                            <div class="text-muted">{{ $serviceRequest->customer->email }}</div>
                            @if($serviceRequest->customer->phone)
                                <div class="text-muted">{{ $serviceRequest->customer->phone }}</div>
                            @endif
                        </div>
                        <div>
                            @if($serviceRequest->customer->phone)
                                <a href="tel:{{ $serviceRequest->customer->phone }}" class="btn btn-outline-success btn-sm me-2">
                                    <i class="fas fa-phone me-1"></i>Call
                                </a>
                            @endif
                            <a href="mailto:{{ $serviceRequest->customer->email }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-envelope me-1"></i>Email
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            @if($serviceRequest->booking)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Booking Information</h6>
                    </div>
                    <div class="card-body">
                        @php $booking = $serviceRequest->booking @endphp
                        <div class="d-flex justify-content-between align-items-center p-3 border rounded mb-2">
                            <div>
                                <div class="fw-bold">Booking #{{ $booking->id }}</div>
                                <div class="text-muted small">
                                    {{ $booking->scheduled_date ? $booking->scheduled_date->format('M d, Y \a\t g:i A') : 'Not scheduled' }}
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ 
                                    $booking->status === 'confirmed' ? 'success' : 
                                    ($booking->status === 'completed' ? 'primary' : 
                                    ($booking->status === 'cancelled' ? 'danger' : 'warning')) 
                                }} fs-6">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Actions Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                </div>
                <div class="card-body">
                    @if($serviceRequest->status === 'pending')
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal">
                                <i class="fas fa-check me-2"></i>Accept Request
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                <i class="fas fa-times me-2"></i>Reject Request
                            </button>
                        </div>
                        
                        <hr>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Next Steps:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Review the request details carefully</li>
                                <li>Accept if you can provide the service</li>
                                <li>Reject with a reason if you cannot</li>
                            </ul>
                        </div>
                    @elseif($serviceRequest->status === 'accepted')
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong>Request Accepted!</strong>
                            <p class="mb-0 mt-2">You have accepted this request. The customer has been notified and can now book your service.</p>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completeModal">
                                <i class="fas fa-check-double me-2"></i>Mark as Completed
                            </button>
                        </div>

                        @if(!$serviceRequest->booking)
                            <div class="alert alert-warning">
                                <i class="fas fa-clock me-2"></i>
                                <strong>Waiting for Booking</strong>
                                <p class="mb-0 mt-2">The customer needs to create a booking to schedule the service.</p>
                            </div>
                        @endif
                    @elseif($serviceRequest->status === 'in_progress')
                        <div class="alert alert-info">
                            <i class="fas fa-play-circle me-2"></i>
                            <strong>Service In Progress</strong>
                            <p class="mb-0 mt-2">This service is currently in progress.</p>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#completeModal">
                                <i class="fas fa-check-double me-2"></i>Mark as Completed
                            </button>
                        </div>
                    @elseif($serviceRequest->status === 'rejected')
                        <div class="alert alert-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            <strong>Request Rejected</strong>
                            <p class="mb-0 mt-2">You have rejected this request. The customer has been notified.</p>
                        </div>
                    @elseif($serviceRequest->status === 'completed')
                        <div class="alert alert-primary">
                            <i class="fas fa-check-double me-2"></i>
                            <strong>Service Completed</strong>
                            <p class="mb-0 mt-2">This service has been completed successfully.</p>
                        </div>
                    @endif
                    
                    <div class="mt-3">
                        <h6 class="text-muted">Request Timeline</h6>
                        <div class="timeline">
                            <div class="timeline-item">
                                <i class="fas fa-plus-circle text-info"></i>
                                <span class="text-muted small">Request created {{ $serviceRequest->created_at->diffForHumans() }}</span>
                            </div>
                            @if($serviceRequest->responded_at)
                                <div class="timeline-item">
                                    <i class="fas fa-{{ $serviceRequest->status === 'accepted' ? 'check' : 'times' }}-circle text-{{ $serviceRequest->status === 'accepted' ? 'success' : 'danger' }}"></i>
                                    <span class="text-muted small">{{ ucfirst($serviceRequest->status) }} {{ $serviceRequest->responded_at->diffForHumans() }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($serviceRequest->status === 'pending')
    <!-- Accept Modal -->
    <div class="modal fade" id="acceptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('provider.service-requests.accept', $serviceRequest) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Accept Service Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to accept this service request from <strong>{{ $serviceRequest->customer->name }}</strong>?</p>
                        <div class="mb-3">
                            <label for="response" class="form-label">Response Message (Optional)</label>
                            <textarea name="response" id="response" class="form-control" rows="3" 
                                      placeholder="Add a message for the customer..."></textarea>
                            <div class="form-text">This message will be sent to the customer along with the acceptance notification.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Accept Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Reject Modal -->
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('provider.service-requests.reject', $serviceRequest) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Service Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to reject this service request from <strong>{{ $serviceRequest->customer->name }}</strong>?</p>
                        <div class="mb-3">
                            <label for="rejectResponse" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea name="response" id="rejectResponse" class="form-control" rows="3" 
                                      placeholder="Please provide a reason for rejection..." required></textarea>
                            <div class="form-text">This reason will be sent to the customer.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject Request</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

@if(in_array($serviceRequest->status, ['accepted', 'in_progress']))
    <!-- Complete Service Modal -->
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('provider.service-requests.complete', $serviceRequest) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Mark Service as Completed</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to mark this service as completed?</p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>What happens next:</strong>
                            <ul class="mb-0 mt-2">
                                <li>The service status will be updated to "Completed"</li>
                                <li>Your availability status will be updated to "Available"</li>
                                <li>The customer will be notified</li>
                                <li>The customer can leave a review</li>
                            </ul>
                        </div>
                        <div class="mb-3">
                            <label for="completion_notes" class="form-label">Completion Notes (Optional)</label>
                            <textarea name="completion_notes" id="completion_notes" class="form-control" rows="3"
                                      placeholder="Add any notes about the completed service..."></textarea>
                            <div class="form-text">These notes will be visible to the customer.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-check-double me-1"></i>Mark as Completed
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline-item {
    position: relative;
    padding-bottom: 10px;
}

.timeline-item i {
    position: absolute;
    left: -25px;
    top: 2px;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: -21px;
    top: 15px;
    width: 2px;
    height: 20px;
    background-color: #dee2e6;
}
</style>
@endsection
