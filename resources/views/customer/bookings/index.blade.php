@extends('layouts.customer')

@section('title', 'My Bookings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Bookings</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('customer.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">My Bookings</li>
            </ol>
        </nav>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Bookings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Upcoming
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['upcoming'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completed
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Cancelled
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['cancelled'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.bookings.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Search bookings...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('customer.bookings.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Bookings</h6>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Provider</th>
                                <th>Scheduled Date</th>
                                <th>Duration</th>
                                <th>Amount</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bookings as $booking)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $booking->serviceRequest->serviceCategory->icon }} text-primary me-2 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold">{{ $booking->serviceRequest->title }}</div>
                                                <small class="text-muted">{{ $booking->serviceRequest->serviceCategory->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($booking->provider->profile_image)
                                                <img src="{{ Storage::url($booking->provider->profile_image) }}" 
                                                     class="rounded-circle me-2" 
                                                     width="40" height="40" 
                                                     alt="{{ $booking->provider->name }}"
                                                     style="object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $booking->provider->name }}</div>
                                                <div class="text-warning small">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star{{ $i <= ($booking->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                                    @endfor
                                                    <span class="text-muted ms-1">({{ number_format($booking->provider->rating ?? 0, 1) }})</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $booking->scheduled_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $booking->scheduled_date->format('h:i A') }}</small>
                                        <div class="small text-muted mt-1">
                                            @if($booking->scheduled_date->isFuture())
                                                <i class="fas fa-clock me-1 text-info"></i>
                                                {{ $booking->scheduled_date->diffForHumans() }}
                                            @else
                                                <i class="fas fa-history me-1 text-muted"></i>
                                                {{ $booking->scheduled_date->diffForHumans() }}
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">{{ $booking->duration }}h</span>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-success">Rs. {{ number_format($booking->total_amount, 0) }}</div>
                                        <small class="text-muted">{{ ucfirst($booking->payment_method) }}</small>
                                        <div class="small">
                                            <span class="badge bg-{{ $booking->payment_status === 'completed' ? 'success' : ($booking->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                {{ ucfirst($booking->payment_status) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'confirmed' => 'info',
                                                'in_progress' => 'primary',
                                                'completed' => 'success',
                                                'cancelled' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$booking->status] ?? 'secondary' }} p-2">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            {{ $booking->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customer.bookings.show', $booking) }}" 
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($booking->status === 'confirmed' && $booking->scheduled_date->diffInHours(now()) >= 24)
                                                <form method="POST" 
                                                      action="{{ route('customer.bookings.cancel', $booking) }}" 
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Cancel Booking"
                                                            onclick="return confirm('Are you sure you want to cancel this booking?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            @if($booking->status === 'completed' && !$booking->review)
                                                <a href="{{ route('customer.reviews.create-from-booking', $booking) }}"
                                                   class="btn btn-sm btn-warning pulse-animation" title="Write Review - Help others by sharing your experience!">
                                                    <i class="fas fa-star me-1"></i>Review
                                                </a>
                                            @elseif($booking->status === 'completed' && $booking->review)
                                                <span class="btn btn-sm btn-success" title="Review Submitted">
                                                    <i class="fas fa-check me-1"></i>Reviewed
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No bookings found</h5>
                    <p class="text-muted">
                        @if(request()->filled('search') || request()->filled('status'))
                            Try adjusting your search criteria or 
                            <a href="{{ route('customer.bookings.index') }}">view all bookings</a>.
                        @else
                            You haven't made any bookings yet. Start by requesting a service!
                        @endif
                    </p>
                    <a href="{{ route('customer.providers.index') }}" class="btn btn-primary">
                        <i class="fas fa-search"></i> Find Services
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}
.text-warning .fas.fa-star {
    color: #f6c23e;
}
.text-warning .fas.fa-star-o {
    color: #d1d3e2;
}

/* Pulse animation for review button */
.pulse-animation {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        box-shadow: 0 0 0 0 rgba(246, 194, 62, 0.7);
    }
    70% {
        box-shadow: 0 0 0 10px rgba(246, 194, 62, 0);
    }
    100% {
        box-shadow: 0 0 0 0 rgba(246, 194, 62, 0);
    }
}

.pulse-animation:hover {
    animation: none;
    transform: scale(1.05);
    transition: transform 0.2s ease;
}
</style>
@endsection
