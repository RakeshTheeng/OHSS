@extends('layouts.app')

@section('title', 'Add Service Category')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-plus me-2"></i>Add Service Category
            </h1>
            <p class="text-muted mb-0">Create a new service category</p>
        </div>
        <div>
            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to Categories
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.service-categories.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="icon" class="form-label">Icon Class <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', 'fas fa-tools') }}" 
                                       placeholder="e.g., fas fa-tools" required>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Use FontAwesome icon classes</div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror" 
                                   id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Upload an image for this category (optional)</div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                       value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Category
                                </label>
                            </div>
                            <div class="form-text">Active categories are visible to users</div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.service-categories.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Icon Preview</h6>
                </div>
                <div class="card-body text-center">
                    <div id="icon-preview" class="mb-3">
                        <i class="fas fa-tools fa-4x text-muted"></i>
                    </div>
                    <p class="text-muted small">Icon will appear here as you type</p>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Popular Icons</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-tools">
                                <i class="fas fa-tools fa-2x text-primary"></i>
                                <div class="small">Tools</div>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-home">
                                <i class="fas fa-home fa-2x text-success"></i>
                                <div class="small">Home</div>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-car">
                                <i class="fas fa-car fa-2x text-info"></i>
                                <div class="small">Car</div>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-laptop">
                                <i class="fas fa-laptop fa-2x text-warning"></i>
                                <div class="small">Tech</div>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-paint-brush">
                                <i class="fas fa-paint-brush fa-2x text-danger"></i>
                                <div class="small">Paint</div>
                            </div>
                        </div>
                        <div class="col-4 mb-3">
                            <div class="icon-option" data-icon="fas fa-leaf">
                                <i class="fas fa-leaf fa-2x text-success"></i>
                                <div class="small">Garden</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const iconInput = document.getElementById('icon');
    const iconPreview = document.getElementById('icon-preview');
    
    // Update icon preview
    function updateIconPreview() {
        const iconClass = iconInput.value || 'fas fa-tools';
        iconPreview.innerHTML = `<i class="${iconClass} fa-4x text-primary"></i>`;
    }
    
    // Listen for icon input changes
    iconInput.addEventListener('input', updateIconPreview);
    
    // Handle icon selection
    document.querySelectorAll('.icon-option').forEach(option => {
        option.addEventListener('click', function() {
            const iconClass = this.dataset.icon;
            iconInput.value = iconClass;
            updateIconPreview();
            
            // Remove previous selection
            document.querySelectorAll('.icon-option').forEach(opt => opt.classList.remove('selected'));
            // Add selection to current
            this.classList.add('selected');
        });
    });
    
    // Initial preview
    updateIconPreview();
});
</script>

<style>
.icon-option {
    cursor: pointer;
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.icon-option:hover {
    background-color: #f8f9fc;
    transform: translateY(-2px);
}

.icon-option.selected {
    background-color: #e3f2fd;
    border: 2px solid #2196f3;
}
</style>
@endpush
