@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            @if(auth()->user()->isCustomer())
                My Bookings
            @elseif(auth()->user()->isProvider())
                My Service Bookings
            @else
                All Bookings
            @endif
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Bookings</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookings->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Upcoming</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookings->where('status', 'confirmed')->where('scheduled_date', '>', now())->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookings->where('status', 'in_progress')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-play fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookings->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Bookings</h6>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Booking ID</th>
                                <th>Service</th>
                                @if(auth()->user()->isCustomer())
                                    <th>Provider</th>
                                @else
                                    <th>Customer</th>
                                @endif
                                <th>Scheduled Date</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <span class="font-weight-bold">#{{ $booking->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $booking->serviceRequest->serviceCategory->icon }} me-2 text-primary"></i>
                                            {{ $booking->serviceRequest->serviceCategory->name }}
                                        </div>
                                    </td>
                                    @if(auth()->user()->isCustomer())
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($booking->provider->profile_image)
                                                    <img class="rounded-circle me-2" src="{{ Storage::url($booking->provider->profile_image) }}" alt="{{ $booking->provider->name }}" width="30" height="30">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 30px; height: 30px;">
                                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold">{{ $booking->provider->name }}</div>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $booking->provider->rating)
                                                                <i class="fas fa-star"></i>
                                                            @else
                                                                <i class="far fa-star"></i>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    @else
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($booking->customer->profile_image)
                                                    <img class="rounded-circle me-2" src="{{ Storage::url($booking->customer->profile_image) }}" alt="{{ $booking->customer->name }}" width="30" height="30">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 30px; height: 30px;">
                                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                {{ $booking->customer->name }}
                                            </div>
                                        </td>
                                    @endif
                                    <td>
                                        <div>
                                            <div class="font-weight-bold">{{ $booking->scheduled_date->format('M d, Y') }}</div>
                                            <div class="text-muted small">{{ $booking->scheduled_date->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>{{ $booking->duration_in_hours }} hour{{ $booking->duration_in_hours != 1 ? 's' : '' }}</td>
                                    <td>
                                        <span class="font-weight-bold text-success">Rs. {{ number_format($booking->total_amount, 2) }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <span class="badge badge-{{ $booking->payment->status_badge ?? 'secondary' }}">
                                                {{ ucfirst($booking->payment->status ?? 'pending') }}
                                            </span>
                                            <div class="small text-muted">{{ ucfirst($booking->payment_method) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $booking->status_badge }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('bookings.show', $booking) }}" class="btn btn-sm btn-info" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->isProvider() && $booking->provider_id === auth()->id())
                                                @if($booking->canBeStarted())
                                                    <form method="POST" action="{{ route('bookings.start', $booking) }}" class="d-inline">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success" title="Start Service">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                
                                                @if($booking->canBeCompleted())
                                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#completeModal{{ $booking->id }}" title="Complete Service">
                                                        <i class="fas fa-check-double"></i>
                                                    </button>
                                                @endif
                                            @endif
                                            
                                            @if($booking->canBeCancelled() && (
                                                (auth()->user()->isCustomer() && $booking->customer_id === auth()->id()) ||
                                                (auth()->user()->isProvider() && $booking->provider_id === auth()->id()) ||
                                                auth()->user()->isAdmin()
                                            ))
                                                <form method="POST" action="{{ route('bookings.cancel', $booking) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to cancel this booking?')" title="Cancel Booking">
                                                        <i class="fas fa-ban"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Complete Service Modal -->
                                @if(auth()->user()->isProvider() && $booking->provider_id === auth()->id() && $booking->canBeCompleted())
                                    <div class="modal fade" id="completeModal{{ $booking->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('bookings.complete', $booking) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Complete Service</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="completion_notes{{ $booking->id }}" class="form-label">Completion Notes (Optional)</label>
                                                            <textarea class="form-control" id="completion_notes{{ $booking->id }}" name="completion_notes" rows="3" placeholder="Add any notes about the completed service..."></textarea>
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
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $bookings->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-4x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-500">No Bookings Found</h4>
                    <p class="text-gray-400 mb-4">
                        @if(auth()->user()->isCustomer())
                            You haven't made any bookings yet.
                        @else
                            You don't have any bookings yet.
                        @endif
                    </p>
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Request a Service
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary { border-left: 0.25rem solid #4e73df !important; }
.border-left-success { border-left: 0.25rem solid #1cc88a !important; }
.border-left-info { border-left: 0.25rem solid #36b9cc !important; }
.border-left-warning { border-left: 0.25rem solid #f6c23e !important; }

.badge-pending { background-color: #f6c23e; color: white; }
.badge-processing { background-color: #36b9cc; color: white; }
.badge-completed { background-color: #1cc88a; color: white; }
.badge-failed { background-color: #e74a3b; color: white; }
.badge-refunded { background-color: #858796; color: white; }
.badge-confirmed { background-color: #36b9cc; color: white; }
.badge-in_progress { background-color: #4e73df; color: white; }
.badge-cancelled { background-color: #858796; color: white; }
</style>
@endsection
