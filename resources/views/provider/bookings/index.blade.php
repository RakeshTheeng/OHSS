@extends('layouts.provider')

@section('title', 'Bookings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-calendar-check me-2"></i>
            Bookings
        </h1>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Bookings</div>
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
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Confirmed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['confirmed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">In Progress</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['in_progress'] }}</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Bookings</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('provider.bookings.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="status">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="search">Search</label>
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Search by customer name or service..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>
                                <a href="{{ route('provider.bookings.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times"></i> Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bookings List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Your Bookings</h6>
        </div>
        <div class="card-body">
            @if($bookings->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Date & Time</th>
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
                                            @if($booking->customer->profile_image)
                                                <img src="{{ Storage::url($booking->customer->profile_image) }}" 
                                                     alt="{{ $booking->customer->name }}" 
                                                     class="rounded-circle me-2" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-white"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="font-weight-bold">{{ $booking->customer->name }}</div>
                                                <div class="text-muted small">{{ $booking->customer->phone ?? 'No phone' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $booking->serviceRequest->title }}</div>
                                        <div class="text-muted small">
                                            <i class="fas fa-tag me-1"></i>
                                            {{ $booking->serviceRequest->serviceCategory->name }}
                                        </div>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold">{{ $booking->scheduled_date->format('M d, Y') }}</div>
                                        <div class="text-muted small">{{ $booking->scheduled_date->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-info">{{ $booking->duration_in_hours }}h</span>
                                    </td>
                                    <td>
                                        <div class="font-weight-bold text-success">Rs. {{ number_format($booking->total_amount, 2) }}</div>
                                        @if($booking->payment)
                                            <div class="text-muted small">{{ ucfirst($booking->payment->payment_method) }}</div>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $booking->status === 'confirmed' ? 'warning' : ($booking->status === 'in_progress' ? 'info' : ($booking->status === 'completed' ? 'success' : 'secondary')) }}">
                                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('provider.bookings.show', $booking) }}" 
                                           class="btn btn-sm btn-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        
                                        @if($booking->canBeStarted())
                                            <form method="POST" action="{{ route('bookings.start', $booking) }}" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success" title="Start Service">
                                                    <i class="fas fa-play"></i>
                                                </button>
                                            </form>
                                        @endif
                                        
                                        @if($booking->canBeCompleted())
                                            <button type="button" class="btn btn-sm btn-primary" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#completeModal{{ $booking->id }}" 
                                                    title="Complete Service">
                                                <i class="fas fa-check-double"></i>
                                            </button>
                                        @endif
                                    </td>
                                </tr>

                                <!-- Complete Service Modal -->
                                @if($booking->canBeCompleted())
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
                                                            <textarea name="completion_notes" 
                                                                    id="completion_notes{{ $booking->id }}" 
                                                                    class="form-control" 
                                                                    rows="3" 
                                                                    placeholder="Add any notes about the completed service..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">
                                                            <i class="fas fa-check"></i> Mark as Completed
                                                        </button>
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
                    {{ $bookings->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No bookings found</h5>
                    <p class="text-muted">You don't have any bookings yet. Bookings will appear here once customers book your services.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
