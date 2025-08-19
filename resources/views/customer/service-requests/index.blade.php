@extends('layouts.customer')

@section('title', 'My Requests')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">My Requests</h1>
        <a href="{{ route('customer.service-requests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Request
        </a>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
                                Pending
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending'] }}</div>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Accepted
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['accepted'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
    </div>

    <!-- Search and Filter -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('customer.service-requests.index') }}" class="row g-3">
                <div class="col-md-4">
                    <input type="text" class="form-control" name="search"
                           value="{{ request('search') }}" placeholder="Search requests...">
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="status">
                        <option value="">All Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-5">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <a href="{{ route('customer.service-requests.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Requests List -->
    <div class="card shadow">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Requests</h6>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Service</th>
                                <th>Provider</th>
                                <th>Date & Time</th>
                                <th>Status</th>
                                <th>Budget</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $request->serviceCategory->icon }} text-primary me-2 fa-lg"></i>
                                            <div>
                                                <div class="fw-bold">{{ $request->title }}</div>
                                                <small class="text-muted">{{ $request->serviceCategory->name }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->provider)
                                            <div class="d-flex align-items-center">
                                                @if($request->provider->profile_image)
                                                    <img src="{{ Storage::url($request->provider->profile_image) }}"
                                                         class="rounded-circle me-2"
                                                         width="40" height="40"
                                                         alt="{{ $request->provider->name }}"
                                                         style="object-fit: cover;">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $request->provider->name }}</div>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            <i class="fas fa-star{{ $i <= ($request->provider->rating ?? 0) ? '' : '-o' }}"></i>
                                                        @endfor
                                                        <span class="text-muted ms-1">({{ number_format($request->provider->rating ?? 0, 1) }})</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">
                                                <i class="fas fa-users me-1"></i>
                                                Any Available Provider
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $request->preferred_date->format('M d, Y') }}</div>
                                        <small class="text-muted">{{ $request->preferred_time }}</small>
                                        <div class="small text-muted mt-1">
                                            <i class="fas fa-clock me-1"></i>
                                            {{ $request->required_hours }}h duration
                                        </div>
                                    </td>
                                    <td>
                                        @php
                                            $statusColors = [
                                                'pending' => 'warning',
                                                'accepted' => 'info',
                                                'rejected' => 'danger',
                                                'completed' => 'success',
                                                'cancelled' => 'secondary'
                                            ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$request->status] ?? 'secondary' }} p-2">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                        <div class="small text-muted mt-1">
                                            {{ $request->created_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td>
                                        @if($request->total_budget)
                                            <div class="fw-bold text-success">Rs. {{ number_format($request->total_budget, 0) }}</div>
                                            <small class="text-muted">Rs. {{ number_format($request->hourly_rate, 0) }}/hr</small>
                                        @else
                                            <span class="text-muted">To be quoted</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('customer.service-requests.show', $request) }}"
                                               class="btn btn-sm btn-outline-primary" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($request->status === 'pending')
                                                <a href="{{ route('customer.service-requests.edit', $request) }}"
                                                   class="btn btn-sm btn-outline-warning" title="Edit Request">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST"
                                                      action="{{ route('customer.service-requests.cancel', $request) }}"
                                                      class="d-inline">
                                                    @csrf
                                                    <button type="submit"
                                                            class="btn btn-sm btn-outline-danger"
                                                            title="Cancel Request"
                                                            onclick="return confirm('Are you sure you want to cancel this request?')">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @elseif($request->status === 'accepted' && !$request->booking)
                                                <a href="{{ route('customer.bookings.create', ['service_request' => $request->id]) }}"
                                                   class="btn btn-sm btn-success" title="Book Service">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
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
                    {{ $requests->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No service requests found</h5>
                    <p class="text-muted">
                        @if(request()->filled('search') || request()->filled('status'))
                            Try adjusting your search criteria or
                            <a href="{{ route('customer.service-requests.index') }}">view all requests</a>.
                        @else
                            Create your first service request to get started.
                        @endif
                    </p>
                    <a href="{{ route('customer.service-requests.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Request
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
</style>
@endsection
