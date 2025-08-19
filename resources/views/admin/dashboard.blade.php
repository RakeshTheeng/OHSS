@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container-fluid">


    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_users'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                Total Revenue
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Rs. {{ number_format($stats['total_revenue'], 2) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
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
                                Service Requests
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['total_service_requests'] }}</div>
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
                                Pending Providers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $stats['pending_providers'] }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Management Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="text-primary mb-3">
                        <i class="fas fa-users fa-3x"></i>
                    </div>
                    <h5 class="card-title">User Management</h5>
                    <p class="card-text text-muted">Manage customers and service providers</p>
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="text-primary font-weight-bold">{{ $stats['total_customers'] }}</div>
                            <div class="text-muted small">Customers</div>
                        </div>
                        <div class="col-6">
                            <div class="text-success font-weight-bold">{{ $stats['total_providers'] }}</div>
                            <div class="text-muted small">Providers</div>
                        </div>
                    </div>
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('admin.users.customers') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-user me-1"></i>Customers
                        </a>
                        <a href="{{ route('admin.users.providers') }}" class="btn btn-success btn-sm">
                            <i class="fas fa-user-cog me-1"></i>Providers
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="text-success mb-3">
                        <i class="fas fa-tools fa-3x"></i>
                    </div>
                    <h5 class="card-title">Service Categories</h5>
                    <p class="card-text text-muted">Manage service categories and settings</p>
                    <div class="mb-3">
                        <div class="text-success font-weight-bold h4">{{ \App\Models\ServiceCategory::count() }}</div>
                        <div class="text-muted small">Total Categories</div>
                    </div>
                    <a href="{{ route('admin.service-categories.index') }}" class="btn btn-success btn-sm w-100">
                        <i class="fas fa-cog me-1"></i>Manage Categories
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="text-info mb-3">
                        <i class="fas fa-chart-area fa-3x"></i>
                    </div>
                    <h5 class="card-title">Analytics</h5>
                    <p class="card-text text-muted">View reports and system analytics</p>
                    <div class="mb-3">
                        <div class="text-info font-weight-bold h4">{{ $stats['completed_bookings'] }}</div>
                        <div class="text-muted small">Completed Bookings</div>
                    </div>
                    <a href="{{ route('admin.analytics.index') }}" class="btn btn-info btn-sm w-100">
                        <i class="fas fa-chart-line me-1"></i>View Analytics
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card shadow h-100">
                <div class="card-body text-center">
                    <div class="text-warning mb-3">
                        <i class="fas fa-user-check fa-3x"></i>
                    </div>
                    <h5 class="card-title">Provider Approvals</h5>
                    <p class="card-text text-muted">Review and approve service providers</p>
                    <div class="mb-3">
                        <div class="text-warning font-weight-bold h4">{{ $stats['pending_providers'] }}</div>
                        <div class="text-muted small">Pending Approvals</div>
                    </div>
                    <a href="{{ route('admin.users.providers', ['provider_status' => 'pending']) }}" class="btn btn-warning btn-sm w-100">
                        <i class="fas fa-clock me-1"></i>Review Providers
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Service Requests -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    <a href="{{ route('admin.service-requests.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recent_requests->count() > 0)
                        @foreach($recent_requests as $request)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-tools text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="small text-gray-500">{{ $request->created_at->diffForHumans() }}</div>
                                    <div class="font-weight-bold">{{ $request->serviceCategory->name ?? 'N/A' }}</div>
                                    <div class="text-gray-600">{{ $request->customer->name ?? 'N/A' }}
                                        @if($request->provider)
                                            → {{ $request->provider->name }}
                                        @endif
                                    </div>
                                </div>
                                <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                    {{ ucfirst($request->status) }}
                                </span>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent service requests</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Pending Provider Approvals -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Pending Provider Approvals</h6>
                    <a href="{{ route('admin.users.providers', ['provider_status' => 'pending']) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-cog me-1"></i>Manage
                    </a>
                </div>
                <div class="card-body">
                    @if($pending_providers->count() > 0)
                        @foreach($pending_providers as $provider)
                            <div class="d-flex align-items-center mb-3">
                                <div class="me-3">
                                    @if($provider->profile_image)
                                        <img class="rounded-circle" src="{{ Storage::url($provider->profile_image) }}"
                                             alt="{{ $provider->name }}" width="40" height="40">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 40px; height: 40px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1">
                                    <div class="font-weight-bold">{{ $provider->name }}</div>
                                    <div class="text-gray-600">{{ $provider->email }}</div>
                                    <div class="small text-gray-500">Applied {{ $provider->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="btn-group" role="group">
                                    <form method="POST" action="{{ route('admin.users.approve-provider', $provider) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.reject-provider', $provider) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger" title="Reject">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-user-check fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No pending approvals</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Bookings -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Bookings</h6>
                    <a href="{{ route('admin.bookings.index') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-eye me-1"></i>View All
                    </a>
                </div>
                <div class="card-body">
                    @if($recent_bookings->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Provider</th>
                                        <th>Scheduled Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recent_bookings as $booking)
                                        <tr>
                                            <td>{{ $booking->customer->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->provider->name ?? 'N/A' }}</td>
                                            <td>{{ $booking->scheduled_date ? $booking->scheduled_date->format('M d, Y H:i') : 'N/A' }}</td>
                                            <td>Rs. {{ number_format($booking->total_amount ?? 0, 2) }}</td>
                                            <td>
                                                <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : ($booking->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($booking->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.bookings.show', $booking) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No recent bookings</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
    @csrf
</form>

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
.icon-circle {
    height: 2.5rem;
    width: 2.5rem;
    border-radius: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.badge-pending { background-color: #f6c23e; }
.badge-accepted { background-color: #1cc88a; }
.badge-rejected { background-color: #e74a3b; }
.badge-completed { background-color: #1cc88a; }
.badge-confirmed { background-color: #36b9cc; }
.badge-cancelled { background-color: #858796; }
</style>
@endsection
