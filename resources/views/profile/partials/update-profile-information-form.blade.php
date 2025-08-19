<div class="card shadow">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="fas fa-user me-2"></i>Profile Information
        </h5>
    </div>
    <div class="card-body">
        <p class="text-muted mb-4">Update your account's profile information and email address.</p>



        <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <!-- Current Profile Image -->
            @if($user->profile_image)
                <div class="mb-4 text-center">
                    <img src="{{ Storage::url($user->profile_image) }}"
                         alt="Current Profile Image"
                         class="rounded-circle"
                         style="width: 100px; height: 100px; object-fit: cover;">
                    <p class="text-muted small mt-2">Current Profile Image</p>
                </div>
            @endif

            <!-- Profile Image Upload -->
            <div class="mb-3">
                <label for="profile_image" class="form-label">Profile Image</label>
                <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                       id="profile_image" name="profile_image" accept="image/*">
                @error('profile_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">Upload a new profile photo (Max: 2MB, JPG/PNG)</div>
            </div>

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror"
                       id="name" name="name" value="{{ old('name', $user->name) }}" required>
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror"
                       id="email" name="email" value="{{ old('email', $user->email) }}" required>
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror


            </div>

            <!-- Phone -->
            <div class="mb-3">
                <label for="phone" class="form-label">Phone Number</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                       id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <textarea class="form-control @error('address') is-invalid @enderror"
                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Provider-specific fields -->
            @if($user->role === 'provider')
                <hr class="my-4">
                <h6 class="text-success mb-3">
                    <i class="fas fa-briefcase me-2"></i>Provider Information
                </h6>

                <!-- Bio -->
                <div class="mb-3">
                    <label for="bio" class="form-label">About Yourself</label>
                    <textarea class="form-control @error('bio') is-invalid @enderror"
                              id="bio" name="bio" rows="4"
                              placeholder="Describe your experience, skills, and what makes you a great service provider...">{{ old('bio', $user->bio) }}</textarea>
                    @error('bio')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <!-- Hourly Rate -->
                    <div class="col-md-6 mb-3">
                        <label for="hourly_rate" class="form-label">Hourly Rate (Rs.)</label>
                        <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror"
                               id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $user->hourly_rate) }}"
                               min="100" max="10000" step="50">
                        @error('hourly_rate')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Experience Years -->
                    <div class="col-md-6 mb-3">
                        <label for="experience_years" class="form-label">Years of Experience</label>
                        <input type="number" class="form-control @error('experience_years') is-invalid @enderror"
                               id="experience_years" name="experience_years" value="{{ old('experience_years', $user->experience_years) }}"
                               min="0" max="50">
                        @error('experience_years')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Citizenship Number -->
                <div class="mb-3">
                    <label for="citizenship_number" class="form-label">Citizenship Number</label>
                    <input type="text" class="form-control @error('citizenship_number') is-invalid @enderror"
                           id="citizenship_number" name="citizenship_number" value="{{ old('citizenship_number', $user->citizenship_number) }}"
                           placeholder="e.g., 12-34-56-78901">
                    @error('citizenship_number')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- KYC Document -->
                <div class="mb-3">
                    <label for="kyc_document" class="form-label">KYC Document</label>
                    @if($user->kyc_document)
                        <div class="mb-2">
                            <a href="{{ Storage::url($user->kyc_document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-file me-1"></i>View Current Document
                            </a>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('kyc_document') is-invalid @enderror"
                           id="kyc_document" name="kyc_document" accept=".pdf,.jpg,.jpeg,.png">
                    @error('kyc_document')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text">Upload citizenship certificate or professional license (Max: 5MB)</div>
                </div>

                <!-- Service Categories -->
                <div class="mb-3">
                    <label class="form-label">Service Categories</label>
                    <div class="row">
                        @foreach($serviceCategories as $category)
                            <div class="col-md-4 mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                           name="service_categories[]" value="{{ $category->id }}"
                                           id="category_{{ $category->id }}"
                                           {{ $user->serviceCategories->contains($category->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category_{{ $category->id }}">
                                        <i class="{{ $category->icon }} me-1"></i>
                                        {{ $category->name }}
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-2"></i>Save Changes
                </button>

                @if (session('status') === 'profile-updated')
                    <div class="alert alert-success mb-0 py-2">
                        <i class="fas fa-check me-2"></i>Profile updated successfully!
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>
