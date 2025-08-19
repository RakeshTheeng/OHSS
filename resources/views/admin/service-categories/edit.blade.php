@extends('layouts.admin')

@section('title', 'Edit Service Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Edit Service Category
            </h1>
            <p class="text-muted mb-0">Update service category information and settings</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.service-categories.show', $serviceCategory) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye me-1"></i>View Category
            </a>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.service-categories.update', $serviceCategory) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Category Image -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="mb-3">
                                    @if($serviceCategory->image)
                                        <img id="image-preview" src="{{ Storage::url($serviceCategory->image) }}" 
                                             class="rounded" width="120" height="120" alt="Category Image" style="object-fit: cover;">
                                    @else
                                        <div id="image-placeholder" class="bg-primary rounded d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 120px; height: 120px;">
                                            <i class="{{ $serviceCategory->icon }} fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="image" class="form-label">Category Image</label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                           id="image" name="image" accept="image/*">
                                    <div class="form-text">Upload an image to represent this service category (optional)</div>
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $serviceCategory->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label">Icon Class <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                           id="icon" name="icon" value="{{ old('icon', $serviceCategory->icon) }}" 
                                           placeholder="e.g., fas fa-tools" required>
                                    <span class="input-group-text">
                                        <i id="icon-preview" class="{{ old('icon', $serviceCategory->icon) }}"></i>
                                    </span>
                                </div>
                                <div class="form-text">Use FontAwesome icon classes (e.g., fas fa-tools, fas fa-home)</div>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required 
                                      placeholder="Describe what services are included in this category...">{{ old('description', $serviceCategory->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       id="is_active" name="is_active" value="1"
                                       {{ old('is_active', $serviceCategory->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    <strong>Active Category</strong>
                                    <div class="form-text">When active, this category will be available for service providers and customers</div>
                                </label>
                            </div>
                        </div>

                        <!-- Current Statistics -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-info">Current Statistics</h6>
                            </div>
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4 mb-3">
                                        <div class="text-primary font-weight-bold h4">{{ $serviceCategory->serviceRequests->count() }}</div>
                                        <div class="text-muted">Service Requests</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-success font-weight-bold h4">{{ $serviceCategory->providers->count() }}</div>
                                        <div class="text-muted">Registered Providers</div>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="text-info font-weight-bold h4">{{ $serviceCategory->created_at->diffForHumans() }}</div>
                                        <div class="text-muted">Created</div>
                                    </div>
                                </div>
                                <div class="alert alert-info mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    <strong>Note:</strong> If you deactivate this category, it will no longer be available for new service requests, but existing data will remain intact.
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update Category
                                </button>
                                <a href="{{ route('admin.service-categories.show', $serviceCategory) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-1"></i>Delete Category
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                    @if($serviceCategory->serviceRequests->count() > 0)
                        <br><strong>Note:</strong> Categories with existing service requests cannot be deleted.
                    @endif
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                @if($serviceCategory->serviceRequests->count() === 0)
                    <form method="POST" action="{{ route('admin.service-categories.destroy', $serviceCategory) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete Category</button>
                    </form>
                @else
                    <button type="button" class="btn btn-danger" disabled>Cannot Delete</button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Icon Reference Modal -->
<div class="modal fade" id="iconModal" tabindex="-1" aria-labelledby="iconModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconModalLabel">FontAwesome Icons Reference</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Here are some commonly used icons for service categories:</p>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <code>fas fa-tools</code> <i class="fas fa-tools ms-2"></i> - General Services
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-home</code> <i class="fas fa-home ms-2"></i> - Home Services
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-car</code> <i class="fas fa-car ms-2"></i> - Automotive
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-laptop</code> <i class="fas fa-laptop ms-2"></i> - Technology
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-paint-brush</code> <i class="fas fa-paint-brush ms-2"></i> - Painting
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-wrench</code> <i class="fas fa-wrench ms-2"></i> - Repair
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-broom</code> <i class="fas fa-broom ms-2"></i> - Cleaning
                    </div>
                    <div class="col-md-6 mb-2">
                        <code>fas fa-seedling</code> <i class="fas fa-seedling ms-2"></i> - Gardening
                    </div>
                </div>
                <p class="mt-3">Visit <a href="https://fontawesome.com/icons" target="_blank">FontAwesome</a> for more icons.</p>
            </div>
        </div>
    </div>
</div>

<script>
// Image preview
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            const placeholder = document.getElementById('image-placeholder');
            
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.outerHTML = `<img id="image-preview" src="${e.target.result}" class="rounded" width="120" height="120" alt="Category Image" style="object-fit: cover;">`;
            }
        };
        reader.readAsDataURL(file);
    }
});

// Icon preview
document.getElementById('icon').addEventListener('input', function(e) {
    const iconPreview = document.getElementById('icon-preview');
    const iconClass = e.target.value;
    
    // Remove all existing classes and add new ones
    iconPreview.className = iconClass || 'fas fa-question';
});

// Show icon reference
function showIconReference() {
    new bootstrap.Modal(document.getElementById('iconModal')).show();
}
</script>

<style>
.form-text {
    font-size: 0.875em;
    color: #6c757d;
}
</style>
@endsection
