@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-edit me-2"></i>Edit User
            </h1>
            <p class="text-muted mb-0">Update user information and settings</p>
        </div>
        <div class="d-sm-flex gap-2">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info btn-sm">
                <i class="fas fa-eye me-1"></i>View User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left me-1"></i>Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Profile Image -->
                        <div class="row mb-4">
                            <div class="col-md-12 text-center">
                                <div class="mb-3">
                                    @if($user->profile_image)
                                        <img id="profile-preview" src="{{ Storage::url($user->profile_image) }}" 
                                             class="rounded-circle" width="120" height="120" alt="Profile">
                                    @else
                                        <div id="profile-placeholder" class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                             style="width: 120px; height: 120px;">
                                            <i class="fas fa-user fa-3x text-white"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status', $user->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $user->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Password Section -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="m-0 font-weight-bold text-warning">Change Password (Optional)</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">New Password</label>
                                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                               id="password" name="password">
                                        <div class="form-text">Leave blank to keep current password</div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" 
                                               id="password_confirmation" name="password_confirmation">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Provider Specific Fields -->
                        @if($user->role === 'provider')
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="m-0 font-weight-bold text-success">Provider Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="hourly_rate" class="form-label">Hourly Rate (Rs.) <span class="text-danger">*</span></label>
                                            <input type="number" step="0.01" min="0" 
                                                   class="form-control @error('hourly_rate') is-invalid @enderror" 
                                                   id="hourly_rate" name="hourly_rate" 
                                                   value="{{ old('hourly_rate', $user->hourly_rate) }}" required>
                                            @error('hourly_rate')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="experience_years" class="form-label">Experience (Years) <span class="text-danger">*</span></label>
                                            <input type="number" min="0" 
                                                   class="form-control @error('experience_years') is-invalid @enderror" 
                                                   id="experience_years" name="experience_years" 
                                                   value="{{ old('experience_years', $user->experience_years) }}" required>
                                            @error('experience_years')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="provider_status" class="form-label">Provider Status <span class="text-danger">*</span></label>
                                            <select class="form-select @error('provider_status') is-invalid @enderror" 
                                                    id="provider_status" name="provider_status" required>
                                                <option value="pending" {{ old('provider_status', $user->provider_status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="approved" {{ old('provider_status', $user->provider_status) === 'approved' ? 'selected' : '' }}>Approved</option>
                                                <option value="rejected" {{ old('provider_status', $user->provider_status) === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                            </select>
                                            @error('provider_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <div class="form-check mt-4">
                                                <input class="form-check-input" type="checkbox" 
                                                       id="is_available" name="is_available" value="1"
                                                       {{ old('is_available', $user->is_available) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="is_available">
                                                    Available for bookings
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="bio" class="form-label">Bio/Description</label>
                                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                                  id="bio" name="bio" rows="4" 
                                                  placeholder="Tell us about your experience and services...">{{ old('bio', $user->bio) }}</textarea>
                                        @error('bio')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Service Categories -->
                                    <div class="mb-3">
                                        <label class="form-label">Service Categories <span class="text-danger">*</span></label>
                                        <div class="row">
                                            @foreach($serviceCategories as $category)
                                                <div class="col-md-6 mb-2">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" 
                                                               name="service_categories[]" value="{{ $category->id }}" 
                                                               id="category_{{ $category->id }}"
                                                               {{ in_array($category->id, old('service_categories', $user->serviceCategories->pluck('id')->toArray())) ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="category_{{ $category->id }}">
                                                            <i class="{{ $category->icon }} me-2"></i>{{ $category->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        @error('service_categories')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i>Update User
                                </button>
                                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-1"></i>Cancel
                                </a>
                            </div>
                            <div>
                                <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                    <i class="fas fa-trash me-1"></i>Delete User
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
                <p>Are you sure you want to delete this user? This action cannot be undone.</p>
                <div class="alert alert-warning">
                    <strong>Warning:</strong> Deleting this user will also remove all associated data including bookings, reviews, and service requests.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            const placeholder = document.getElementById('profile-placeholder');
            
            if (preview) {
                preview.src = e.target.result;
            } else if (placeholder) {
                placeholder.outerHTML = `<img id="profile-preview" src="${e.target.result}" class="rounded-circle" width="120" height="120" alt="Profile">`;
            }
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
