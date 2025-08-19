@extends('layouts.admin')

@section('title', 'Service Categories')

@section('content')
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-tools me-2"></i>Service Categories
            </h1>
            <p class="text-muted mb-0">Manage service categories and their settings</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i>Add Category
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
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_categories']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Active Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_categories']) }}</div>
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
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Inactive Categories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['inactive_categories']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-pause-circle fa-2x text-gray-300"></i>
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
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Requests</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_requests']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
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
            <form method="GET" action="{{ route('admin.service-categories.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Search</label>
                        <input type="text" name="search" id="search" class="form-control" 
                               placeholder="Category name or description..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-4 mb-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="fas fa-search me-1"></i>Filter
                        </button>
                        <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-1"></i>Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row">
        @if($categories->count() > 0)
            @foreach($categories as $category)
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card shadow h-100">
                        @if($category->image)
                            <img src="{{ Storage::url($category->image) }}" class="card-img-top" 
                                 style="height: 200px; object-fit: cover;" alt="{{ $category->name }}">
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                 style="height: 200px;">
                                <i class="{{ $category->icon }} fa-4x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="card-title mb-0">{{ $category->name }}</h5>
                                <span class="badge bg-{{ $category->is_active ? 'success' : 'danger' }}">
                                    {{ $category->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                            
                            <p class="card-text text-muted small flex-grow-1">
                                {{ Str::limit($category->description, 100) }}
                            </p>
                            
                            <div class="row text-center mb-3">
                                <div class="col-6">
                                    <div class="text-primary font-weight-bold">{{ $category->service_requests_count }}</div>
                                    <div class="text-muted small">Requests</div>
                                </div>
                                <div class="col-6">
                                    <div class="text-success font-weight-bold">{{ $category->providers_count }}</div>
                                    <div class="text-muted small">Providers</div>
                                </div>
                            </div>
                            
                            <div class="btn-group w-100 mb-2" role="group">
                                <a href="{{ route('admin.service-categories.show', $category) }}"
                                   class="btn btn-info btn-sm">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="{{ route('admin.service-categories.edit', $category) }}"
                                   class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                            </div>
                            <div class="btn-group w-100" role="group">
                                <form method="POST" action="{{ route('admin.service-categories.toggle-status', $category) }}"
                                      style="display: inline;" class="flex-fill">
                                    @csrf
                                    <button type="submit" class="btn btn-secondary btn-sm w-100">
                                        <i class="fas fa-{{ $category->is_active ? 'pause' : 'play' }}"></i>
                                        {{ $category->is_active ? 'Disable' : 'Enable' }}
                                    </button>
                                </form>
                                <button type="button" class="btn btn-danger btn-sm"
                                        onclick="confirmDelete('{{ route('admin.service-categories.destroy', $category) }}')"
                                        title="Delete Category">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="fas fa-tools fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No service categories found</h5>
                        <p class="text-muted">Create your first service category to get started</p>
                        <a href="{{ route('admin.service-categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Add Service Category
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $categories->appends(request()->query())->links() }}
        </div>
    @endif
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
                Are you sure you want to delete this service category? This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmDelete(url) {
    document.getElementById('deleteForm').action = url;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endpush
