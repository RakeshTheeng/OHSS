@extends('layouts.admin')

@section('title', 'Service Category Details')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="{{ $serviceCategory->icon }} me-2"></i>{{ $serviceCategory->name }}
            </h1>
            <p class="text-muted mb-0">Service Category Details</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.service-categories.edit', $serviceCategory) }}" class="btn btn-warning btn-sm">
                <i class="fas fa-edit me-1"></i>Edit Category
            </a>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Category Information -->
        <div class="col-lg-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body text-center">
                    <div class="mb-3">
                        @if($serviceCategory->image)
                            <img src="{{ Storage::url($serviceCategory->image) }}" 
                                 class="rounded mb-3" width="120" height="120" alt="Category Image" style="object-fit: cover;">
                        @else
                            <div class="bg-primary rounded d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 120px; height: 120px;">
                                <i class="{{ $serviceCategory->icon }} fa-3x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <h5 class="font-weight-bold">{{ $serviceCategory->name }}</h5>
                    <p class="text-muted">{{ $serviceCategory->description }}</p>
                    
                    <div class="row text-center mb-3">
                        <div class="col-12 mb-2">
                            <span class="badge bg-{{ $serviceCategory->is_active ? 'success' : 'danger' }} p-2">
                                {{ $serviceCategory->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>

                    <div class="btn-group w-100 mb-3" role="group">
                        <form method="POST" action="{{ route('admin.service-categories.toggle-status', $serviceCategory) }}" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-{{ $serviceCategory->is_active ? 'warning' : 'success' }} btn-sm">
                                <i class="fas fa-{{ $serviceCategory->is_active ? 'pause' : 'play' }} me-1"></i>
                                {{ $serviceCategory->is_active ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>
                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category Statistics -->
            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="text-primary font-weight-bold h4">{{ $serviceCategory->serviceRequests->count() }}</div>
                            <div class="text-muted small">Service Requests</div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="text-success font-weight-bold h4">{{ $serviceCategory->providers->count() }}</div>
                            <div class="text-muted small">Providers</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <strong>Slug:</strong>
                        <div class="text-muted">{{ $serviceCategory->slug }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Created:</strong>
                        <div class="text-muted">{{ $serviceCategory->created_at->format('M d, Y H:i A') }}</div>
                    </div>
                    <div class="mb-3">
                        <strong>Last Updated:</strong>
                        <div class="text-muted">{{ $serviceCategory->updated_at->format('M d, Y H:i A') }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Service Requests -->
        <div class="col-lg-8 mb-4">
            @if($serviceCategory->serviceRequests->count() > 0)
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Service Requests</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Customer</th>
                                        <th>Provider</th>
                                        <th>Status</th>
                                        <th>Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($serviceCategory->serviceRequests->take(10) as $request)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($request->customer->profile_image)
                                                        <img src="{{ Storage::url($request->customer->profile_image) }}" 
                                                             class="rounded-circle me-2" width="30" height="30" alt="Profile">
                                                    @else
                                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                             style="width: 30px; height: 30px;">
                                                            <i class="fas fa-user text-white small"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold small">{{ $request->customer->name ?? 'N/A' }}</div>
                                                        <div class="text-muted small">{{ $request->customer->email ?? 'N/A' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($request->provider)
                                                    <div class="d-flex align-items-center">
                                                        @if($request->provider->profile_image)
                                                            <img src="{{ Storage::url($request->provider->profile_image) }}" 
                                                                 class="rounded-circle me-2" width="30" height="30" alt="Profile">
                                                        @else
                                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2" 
                                                                 style="width: 30px; height: 30px;">
                                                                <i class="fas fa-user text-white small"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <div class="font-weight-bold small">{{ $request->provider->name }}</div>
                                                            <div class="text-muted small">{{ $request->provider->email }}</div>
                                                        </div>
                                                    </div>
                                                @else
                                                    <span class="text-muted">Not assigned</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $request->status === 'completed' ? 'success' : ($request->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($request->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $request->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('admin.service-requests.show', $request) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($serviceCategory->serviceRequests->count() > 10)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.service-requests.index', ['category' => $serviceCategory->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-eye me-1"></i>View All Service Requests
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Providers -->
            @if($serviceCategory->providers->count() > 0)
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Providers in this Category</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($serviceCategory->providers->take(12) as $provider)
                                <div class="col-lg-6 col-md-6 mb-3">
                                    <div class="card border-left-success">
                                        <div class="card-body py-3">
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
                                                <div class="flex-grow-1">
                                                    <div class="font-weight-bold">{{ $provider->name }}</div>
                                                    <div class="text-muted small">{{ $provider->email }}</div>
                                                    <div class="text-success small">Rs. {{ number_format($provider->hourly_rate ?? 0, 2) }}/hr</div>
                                                </div>
                                                <div>
                                                    <span class="badge bg-{{ $provider->provider_status === 'approved' ? 'success' : ($provider->provider_status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($provider->provider_status) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        @if($serviceCategory->providers->count() > 12)
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.users.providers', ['service_category' => $serviceCategory->id]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-eye me-1"></i>View All Providers
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-user-cog fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No Providers Yet</h5>
                        <p class="text-muted">No providers have registered for this service category yet.</p>
                    </div>
                </div>
            @endif
        </div>
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
                <p>Are you sure you want to delete this service category? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> This category has {{ $serviceCategory->serviceRequests->count() }} service requests and {{ $serviceCategory->providers->count() }} providers associated with it.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.service-categories.destroy', $serviceCategory) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete Category</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}
</style>
@endsection
