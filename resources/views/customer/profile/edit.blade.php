@extends('layouts.customer')

@section('title', 'Edit Profile')

@section('content')
<div class="container-fluid">
    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-user-edit"></i> Edit Profile
        </h1>
    </div>

    <!-- Profile Edit Form -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Profile Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('customer.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Image -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="profile-image-container">
                                    @if($customer->profile_image)
                                        <img src="{{ Storage::url($customer->profile_image) }}" 
                                             alt="{{ $customer->name }}" 
                                             class="rounded-circle mb-3" 
                                             style="width: 150px; height: 150px; object-fit: cover;"
                                             id="profile-preview">
                                    @else
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                             style="width: 150px; height: 150px;"
                                             id="profile-preview">
                                            <i class="fas fa-user text-white fa-4x"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="form-group">
                                    <label for="profile_image" class="form-label">Profile Image</label>
                                    <input type="file" 
                                           class="form-control @error('profile_image') is-invalid @enderror" 
                                           id="profile_image" 
                                           name="profile_image" 
                                           accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Accepted formats: JPEG, PNG, JPG. Maximum size: 2MB.
                                    </small>
                                </div>
                            </div>
                        </div>

                        <!-- Name -->
                        <div class="form-group mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $customer->name) }}" 
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email', $customer->email) }}" 
                                   required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="form-group mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="text" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone', $customer->phone) }}" 
                                   placeholder="e.g., +977-9841234567">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="form-group mb-4">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      placeholder="Enter your full address">{{ old('address', $customer->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('customer.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Back to Dashboard
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Stats -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-primary">{{ $customer->serviceRequests->count() }}</h4>
                                <small class="text-muted">Service Requests</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-success">{{ $customer->bookings->count() }}</h4>
                            <small class="text-muted">Bookings</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h4 class="text-warning">{{ $customer->givenReviews->count() }}</h4>
                                <small class="text-muted">Reviews Given</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h4 class="text-info">{{ $customer->favorites->count() }}</h4>
                            <small class="text-muted">Favorite Providers</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Account Details -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Role:</strong>
                        <span class="badge badge-info">Customer</span>
                    </div>
                    <div class="mb-3">
                        <strong>Member Since:</strong>
                        <span class="text-muted">{{ $customer->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="mb-3">
                        <strong>Email Status:</strong>
                        @if($customer->email_verified_at)
                            <span class="badge badge-success">Verified</span>
                        @else
                            <span class="badge badge-warning">Unverified</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Preview profile image before upload
document.getElementById('profile_image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('profile-preview');
            preview.innerHTML = `<img src="${e.target.result}" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">`;
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
