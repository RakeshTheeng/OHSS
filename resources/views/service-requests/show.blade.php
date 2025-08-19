@extends('layouts.app')

@section('title', 'Service Request Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Service Request Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Service Request Details</h6>
                    <span class="badge badge-{{ $serviceRequest->status_badge }} badge-lg">
                        {{ ucfirst($serviceRequest->status) }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Service Information</h6>
                            <div class="d-flex align-items-center mb-2">
                                <i class="{{ $serviceRequest->serviceCategory->icon }} fa-2x text-primary me-3"></i>
                                <div>
                                    <h5 class="mb-0">{{ $serviceRequest->serviceCategory->name }}</h5>
                                    <small class="text-muted">{{ $serviceRequest->serviceCategory->description }}</small>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Request Information</h6>
                            <p class="mb-1"><strong>Request ID:</strong> #{{ $serviceRequest->id }}</p>
                            <p class="mb-1"><strong>Created:</strong> {{ $serviceRequest->created_at->format('M d, Y H:i') }}</p>
                            @if($serviceRequest->preferred_date)
                                <p class="mb-1"><strong>Preferred Date:</strong> {{ $serviceRequest->preferred_date->format('M d, Y H:i') }}</p>
                            @endif
                            @if($serviceRequest->estimated_duration)
                                <p class="mb-1"><strong>Estimated Duration:</strong> {{ $serviceRequest->estimated_duration }} minutes</p>
                            @endif
                            @if($serviceRequest->estimated_price)
                                <p class="mb-1"><strong>Estimated Price:</strong> Rs. {{ number_format($serviceRequest->estimated_price, 2) }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h6 class="text-primary">Customer Details</h6>
                            <div class="d-flex align-items-center">
                                @if($serviceRequest->customer->profile_image)
                                    <img class="rounded-circle me-3" src="{{ Storage::url($serviceRequest->customer->profile_image) }}" alt="{{ $serviceRequest->customer->name }}" width="50" height="50">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $serviceRequest->customer->name }}</h6>
                                    <p class="mb-0 text-muted">{{ $serviceRequest->customer->email }}</p>
                                    @if($serviceRequest->customer->phone)
                                        <p class="mb-0 text-muted">{{ $serviceRequest->customer->phone }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-primary">Provider Details</h6>
                            <div class="d-flex align-items-center">
                                @if($serviceRequest->provider->profile_image)
                                    <img class="rounded-circle me-3" src="{{ Storage::url($serviceRequest->provider->profile_image) }}" alt="{{ $serviceRequest->provider->name }}" width="50" height="50">
                                @else
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 50px; height: 50px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                @endif
                                <div>
                                    <h6 class="mb-0">{{ $serviceRequest->provider->name }}</h6>
                                    <p class="mb-0 text-muted">{{ $serviceRequest->provider->email }}</p>
                                    <div class="text-warning">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $serviceRequest->provider->rating)
                                                <i class="fas fa-star"></i>
                                            @else
                                                <i class="far fa-star"></i>
                                            @endif
                                        @endfor
                                        <span class="text-muted">({{ $serviceRequest->provider->total_reviews }} reviews)</span>
                                    </div>
                                    <p class="mb-0 text-muted">Rs. {{ number_format($serviceRequest->provider->hourly_rate, 2) }}/hour</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">Service Description</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{{ $serviceRequest->description }}</p>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h6 class="text-primary">Service Address</h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">
                                <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                {{ $serviceRequest->address }}
                            </p>
                        </div>
                    </div>

                    @if($serviceRequest->provider_response)
                        <div class="mb-4">
                            <h6 class="text-primary">Provider Response</h6>
                            <div class="bg-light p-3 rounded">
                                <p class="mb-1">{{ $serviceRequest->provider_response }}</p>
                                <small class="text-muted">Responded on {{ $serviceRequest->responded_at->format('M d, Y H:i') }}</small>
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('service-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Back to Requests
                        </a>
                        
                        <div>
                            @if(auth()->user()->isProvider() && $serviceRequest->provider_id === auth()->id() && $serviceRequest->status === 'pending')
                                <button type="button" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#acceptModal">
                                    <i class="fas fa-check me-2"></i>
                                    Accept Request
                                </button>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                                    <i class="fas fa-times me-2"></i>
                                    Reject Request
                                </button>
                            @endif
                            
                            @if(auth()->user()->isCustomer() && $serviceRequest->customer_id === auth()->id() && $serviceRequest->status === 'accepted')
                                <a href="{{ route('bookings.create-from-request', $serviceRequest) }}" class="btn btn-primary">
                                    <i class="fas fa-calendar-plus me-2"></i>
                                    Book Now
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Booking Information -->
            @if($serviceRequest->booking)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Booking Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Booking ID:</strong> #{{ $serviceRequest->booking->id }}</p>
                                <p class="mb-1"><strong>Scheduled Date:</strong> {{ $serviceRequest->booking->scheduled_date->format('M d, Y H:i') }}</p>
                                <p class="mb-1"><strong>Duration:</strong> {{ $serviceRequest->booking->duration }} minutes</p>
                                <p class="mb-1"><strong>Total Amount:</strong> Rs. {{ number_format($serviceRequest->booking->total_amount, 2) }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Payment Method:</strong> {{ ucfirst($serviceRequest->booking->payment_method) }}</p>
                                <p class="mb-1"><strong>Status:</strong> 
                                    <span class="badge badge-{{ $serviceRequest->booking->status_badge }}">
                                        {{ ucfirst($serviceRequest->booking->status) }}
                                    </span>
                                </p>
                                @if($serviceRequest->booking->special_instructions)
                                    <p class="mb-1"><strong>Special Instructions:</strong> {{ $serviceRequest->booking->special_instructions }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="{{ route('bookings.show', $serviceRequest->booking) }}" class="btn btn-info">
                                <i class="fas fa-eye me-2"></i>
                                View Booking Details
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Chat Section -->
            @if($serviceRequest->status === 'accepted' || $serviceRequest->status === 'booked' || $serviceRequest->status === 'in_progress' || $serviceRequest->status === 'completed')
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-info">
                            <i class="fas fa-comments me-2"></i>
                            Chat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="chat-messages" style="height: 300px; overflow-y: auto; border: 1px solid #e3e6f0; padding: 10px; margin-bottom: 10px;">
                            @if($serviceRequest->chatMessages->count() > 0)
                                @foreach($serviceRequest->chatMessages as $message)
                                    <div class="message mb-2 {{ $message->sender_id === auth()->id() ? 'text-end' : '' }}">
                                        <div class="d-inline-block p-2 rounded {{ $message->sender_id === auth()->id() ? 'bg-primary text-white' : 'bg-light' }}">
                                            @if($message->message)
                                                <p class="mb-0">{{ $message->message }}</p>
                                            @endif
                                            @if($message->hasFile())
                                                <p class="mb-0">
                                                    <i class="fas fa-paperclip me-1"></i>
                                                    <a href="{{ $message->file_url }}" target="_blank" class="{{ $message->sender_id === auth()->id() ? 'text-white' : 'text-primary' }}">
                                                        {{ $message->file_name }}
                                                    </a>
                                                </p>
                                            @endif
                                            <small class="d-block mt-1 {{ $message->sender_id === auth()->id() ? 'text-white-50' : 'text-muted' }}">
                                                {{ $message->created_at->format('H:i') }}
                                            </small>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center">No messages yet. Start the conversation!</p>
                            @endif
                        </div>
                        
                        <form id="chat-form">
                            @csrf
                            <div class="input-group">
                                <input type="text" class="form-control" id="message-input" placeholder="Type your message...">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-warning">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if(auth()->user()->isCustomer() && $serviceRequest->customer_id === auth()->id())
                        @if($serviceRequest->status === 'pending')
                            <p class="text-muted mb-3">Your request is pending provider response.</p>
                        @elseif($serviceRequest->status === 'accepted')
                            <a href="{{ route('bookings.create-from-request', $serviceRequest) }}" class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-calendar-plus me-2"></i>
                                Book This Service
                            </a>
                        @elseif($serviceRequest->status === 'completed' && !$serviceRequest->booking?->review)
                            <button class="btn btn-warning btn-block mb-2">
                                <i class="fas fa-star me-2"></i>
                                Leave Review
                            </button>
                        @endif
                    @endif
                    
                    @if(auth()->user()->isProvider() && $serviceRequest->provider_id === auth()->id())
                        @if($serviceRequest->booking && $serviceRequest->booking->canBeStarted())
                            <form method="POST" action="{{ route('bookings.start', $serviceRequest->booking) }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-block mb-2">
                                    <i class="fas fa-play me-2"></i>
                                    Start Service
                                </button>
                            </form>
                        @endif
                        
                        @if($serviceRequest->booking && $serviceRequest->booking->canBeCompleted())
                            <button type="button" class="btn btn-info btn-block mb-2" data-bs-toggle="modal" data-bs-target="#completeModal">
                                <i class="fas fa-check-double me-2"></i>
                                Complete Service
                            </button>
                        @endif
                    @endif
                    
                    <a href="tel:{{ auth()->user()->isCustomer() ? $serviceRequest->provider->phone : $serviceRequest->customer->phone }}" class="btn btn-outline-primary btn-block">
                        <i class="fas fa-phone me-2"></i>
                        Call {{ auth()->user()->isCustomer() ? 'Provider' : 'Customer' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Accept Modal -->
@if(auth()->user()->isProvider() && $serviceRequest->provider_id === auth()->id() && $serviceRequest->status === 'pending')
    <div class="modal fade" id="acceptModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('service-requests.accept', $serviceRequest) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Accept Service Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="response" class="form-label">Response Message (Optional)</label>
                            <textarea class="form-control" id="response" name="response" rows="3" placeholder="Add a message for the customer..."></textarea>
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
                <form method="POST" action="{{ route('service-requests.reject', $serviceRequest) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Service Request</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="rejectResponse" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="rejectResponse" name="response" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
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

<!-- Complete Service Modal -->
@if(auth()->user()->isProvider() && $serviceRequest->booking && $serviceRequest->booking->canBeCompleted())
    <div class="modal fade" id="completeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" action="{{ route('bookings.complete', $serviceRequest->booking) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Complete Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="completion_notes" class="form-label">Completion Notes (Optional)</label>
                            <textarea class="form-control" id="completion_notes" name="completion_notes" rows="3" placeholder="Add any notes about the completed service..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success">Mark as Complete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endif

<style>
.badge-lg {
    font-size: 0.875rem;
    padding: 0.5rem 0.75rem;
}
.badge-pending { background-color: #f6c23e; color: white; }
.badge-accepted { background-color: #1cc88a; color: white; }
.badge-rejected { background-color: #e74a3b; color: white; }
.badge-completed { background-color: #1cc88a; color: white; }
.badge-booked { background-color: #36b9cc; color: white; }
.badge-in_progress { background-color: #36b9cc; color: white; }
.badge-confirmed { background-color: #36b9cc; color: white; }
.badge-cancelled { background-color: #858796; color: white; }
</style>
@endsection
