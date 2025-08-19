@extends('layouts.app')

@section('title', 'Add ' . ucfirst($type))

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-user-plus me-2"></i>Add {{ ucfirst($type) }}
            </h1>
            <p class="text-muted mb-0">Create a new {{ $type }} account</p>
        </div>
        <div>
            <a href="{{ route('admin.users.index', ['type' => $type === 'provider' ? 'providers' : 'customers']) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i>Back to {{ ucfirst($type) }}s
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">{{ ucfirst($type) }} Information</h6>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="role" value="{{ $type }}">
                        
                        <!-- Basic Information -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                <input type="password" class="form-control" 
                                       id="password_confirmation" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" 
                                      id="address" name="address" rows="3">{{ old('address') }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="profile_image" class="form-label">Profile Image</label>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror" 
                                   id="profile_image" name="profile_image" accept="image/*">
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if($type === 'provider')
                            <!-- Provider Specific Fields -->
                            <hr class="my-4">
                            <h5 class="text-primary mb-3">Provider Details</h5>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="hourly_rate" class="form-label">Hourly Rate (Rs.) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                           id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate') }}" 
                                           min="0" step="0.01" required>
                                    @error('hourly_rate')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="experience_years" class="form-label">Experience (Years) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                           id="experience_years" name="experience_years" value="{{ old('experience_years') }}" 
                                           min="0" required>
                                    @error('experience_years')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="bio" class="form-label">Bio/Description</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" 
                                          id="bio" name="bio" rows="4">{{ old('bio') }}</textarea>
                                @error('bio')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="service_categories" class="form-label">Service Categories <span class="text-danger">*</span></label>
                                <select class="form-select @error('service_categories') is-invalid @enderror" 
                                        id="service_categories" name="service_categories[]" multiple required>
                                    @foreach($serviceCategories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ in_array($category->id, old('service_categories', [])) ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('service_categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Hold Ctrl/Cmd to select multiple categories</div>
                            </div>

                            <div class="mb-3">
                                <label for="provider_status" class="form-label">Provider Status <span class="text-danger">*</span></label>
                                <select class="form-select @error('provider_status') is-invalid @enderror" 
                                        id="provider_status" name="provider_status" required>
                                    <option value="pending" {{ old('provider_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ old('provider_status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ old('provider_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                                @error('provider_status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.users.index', ['type' => $type === 'provider' ? 'providers' : 'customers']) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i>Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Create {{ ucfirst($type) }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">{{ ucfirst($type) }} Guidelines</h6>
                </div>
                <div class="card-body">
                    @if($type === 'customer')
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Customers can browse and book services</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>They can leave reviews and ratings</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Payment processing is handled automatically</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Profile verification is optional</li>
                        </ul>
                    @else
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Providers must be approved before offering services</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>They can set their own hourly rates</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Multiple service categories can be selected</li>
                            <li class="mb-2"><i class="fas fa-check text-success me-2"></i>Profile verification is required</li>
                        </ul>
                    @endif
                </div>
            </div>

            @if($type === 'provider')
                <div class="card shadow mt-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-warning">Available Categories</h6>
                    </div>
                    <div class="card-body">
                        @if($serviceCategories->count() > 0)
                            @foreach($serviceCategories as $category)
                                <div class="d-flex align-items-center mb-2">
                                    <i class="{{ $category->icon }} text-primary me-2"></i>
                                    <span>{{ $category->name }}</span>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No service categories available</p>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
