@extends('layouts.provider')

@section('title', 'Service Requests')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-clipboard-list me-2"></i>Service Requests
            </h1>
            <p class="text-muted mb-0">Manage incoming service requests from customers</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Accepted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['accepted'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['completed'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Requests</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('provider.service-requests.index') }}">
                <div class="row">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-control">
                            <option value="all" {{ request('status') === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="accepted" {{ request('status') === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Search by title, description, or customer name..." 
                               value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search"></i> Filter
                        </button>
                        <a href="{{ route('provider.service-requests.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Service Requests List -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Service Requests</h6>
        </div>
        <div class="card-body">
            @if($serviceRequests->count() > 0)
                @foreach($serviceRequests as $request)
                    <div class="card mb-3 border-left-{{ 
                        $request->status === 'pending' ? 'warning' : 
                        ($request->status === 'accepted' ? 'success' : 
                        ($request->status === 'completed' ? 'primary' : 'danger')) 
                    }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <div class="d-flex align-items-center mb-2">
                                        <div class="me-3">
                                            @if($request->serviceCategory)
                                                <i class="{{ $request->serviceCategory->icon }} fa-2x text-primary"></i>
                                            @else
                                                <i class="fas fa-tools fa-2x text-muted"></i>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="mb-1">{{ $request->title ?? 'Service Request' }}</h5>
                                            <div class="text-muted">
                                                <i class="fas fa-user me-1"></i>
                                                {{ $request->customer->name }}
                                                <span class="ms-3">
                                                    <i class="fas fa-tag me-1"></i>
                                                    {{ $request->serviceCategory->name ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <p class="text-muted mb-2">{{ Str::limit($request->description, 100) }}</p>
                                    
                                    <div class="row text-muted small">
                                        <div class="col-md-6">
                                            @if($request->preferred_date)
                                                <i class="fas fa-calendar me-1"></i>
                                                {{ \Carbon\Carbon::parse($request->preferred_date)->format('M d, Y') }}
                                            @endif
                                            @if($request->preferred_time)
                                                <span class="ms-2">
                                                    <i class="fas fa-clock me-1"></i>
                                                    {{ ucfirst($request->preferred_time) }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="col-md-6">
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            {{ Str::limit($request->address, 50) }}
                                        </div>
                                    </div>
                                    
                                    @if($request->total_budget)
                                        <div class="mt-2">
                                            <i class="fas fa-calculator me-1 text-success"></i>
                                            <span class="text-success">
                                                {{ $request->required_hours }}h × Rs. {{ number_format($request->hourly_rate) }}/hr = Rs. {{ number_format($request->total_budget) }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                
                                <div class="col-md-4 text-end">
                                    <div class="mb-3">
                                        <span class="badge bg-{{ 
                                            $request->status === 'pending' ? 'warning' : 
                                            ($request->status === 'accepted' ? 'success' : 
                                            ($request->status === 'completed' ? 'primary' : 'danger')) 
                                        }} fs-6">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </div>
                                    
                                    @if($request->urgency)
                                        <div class="mb-2">
                                            <span class="badge bg-{{ 
                                                $request->urgency === 'high' ? 'danger' : 
                                                ($request->urgency === 'medium' ? 'warning' : 'info') 
                                            }}">
                                                {{ ucfirst($request->urgency) }} Priority
                                            </span>
                                        </div>
                                    @endif
                                    
                                    <div class="text-muted small mb-3">
                                        Created {{ $request->created_at->diffForHumans() }}
                                    </div>
                                    
                                    <div class="btn-group-vertical w-100" role="group">
                                        <a href="{{ route('provider.service-requests.show', $request) }}" 
                                           class="btn btn-primary btn-sm">
                                            <i class="fas fa-eye me-1"></i>View Details
                                        </a>
                                        
                                        @if($request->status === 'pending')
                                            <button type="button" class="btn btn-success btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#acceptModal{{ $request->id }}">
                                                <i class="fas fa-check me-1"></i>Accept
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger btn-sm" 
                                                    data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($request->status === 'pending')
                        <!-- Accept Modal -->
                        <div class="modal fade" id="acceptModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('provider.service-requests.accept', $request) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Accept Service Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to accept this service request from <strong>{{ $request->customer->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label for="response{{ $request->id }}" class="form-label">Response Message (Optional)</label>
                                                <textarea name="response" id="response{{ $request->id }}" class="form-control" rows="3" 
                                                          placeholder="Add a message for the customer..."></textarea>
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
                        <div class="modal fade" id="rejectModal{{ $request->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form method="POST" action="{{ route('provider.service-requests.reject', $request) }}">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Service Request</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure you want to reject this service request from <strong>{{ $request->customer->name }}</strong>?</p>
                                            <div class="mb-3">
                                                <label for="rejectResponse{{ $request->id }}" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                <textarea name="response" id="rejectResponse{{ $request->id }}" class="form-control" rows="3" 
                                                          placeholder="Please provide a reason for rejection..." required></textarea>
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
                @endforeach
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $serviceRequests->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No Service Requests Found</h4>
                    <p class="text-muted">
                        @if(request('status') || request('search'))
                            No requests match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't received any service requests yet. Make sure your profile is complete and you're marked as available.
                        @endif
                    </p>
                    @if(request('status') || request('search'))
                        <a href="{{ route('provider.service-requests.index') }}" class="btn btn-primary">
                            <i class="fas fa-times me-2"></i>Clear Filters
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
