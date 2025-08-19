@extends('layouts.app')

@section('title', 'Service Requests')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Service Requests</h1>
        @if(auth()->user()->isCustomer())
            <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                New Request
            </a>
        @endif
    </div>

    <!-- Filter Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-warning">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $requests->where('status', 'pending')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Accepted</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $requests->where('status', 'accepted')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-info">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Completed</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $requests->where('status', 'completed')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-double fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">Rejected</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $requests->where('status', 'rejected')->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Requests Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Service Requests</h6>
        </div>
        <div class="card-body">
            @if($requests->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Service</th>
                                @if(auth()->user()->isCustomer())
                                    <th>Provider</th>
                                @else
                                    <th>Customer</th>
                                @endif
                                <th>Description</th>
                                <th>Preferred Date</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($requests as $request)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="{{ $request->serviceCategory->icon }} me-2 text-primary"></i>
                                            {{ $request->serviceCategory->name }}
                                        </div>
                                    </td>
                                    @if(auth()->user()->isCustomer())
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($request->provider->profile_image)
                                                    <img class="rounded-circle me-2" src="{{ Storage::url($request->provider->profile_image) }}" alt="{{ $request->provider->name }}" width="30" height="30">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 30px; height: 30px;">
                                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="font-weight-bold">{{ $request->provider->name }}</div>
                                                    <div class="text-warning small">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $request->provider->rating)
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
                                                @if($request->customer->profile_image)
                                                    <img class="rounded-circle me-2" src="{{ Storage::url($request->customer->profile_image) }}" alt="{{ $request->customer->name }}" width="30" height="30">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                         style="width: 30px; height: 30px;">
                                                        <i class="fas fa-user text-white" style="font-size: 12px;"></i>
                                                    </div>
                                                @endif
                                                {{ $request->customer->name }}
                                            </div>
                                        </td>
                                    @endif
                                    <td>{{ Str::limit($request->description, 50) }}</td>
                                    <td>
                                        @if($request->preferred_date)
                                            {{ $request->preferred_date->format('M d, Y H:i') }}
                                        @else
                                            <span class="text-muted">Not specified</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $request->status_badge }}">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $request->created_at->diffForHumans() }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('service-requests.show', $request) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            
                                            @if(auth()->user()->isProvider() && $request->status === 'pending' && $request->provider_id === auth()->id())
                                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#acceptModal{{ $request->id }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $request->id }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                            
                                            @if(auth()->user()->isCustomer() && $request->status === 'accepted' && $request->customer_id === auth()->id())
                                                <a href="{{ route('bookings.create-from-request', $request) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-calendar-plus"></i>
                                                </a>
                                            @endif
                                            
                                            @if((auth()->user()->isCustomer() && $request->customer_id === auth()->id() && $request->status === 'pending') || auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('service-requests.destroy', $request) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>

                                <!-- Accept Modal -->
                                @if(auth()->user()->isProvider() && $request->status === 'pending' && $request->provider_id === auth()->id())
                                    <div class="modal fade" id="acceptModal{{ $request->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form method="POST" action="{{ route('service-requests.accept', $request) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Accept Service Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="response{{ $request->id }}" class="form-label">Response Message (Optional)</label>
                                                            <textarea class="form-control" id="response{{ $request->id }}" name="response" rows="3" placeholder="Add a message for the customer..."></textarea>
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
                                                <form method="POST" action="{{ route('service-requests.reject', $request) }}">
                                                    @csrf
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Reject Service Request</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="rejectResponse{{ $request->id }}" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                                            <textarea class="form-control" id="rejectResponse{{ $request->id }}" name="response" rows="3" placeholder="Please provide a reason for rejection..." required></textarea>
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
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $requests->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-clipboard-list fa-4x text-gray-300 mb-4"></i>
                    <h4 class="text-gray-500">No Service Requests Found</h4>
                    <p class="text-gray-400 mb-4">
                        @if(auth()->user()->isCustomer())
                            You haven't made any service requests yet.
                        @else
                            You don't have any service requests yet.
                        @endif
                    </p>
                    @if(auth()->user()->isCustomer())
                        <a href="{{ route('service-requests.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>
                            Create Your First Request
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
.border-left-danger { border-left: 0.25rem solid #e74a3b !important; }

.badge-pending { background-color: #f6c23e; color: white; }
.badge-accepted { background-color: #1cc88a; color: white; }
.badge-rejected { background-color: #e74a3b; color: white; }
.badge-completed { background-color: #1cc88a; color: white; }
.badge-booked { background-color: #36b9cc; color: white; }
.badge-cancelled { background-color: #858796; color: white; }
</style>
@endsection
