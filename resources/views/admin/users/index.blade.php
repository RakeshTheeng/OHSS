@extends('layouts.admin')

@section('title', 'User Management')

@section('content')
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-users me-2"></i>User Management
            </h1>
            <p class="text-muted mb-0">Manage customers and service providers</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.users.create', ['type' => 'customer']) }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Add Customer
            </a>
            <a href="{{ route('admin.users.create', ['type' => 'provider']) }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-1"></i>Add Provider
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_users']) }}</div>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Customers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_customers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Providers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_providers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-cog fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Pending Providers</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['pending_providers']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters & Search</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users.index') }}">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="type" class="form-label">User Type</label>
                        <select name="type" id="type" class="form-select">
                            <option value="all" {{ request('type') === 'all' ? 'selected' : '' }}>All Users</option>
                            <option value="customers" {{ request('type') === 'customers' ? 'selected' : '' }}>Customers Only</option>
                            <option value="providers" {{ request('type') === 'providers' ? 'selected' : '' }}>Providers Only</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="provider_status" class="form-label">Provider Status</label>
                        <select name="provider_status" id="provider_status" class="form-select">
                            <option value="">All Provider Status</option>
                            <option value="pending" {{ request('provider_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ request('provider_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('provider_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Name, email, phone..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                @if($type === 'customers')
                    Customer Users
                @elseif($type === 'providers')
                    Service Provider Users
                @else
                    All Users
                @endif
                ({{ $users->total() }} total)
            </h6>
        </div>
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>User</th>
                                <th>Contact</th>
                                <th>Role</th>
                                <th>Status</th>
                                @if($type !== 'customers')
                                    <th>Provider Status</th>
                                @endif
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($user->profile_image)
                                                    <img src="{{ Storage::url($user->profile_image) }}" 
                                                         class="rounded-circle" width="40" height="40" alt="Profile">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-weight-bold">{{ $user->name }}</div>
                                                <div class="text-muted small">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $user->phone ?? 'N/A' }}</div>
                                        <div class="text-muted small">{{ Str::limit($user->address ?? 'N/A', 30) }}</div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->role === 'customer' ? 'primary' : 'success' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $user->status === 'active' ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    @if($type !== 'customers')
                                        <td>
                                            @if($user->role === 'provider')
                                                <span class="badge bg-{{ $user->provider_status === 'approved' ? 'success' : ($user->provider_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($user->provider_status) }}
                                                </span>
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                    @endif
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.users.show', $user) }}"
                                               class="btn btn-info btn-sm" title="View">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.users.edit', $user) }}"
                                               class="btn btn-warning btn-sm" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if($user->role === 'provider' && $user->provider_status === 'pending')
                                                <form method="POST" action="{{ route('admin.users.approve-provider', $user) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm" title="Approve">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('admin.users.reject-provider', $user) }}"
                                                      style="display: inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Reject">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <form method="POST" action="{{ route('admin.users.toggle-status', $user) }}"
                                                  style="display: inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-secondary btn-sm"
                                                        title="{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}">
                                                    <i class="fas fa-{{ $user->status === 'active' ? 'pause' : 'play' }}"></i>
                                                </button>
                                            </form>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                        data-bs-toggle="dropdown" aria-expanded="false" title="More Actions">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form method="POST" action="{{ route('admin.users.reset-password', $user) }}"
                                                              style="display: inline;" class="w-100">
                                                            @csrf
                                                            <button type="submit" class="dropdown-item"
                                                                    onclick="return confirm('Are you sure you want to reset this user\'s password?')">
                                                                <i class="fas fa-key me-2"></i>Reset Password
                                                            </button>
                                                        </form>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger"
                                                                onclick="confirmDelete('{{ route('admin.users.destroy', $user) }}')">
                                                            <i class="fas fa-trash me-2"></i>Delete User
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $users->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-4">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No users found matching your criteria</p>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add First User
                    </a>
                </div>
            @endif
        </div>
    </div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Deleting this user will also remove all associated data including bookings, reviews, and service requests.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
