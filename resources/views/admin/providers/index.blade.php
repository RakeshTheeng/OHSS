@extends('layouts.admin')

@section('title', 'Provider Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-cog me-2"></i>Provider Management
        </h1>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.providers.pending') }}" class="btn btn-warning btn-sm">
                <i class="fas fa-clock me-1"></i>
                Pending Approvals ({{ $stats['awaiting'] }})
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Providers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['total']) }}
                            </div>
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
                                Approved
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['approved']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                Awaiting Approval
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['awaiting']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Rejected
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format($stats['rejected']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filters & Search
            </h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.providers.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               value="{{ request('search') }}" placeholder="Search by name or email...">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">All Statuses</option>
                            <option value="awaiting" {{ request('status') === 'awaiting' ? 'selected' : '' }}>Awaiting</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    <div class="col-md-5 mb-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>Search
                        </button>
                        <a href="{{ route('admin.providers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Providers Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Providers List
            </h6>
        </div>
        <div class="card-body">
            @if($providers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Provider</th>
                                <th>Contact</th>
                                <th>Services</th>
                                <th>Status</th>
                                <th>Availability</th>
                                <th>Joined</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($providers as $provider)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                @if($provider->profile_image)
                                                    <img src="{{ Storage::url($provider->profile_image) }}" 
                                                         class="rounded-circle" width="40" height="40" alt="Profile">
                                                @else
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ $provider->name }}</h6>
                                                <small class="text-muted">
                                                    {{ $provider->experience_years }} years exp.
                                                </small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <small class="d-block">{{ $provider->email }}</small>
                                            <small class="d-block text-muted">{{ $provider->phone }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($provider->serviceCategories->take(3) as $category)
                                                <span class="badge bg-info">{{ $category->name }}</span>
                                            @endforeach
                                            @if($provider->serviceCategories->count() > 3)
                                                <span class="badge bg-secondary">+{{ $provider->serviceCategories->count() - 3 }}</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $provider->provider_status === 'approved' ? 'success' : ($provider->provider_status === 'awaiting' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($provider->provider_status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($provider->provider_status === 'approved')
                                            <span class="badge bg-{{ $provider->is_available ? 'success' : 'secondary' }}">
                                                {{ $provider->is_available ? 'Available' : 'Unavailable' }}
                                            </span>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $provider->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.providers.show', $provider) }}" 
                                               class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($provider->provider_status === 'approved')
                                                <form method="POST" action="{{ route('admin.providers.toggle-availability', $provider) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-{{ $provider->is_available ? 'warning' : 'success' }} btn-sm"
                                                            title="{{ $provider->is_available ? 'Mark Unavailable' : 'Mark Available' }}">
                                                        <i class="fas fa-{{ $provider->is_available ? 'pause' : 'play' }}"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            <button type="button" class="btn btn-outline-danger btn-sm" 
                                                    onclick="confirmDelete({{ $provider->id }}, '{{ $provider->name }}')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div>
                        <small class="text-muted">
                            Showing {{ $providers->firstItem() }} to {{ $providers->lastItem() }} of {{ $providers->total() }} results
                        </small>
                    </div>
                    <div>
                        {{ $providers->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No providers found</h5>
                    <p class="text-muted">No providers match your current filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete provider <strong id="providerName"></strong>?</p>
                <p class="text-danger small">This action cannot be undone and will delete all associated data.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Provider</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function confirmDelete(providerId, providerName) {
        document.getElementById('providerName').textContent = providerName;
        document.getElementById('deleteForm').action = `/admin/providers/${providerId}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
@endpush
