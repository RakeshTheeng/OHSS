@extends('layouts.provider')

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
                    <form method="POST" action="{{ route('provider.profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Image -->
                        <div class="row mb-4">
                            <div class="col-md-3 text-center">
                                <div class="profile-image-container">
                                    @if($provider->profile_image)
                                        <img src="{{ Storage::url($provider->profile_image) }}" 
                                             alt="{{ $provider->name }}" 
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
                                    <small class="form-text text-muted">Upload a professional profile photo (JPG, PNG, max 2MB)</small>
                                </div>
                            </div>
                        </div>

                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="name" class="form-label">Full Name *</label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name', $provider->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="email" class="form-label">Email Address *</label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email', $provider->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="phone" class="form-label">Phone Number *</label>
                                    <input type="text" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone', $provider->phone) }}" 
                                           required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="hourly_rate" class="form-label">Hourly Rate (Rs.) *</label>
                                    <input type="number" 
                                           class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" 
                                           name="hourly_rate" 
                                           value="{{ old('hourly_rate', $provider->hourly_rate) }}" 
                                           min="100" 
                                           max="10000" 
                                           step="50" 
                                           required>
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="experience_years" class="form-label">Years of Experience *</label>
                                    <input type="number" 
                                           class="form-control @error('experience_years') is-invalid @enderror" 
                                           id="experience_years" 
                                           name="experience_years" 
                                           value="{{ old('experience_years', $provider->experience_years) }}" 
                                           min="0" 
                                           max="50" 
                                           required>
                                    @error('experience_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label for="service_categories" class="form-label">Service Categories *</label>
                                    <select class="form-control @error('service_categories') is-invalid @enderror" 
                                            id="service_categories" 
                                            name="service_categories[]" 
                                            multiple 
                                            required>
                                        @foreach($serviceCategories as $category)
                                            <option value="{{ $category->id }}"
                                                    {{ in_array($category->id, old('service_categories', $provider->serviceCategories ? $provider->serviceCategories->pluck('id')->toArray() : [])) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_categories')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Hold Ctrl/Cmd to select multiple categories</small>
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" 
                                      name="address" 
                                      rows="3" 
                                      required>{{ old('address', $provider->address) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-4">
                            <label for="bio" class="form-label">Professional Bio</label>
                            <textarea class="form-control @error('bio') is-invalid @enderror" 
                                      id="bio" 
                                      name="bio" 
                                      rows="4" 
                                      placeholder="Tell customers about your experience, skills, and what makes you unique...">{{ old('bio', $provider->bio) }}</textarea>
                            @error('bio')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">This will be displayed on your public profile</small>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Profile
                            </button>
                            <a href="{{ route('provider.dashboard') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Profile Summary -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-success">Profile Summary</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-3">
                        @if($provider->profile_image)
                            <img src="{{ Storage::url($provider->profile_image) }}" 
                                 alt="{{ $provider->name }}" 
                                 class="rounded-circle" 
                                 style="width: 100px; height: 100px; object-fit: cover;">
                        @else
                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto" 
                                 style="width: 100px; height: 100px;">
                                <i class="fas fa-user text-white fa-3x"></i>
                            </div>
                        @endif
                        <h5 class="mt-2">{{ $provider->name }}</h5>
                        <p class="text-muted">{{ $provider->email }}</p>
                    </div>

                    <div class="mb-3">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $provider->provider_status === 'approved' ? 'success' : ($provider->provider_status === 'awaiting' ? 'warning' : 'danger') }}">
                            {{ ucfirst($provider->provider_status) }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Availability:</strong>
                        <span class="badge bg-{{ $provider->is_available ? 'success' : 'secondary' }}">
                            {{ $provider->is_available ? 'Available' : 'Unavailable' }}
                        </span>
                    </div>

                    <div class="mb-3">
                        <strong>Hourly Rate:</strong>
                        <span class="text-success font-weight-bold">Rs. {{ number_format($provider->hourly_rate, 0) }}/hr</span>
                    </div>

                    <div class="mb-3">
                        <strong>Experience:</strong>
                        <span>{{ $provider->experience_years }} years</span>
                    </div>

                    @if($provider->serviceCategories->count() > 0)
                        <div class="mb-3">
                            <strong>Services:</strong>
                            <div class="mt-1">
                                @foreach($provider->serviceCategories as $category)
                                    <span class="badge bg-primary me-1 mb-1">{{ $category->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @if($provider->rating > 0)
                        <div class="mb-3">
                            <strong>Rating:</strong>
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star{{ $i <= $provider->rating ? '' : '-o' }}"></i>
                                @endfor
                                <span class="text-muted">({{ $provider->total_reviews }} reviews)</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('provider.kyc.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-file-alt"></i> Manage KYC Documents
                        </a>
                        <a href="{{ route('provider.availability.index') }}" class="btn btn-outline-success btn-sm">
                            <i class="fas fa-clock"></i> Set Availability
                        </a>
                        <a href="{{ route('provider.earnings.index') }}" class="btn btn-outline-warning btn-sm">
                            <i class="fas fa-dollar-sign"></i> View Earnings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
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
